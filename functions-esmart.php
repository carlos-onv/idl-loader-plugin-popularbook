<?php
require_once('functions-esmart-debug.php');
require_once('functions-esmart-admin.php');








add_action('init', 'custom_testcms');
function custom_testcms()
{
    if (isset($_REQUEST['testcms']) && $_REQUEST['testcms'] == "process") {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        $order_id = 116377;
        $type = 'Payment';
        if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'refund') {
            $type = 'refund';
        } else if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'public_exams') {
            $type = 'public_exams';
        }
        process_subscription_custom($order_id, $type, true); // True = Debug mode for manual testing

        exit;
    }
}

/**
 * FEATURE 1: Database Log Table Creation
 * This runs automatically when an admin visits the dashboard.
 */
add_action('admin_init', 'emathsmart_create_log_table');
function emathsmart_create_log_table()
{
    $current_version = '1.0';
    if (get_option('emathsmart_log_table_version') === $current_version) {
        return; // Table already created, skip
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'emathsmart_log';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        order_id bigint(20) NOT NULL,
        api_type varchar(50) NOT NULL,
        attempt int(11) NOT NULL,
        request_payload text NOT NULL,
        response_code int(11) DEFAULT NULL,
        response_body text DEFAULT NULL,
        curl_error text DEFAULT NULL,
        http_status int(11) DEFAULT NULL,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id),
        KEY order_id (order_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    update_option('emathsmart_log_table_version', $current_version);
}

/**
 * PRODUCTION HOOKS: Trigger eMathSmart notifications automatically
 */
add_action('woocommerce_order_status_completed', 'emathsmart_trigger_payment_notification', 20, 1);
function emathsmart_trigger_payment_notification($order_id)
{
    // Only notify eMathSmart for subscription orders
    if (!function_exists('wcs_get_subscriptions_for_order')) return;
    $subscriptions = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
    if (empty($subscriptions)) return;

    process_subscription_custom($order_id, 'Payment', false);
}

add_action('woocommerce_order_status_refunded', 'emathsmart_trigger_refund_notification', 20, 1);
function emathsmart_trigger_refund_notification($order_id)
{
    // Only notify eMathSmart for subscription orders
    if (!function_exists('wcs_get_subscriptions_for_order')) return;
    $subscriptions = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
    if (empty($subscriptions)) return;

    process_subscription_custom($order_id, 'refund', false); // False = Silent mode for production
}

// Auto-cancel active subscriptions when a parent order is refunded.
// WCS built-in (maybe_cancel_subscription_on_full_refund) only handles pending-cancel status.
// This ensures active subscriptions are also cancelled on refund.
add_action('woocommerce_order_status_refunded', 'emathsmart_cancel_subscription_on_refund', 30, 1);
function emathsmart_cancel_subscription_on_refund($order_id)
{
    if (!function_exists('wcs_get_subscriptions_for_order')) return;

    $subscriptions = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'parent'));
    $order = wc_get_order($order_id);

    foreach ($subscriptions as $subscription) {
        if ($subscription->has_status(array('active', 'on-hold')) && $subscription->can_be_updated_to('cancelled')) {
            $subscription->update_status(
                'cancelled',
                sprintf(
                    __('Subscription automatically cancelled because order #%s was refunded.', 'woocommerce-subscriptions'),
                    $order_id
                )
            );

            // Add note to parent order too
            if ($order) {
                $order->add_order_note(
                    sprintf(
                        __('Subscription #%s automatically cancelled after this order was refunded.', 'woocommerce-subscriptions'),
                        $subscription->get_id()
                    )
                );
            }
        }
    }
}

/**
 * API #9 Helper: Fetch Public Exam PDF links for a specific order
 * Returns an array of items with 'title' and 'url'
 */
function emathsmart_get_public_exam_links($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return [];

    $now = time();
    $nonce = bin2hex(random_bytes(16));
    $url = "https://test.emathsmart.ca/api/customer-center/getPublicExamQuestions";
    $expireTimestamp = $now + (365 * 86400);

    // Get the real WooCommerce Subscription ID (As per Jatin's feedback)
    $wc_subscription_id = (string) $order_id;
    if (function_exists('wcs_get_subscriptions_for_order')) {
        $subs = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
        foreach ($subs as $sub) {
            $wc_subscription_id = (string) $sub->get_id();
            break;
        }
    }

    $secret = "yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm";
    $user_id = $order->get_user_id();

    // Signature excludes expireTimestamp
    $sign_params = [
        'appId' => 'ParentClub',
        'parentId' => (string) $user_id,
        'subscriptionId' => (string) $wc_subscription_id,
        'timestamp' => (string) $now,
        'nonce' => $nonce,
    ];
    
    ksort($sign_params);
    $pairs = [];
    foreach ($sign_params as $k => $v) $pairs[] = "$k=$v";
    $string = implode('&', $pairs);
    $signature = base64_encode(hash_hmac('sha256', $string, $secret, true));
    $signature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);

    $post_body = $sign_params;
    $post_body['signature'] = $signature;
    $post_body['expireTimestamp'] = (int) $expireTimestamp;
    $post_body['timestamp'] = (int) $now;

    $response = wp_remote_post($url, [
        'body' => json_encode($post_body),
        'headers' => ['Content-Type' => 'application/json'],
        'timeout' => 15
    ]);

    if (is_wp_error($response)) return [];

    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (isset($body['code']) && $body['code'] == 200 && !empty($body['data'])) {
        $links = [];
        foreach ($body['data'] as $item) {
            $links[] = [
                'title' => $item['examTitle'],
                'url' => $item['url']
            ];
        }
        return $links;
    }

    return [];
}


/**
 * FEATURE 2: Error Logging Helper
 */
function emathsmart_log_api_error($order_id, $type, $attempt, $payload, $response, $curl_error = '', $http_status = 0)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'emathsmart_log';

    // Parse response code if possible
    $response_code = null;
    if (!empty($response)) {
        $decoded = json_decode($response, true);
        if (isset($decoded['code'])) {
            $response_code = (int) $decoded['code'];
        }
    }

    $wpdb->insert($table_name, [
        'order_id' => $order_id,
        'api_type' => $type,
        'attempt' => $attempt,
        'request_payload' => is_array($payload) ? json_encode($payload) : $payload,
        'response_code' => $response_code,
        'response_body' => $response,
        'curl_error' => $curl_error,
        'http_status' => (int) $http_status,
        'created_at' => current_time('mysql')
    ]);
}

/**
 * Deferred Order Notes: Queue notes to be added AFTER WooCommerce finishes
 * its status transition (so our note appears above the "status changed" note)
 */
function emathsmart_defer_order_note($order_id, $note) {
    if (!isset($GLOBALS['emathsmart_pending_notes'])) {
        $GLOBALS['emathsmart_pending_notes'] = [];
        add_action('shutdown', 'emathsmart_flush_deferred_notes');
    }
    $GLOBALS['emathsmart_pending_notes'][] = ['order_id' => $order_id, 'note' => $note];
}

function emathsmart_flush_deferred_notes() {
    if (empty($GLOBALS['emathsmart_pending_notes'])) return;
    foreach ($GLOBALS['emathsmart_pending_notes'] as $pending) {
        $order = wc_get_order($pending['order_id']);
        if ($order) {
            $order->add_order_note($pending['note']);
        }
    }
}

function process_subscription_custom($order_id, $subscription_type = 'Payment', $debug = false)
{
    if (isset($order_id) && is_numeric($order_id) && $order_id > 0) {
        $order = wc_get_order($order_id);
        if (!$order)
            return;

        $max_attempts = 3;
        $attempt = 1;
        $success = false;

        while ($attempt <= $max_attempts && !$success) {
            $url = "";
            $now = time();
            $nonce = bin2hex(random_bytes(16));
            $sign_params = [];
            $post_body = [];

            if ($subscription_type == 'Payment') {
                $url = "https://test.emathsmart.ca/api/user-center/order/paymentNotify";

                $subscriptions = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
                $sub_data = ['billing_period' => '', 'next_payment' => '', 'trial_end' => ''];
                foreach ($subscriptions as $sub_obj) {
                    $sub_data['billing_period'] = $sub_obj->get_billing_period();
                    $sub_data['next_payment'] = $sub_obj->get_date('next_payment');
                    $sub_data['trial_end'] = $sub_obj->get_date('trial_end');
                }

                $expireTimestamp = $now + (365 * 86400);
                if (!empty($sub_data['next_payment'])) {
                    $expireTimestamp = strtotime($sub_data['next_payment']);
                }

                $subscriptionType = 2; // Default Month
                $trialType = 0;
                if (!empty($sub_data['trial_end'])) {
                    $subscriptionType = 1;
                    $trialType = 1;
                } else if ($sub_data['billing_period'] == "year") {
                    $subscriptionType = 3;
                }

                // Fix "The Register": Use real WCS Subscription ID instead of Order ID
                $wc_subscription_id = (string) $order_id; // Fallback
                if (function_exists('wcs_get_subscriptions_for_order')) {
                    $subs = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
                    foreach ($subs as $sub) {
                        $wc_subscription_id = (string) $sub->get_id();
                        break;
                    }
                }

                $sign_params = [
                    'appId' => 'ParentClub',
                    'timestamp' => (string) $now,
                    'nonce' => $nonce,
                    'orderId' => (string) $order_id,
                    'parentClubParentId' => (string) $order->get_user_id(),
                    'type' => '1',
                    'payStatus' => '1',
                    'payAmount' => number_format((float) $order->get_total(), 2, '.', ''),
                    'payTimestamp' => (string) $now,
                    'expireTimestamp' => (string) $expireTimestamp,
                    'subscriptionType' => (string) $subscriptionType,
                    'trialType' => (string) $trialType,
                    'parentClubSubscriptionId' => $wc_subscription_id,
                ];
                $post_body = $sign_params;
                $post_body['timestamp'] = (int) $now;
                $post_body['type'] = 1;
                $post_body['payStatus'] = 1;
                $post_body['payTimestamp'] = (int) $now;
                $post_body['expireTimestamp'] = (int) $expireTimestamp;
                $post_body['subscriptionType'] = (int) $subscriptionType;
                $post_body['trialType'] = (int) $trialType;

            } else if ($subscription_type == 'refund') {
                $url = "https://test.emathsmart.ca/api/user-center/order/refundNotify";
                
                $date_modified = $order->get_date_modified();
                $refundTimestamp = $date_modified ? $date_modified->getTimestamp() : $now;

                $sign_params = [
                    'appId'           => 'ParentClub',
                    'orderId'         => (string) $order_id,
                    'timestamp'       => (string) $now,
                    'nonce'           => $nonce,
                ];
                $post_body = $sign_params;
                $post_body['parentId'] = (string) $order->get_user_id();
                $post_body['refundTimestamp'] = (int) $refundTimestamp;
                $post_body['timestamp'] = (int) $now;

            } else if ($subscription_type == 'public_exams') {
                $url = "https://test.emathsmart.ca/api/customer-center/getPublicExamQuestions";
                $expireTimestamp = $now + (365 * 86400);

                // Fix "The Register": Use real WCS Subscription ID
                $wc_subscription_id = (string) $order_id;
                if (function_exists('wcs_get_subscriptions_for_order')) {
                    $subs = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
                    foreach ($subs as $sub) {
                        $wc_subscription_id = (string) $sub->get_id();
                        break;
                    }
                }

                // Note: expireTimestamp is EXCLUDED from signature
                $sign_params = [
                    'appId' => 'ParentClub',
                    'parentId' => (string) $order->get_user_id(),
                    'subscriptionId' => $wc_subscription_id,
                    'timestamp' => (string) $now,
                    'nonce' => $nonce,
                ];
                $post_body = $sign_params;
                $post_body['timestamp'] = (int) $now;
                $post_body['expireTimestamp'] = (int) $expireTimestamp;
            } else {
                // Unknown subscription type — bail out
                if ($debug) echo "<pre>Unknown subscription type: $subscription_type</pre>";
                return;
            }

            $secret = "yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm";
            
            // DEBUG OVERRIDE: Allow test suite to poison the request
            if (isset($GLOBALS['emathsmart_debug_override'])) {
                if ($GLOBALS['emathsmart_debug_override'] === 'wrong_key') {
                    $secret = "DEBUG_WRONG_KEY";
                } elseif ($GLOBALS['emathsmart_debug_override'] === 'dead_url') {
                    $url = "https://this-domain-does-not-exist-12345.com/api";
                }
            }

            ksort($sign_params);
            $pairs = [];
            foreach ($sign_params as $k => $v) {
                if ($v !== null)
                    $pairs[] = $k . '=' . $v;
            }
            $content = implode('&', $pairs);
            $hash = hash_hmac('sha256', $content, $secret, true);
            $signature = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');

            $post_body['signature'] = $signature;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_body, JSON_UNESCAPED_UNICODE));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            $response = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            curl_close($ch);

            $decoded = json_decode($response, true);
            $code = isset($decoded['code']) ? (int) $decoded['code'] : 0;

            // Log everything to the DB so the Live Demo works
            emathsmart_log_api_error($order_id, $subscription_type, $attempt, $post_body, $response, $curl_error, $http_status);

            if ($code === 200 || $code === 40101 || $code === 40201 || $code === 40202 || ($subscription_type === 'public_exams' && $code === 40303)) {
                $success = true;

                $api_label = ($subscription_type === 'refund') ? 'Refund Notify' : 'Payment Notify';
                $note = "eMathSmart $api_label: Synced ✅";
                
                if ($code === 40101 || $code === 40202) {
                    $note .= " (already processed)";
                } elseif ($code === 40201) {
                    $note .= " (parked - awaiting payment)";
                }
                
                emathsmart_defer_order_note($order_id, $note);
            } else {
                // Determine if we should retry
                $should_retry = false;
                // Add 20306 to retry list (observed in live tests for signature errors)
                if ($curl_error || $http_status >= 500 || in_array($code, [40001, 20306, 40002, 40003, 50000])) {
                    $should_retry = true;
                }

                if ($should_retry && $attempt < $max_attempts) {
                    $attempt++;
                    sleep(2); // Wait 2 seconds before retry
                    continue;
                } else {
                    // FEATURE 3: Specific Failure Notes
                    $api_label = ($subscription_type === 'refund') ? 'Refund Notify' : 'Payment Notify';
                    $error_msg = "eMathSmart $api_label: Failed ❌ after $attempt attempts.";
                    if ($code) $error_msg .= " (Code: $code)";
                    if ($curl_error) $error_msg .= " (cURL Error: $curl_error)";

                    if ($subscription_type === 'refund') {
                        if ($code === 40203) $error_msg = "eMathSmart Refund Notify: Failed ❌ Parent ID mismatch.";
                        if ($code === 40204) $error_msg = "eMathSmart Refund Notify: Failed ❌ Timestamp before payment.";
                    }

                    emathsmart_defer_order_note($order_id, $error_msg);
                    break;
                }
            }
        } // End While Loop

        if ($debug) {
            echo "<pre><h3>Final Result (" . $subscription_type . "):</h3>";
            echo "Success: " . ($success ? "YES" : "NO") . "\n";
            echo "Attempts: " . $attempt . "\n";
            echo "Last Response: " . htmlentities($response) . "\n";
            echo "</pre>";
        }
    }
}







/**
 * FEATURE 4: Admin UI - Resend to eMathSmart Button
 * Adds a custom action to the WooCommerce Order Actions metabox
 */
add_filter('woocommerce_order_actions', 'emathsmart_add_resend_order_action');
function emathsmart_add_resend_order_action($actions)
{
    global $theorder;
    // Only show for completed or refunded SUBSCRIPTION orders
    if ($theorder->has_status(['completed', 'refunded'])) {
        if (function_exists('wcs_get_subscriptions_for_order')) {
            $subscriptions = wcs_get_subscriptions_for_order($theorder->get_id(), array('order_type' => 'any'));
            if (!empty($subscriptions)) {
                $actions['emathsmart_resend'] = __('Resend to eMathSmart', 'woocommerce');
            }
        }
    }
    return $actions;
}

add_action('woocommerce_order_action_emathsmart_resend', 'emathsmart_process_resend_action');
function emathsmart_process_resend_action($order)
{
    $order_id = $order->get_id();
    $type = ($order->get_status() === 'refunded') ? 'refund' : 'Payment';
    
    // Add the trigger note FIRST so it appears below the result in the timeline
    $order->add_order_note(sprintf(__('Manual resend to eMathSmart triggered by %s.', 'woocommerce'), wp_get_current_user()->display_name));

    // Trigger the notification
    process_subscription_custom($order_id, $type, false);
}


/**
 * Inject Public Exam links into the Trial Expiration email
 */
add_action('woocommerce_email_customer_details', 'emathsmart_inject_exam_links_to_email', 20, 4);
add_action('woocommerce_subscriptions_email_order_details', 'emathsmart_inject_exam_links_to_email', 20, 4);
function emathsmart_inject_exam_links_to_email($order, $sent_to_admin, $plain_text, $email) {
    // Only target the Trial Expiration notification
    if ($email->id !== 'customer_notification_manual_trial_expiry' && $email->id !== 'customer_notification_auto_trial_expiry') {
        return;
    }

    $order_id = $order->get_id();
    $links = emathsmart_get_public_exam_links($order_id);

    if (empty($links)) {
        return;
    }

    if ($plain_text) {
        echo "\n" . strtoupper(__('Your Bonus Public Exam Files', 'book-junky')) . "\n";
        echo "---------------------------------------\n";
        foreach ($links as $link) {
            echo $link['title'] . ": " . $link['url'] . "\n";
        }
        echo "\n";
    } else {
        echo '<div style="margin-bottom: 40px; padding: 20px; border: 1px solid #e5e5e5; border-radius: 3px; background: #f9f9f9;">';
        echo '<h2 style="color: #c90000; display: block; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 15px; text-align: left;">' . __('📚 Download Your Exam Papers', 'book-junky') . '</h2>';
        echo '<p style="margin: 0 0 15px;">' . __('Here are the exam papers and answer keys included with your eMathSmart subscription:', 'book-junky') . '</p>';
        echo '<p>';
        foreach ($links as $link) {
            echo '<a href="' . esc_url($link['url']) . '" style="color: #c90000; font-weight: bold; text-decoration: underline;">' . esc_html($link['title']) . '</a>';
            echo '</br>';
        }
        echo '</p>';
        echo '<p style="font-size: 12px; color: #777; margin-top: 15px;">' . __('Note: These links will remain active while your trial is valid.', 'book-junky') . '</p>';
        echo '</div>';
    }
}

// ============================================================
// eMathSmart SSO Logout Endpoint
// When eMathSmart redirects the user to:
//   https://popularbook.ca/?emathsmart_logout=1
// This hook logs them out of WordPress and redirects home.
// ============================================================
add_action('init', 'emathsmart_handle_sso_logout');
function emathsmart_handle_sso_logout()
{
    if (!isset($_GET['emathsmart_logout']) || $_GET['emathsmart_logout'] !== '1') return;

    // Log out the current WordPress user (if logged in)
    if (is_user_logged_in()) {
        wp_logout();
    }

    // Redirect to homepage after logout
    wp_redirect(home_url('/'));
    exit;
}
