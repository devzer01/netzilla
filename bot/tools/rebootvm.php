<?php

set_time_limit(0);

$action = ((empty($_GET['action'])) ? 'restart' : $_GET['action']);

for($i = 101; $i <= 183; $i++){
        if($i <= 161 || $i >= 181){
                // echo 'Connect to IP 192.168.1.'.$i.'<br />';
                $connection = ssh2_connect('192.168.1.'.$i, 22);
                ssh2_auth_password($connection, 'root', 'netzillacompany');
                # $stream = ssh2_exec($connection, 'service tor '.$action);
                $stream = ssh2_exec($connection,'reboot');
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);

                // Display
                // echo nl2br(stream_get_contents($stream_out))
                        // .'<p>- Closed Connnection -</p>';
                // sleep(1);
        }
}
