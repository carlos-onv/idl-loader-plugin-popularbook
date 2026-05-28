<?php
// Test signature calculation for successful historical log 154

$payload = [
    "appId" => "ParentClub",
    "timestamp" => 1779395635,
    "nonce" => "b562e830972e0f863ee50b9d53915448",
    "orderId" => "116377",
    "parentClubParentId" => "1",
    "type" => 1,
    "payStatus" => 1,
    "payAmount" => "0.00",
    "payTimestamp" => 1779395635,
    "expireTimestamp" => 1810931635,
    "subscriptionType" => 2,
    "trialType" => 0,
    "parentClubSubscriptionId" => "116378"
];

$expected_signature = "pjq0VuFrlRG13U6txGHLWlivtbmC8wHlE3g9YmS7_mo";
$secret = "yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm";

$test_cases = [
    "all_fields_included" => function($payload) {
        $params = [];
        foreach ($payload as $k => $v) {
            $params[$k] = (string)$v;
        }
        return $params;
    },
    "exclude_expireTimestamp" => function($payload) {
        $params = $payload;
        unset($params['expireTimestamp']);
        foreach ($params as $k => $v) $params[$k] = (string)$v;
        return $params;
    },
    "exclude_subscriptionType_trialType_parentClubSubscriptionId_expireTimestamp" => function($payload) {
        $params = $payload;
        unset($params['expireTimestamp'], $params['subscriptionType'], $params['trialType'], $params['parentClubSubscriptionId']);
        foreach ($params as $k => $v) $params[$k] = (string)$v;
        return $params;
    }
];

echo "Target Signature: $expected_signature\n\n";

foreach ($test_cases as $case_name => $setup_func) {
    $params = $setup_func($payload);
    ksort($params);
    
    $pairs = [];
    foreach ($params as $k => $v) {
        if ($v !== null) {
            $pairs[] = "$k=$v";
        }
    }
    $string = implode('&', $pairs);
    
    // Base64url HMAC-SHA256
    $hash = hash_hmac('sha256', $string, $secret, true);
    $signature = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    
    $matched = ($signature === $expected_signature) ? "MATCH!!!" : "no match";
    
    echo "  Case: $case_name\n";
    echo "  String: $string\n";
    echo "  Result: $signature ($matched)\n\n";
}
