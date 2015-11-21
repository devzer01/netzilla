<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$proc = $_GET['proc'];
$user = $_GET['user'];
$_SERVER['HTTP_REFERER'] = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:"?action=admin_copyfakeprofiles_already";

if(($proc=='del')&&($_SESSION['sess_permission']==1))
{ 
	$sql = "DELETE FROM member_temp WHERE username = '$user'";
	DBconnect::execute($sql);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(($proc=='copy')&&($_SESSION['sess_permission']==1))
{
	copyApprovedProfile($user);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(($proc=='copyall')&&($_SESSION['sess_permission']==1))
{
	$list = DBConnect::assoc_query_2D("SELECT username FROM member_temp");

	if(is_array($list) && count($list))
	{
		foreach($list as $user)
		{
			set_time_limit(30);
			copyApprovedProfile($user['username']);
		}
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(($proc=='delete-unconnected')&&($_SESSION['sess_permission']==1))
{
	$list = DBConnect::assoc_query_2D("SELECT id FROM member WHERE id NOT IN (SELECT from_id FROM message_inbox) AND id NOT IN (SELECT to_id FROM message_outbox) AND id NOT IN (SELECT child_id FROM favorite) AND fake=1 AND id>1000");

	foreach($list as $userid)
	{
		$userid = $userid['id'];
		$files = glob(UPLOAD_DIR.$userid."/foto/*");
		foreach($files as $file)
		{
			if(is_file($file))
				unlink($file);
		}
		if(is_dir(UPLOAD_DIR.$userid."/foto"))
			rmdir(UPLOAD_DIR.$userid."/foto");

		$files = glob(UPLOAD_DIR.$userid."/*");
		foreach($files as $file)
		{
			if(is_file($file))
				unlink($file);
		}
		if(is_dir(UPLOAD_DIR.$userid))
			rmdir(UPLOAD_DIR.$userid);

		DBconnect::execute("DELETE FROM fotoalbum WHERE userid = '$userid'");
		DBconnect::execute("DELETE FROM member WHERE id = '$userid'");
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
else
{
	//smarty paging
	SmartyPaginate::connect();
	SmartyPaginate::setLimit(50); //smarty paging set records per page
	SmartyPaginate::setPageLimit(20); //smarty paging set limit pages show
	SmartyPaginate::setUrl("?action=".$_GET['action']."&order=".$_GET['order']."&f=".$_GET['f']."&g=".$_GET['g']."&lg=".$_GET['lg']."&co=".$_GET['co']."&s=".$_GET['s']."&ci=".$_GET['ci']."&min_age=".$_GET['min_age']."&max_age=".$_GET['max_age']."&u=".$_GET['u']); //smarty paging set URL

	if(!isset($_GET['next']))
		SmartyPaginate::setCurrentItem(1); //go to first record

	$country = $_GET['co'];
	$city = $_GET['ci'];
	$state = $_GET['s'];
	$gender = $_GET['g'];
	$search_username = $_GET['u'];

	$arr = funcs::getUsersTempList($_GET);
	$data = $arr['data'];

	for($i=0; $i<count($data); $i++)
	{
		if(DBConnect::retrieve_value("SELECT 1 FROM member WHERE username='".$data[$i]['username']."'"))
		{
			$data[$i]['copied'] = 1;
		}
	}

	$countMember = $arr['count'];

	SmartyPaginate::setTotal($countMember);
	SmartyPaginate::assign($smarty);

	$smarty->assign('countMember',$countMember);
	$smarty->assign('userrec',$data);
	$smarty->display('admin.tpl');
}

function url_exists($url) {
    $h = get_headers($url);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0] , $status);
    return ($status[1] == 200);
}

function copyApprovedProfile($user)
{
	if(!DBConnect::retrieve_value("SELECT 1 FROM member WHERE username='".$user."'"))
	{
		DBConnect::execute_q("INSERT INTO member (username, password, email, gender, birthday, country, state, city, area, height, weight, appearance, eyescolor, haircolor, hairlength, beard, zodiac, civilstatus, sexuality, tattos, smoking, glasses, handicapped, piercings, lookmen, lookwomen, lookpairs, minage, maxage, relationship, onenightstand, affair, friendship, cybersex, picture_swapping, live_dating, role_playing, s_m, partner_exchange, voyeurism, description, picturepath, signup_datetime, signin_datetime, sendmail_datetime, isactive, type, fake, mobileno, waitver_mobileno, vcode_mobile, vcode_mobile_insert_time, vcode_count, sms, payment, flag, rand_visitor, rand_visitor_datetime, count, ursprung, validated, payment_received, last_action_to, last_action_from, rundmail, was_newest, in_storno, prolonging_payment_id, fsk18, isactive_datetime, checked, advertise_regist, prefer, coin, isScammer, ip_address, first_message_sent, agent, agent_profile_username, facebook_id, facebook_username, facebook_token, username_confirmed) SELECT username, password, email, gender, birthday, country, state, city, area, height, weight, appearance, eyescolor, haircolor, hairlength, beard, zodiac, civilstatus, sexuality, tattos, smoking, glasses, handicapped, piercings, lookmen, lookwomen, lookpairs, minage, maxage, relationship, onenightstand, affair, friendship, cybersex, picture_swapping, live_dating, role_playing, s_m, partner_exchange, voyeurism, description, picturepath, signup_datetime, signin_datetime, sendmail_datetime, isactive, type, fake, mobileno, waitver_mobileno, vcode_mobile, vcode_mobile_insert_time, vcode_count, sms, payment, flag, rand_visitor, rand_visitor_datetime, count, ursprung, validated, payment_received, last_action_to, last_action_from, rundmail, was_newest, in_storno, prolonging_payment_id, fsk18, isactive_datetime, checked, advertise_regist, prefer, coin, isScammer, ip_address, first_message_sent, agent, agent_profile_username, facebook_id, facebook_username, facebook_token, username_confirmed FROM member_temp WHERE username='".$user."'");
		$id = mysql_insert_id();
		$picturepath = DBConnect::retrieve_value("SELECT picturepath FROM member WHERE id=".$id);
		if(!is_dir(SITE."thumbs/".$id))
			mkdir(SITE."thumbs/".$id, 0777);

		$src = SITE."thumbs_temp/".$picturepath;
		$target = $id."/".basename($picturepath);
		copy($src, SITE."thumbs/".$target);
		DBConnect::execute_q("UPDATE member SET picturepath='".$target."' WHERE id=".$id);
		return true;
	}
	else
	{
		return false;
	}
}
?> 