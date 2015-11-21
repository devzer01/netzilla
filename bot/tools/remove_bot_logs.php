<?php

set_time_limit(0);

for($i = 101; $i <= 183; $i++){
        if($i <= 161 || $i >= 181){
                // echo 'Connect to IP 192.168.1.'.$i.'<br />';
                // $connection = ssh2_connect('192.168.1.'.$i, 22);
                // ssh2_auth_password($connection, 'root', 'netzillacompany');
                // # $stream = ssh2_exec($connection, 'service tor '.$action);
                // $stream = ssh2_exec($connection,'cd /var/www/html/postdata/ && /usr/bin/php -q delete_old_log_files.php');
                // stream_set_blocking($stream, true);
                // $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, 'http://192.168.1.'.$i.'/postdata/delete_old_log_files.php');
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    $response = curl_exec($ch);
			    curl_close($ch);
        }
}