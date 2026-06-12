<?php
// Test vector inputs
$secret = 'yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm';
$params = [
    'startTime' => 1685754528,
    'endTime'   => 1780434535,
    'pageNo'    => 1,
    'pageSize'  => 20,
    'timestamp' => 1709337605,
    'nonce'     => 'abc123def456ghi789jkl012mno345pq'
];

function emath_sign(array $params, string $secret): string
{
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

    echo "Calculated string to sign:\n" . $content . "\n\n";

    $raw = hash_hmac('sha256', $content, $secret, true);
    return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
}

$sig = emath_sign($params, $secret);
echo "Calculated Signature: " . $sig . "\n";
echo "Expected Signature:   vTAgFuXw7j9bmYn3k3inrSJGq-_KoL-XtZ3BOF_ZnVw\n";
echo "Match: " . ($sig === 'vTAgFuXw7j9bmYn3k3inrSJGq-_KoL-XtZ3BOF_ZnVw' ? 'YES' : 'NO') . "\n";
