<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$username = DBConnect::retrieve_value("SELECT username FROM member WHERE username='".$_GET['username']."'");
if($username)
{
	$from = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'");
	if(isset($_GET['username2']) && ($_GET['username2']!="") && ($username2 = DBConnect::retrieve_value("SELECT username FROM member WHERE username='".$_GET['username2']."'")))
	{
		$to = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username2."'");
	}
	else
	{
		$to = null;
	}
	$messages = getMessages($from, $to);

	$smarty->assign('messages', $messages);

	if(isset($_GET['display']) && ($_GET['display']=="part"))
	{
		$smarty->display('admin_view_messages.tpl');
	}
	else
	{
		$smarty->display('admin.tpl');
	}
}
else
{
	if($_SERVER['HTTP_REFERER'])
		header("location: ".$_SERVER['HTTP_REFERER']);
	else
		header("location: .");
}

function getMessages($userid, $to=null)
{
	$total = 10;
	$userid = funcs::check_input($userid);

	$sql1 = "";
	$sql2 = "";

	if($to)
	{
		$total = 1000;
		$sql1 = " AND i.from_id=".$to;
		$sql2 = " AND o.to_id=".$to;
	}

	$inbox = DBconnect::assoc_query_2D("SELECT m.username AS username, m.picturepath, i.* FROM message_inbox i LEFT JOIN member m ON i.from_id=m.id WHERE i.to_id=".$userid.$sql1);

	//$outbox = array();
	$outbox = DBconnect::assoc_query_2D("SELECT m.username AS username, m.picturepath, o.* FROM message_outbox o LEFT JOIN member m ON o.from_id=m.id WHERE o.from_id=".$userid.$sql2);

	$mdarray = array();
	if(is_array($inbox))
		$mdarray = array_merge($mdarray, $inbox);

	if(is_array($outbox))
		$mdarray = array_merge($mdarray, $outbox);

	if(is_array($mdarray) && count($mdarray))
	{
		foreach ($mdarray as $key => $row) {
			$dates[$key]  = $row["datetime"]; 
		}

		array_multisort($dates, SORT_DESC, $mdarray);
	}

	if(count($mdarray)>$total)
		$mdarray = array_slice($mdarray,0-$total);

	return $mdarray;
}
?> 