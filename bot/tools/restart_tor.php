<?php
set_time_limit(0);
$output = "";

for($i = 101; $i <= 183; $i++){
	if($i <= 161 || $i >= 181){
		$connection = ssh2_connect('192.168.1.'.$i, 22);
		ssh2_auth_password($connection, 'root', 'netzillacompany');
		$stream = ssh2_exec($connection, 'service tor restart');
		stream_set_blocking($stream, true);
		$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
		$output .= stream_get_contents($stream_out);
	}
}

if(isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!=""))
{
	header("location: ".$_SERVER['HTTP_REFERER']);
}
else
{
	echo "<pre>".$output."</pre>";
}