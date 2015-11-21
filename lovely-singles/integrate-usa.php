<?php
set_time_limit(0);
ob_start();
require_once('classes/DBconnect.php');
require_once('classes/config.php');
$server = "http://www.yourbuddy24.com/";
define('SOAPSERVERURL', "http://www.yourbuddy24.com/soapserver.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Profile integration</title>
</head>
<body>
<?php

if($_GET['send'])
{
	$start = time();
	$start_from = 58;
	if(is_numeric($_GET['start_from']))
		$start_from = $_GET['start_from'];
	$itemsperpage = 1000;
	$i=1;

	$profiles = DBConnect::assoc_query_2D("SELECT * FROM member_usa LIMIT ".$start_from.",".$itemsperpage);

	foreach($profiles as $list)
	{
		$response = sendProfile($list);
		if($response['response'])
		{
			echo ($start_from+$i)."<img src='".$response['picturepath']."' width='90px'/> ";
			echo $response['username']." sent. [".(time()-$start)."]<br/>";
			$i++;
		}
		else
		{
			echo ($start_from+$i)."<img src='".$response['picturepath']."' width='90px'/> ";
			echo "<font color='red'>".$response['username']." duplicated. [".(time()-$start)."]</font><br/>";
			$i++;
		}

		flush_buffers();
		sleep(60*6);
	}

	$end = time();
	$consume = $end-$start;
	//echo "Time used: ".$consume." seconds<br/>";
	if(count($profiles)==$itemsperpage)
	{
		?>
		<script type="text/javascript">
			window.location="integrate-usa.php?send=1&start_from=<?php //echo $start_from+$itemsperpage; ?>";
		</script>
		<?php
	}
}
else
{
	$list = DBConnect::assoc_query_2D("SELECT * FROM member_usa");
	print_r($list);
}

function sendProfile($item)
{
	$client = new SoapClient(null, array('location' => SOAPSERVERURL, 'uri' => "urn://kontaktmarkt"));
	$method = "addFakeProfile";

	if(trim($item['picturepath']) != "")
	{
		$web_path = "/lovely-singles.com/thumbs/";
		$path="/var/www/lovely-singles.com/thumbs/";
		unset($item['id']);

		if($item['picturepath'])
			$item['pic']="http://netzilla.no-ip.org".$web_path.str_replace(" ","%20",$item['picturepath']);
		$item['replace']=0;
		$item['checked']=1;
		$item['approved']=0;
		$item['signup_datetime']="2012-02-28 00:00:00";

		if(is_file("/var/www/lovely-singles.com/thumbs/".$item['picturepath']))
		{
			cutImageBottom("/var/www/lovely-singles.com/thumbs/".$item['picturepath'],18);
		}

		if($response = $client->$method($item))
		{
			$item['response'] = $response;
			$item['picturepath'] = $web_path.$item['picturepath'];
			return $item;
		}
		else
		{
			$item['response'] = $response;
			$item['picturepath'] = $web_path.$item['picturepath'];
			return $item;
		}
	}
}

function flush_buffers(){
    ob_end_flush();
    ob_flush();
    flush();
    ob_start();
}

function cutImageBottom($file, $cut_height)
{
	$in_filename = $file;

	list($width, $height) = getimagesize($in_filename);

	$offset_x = 0;
	$offset_y = 0;

	$new_height = $height - $cut_height;
	$new_width = $width;

	$image = imagecreatefromjpeg($in_filename);
	$new_image = imagecreatetruecolor($new_width, $new_height);
	imagecopy($new_image, $image, 0, 0, $offset_x, $offset_y, $width, $height);

	imagejpeg($new_image,$file);
}
?>
</body>
</html>