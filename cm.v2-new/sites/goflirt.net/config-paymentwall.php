<?php
$paymenywall_secret = '355b616327dfebaf87dfa2a382340e25';
$paymenywall_key = '69d127cea92c9d69d079d7367d6d7be5';

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