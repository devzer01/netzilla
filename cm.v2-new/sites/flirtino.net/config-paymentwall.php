<?php
$paymenywall_secret = 'f7dea49fc2c80acdbe1e22b7e5f0fed8';
$paymenywall_key = 'd48756607cc953a4f5f74cec8718ba66';

function calculateWidgetSignature($params, $secret) {

    // work with sorted data
    ksort($params);
    // generate the base string
    $baseString = '';
    foreach($params as $key => $value) {
        $baseString .= $key . '=' . $value;
    }
    $baseString .= $secret;
    return md5($baseString);
}
?>