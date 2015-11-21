<?php
//check permission type//
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

$id=$_GET['id'];
if(isset($_GET['m_id']) && isset($_GET['type']))
{
	funcs::checkMessage($_GET['m_id'],$_GET['type']);
}
$rec = funcs2::detailCardLog($id);
$form_info = funcs::getProfile($rec['parent_id']); 

$sql = "SELECT ".TABLE_CARD_ID." AS cardid ,"
			.TABLE_CARD_CARDTMP." AS cardtmp ,"
			.TABLE_CARD_CARDPATH." AS cardpath ,"
			.TABLE_CARD_CARDTMP." AS cardtmp  ,"
			.TABLE_CARD_CARDSHOW." AS cardshow   FROM ".TABLE_CARD
			." WHERE ".TABLE_CARD_CARDSHOW." = '1'"
			." ORDER  BY " .TABLE_CARD_ID." ASC";
$rec2 = DBconnect::assoc_query_1D($sql); 
$image = $rec2['cardpath']; 
$rec['username'] = $form_info['username'];
$rec['image'] =  $rec2['cardpath']; 
$smarty->assign("message",$rec);
$smarty->display('index.tpl'); //select template file 
?>