<?php

$connection = ssh2_connect('127.0.0.1', 22);
ssh2_auth_password($connection, 'root', 'netzillacompany');
$stream = ssh2_exec($connection, '/var/www/tools/__rsyncBot.sh');
stream_set_blocking($stream, true);
$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);

// Display
echo nl2br(stream_get_contents($stream_out));
