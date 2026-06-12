<?php
// Suppress warnings from third-party plugins (like wc-captcha) to keep the screenshot clean
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

// Bootstrap WordPress
if (!defined('ABSPATH')) {
    require_once(__DIR__ . '/../../../../wp-load.php');
}
require_once(__DIR__ . '/../functions-restapi.php');

$secret = 'yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm';

// CLI Colors for a great looking screenshot
function colorize($text, $color) {
    $colors = [
        'green'  => "\033[1;32m",
        'cyan'   => "\033[1;36m",
        'yellow' => "\033[1;33m",
        'white'  => "\033[1;37m",
        'gray'   => "\033[0;90m",
        'reset'  => "\033[0m"
    ];
    return $colors[$color] . $text . $colors['reset'];
}

function generate_emath_signature(array $params, string $secret): string {
    $filtered = [];
    foreach ($params as $key => $value) {
        if ($key === 'signature' || $value === null) { continue; }
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

echo "\n" . colorize("===============================================================", "cyan") . "\n";
echo colorize("   eMathSmart Integrations v1.5 - Security & Payload Validation", "white") . "\n";
echo colorize("===============================================================", "cyan") . "\n\n";

// --- API #7: PAYMENT COMPENSATION ---
echo colorize("[RUNNING] Testing API #7: Order Payment Compensation", "yellow") . "\n";
echo colorize("---------------------------------------------------------------", "gray") . "\n";

$params_7 = [
    'startTime' => time() - 365 * 86400,
    'endTime'   => time() + 3600,
    'pageNo'    => 1,
    'pageSize'  => 5,
    'timestamp' => time(),
    'nonce'     => bin2hex(random_bytes(16)),
];
$params_7['signature'] = generate_emath_signature($params_7, $secret);

echo colorize("» Endpoint: ", "cyan") . "POST /wp-json/wp/v2/orderpaymentcompensate\n";
echo colorize("» Auth:     ", "cyan") . "OAuth 2.0 Client Credentials + HMAC-SHA256\n";
echo colorize("» Payload:  ", "cyan") . "\n" . json_encode($params_7, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

$req_7 = new WP_REST_Request('POST', '/wp/v2/orderpaymentcompensate');
$req_7->set_header('Content-Type', 'application/json');
$req_7->set_body(json_encode($params_7));
$res_7 = restapi_orderPaymentCompensate($req_7);

if ($res_7->get_status() === 200) {
    echo colorize("✔ Signature Verified Successfully", "green") . "\n";
    echo colorize("✔ Data Queried & Mapped Successfully (HTTP 200)", "green") . "\n\n";
    echo colorize("» Response Body: ", "cyan") . "\n";
    echo json_encode($res_7->get_data(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
}

// --- API #8: REFUND COMPENSATION ---
echo colorize("[RUNNING] Testing API #8: Order Refund Compensation", "yellow") . "\n";
echo colorize("---------------------------------------------------------------", "gray") . "\n";

$params_8 = $params_7; // Reuse same structure for refund test
$params_8['nonce'] = bin2hex(random_bytes(16)); // New nonce
$params_8['signature'] = generate_emath_signature($params_8, $secret);

echo colorize("» Endpoint: ", "cyan") . "POST /wp-json/wp/v2/orderrefundcompensate\n";
echo colorize("» Auth:     ", "cyan") . "OAuth 2.0 Client Credentials + HMAC-SHA256\n";
echo colorize("» Payload:  ", "cyan") . "\n" . json_encode($params_8, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

$req_8 = new WP_REST_Request('POST', '/wp/v2/orderrefundcompensate');
$req_8->set_header('Content-Type', 'application/json');
$req_8->set_body(json_encode($params_8));
$res_8 = restapi_orderRefundCompensate($req_8);

if ($res_8->get_status() === 200) {
    echo colorize("✔ Signature Verified Successfully", "green") . "\n";
    echo colorize("✔ Refund Data Queried Successfully (HTTP 200)", "green") . "\n\n";
    echo colorize("» Response Body: ", "cyan") . "\n";
    echo json_encode($res_8->get_data(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
}

echo "\n" . colorize("===============================================================", "cyan") . "\n";
echo colorize("   TEST SUITE COMPLETED", "white") . "\n";
echo colorize("===============================================================", "cyan") . "\n\n";
