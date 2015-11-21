<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

if($_REQUEST['history_action'] == "display_search_result"){
	//print_r($_REQUEST);
	$user_name = $_REQUEST['user_name'] != '' ? $_REQUEST['user_name'] : ''; 
	//smarty paging//
	SmartyPaginate::connect(); //smarty paging connect
	SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
	SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
	SmartyPaginate::setUrl("?action=admin_history&history_action=display_search_result&user_name=$user_name"); //smarty paging set URL
		
	$usertbl = TABLE_MEMBER;
	
	if(!isset($_GET['next']))
		SmartyPaginate::setCurrentItem(1); //go to first record

	$sql = "SELECT COUNT(*) FROM $usertbl WHERE username LIKE '%$user_name%'";
	$user_nbr = DBconnect::get_nbr($sql);

	$mymessage = funcs::admin_getAllMessage_outbox(SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());	//get limit all message outbox
	$start = SmartyPaginate::getCurrentIndex();
	$limit = SmartyPaginate::getLimit();
	
	$sql = "SELECT payment_log.*, member.username FROM member LEFT JOIN payment_log ON(member.username = payment_log.username) WHERE member.username LIKE '%$user_name%'";

	//$sql = "SELECT t2.* FROM ".TABLE_MEMBER." t1, ".TABLE_PAY_LOG." t2 WHERE t1.username = t2.username AND t2.username like '%%".$username."'";
	
	if(!(empty($start) && empty($limit)))
		$sql .= " LIMIT ".$start.", ".$limit;
	
	//echo $sql;
	$users = DBconnect::assoc_query_2D($sql);
	//print_r($users);
	
	$smarty->assign('users', $users);	//send data outbox message to template
	SmartyPaginate::setTotal($user_nbr);	//define data total
	SmartyPaginate::assign($smarty);	//send data about smarty paging to template
	$smarty->assign('display', 'history_list');
}else{
	$smarty->assign('display', 'search_form');
}

$smarty->display('admin.tpl');