<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$proc = $_GET['proc'];
$cid = $_GET['cid'];
$value = $_GET['value'];
$page = $_GET['page'];
//update card//
if($proc=='open')
{
	DBconnect::update_field(TABLE_CARD, TABLE_CARD_CARDSHOW, $value, TABLE_CARD_ID, $cid);
	header("Location:  ?action=admin_managecard&page=$page");
	exit;
}
//delete card//
elseif($proc=='del')
{
	funcs::DeleteCard($cid);
	header("Location:  ?action=admin_managecard&page=$page");
	exit;
}
$today = funcs::getDate();  //get today date
$card_rows = funcs::numDataCard(); //get all card
if($card_rows<=0)
	$tr = 1;
else	
	$tr = 2;
//get card data
$sql = "SELECT ".TABLE_CARD_ID." AS cardid ,"
		.TABLE_CARD_CARDTMP." AS cardtmp ,"
		.TABLE_CARD_CARDPATH." AS cardpath ,"
		.TABLE_CARD_CARDSHOW." AS cardshow   FROM ".TABLE_CARD
		. " ORDER  BY " .TABLE_CARD_ID." DESC";
$cardrec = DBconnect::assoc_query_2D($sql);
//paging function//
$page = $_REQUEST[page];
if(!$page){ $page = 0;}
$recordperpage = 10;
$datas = array_slice($cardrec, $page*$recordperpage, $recordperpage);
$count = count($cardrec);
$pages = ceil($count / $recordperpage);
$page_number = "";
if($pages > 0)
{
	$page_number .= '['; 
	for($i=1; $i<=$pages; $i++)
	{ 
		if($_REQUEST[page]==($i-1))
			$page_number.= ' <a href="?action=admin_managecard&page=' . ($i-1)  . '"><font color="red"><b>' . $i . '</b></font></a> '; 
		else
			$page_number.= ' <a href="?action=admin_managecard&page=' . ($i-1) . '">' . $i . '</a> '; 
	}
	$page_number.= ']';
}
if($_REQUEST[page]>0)
	$page_number = ' <a href="?action=admin_managecard&page=' . ($_REQUEST[page]-1).'"> ' . funcs::getText($_SESSION['lang'], '$back_button') . '</a> '.$page_number;

if($pages > 0 &&$_REQUEST[page]<$pages-1)
	$page_number .= ' <a href="?action=admin_managecard&page=' . ($_REQUEST[page]+1) .'"> ' . funcs::getText($_SESSION['lang'], '$next_button') . '</a> ';

//send data to template//
$smarty->assign('tr',$tr); 
$smarty->assign('cardrec',$datas); 
$smarty->assign('page_number',$page_number); 
//select template file//
$smarty->display('admin.tpl'); 
?> 