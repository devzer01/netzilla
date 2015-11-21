<?php
if(isset($_GET['domain']) && ($_GET['domain']!=""))
{
	setcookie("domain", $_GET['domain'], time()+60*60*24*30*365, "/");
	header("location: .");
}
elseif ($handle = opendir('sites'))
{
    while (false !== ($entry = readdir($handle)))
	{
		$exceptions = array(".", "..");
		if(!in_array($entry, $exceptions))
	        echo "<a href='domains.php?domain=$entry'>$entry</a><br/>";
    }
    closedir($handle);
}
else
	header("location: domains.php?domain=flirten48.v2");
?>