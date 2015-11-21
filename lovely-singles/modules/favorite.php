<?php
//check permission type//
$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']."&searchChar=".$_GET['searchChar']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

switch($_GET['do'])
{
	//delete favorite//
	case 'del':
		$url = "?action=".$_GET['action']."&searchChar=".$_GET['searchChar'];
		funcs::deleteFavorite($_SESSION['sess_id'], $_GET['username']);
		header("Location: $url");
		break;
	//get favorite data//
	default:
		$total = funcs::getNumListFavorite($_SESSION['sess_id'], $_GET['searchChar']);
		$datas = funcs::getListFavorite($_SESSION['sess_id'], $_GET['searchChar'], SmartyPaginate::getCurrentIndex(), MESSAGE_RECORD_LIMIT);
		for($n = 0; $datas[$n]; $n++)
		{
			$datas[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $datas[$n][TABLE_MEMBER_CITY]);
			$datas[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $datas[$n][TABLE_MEMBER_APPEARANCE]);
			$datas[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $datas[$n][TABLE_MEMBER_CIVIL]);
			$datas[$n][TABLE_MEMBER_HEIGHT] = ($datas[$n][TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height',$datas[$n][TABLE_MEMBER_HEIGHT]) : "";
		} 
		$smarty->assign('datas', $datas);
		
		
}

//get choice A-Z//
$A_Z = funcs::getRangeAge('A', 'Z');
array_unshift($A_Z, "All");

$n = 0;
$hrefs = array();
$onclicks = array();
$texts = array();
foreach($A_Z as $val)
{
	$hrefs[$n] = "?action=favorite&searchChar=".$val;
	$texts[$n] = $val;
	$n += 1;
}
//send data to template//
$smarty->assign('year', date('Y'));
$smarty->assign('hrefs', $hrefs);
$smarty->assign('texts', $texts);
SmartyPaginate::setTotal($total);
SmartyPaginate::assign($smarty);
//select template file//
$smarty->display('index.tpl');
?>