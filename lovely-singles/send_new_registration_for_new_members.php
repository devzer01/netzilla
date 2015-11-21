<?php
error_reporting(E_ALL);
require_once('classes/top.class.php');

$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
$result = $client->checkMissingMembers(SERVER_ID);

$list = DBConnect::assoc_query_2D("SELECT id, username, signup_datetime FROM member WHERE id NOT IN (".join(",",$result).") AND fake=0 AND signup_datetime > NOW() - INTERVAL 5 MONTH AND isactive=1 ORDER BY signup_datetime DESC");
echo "<pre>";
print_r($list);

$_SESSION['sess_username'] = "bigbrother";

foreach($list as $member)
{
	$to = funcs::randomStartProfile($member['id']);
	$userid = funcs::getUserid($to);

	$message_assoc_array=array('to'=>$userid,'from'=>$member['id'],'msg'=>"New registration", 'subject'=>"New registration", 'serverID'=>SERVER_ID, 'type'=>4, 'payment'=>"0000-00-00 00:00:00", 'mtype'=>3);
	$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
	$result = $client->sendMessage((object)$message_assoc_array);
}
echo "</pre>";
?>