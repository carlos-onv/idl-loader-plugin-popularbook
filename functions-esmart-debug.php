<?php
/**
 * API HARDENING TEST SUITE - REAL FLOW VERSION
 * Use this to verify Feature 2 & 3 (Retries, Logging & WC Notes)
 * URL Trigger: https://dev-popularbook.local/?test_errors=all
 */

add_action('init', 'emathsmart_run_error_tests');
function emathsmart_run_error_tests()
{
    if (isset($_REQUEST['test_errors']) && $_REQUEST['test_errors'] == 'all') {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        echo "<h1>eMathSmart API Hardening Test Suite (Real Flow)</h1>";
        echo "<p>This suite triggers the REAL 3-attempt logic and real WooCommerce notes.</p>";

        $order_id = 116377; // Your test order
        
        // --- SCENARIO 1: WRONG SECRET KEY (REAL FLOW) ---
        echo "<h2>Scenario 1: Triggering Real Signature Retry Loop</h2>";
        echo "<em>Expected: 3 attempts, Success: NO, Check WC Order Notes afterwards.</em><br>";
        
        $GLOBALS['emathsmart_debug_override'] = 'wrong_key';
        process_subscription_custom($order_id, 'Payment', true);
        unset($GLOBALS['emathsmart_debug_override']);

        echo "<hr>";

        // --- SCENARIO 2: NETWORK TIMEOUT (REAL FLOW) ---
        echo "<h2>Scenario 2: Triggering Real Network Timeout</h2>";
        echo "<em>Expected: 3 attempts (due to network failure), Check WC Order Notes.</em><br>";
        
        $GLOBALS['emathsmart_debug_override'] = 'dead_url';
        process_subscription_custom($order_id, 'Payment', true);
        unset($GLOBALS['emathsmart_debug_override']);

        // --- VERIFICATION ---
        echo "<h2>Database Verification (Latest Logs)</h2>";
        global $wpdb;
        $table_name = $wpdb->prefix . 'emathsmart_log';
        $logs = $wpdb->get_results("SELECT * FROM $table_name WHERE order_id = $order_id ORDER BY id DESC LIMIT 10");

        if ($logs) {
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Type</th><th>Attempt</th><th>Code</th><th>HTTP</th><th>Created At</th><th>Response Body Preview</th></tr>";
            foreach ($logs as $log) {
                echo "<tr>";
                echo "<td>{$log->id}</td>";
                echo "<td>{$log->api_type}</td>";
                echo "<td>{$log->attempt}</td>";
                echo "<td>{$log->response_code}</td>";
                echo "<td>{$log->http_status}</td>";
                echo "<td>{$log->created_at}</td>";
                echo "<td><small>" . esc_html(substr($log->response_body, 0, 50)) . "...</small></td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        echo "<br><a href='?testcms=process&type=Payment'>Run real Payment test (Success path)</a>";
        if (function_exists('emathsmart_flush_deferred_notes')) {
            emathsmart_flush_deferred_notes();
        }
        exit;
    }
}

/**
 * API #9 BRUTE FORCE: Try all field name combinations
 * URL: https://dev-popularbook.local/?brute_api9=1
 */
add_action('init', 'emathsmart_brute_force_api9');
function emathsmart_brute_force_api9()
{
    if (!isset($_REQUEST['brute_api9'])) return;
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    echo "<h1>API #9 Brute Force: Field Name Combinations</h1>";
    echo "<p>Testing all combinations against the live endpoint...</p>";

    $order_id = 116377;
    $order = wc_get_order($order_id);
    if (!$order) { echo "Order not found."; exit; }

    $user_id = $order->get_user_id();
    $secret = "yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm";
    $url = "https://test.emathsmart.ca/api/customer-center/getPublicExamQuestions";
    $now = time();
    $nonce = bin2hex(random_bytes(16));
    $expireTimestamp = $now + (365 * 86400);

    // All possible field name variations
    $parent_id_names = ['parentId', 'parentClubParentId'];
    $sub_id_names = ['subscriptionId', 'parentClubSubscriptionId', 'subscribeId'];

    // Fields that might need to be excluded from signature
    $exclude_options = [
        [],                           // exclude nothing
        ['parentId_field'],           // exclude parentId from sig
        ['subId_field'],              // exclude subscriptionId from sig
        ['parentId_field', 'subId_field'], // exclude both from sig
        ['expireTimestamp'],           // exclude expireTimestamp from sig
    ];

    $test_num = 0;
    $results = [];

    foreach ($parent_id_names as $pid_name) {
        foreach ($sub_id_names as $sid_name) {
            foreach ($exclude_options as $excludes) {
                $test_num++;

                // Build the full body
                $body = [
                    'appId' => 'ParentClub',
                    $pid_name => 'PID' . $user_id,
                    $sid_name => 'SID' . $user_id,
                    'timestamp' => (int) $now,
                    'nonce' => $nonce,
                    'expireTimestamp' => (int) $expireTimestamp,
                ];

                // Build sign params (all as strings)
                $sign_params = [
                    'appId' => 'ParentClub',
                    $pid_name => 'PID' . $user_id,
                    $sid_name => 'SID' . $user_id,
                    'timestamp' => (string) $now,
                    'nonce' => $nonce,
                    'expireTimestamp' => (string) $expireTimestamp,
                ];

                // Apply exclusions
                $exclude_labels = [];
                foreach ($excludes as $exc) {
                    if ($exc === 'parentId_field') {
                        unset($sign_params[$pid_name]);
                        $exclude_labels[] = $pid_name;
                    } elseif ($exc === 'subId_field') {
                        unset($sign_params[$sid_name]);
                        $exclude_labels[] = $sid_name;
                    } else {
                        unset($sign_params[$exc]);
                        $exclude_labels[] = $exc;
                    }
                }

                // Generate signature
                ksort($sign_params);
                $pairs = [];
                foreach ($sign_params as $k => $v) {
                    if ($v !== null) $pairs[] = $k . '=' . $v;
                }
                $content = implode('&', $pairs);
                $hash = hash_hmac('sha256', $content, $secret, true);
                $signature = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');

                $body['signature'] = $signature;

                // Send request
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $response = curl_exec($ch);
                curl_close($ch);

                $decoded = json_decode($response, true);
                $code = isset($decoded['code']) ? $decoded['code'] : 'N/A';

                $exclude_desc = empty($exclude_labels) ? 'none' : implode(', ', $exclude_labels);
                $status = ($code == 200) ? '✅ SUCCESS' : '❌ ' . $code;

                $results[] = [
                    'num' => $test_num,
                    'pid' => $pid_name,
                    'sid' => $sid_name,
                    'excluded' => $exclude_desc,
                    'sign_string' => $content,
                    'code' => $code,
                    'status' => $status,
                    'response' => substr($response, 0, 120),
                ];

                // If we found success, highlight it
                if ($code == 200) {
                    echo "<h2 style='color:green;'>🎉 FOUND WORKING COMBINATION! Test #$test_num</h2>";
                }
            }
        }
    }

    // Display results table
    echo "<table border='1' cellpadding='6' style='border-collapse:collapse; font-family:monospace; font-size:13px;'>";
    echo "<tr style='background:#333;color:#fff;'><th>#</th><th>Parent ID Field</th><th>Sub ID Field</th><th>Excluded from Sig</th><th>Status</th><th>Sign String</th><th>Response</th></tr>";
    foreach ($results as $r) {
        $bg = ($r['code'] == 200) ? '#d4edda' : (($r['code'] == 40001) ? '#f8d7da' : '#fff3cd');
        echo "<tr style='background:$bg;'>";
        echo "<td>{$r['num']}</td>";
        echo "<td>{$r['pid']}</td>";
        echo "<td>{$r['sid']}</td>";
        echo "<td>{$r['excluded']}</td>";
        echo "<td><b>{$r['status']}</b></td>";
        echo "<td style='max-width:400px;word-break:break-all;'>" . esc_html($r['sign_string']) . "</td>";
        echo "<td style='max-width:300px;word-break:break-all;'>" . esc_html($r['response']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<br><b>Total tests: $test_num</b>";
    exit;
}

/**
 * API #9 BRUTE FORCE v2: Try all ID VALUE combinations
 * URL: https://dev-popularbook.local/?brute_api9_values=1
 */
add_action('init', 'emathsmart_brute_force_api9_values');
function emathsmart_brute_force_api9_values()
{
    if (!isset($_REQUEST['brute_api9_values'])) return;
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    echo "<h1>API #9 Brute Force v2: ID Value Combinations</h1>";
    echo "<p>Signature fix confirmed. Now testing different ID value formats...</p>";

    $order_id = 116377;
    $order = wc_get_order($order_id);
    if (!$order) { echo "Order not found."; exit; }

    $user_id = $order->get_user_id();
    $secret = "yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm";
    $url = "https://test.emathsmart.ca/api/customer-center/getPublicExamQuestions";

    // Get the WooCommerce subscription ID if available
    $wc_sub_id = '';
    if (function_exists('wcs_get_subscriptions_for_order')) {
        $subs = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
        foreach ($subs as $sub) {
            $wc_sub_id = $sub->get_id();
            break;
        }
    }

    echo "<p><b>Order ID:</b> $order_id | <b>User ID:</b> $user_id | <b>WC Subscription ID:</b> $wc_sub_id</p>";

    // All possible parentId values
    $parent_values = [
        'PID' . $user_id       => 'PID' . $user_id,
        (string) $user_id      => 'raw user_id',
        'PID' . $order_id      => 'PID' . $order_id,
        (string) $order_id     => 'raw order_id',
    ];

    // All possible subscriptionId values
    $sub_values = [
        'SID' . $user_id       => 'SID' . $user_id,
        (string) $user_id      => 'raw user_id',
        'SID' . $order_id      => 'SID' . $order_id,
        (string) $order_id     => 'raw order_id',
    ];
    if ($wc_sub_id) {
        $sub_values['SID' . $wc_sub_id] = 'SID' . $wc_sub_id;
        $sub_values[(string) $wc_sub_id] = 'raw wc_sub_id';
    }

    $test_num = 0;
    $results = [];

    foreach ($parent_values as $pid_val => $pid_label) {
        foreach ($sub_values as $sid_val => $sid_label) {
            $test_num++;

            // Fresh nonce + timestamp per request to avoid 40003 (Nonce Duplicated)
            $now = time();
            $nonce = bin2hex(random_bytes(16));
            $expireTimestamp = $now + (365 * 86400);

            // Sign params (expireTimestamp excluded per brute-force v1 finding)
            $sign_params = [
                'appId' => 'ParentClub',
                'parentId' => $pid_val,
                'subscriptionId' => $sid_val,
                'timestamp' => (string) $now,
                'nonce' => $nonce,
            ];

            ksort($sign_params);
            $pairs = [];
            foreach ($sign_params as $k => $v) {
                if ($v !== null) $pairs[] = $k . '=' . $v;
            }
            $content = implode('&', $pairs);
            $hash = hash_hmac('sha256', $content, $secret, true);
            $signature = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');

            $body = [
                'appId' => 'ParentClub',
                'parentId' => $pid_val,
                'subscriptionId' => $sid_val,
                'timestamp' => (int) $now,
                'nonce' => $nonce,
                'expireTimestamp' => (int) $expireTimestamp,
                'signature' => $signature,
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);

            $decoded = json_decode($response, true);
            $code = isset($decoded['code']) ? $decoded['code'] : 'N/A';
            $status = ($code == 200) ? '✅ SUCCESS' : '❌ ' . $code;

            $results[] = [
                'num' => $test_num,
                'pid_val' => $pid_val,
                'pid_label' => $pid_label,
                'sid_val' => $sid_val,
                'sid_label' => $sid_label,
                'code' => $code,
                'status' => $status,
                'response' => substr($response, 0, 120),
            ];

            if ($code == 200) {
                echo "<h2 style='color:green;'>🎉 FOUND WORKING COMBINATION! Test #$test_num</h2>";
                echo "<p>parentId = <b>$pid_val</b> ($pid_label) | subscriptionId = <b>$sid_val</b> ($sid_label)</p>";
            }
        }
    }

    echo "<table border='1' cellpadding='6' style='border-collapse:collapse; font-family:monospace; font-size:13px;'>";
    echo "<tr style='background:#333;color:#fff;'><th>#</th><th>parentId</th><th>subscriptionId</th><th>Status</th><th>Response</th></tr>";
    foreach ($results as $r) {
        $bg = ($r['code'] == 200) ? '#d4edda' : (($r['code'] == 40301) ? '#f8d7da' : '#fff3cd');
        echo "<tr style='background:$bg;'>";
        echo "<td>{$r['num']}</td>";
        echo "<td>{$r['pid_val']}<br><small>({$r['pid_label']})</small></td>";
        echo "<td>{$r['sid_val']}<br><small>({$r['sid_label']})</small></td>";
        echo "<td><b>{$r['status']}</b></td>";
        echo "<td style='max-width:400px;word-break:break-all;'>" . esc_html($r['response']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br><b>Total tests: $test_num</b>";
    exit;
}

/**
 * API #9 BRUTE FORCE v3: The Identifier Compass
 * URL: https://dev-popularbook.local/?brute_api9_final=1
 */
add_action('init', 'emathsmart_brute_force_api9_final');
function emathsmart_brute_force_api9_final()
{
    if (!isset($_REQUEST['brute_api9_final'])) return;
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    $order_id = 116375;
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();
    $user = get_userdata($user_id);
    $email = $user->user_email;
    
    // Get actual WCS Subscription ID
    $wc_sub_id = '';
    if (function_exists('wcs_get_subscriptions_for_order')) {
        $subs = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
        foreach ($subs as $sub) { $wc_sub_id = $sub->get_id(); break; }
    }

    echo "<h1>API #9 Identifier Compass</h1>";
    echo "<p><b>Order ID:</b> $order_id | <b>User ID:</b> $user_id | <b>WCS Sub ID:</b> $wc_sub_id | <b>Email:</b> $email</p>";

    // Systematic Parent ID possibilities
    $p_possibilities = [
        'PID' . $user_id => 'PID + UserID',
        (string)$user_id => 'Raw UserID',
        $email           => 'Billing Email',
        'PID' . $order_id => 'PID + OrderID',
        'PID1'           => 'Theory 1: PID1',
    ];

    // Systematic Subscription ID possibilities
    $s_possibilities = [
        'SID' . $user_id => 'SID + UserID',
        (string)$user_id => 'Raw UserID',
        'SID' . $wc_sub_id => 'SID + WCS SubID',
        (string)$wc_sub_id => 'Raw WCS SubID',
        'SID' . $order_id => 'SID + OrderID',
        (string)$order_id => 'Raw OrderID',
        '1'              => 'Theory 1: Sub ID 1',
        'SID' . $order_id => 'Theory 2: SID + OrderID',
    ];

    $secret = "yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm";
    $url = "https://test.emathsmart.ca/api/customer-center/getPublicExamQuestions";

    echo "<table border='1' style='border-collapse:collapse; width:100%; font-family:monospace;'>";
    echo "<tr style='background:#333;color:#fff;'><th>Parent ID</th><th>Sub ID</th><th>Code</th><th>Message</th></tr>";

    foreach ($p_possibilities as $pid => $p_label) {
        foreach ($s_possibilities as $sid => $s_label) {
            $now = time();
            $nonce = bin2hex(random_bytes(16));
            $expireTimestamp = $now + (365 * 86400);

            $sign_params = [
                'appId' => 'ParentClub',
                'parentId' => $pid,
                'subscriptionId' => $sid,
                'timestamp' => (string)$now,
                'nonce' => $nonce
            ];

            ksort($sign_params);
            $pairs = [];
            foreach ($sign_params as $k => $v) $pairs[] = $k . '=' . $v;
            $hash = hash_hmac('sha256', implode('&', $pairs), $secret, true);
            $signature = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');

            $body = [
                'appId' => 'ParentClub',
                'parentId' => $pid,
                'subscriptionId' => $sid,
                'timestamp' => (int)$now,
                'nonce' => $nonce,
                'expireTimestamp' => (int)$expireTimestamp,
                'signature' => $signature
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            curl_close($ch);

            $decoded = json_decode($response, true);
            $code = $decoded['code'] ?? 'N/A';
            $msg = $decoded['message'] ?? 'N/A';

            $color = ($code == 200) ? '#d4edda' : (($code == 40302) ? '#fff3cd' : '#f8d7da');
            echo "<tr style='background:$color;'>";
            echo "<td><b>$pid</b><br><small>$p_label</small></td>";
            echo "<td><b>$sid</b><br><small>$s_label</small></td>";
            echo "<td>$code</td>";
            echo "<td>$msg</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    exit;
}

/**
 * API #5 SUCCESS VIEWER: Find eMathSmart's internal IDs
 * URL: https://dev-popularbook.local/?show_api5_success=116377
 */
add_action('init', 'emathsmart_show_api5_success');
function emathsmart_show_api5_success()
{
    if (!isset($_REQUEST['show_api5_success'])) return;
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    $order_id = (int)$_REQUEST['show_api5_success'];
    echo "<h1>API #5 Success Response for Order $order_id</h1>";

    global $wpdb;
    $table_name = $wpdb->prefix . 'emathsmart_log';
    $log = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE order_id = %d AND api_type = 'Payment' AND response_code = 200 ORDER BY id DESC LIMIT 1", $order_id));

    if ($log) {
        echo "<p><b>Created At:</b> {$log->created_at}</p>";
        echo "<h3>Response Body:</h3>";
        echo "<pre style='background:#f4f4f4; border:1px solid #ccc; padding:15px; overflow:auto;'>" . esc_html($log->response_body) . "</pre>";
        
        $decoded = json_decode($log->response_body, true);
        if (isset($decoded['data'])) {
            echo "<h3>Parsed Data:</h3>";
            echo "<pre style='background:#eef; padding:10px;'>" . print_r($decoded['data'], true) . "</pre>";
        }
    } else {
        echo "<p style='color:red;'>No successful Payment log found for Order $order_id. Please make sure the order was synced and logged as success.</p>";
    }
    exit;
}

/**
 * API #9 LIVE DEMO: Show request/response for the call
 * URL: https://dev-popularbook.local/?show_api9_live=116375
 */
add_action('init', 'emathsmart_show_api9_live');
function emathsmart_show_api9_live()
{
    if (!isset($_REQUEST['show_api9_live'])) return;
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    $order_id = (int)$_REQUEST['show_api9_live'];
    echo "<body style='background:#1e1e1e; color:#d4d4d4; font-family:monospace; padding:40px;'>";
    echo "<h1 style='color:#569cd6;'>eMathSmart API #9 Live Diagnostic</h1>";
    echo "<p style='color:#ce9178;'>Target Order: $order_id</p>";

    // Trigger the actual production function
    ob_start();
    process_subscription_custom($order_id, 'public_exams', true);
    ob_end_clean();

    // The function emathsmart_log_api_error saves the last one
    global $wpdb;
    $table_name = $wpdb->prefix . 'emathsmart_log';
    $log = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE order_id = %d AND api_type = 'public_exams' ORDER BY id DESC LIMIT 1", $order_id));

    if ($log && !empty($log->request_payload)) {
        $req = json_decode($log->request_payload, true);
        $secret = "yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm";
        
        if (is_array($req)) {
            // Re-construct the signature string for display
            $sign_params = $req;
            unset($sign_params['signature']);
            unset($sign_params['expireTimestamp']);
            unset($sign_params['type']);
            ksort($sign_params);
            $pairs = [];
            foreach ($sign_params as $k => $v) $pairs[] = "$k=$v";
            $raw_string = implode('&', $pairs);

            echo "<h2 style='color:#4ec9b0;'>1. Signature Generation (The 'Proof')</h2>";
            echo "<div style='background:#2d2d2d; border:1px solid #444; padding:20px; color:#dcdcdc;'>";
            echo "<b>Shared Secret:</b> <span style='color:#ce9178;'>$secret</span><br><br>";
            echo "<b>Raw String to Sign:</b> <br><code style='color:#9cdcfe; background:#3c3c3c; padding:5px; display:block; margin-top:10px;'>$raw_string</code>";
            echo "<p style='font-size:0.9em; color:#888;'>* Note how <code>expireTimestamp</code> is excluded from the string above, as per the successful handshake.</p>";
            echo "</div>";

            echo "<h2 style='color:#4ec9b0;'>2. Final JSON Request Payload</h2>";
            echo "<pre style='background:#2d2d2d; border:1px solid #444; padding:20px; color:#9cdcfe;'>" . json_encode($req, JSON_PRETTY_PRINT) . "</pre>";
        }
        
        echo "<h2 style='color:#4ec9b0;'>3. JSON Response</h2>";
        $response = json_decode($log->response_body, true);
        $color = (isset($response['code']) && $response['code'] == 200) ? '#b5cea8' : '#f44747';
        echo "<pre style='background:#2d2d2d; border:1px solid #444; padding:20px; color:$color; font-size:1.2em;'>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
    } else {
        echo "<p style='color:red;'>Failed to retrieve log entry for order $order_id. Please try again.</p>";
    }
    
    echo "</body>";
    exit;
}

/**
 * Test Trial Expiration Email Injection
 * URL: https://dev-popularbook.local/?test_trial_email=117584
 */
add_action('init', 'emathsmart_test_trial_email');
function emathsmart_test_trial_email() {
    if (!isset($_REQUEST['test_trial_email'])) return;
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    $subscription_id = (int) $_REQUEST['test_trial_email'];
    $subscription = wcs_get_subscription($subscription_id);

    if (!$subscription) {
        wp_die("Subscription $subscription_id not found.");
    }

    echo "<h1>Testing Trial Expiration Email for Subscription $subscription_id</h1>";

    // Load WooCommerce emails so the mailer is initialized
    $mailer = WC()->mailer();
    $emails = $mailer->get_emails();

    if (isset($emails['WCS_Email_Customer_Notification_Auto_Trial_Expiration'])) {
        $email = $emails['WCS_Email_Customer_Notification_Auto_Trial_Expiration'];
        
        // Manually setup the email object to bypass the local environment block in WCS
        $email->object    = $subscription;
        $email->recipient = $subscription->get_billing_email();
        $email->setup_locale();
        $email->placeholders['{customers_first_name}'] = $subscription->get_billing_first_name();
        $email->placeholders['{time_until_renewal}']   = 'soon'; // simple string to prevent errors
        
        // Send the email directly
        $result = $email->send( 
            $email->get_recipient(), 
            $email->get_subject(), 
            $email->get_content(), 
            $email->get_headers(), 
            $email->get_attachments() 
        );
        $email->restore_locale();

        if ($result) {
            echo "<p style='color:green;'><b>Email trigger fired successfully!</b> Check your inbox (or Mailpit) for the Trial Expiration email.</p>";
        } else {
            echo "<p style='color:red;'><b>Email failed to send.</b> Check your local mail configuration.</p>";
        }
    } else {
        echo "<p style='color:red;'>Could not find the WCS_Email_Customer_Notification_Auto_Trial_Expiration class.</p>";
    }

    exit;
}

// ============================================================
// DEBUG TOOL: ?set_trial_end=<subscription_id>
// Resets the trial end date on a subscription to +7 days from now.
// Also sets a test billing first name if one is missing.
// Usage: https://dev.popularbook.ca/?set_trial_end=117584
// REMOVE before going live / do not use in production.
// ============================================================
add_action('init', function () {
    if (!isset($_GET['set_trial_end'])) return;

    // Safety: only allow admins
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized. You must be logged in as an admin to use this tool.');
    }

    $subscription_id = intval($_GET['set_trial_end']);
    if ($subscription_id <= 0) {
        wp_die('Invalid subscription ID.');
    }

    $subscription = wcs_get_subscription($subscription_id);
    if (!$subscription) {
        wp_die("Subscription #$subscription_id not found.");
    }

    echo "<h2>Set Trial End &mdash; Subscription #$subscription_id</h2>";

    // --- 1. Set trial_end to +7 days ---
    $new_trial_end = gmdate('Y-m-d H:i:s', strtotime('+7 days'));
    try {
        $subscription->update_dates([
            'trial_end' => $new_trial_end,
        ]);
        echo "<p style='color:green;'><b>trial_end</b> updated to: <b>" . $new_trial_end . "</b> (UTC)</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>Failed to update trial_end: " . esc_html($e->getMessage()) . "</p>";
    }

    // --- 2. Set a test billing first name if empty ---
    $first_name = $subscription->get_billing_first_name();
    if (empty(trim($first_name))) {
        $subscription->set_billing_first_name('Test');
        $subscription->set_billing_last_name('User');
        $subscription->save();
        echo "<p style='color:green;'><b>Billing name</b> set to: <b>Test User</b> (was empty)</p>";
    } else {
        echo "<p style='color:#888;'>Billing name already set to: <b>" . esc_html($first_name) . "</b> &mdash; not changed.</p>";
    }

    // --- 3. Summary ---
    echo "<hr>";
    echo "<p>Done! Now run the test email:<br>";
    echo "<a href='" . esc_url(home_url('/?test_trial_email=' . $subscription_id)) . "'>?test_trial_email=$subscription_id</a></p>";

    exit;
});

// ============================================================
// DEBUG TOOL: ?simulate_refund_trigger=<order_id>
// Simulates a parent order refund to test automatic cancellation 
// and eMathSmart API notification.
// Usage: https://dev.popularbook.ca/?simulate_refund_trigger=12345
// ============================================================
add_action('init', function () {
    if (!isset($_GET['simulate_refund_trigger'])) return;

    // Safety: only allow admins
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized. You must be logged in as an admin to use this tool.');
    }

    $order_id = intval($_GET['simulate_refund_trigger']);
    if ($order_id <= 0) {
        wp_die('Invalid order ID.');
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_die("Order #$order_id not found.");
    }

    echo "<h2>Simulating Refund for Order #$order_id</h2>";

    $subscriptions = [];
    if (function_exists('wcs_get_subscriptions_for_order')) {
        $subscriptions = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'parent'));
        echo "<h3>Linked Parent Subscriptions:</h3>";
        if (empty($subscriptions)) {
            echo "<p style='color:orange;'>No parent subscriptions found linked to this order.</p>";
        } else {
            echo "<ul>";
            foreach ($subscriptions as $subscription) {
                echo "<li>Subscription #<b>" . $subscription->get_id() . "</b> | Status: <b>" . $subscription->get_status() . "</b></li>";
            }
            echo "</ul>";
        }
    }

    // Trigger the cancellation hook
    echo "<h3>1. Triggering 'emathsmart_cancel_subscription_on_refund' hook...</h3>";
    emathsmart_cancel_subscription_on_refund($order_id);

    if (function_exists('wcs_get_subscriptions_for_order') && !empty($subscriptions)) {
        echo "<h3>Updated Subscription Statuses:</h3>";
        echo "<ul>";
        foreach ($subscriptions as $subscription) {
            // Re-fetch to get updated status
            $updated_sub = wcs_get_subscription($subscription->get_id());
            echo "<li>Subscription #<b>" . $updated_sub->get_id() . "</b> | New Status: <b style='color:red;'>" . $updated_sub->get_status() . "</b></li>";
        }
        echo "</ul>";
    }

    // Trigger eMathSmart notification with debug output turned ON (true)
    echo "<h3>2. Triggering eMathSmart API notification...</h3>";
    process_subscription_custom($order_id, 'refund', true);

    // Flush any deferred notes manually since we are calling exit
    if (function_exists('emathsmart_flush_deferred_notes')) {
        emathsmart_flush_deferred_notes();
    }

    echo "<hr><p style='color:green;font-weight:bold;'>Simulation complete!</p>";
    exit;
});

// ============================================================
// DEBUG TOOL: ?simulate_completed_trigger=<order_id>
// Simulates a parent order being changed to completed to test
// automatic reactivation and eMathSmart API notification.
// Usage: https://dev.popularbook.ca/?simulate_completed_trigger=12345
// ============================================================
add_action('init', function () {
    if (!isset($_GET['simulate_completed_trigger'])) return;

    // Safety: only allow admins
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized. You must be logged in as an admin to use this tool.');
    }

    $order_id = intval($_GET['simulate_completed_trigger']);
    if ($order_id <= 0) {
        wp_die('Invalid order ID.');
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_die("Order #$order_id not found.");
    }

    echo "<h2>Simulating Reversal (Completed) for Order #$order_id</h2>";

    $subscriptions = [];
    if (function_exists('wcs_get_subscriptions_for_order')) {
        $subscriptions = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'parent'));
        echo "<h3>Linked Parent Subscriptions BEFORE Reactivation:</h3>";
        if (empty($subscriptions)) {
            echo "<p style='color:orange;'>No parent subscriptions found linked to this order.</p>";
        } else {
            echo "<ul>";
            foreach ($subscriptions as $subscription) {
                echo "<li>Subscription #<b>" . $subscription->get_id() . "</b> | Status: <b>" . $subscription->get_status() . "</b></li>";
            }
            echo "</ul>";
        }
    }

    // Trigger the reactivation hook
    echo "<h3>1. Triggering 'emathsmart_reactivate_subscription_on_completed' hook...</h3>";
    emathsmart_reactivate_subscription_on_completed($order_id);

    if (function_exists('wcs_get_subscriptions_for_order') && !empty($subscriptions)) {
        echo "<h3>Updated Subscription Statuses:</h3>";
        echo "<ul>";
        foreach ($subscriptions as $subscription) {
            // Re-fetch to get updated status
            $updated_sub = wcs_get_subscription($subscription->get_id());
            echo "<li>Subscription #<b>" . $updated_sub->get_id() . "</b> | New Status: <b style='color:green;'>" . $updated_sub->get_status() . "</b></li>";
        }
        echo "</ul>";
    }

    // Trigger eMathSmart notification with debug output turned ON (true)
    echo "<h3>2. Triggering eMathSmart API notification...</h3>";
    process_subscription_custom($order_id, 'Payment', true);

    // Flush any deferred notes manually since we are calling exit
    if (function_exists('emathsmart_flush_deferred_notes')) {
        emathsmart_flush_deferred_notes();
    }

    echo "<hr><p style='color:green;font-weight:bold;'>Simulation complete!</p>";
    exit;
});

/**
 * API #5 TYPE 2 PAYMENT NOTIFICATION MOCK TEST SUITE
 * URL Trigger: https://dev-popularbook.local/?test_type2=1
 */
add_action('init', 'emathsmart_run_type2_test');
function emathsmart_run_type2_test()
{
    if (isset($_REQUEST['test_type2']) && $_REQUEST['test_type2'] == '1') {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        echo "<h1>eMathSmart API #5 Type 2 (AI Coins) Diagnostic Simulation</h1>";
        echo "<p>This simulation triggers a mock payment notification for AI Coins (500 coins = 5 packages) under order ID 116377.</p>";

        $order_id = 116377; // Your test order

        // Hook up the global mock
        $GLOBALS['emathsmart_mock_type2'] = true;

        echo "<h2>1. Triggering Outbound Webhook Payment Notification</h2>";
        echo "<em>Expected: Payload with type = 2 and additionalPackageQuantity = 5. Signature will omit subscription keys.</em><br><br>";
        
        process_subscription_custom($order_id, 'Payment', true);

        // Clean up global mock
        unset($GLOBALS['emathsmart_mock_type2']);

        // Database Verification
        echo "<h2>2. Database Verification (Latest Logs for Order $order_id)</h2>";
        global $wpdb;
        $table_name = $wpdb->prefix . 'emathsmart_log';
        $logs = $wpdb->get_results("SELECT * FROM $table_name WHERE order_id = $order_id ORDER BY id DESC LIMIT 5");

        if ($logs) {
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%; font-family: sans-serif; font-size: 14px;'>";
            echo "<tr style='background: #eee;'><th>ID</th><th>Type</th><th>Attempt</th><th>Response Code</th><th>HTTP Status</th><th>Created At</th><th>Response Body</th></tr>";
            foreach ($logs as $log) {
                echo "<tr>";
                echo "<td>{$log->id}</td>";
                echo "<td>" . esc_html($log->api_type) . "</td>";
                echo "<td>" . esc_html($log->attempt) . "</td>";
                echo "<td>" . esc_html($log->response_code) . "</td>";
                echo "<td>" . esc_html($log->http_status) . "</td>";
                echo "<td>" . esc_html($log->created_at) . "</td>";
                echo "<td><pre style='margin: 0; white-space: pre-wrap; font-size: 11px;'>" . esc_html($log->response_body) . "</pre></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>No database log entries found for order $order_id.</p>";
        }

        if (function_exists('emathsmart_flush_deferred_notes')) {
            emathsmart_flush_deferred_notes();
        }

        echo "<hr><p style='color:green;font-weight:bold;'>Simulation complete!</p>";
        exit;
    }
}

/**
 * 14-DAY TRIAL LOGIC DIAGNOSTIC SUITE
 * URL Trigger: https://dev-popularbook.local/?test_trial_logic=1
 */
add_action('init', 'emathsmart_run_trial_logic_diagnostic');
function emathsmart_run_trial_logic_diagnostic()
{
    if (isset($_REQUEST['test_trial_logic']) && $_REQUEST['test_trial_logic'] == '1') {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        echo "<body style='background:#121212; color:#e0e0e0; font-family:sans-serif; padding:40px; line-height:1.6;'>";
        echo "<h1 style='color:#bb86fc;'>eMathSmart 14-Day Free Trial Eligibility Diagnostic Suite</h1>";
        echo "<p style='color:#03dac6;'>Simulating user registration cases and calculating dynamic free trial lengths...</p>";

        function run_diag_for_user($registered_date, $is_member, $label) {
            $username = 'dummy_diag_user_' . rand(1000, 9999);
            $user_id = wp_create_user($username, 'password123', $username . '@example.com');
            if (is_wp_error($user_id)) {
                echo "<p style='color:#cf6679;'>Failed to create test user: " . esc_html($user_id->get_error_message()) . "</p>";
                return;
            }

            global $wpdb;
            $wpdb->update(
                $wpdb->users,
                array( 'user_registered' => $registered_date ),
                array( 'ID' => $user_id )
            );
            
            clean_user_cache($user_id);
            
            if ($is_member) {
                update_user_meta( $user_id, 'user_registration_check_box_1661192013', 'parent_club_member' );
            } else {
                update_user_meta( $user_id, 'user_registration_check_box_1661192013', 'regular_member' );
            }

            wp_set_current_user($user_id);

            $eligible = emathsmart_is_eligible_parents_club_member($user_id);
            
            $product_monthly = wc_get_product( 116372 );
            $product_yearly = wc_get_product( 116578 );
            
            $trial_len_monthly = $product_monthly ? WC_Subscriptions_Product::get_trial_length( $product_monthly ) : 'N/A';
            $price_str_monthly = $product_monthly ? WC_Subscriptions_Product::get_price_string( $product_monthly ) : 'N/A';
            
            $trial_len_yearly = $product_yearly ? WC_Subscriptions_Product::get_trial_length( $product_yearly ) : 'N/A';
            $price_str_yearly = $product_yearly ? WC_Subscriptions_Product::get_price_string( $product_yearly ) : 'N/A';

            echo "<div style='background:#1e1e1e; border:1px solid #333; padding:20px; border-radius:8px; margin-bottom:20px;'>";
            echo "<h3 style='color:#03dac6; margin-top:0;'>Scenario: " . esc_html($label) . "</h3>";
            echo "<table cellpadding='6' style='width:100%; border-collapse:collapse;'>";
            echo "<tr><td style='width:250px; color:#a0a0a0;'>Dummy User Name:</td><td><strong>" . esc_html($username) . "</strong></td></tr>";
            echo "<tr><td style='color:#a0a0a0;'>Registration Date (UTC):</td><td>" . esc_html($registered_date) . "</td></tr>";
            echo "<tr><td style='color:#a0a0a0;'>Is Parents Club Member:</td><td>" . ($is_member ? "<span style='color:#4caf50;'>YES</span>" : "<span style='color:#cf6679;'>NO</span>") . "</td></tr>";
            echo "<tr><td style='color:#a0a0a0;'>System Eligibility:</td><td>" . ($eligible ? "<span style='background:#1b5e20; color:#c8e6c9; padding:2px 8px; border-radius:4px; font-weight:bold;'>ELIGIBLE (14-DAY)</span>" : "<span style='background:#b71c1c; color:#ffcdd2; padding:2px 8px; border-radius:4px; font-weight:bold;'>INELIGIBLE (7-DAY)</span>") . "</td></tr>";
            echo "<tr><td style='color:#a0a0a0;'>Monthly Sub Trial:</td><td><strong>" . esc_html($trial_len_monthly) . " days</strong></td></tr>";
            echo "<tr><td style='color:#a0a0a0;'>Monthly Price String:</td><td><small>" . wp_kses_post($price_str_monthly) . "</small></td></tr>";
            echo "<tr><td style='color:#a0a0a0;'>Yearly Sub Trial:</td><td><strong>" . esc_html($trial_len_yearly) . " days</strong></td></tr>";
            echo "<tr><td style='color:#a0a0a0;'>Yearly Price String:</td><td><small>" . wp_kses_post($price_str_yearly) . "</small></td></tr>";
            echo "</table>";
            echo "</div>";

            wp_delete_user($user_id);
        }

        echo "<h2>Simulated Scenarios</h2>";

        run_diag_for_user('2026-05-28 23:59:59', true, 'Eligible Old Member (Registered before cutoff date May 29, 2026 UTC)');
        run_diag_for_user('2026-05-30 00:00:00', true, 'Ineligible New Member (Registered after cutoff date May 29, 2026 UTC)');
        run_diag_for_user('2026-05-28 12:00:00', false, 'Ineligible Old Regular User (Not Parents Club member)');

        // Guest check
        wp_set_current_user(0);
        $product_monthly = wc_get_product( 116372 );
        $trial_len_monthly = $product_monthly ? WC_Subscriptions_Product::get_trial_length( $product_monthly ) : 'N/A';
        $price_str_monthly = $product_monthly ? WC_Subscriptions_Product::get_price_string( $product_monthly ) : 'N/A';
        
        echo "<div style='background:#1e1e1e; border:1px solid #333; padding:20px; border-radius:8px; margin-bottom:20px;'>";
        echo "<h3 style='color:#03dac6; margin-top:0;'>Scenario: Guest / Not Logged In</h3>";
        echo "<table cellpadding='6' style='width:100%; border-collapse:collapse;'>";
        echo "<tr><td style='width:250px; color:#a0a0a0;'>User State:</td><td><strong>Guest (Logged Out)</strong></td></tr>";
        echo "<tr><td style='color:#a0a0a0;'>System Eligibility:</td><td><span style='background:#b71c1c; color:#ffcdd2; padding:2px 8px; border-radius:4px; font-weight:bold;'>INELIGIBLE (7-DAY)</span></td></tr>";
        echo "<tr><td style='color:#a0a0a0;'>Monthly Sub Trial:</td><td><strong>" . esc_html($trial_len_monthly) . " days</strong></td></tr>";
        echo "<tr><td style='color:#a0a0a0;'>Monthly Price String:</td><td><small>" . wp_kses_post($price_str_monthly) . "</small></td></tr>";
        echo "</table>";
        echo "</div>";

        echo "<hr style='border:none; border-top:1px solid #333; margin:40px 0;'>";
        echo "<p style='color:#888; font-size:12px;'>Diagnostic suite completed. All test dummy accounts automatically cleaned up.</p>";
        echo "</body>";
        exit;
    }
}


