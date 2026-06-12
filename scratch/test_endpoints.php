<?php
// Bootstrap WordPress if not running under WP-CLI
if (!defined('ABSPATH')) {
    require_once(__DIR__ . '/../../../../wp-load.php');
}

// Ensure functions-restapi.php is loaded (it should be automatically loaded by the plugin, but let's make sure)
require_once(__DIR__ . '/../functions-restapi.php');

// Define testing parameters
$secret = 'yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm';
$test_params = [
    'startTime' => time() - 365 * 86400, // 1 year ago
    'endTime'   => time() + 3600,       // 1 hour from now
    'pageNo'    => 1,
    'pageSize'  => 5,
];

// Helper to generate eMathSmart signature
function generate_emath_signature(array $params, string $secret): string {
    $filtered = [];
    foreach ($params as $key => $value) {
        if ($key === 'signature' || $value === null) {
            continue;
        }
        $filtered[$key] = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
    }
    ksort($filtered, SORT_STRING);

    $pairs = [];
    foreach ($filtered as $key => $value) {
        $pairs[] = $key . '=' . $value;
    }
    $content = implode('&', $pairs);

    $raw = hash_hmac('sha256', $content, $secret, true);
    return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
}

echo "=== TESTING SIGNATURE VERIFICATION ===\n";

// Test case 1: Valid request
$valid_params = $test_params;
$valid_params['timestamp'] = time();
$valid_params['nonce'] = bin2hex(random_bytes(16));
$valid_params['signature'] = generate_emath_signature($valid_params, $secret);

$request_valid = new WP_REST_Request('POST', '/wp/v2/orderpaymentcompensate');
$request_valid->set_header('Content-Type', 'application/json');
$request_valid->set_body(json_encode($valid_params));

$response_valid = restapi_orderPaymentCompensate($request_valid);
echo "Valid Request Result:\n";
echo "HTTP Status: " . $response_valid->get_status() . "\n";
$data_valid = $response_valid->get_data();
echo "Response: " . json_encode($data_valid, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Test case 2: Invalid signature
$invalid_params = $valid_params;
$invalid_params['signature'] = 'invalid_sig_here';

$request_invalid = new WP_REST_Request('POST', '/wp/v2/orderpaymentcompensate');
$request_invalid->set_header('Content-Type', 'application/json');
$request_invalid->set_body(json_encode($invalid_params));

$response_invalid = restapi_orderPaymentCompensate($request_invalid);
echo "Invalid Signature Request Result:\n";
echo "HTTP Status: " . $response_invalid->get_status() . "\n";
$data_invalid = $response_invalid->get_data();
echo "Response Message: " . $data_invalid['message'] . "\n\n";

// Test case 3: Expired request (replay attack simulation)
$expired_params = $test_params;
$expired_params['timestamp'] = time() - 600; // 10 minutes ago
$expired_params['nonce'] = bin2hex(random_bytes(16));
$expired_params['signature'] = generate_emath_signature($expired_params, $secret);

$request_expired = new WP_REST_Request('POST', '/wp/v2/orderpaymentcompensate');
$request_expired->set_header('Content-Type', 'application/json');
$request_expired->set_body(json_encode($expired_params));

$response_expired = restapi_orderPaymentCompensate($request_expired);
echo "Expired Timestamp Request Result:\n";
echo "HTTP Status: " . $response_expired->get_status() . "\n";
$data_expired = $response_expired->get_data();
echo "Response Message: " . $data_expired['message'] . "\n\n";

// Test case 4: Refund endpoint with valid signature
$request_refund = new WP_REST_Request('POST', '/wp/v2/orderrefundcompensate');
$request_refund->set_header('Content-Type', 'application/json');
$request_refund->set_body(json_encode($valid_params));

$response_refund = restapi_orderRefundCompensate($request_refund);
echo "Refund Request Result (Valid Signature):\n";
echo "HTTP Status: " . $response_refund->get_status() . "\n";
$data_refund = $response_refund->get_data();
echo "Response: " . json_encode($data_refund, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
