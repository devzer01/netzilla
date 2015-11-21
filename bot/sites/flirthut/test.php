<?php
$ch = curl_init();
define("PROXY_IP", "127.0.0.1");
define("PROXY_PORT", "9050");
				
curl_setopt($ch, CURLOPT_PROXY, "localhost:9050"); 
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($ch, CURLOPT_URL, "http://whatismyip.com/");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT,30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

echo $result = curl_exec($ch);
curl_close($ch);
?>