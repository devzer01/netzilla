<?php
date_default_timezone_set("Asia/Bangkok");
ob_start();
$old_limit = 60*60*24*3;
$folders = glob('*');
//$folders = array("qeep");
foreach($folders as $folder)
{
	showOutput("Checking ".$folder);
	$paths = array("cookies", "logging", "login", "logs", "search", "sending", "xml");
	foreach($paths as $path)
	{
		if(file_exists($folder."/".$path))
		{
			showOutput("Checking ".$folder."/".$path);
			$files = glob($folder."/".$path."/*.*");
			{
				set_time_limit(120);
				showOutput("Deleting old files in ".$folder."/".$path);
				foreach($files as $file)
				{
					$filetime = filemtime($file);
					if((time()-$filetime)>$old_limit)
						unlink($file);
				}
			}
		}
	}
	//exit;
}

function showOutput($msg)
{
	$time=date("Y-m-d H:i:s");
	$scrollScript = "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

	echo "[$time] $msg                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <br/>".$scrollScript."\r\n";
	ob_end_flush();
	if( ob_get_level() > 0 ) ob_flush();
	flush();
	ob_start();
}
?>