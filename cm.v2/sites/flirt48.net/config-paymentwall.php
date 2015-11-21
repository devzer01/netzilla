<?php
$paymenywall_key = '1d8c81efc831418bc798c38056f3d060';
$paymenywall_secret = '913b1821b0a3df84e9f4154553a48ada';

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