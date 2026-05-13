<?php
require_once('functions-esmart-debug.php');








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
add_action('woocommerce_order_status_completed', 'emathsmart_trigger_payment_notification', 10, 1);
function emathsmart_trigger_payment_notification($order_id)
{
    process_subscription_custom($order_id, 'Payment', false); // False = Silent mode for production
}

add_action('woocommerce_order_status_refunded', 'emathsmart_trigger_refund_notification', 10, 1);
function emathsmart_trigger_refund_notification($order_id)
{
    process_subscription_custom($order_id, 'refund', false); // False = Silent mode for production
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
                $url = "https://math-pro-cms.dcraysai.com/api/user-center/order/paymentNotify";

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

                $sign_params = [
                    'appId' => 'ParentClub',
                    'timestamp' => (string) $now,
                    'nonce' => $nonce,
                    'orderId' => (string) $order_id,
                    'parentClubParentId' => 'PID' . $order->get_user_id(),
                    'type' => '1',
                    'payStatus' => '1',
                    'payAmount' => number_format((float) $order->get_total(), 2, '.', ''),
                    'payTimestamp' => (string) $now,
                    'expireTimestamp' => (string) $expireTimestamp,
                    'subscriptionType' => (string) $subscriptionType,
                    'trialType' => (string) $trialType,
                    'parentClubSubscriptionId' => 'SID' . $order->get_user_id(),
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
                $url = "https://math-pro-cms.dcraysai.com/api/user-center/order/refundNotify";
                $sign_params = [
                    'appId' => 'ParentClub',
                    'orderId' => (string) $order_id,
                    'timestamp' => (string) $now,
                    'nonce' => $nonce,
                ];
                $post_body = $sign_params;
                $post_body['timestamp'] = (int) $now;

            } else if ($subscription_type == 'public_exams') {
                $url = "https://math-pro-cms.dcraysai.com/api/customer-center/getPublicExamQuestions";
                $expireTimestamp = $now + (365 * 86400);
                $sign_params = [
                    'appId' => 'ParentClub',
                    'parentId' => 'PID' . $order->get_user_id(),
                    'subscriptionId' => 'SID' . $order->get_user_id(),
                    'timestamp' => (string) $now,
                    'nonce' => $nonce,
                    'expireTimestamp' => (string) $expireTimestamp,
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

            if ($code === 200 || $code === 40101) {
                $success = true;

                // FEATURE 3: Add Success Note
                $note = ($code === 200) ? "eMathSmart: Synced ✅" : "eMathSmart: Synced ✅ (already processed)";
                $order->add_order_note($note);
            } else {
                // Log failure to DB
                emathsmart_log_api_error($order_id, $subscription_type, $attempt, $post_body, $response, $curl_error, $http_status);

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
                    // FEATURE 3: Add Failure Note after max attempts
                    $error_msg = "eMathSmart: Failed ❌ after $attempt attempts.";
                    if ($code)
                        $error_msg .= " (Code: $code)";
                    if ($curl_error)
                        $error_msg .= " (cURL Error: $curl_error)";
                    $order->add_order_note($error_msg);
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
    // Only show for completed or refunded orders
    if ($theorder->has_status(['completed', 'refunded'])) {
        $actions['emathsmart_resend'] = __('Resend to eMathSmart', 'woocommerce');
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

?>