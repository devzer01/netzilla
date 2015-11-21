<?php
$paymenywall_key = '84ffe3ac8390ca2abd0963d820b4b8e0';
$paymenywall_secret = 'e996a6593959ebadafdbb6abad2f7a93';

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