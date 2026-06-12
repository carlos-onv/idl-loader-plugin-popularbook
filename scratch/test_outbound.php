<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

if (!defined('ABSPATH')) {
    require_once(__DIR__ . '/../../../../wp-load.php');
}

function colorize($text, $color) {
    $colors = [
        'green'  => "\033[1;32m",
        'cyan'   => "\033[1;36m",
        'yellow' => "\033[1;33m",
        'red'    => "\033[1;31m",
        'white'  => "\033[1;37m",
        'gray'   => "\033[0;90m",
        'reset'  => "\033[0m"
    ];
    return $colors[$color] . $text . $colors['reset'];
}

echo "\n" . colorize("===============================================================", "cyan") . "\n";
echo colorize("   Testing Outbound APIs (#10 and #11)", "white") . "\n";
echo colorize("===============================================================", "cyan") . "\n\n";

$test_user_id = 60770; // Picked an arbitrary user ID from the DB
$test_subscribe_id = 116675;

// Test API #10: Logout Notify (We just call it manually instead of logging out)
echo colorize("[RUNNING] Testing API #10: Logout Notify", "yellow") . "\n";
echo colorize("---------------------------------------------------------------", "gray") . "\n";
// Temporarily mock wp_remote_post to capture the request if the real eMathSmart endpoint returns 404
add_filter('pre_http_request', function($preempt, $parsed_args, $url) {
    if (strpos($url, 'logoutNotify') !== false) {
        echo colorize("» Captured Outbound Request Payload:", "cyan") . "\n";
        echo json_encode(json_decode($parsed_args['body']), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
        return [
            'headers' => [],
            'body' => json_encode(['code' => 200, 'message' => 'mock success']),
            'response' => ['code' => 200, 'message' => 'OK'],
            'cookies' => [],
            'filename' => null
        ];
    }
    return false; // proceed normal request
}, 10, 3);

emathsmart_trigger_logout_notification($test_user_id);
echo colorize("✔ API #10 Function Executed", "green") . "\n\n";

// Test API #11: Get Student List
echo colorize("[RUNNING] Testing API #11: Get Student Info", "yellow") . "\n";
echo colorize("---------------------------------------------------------------", "gray") . "\n";

// Remove the mock so it actually hits the network (or try to hit it and see what it returns)
// Actually we will mock it too just to see the payload structure, because the staging server might not have test_user_id 60770
add_filter('pre_http_request', function($preempt, $parsed_args, $url) {
    if (strpos($url, 'getStudentList') !== false) {
        echo colorize("» Captured Outbound Request Payload:", "cyan") . "\n";
        echo json_encode(json_decode($parsed_args['body']), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
        
        // Mock a success response
        return [
            'headers' => [],
            'body' => json_encode([
                'code' => 200, 
                'message' => 'mock success',
                'data' => [
                    [
                        'studentId' => 'stu_123',
                        'name' => 'John Doe',
                        'gradeName' => 'Grade 10'
                    ]
                ]
            ]),
            'response' => ['code' => 200, 'message' => 'OK'],
            'cookies' => [],
            'filename' => null
        ];
    }
    return false;
}, 10, 3);

$students = emathsmart_get_student_list($test_user_id, $test_subscribe_id);
if ($students !== false) {
    echo colorize("✔ API #11 Data Received Successfully", "green") . "\n";
    echo colorize("» Response Body: ", "cyan") . "\n";
    echo json_encode($students, JSON_PRETTY_PRINT) . "\n\n";
} else {
    echo colorize("✖ API #11 Failed", "red") . "\n\n";
}

echo "\n" . colorize("===============================================================", "cyan") . "\n";
echo colorize("   TEST SUITE COMPLETED", "white") . "\n";
echo colorize("===============================================================", "cyan") . "\n\n";
