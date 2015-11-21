<?php

// $permission_lv = array(1, 2, 8);	//define type permission can open this page.
$permission_lv = array(1, 2, 3, 4, 8); //jeab edited
funcs::checkPermission($smarty, $permission_lv);	//check permission
//get choices card//
$sql = "SELECT ".TABLE_CARD_ID." AS cardid ,"
			.TABLE_CARD_CARDTMP." AS cardtmp ,"
			.TABLE_CARD_CARDPATH." AS cardpath ,"
			.TABLE_CARD_CARDTMP." AS cardtmp  ,"
			.TABLE_CARD_CARDSHOW." AS cardshow   FROM ".TABLE_CARD
			." WHERE ".TABLE_CARD_CARDSHOW." = '1'"
			." ORDER  BY " .TABLE_CARD_ID." ASC";
$rec = DBconnect::assoc_query_2D($sql); 
foreach($rec as $key => &$val){
	list($val['width'], $val['height'], $type, $attr) = getimagesize($val['cardpath']);
	$val['cardid'];
	$cardrec[$key] = ' <table width="150" height="100"  border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">';  
	$cardrec[$key] .= '  <tr><td width="200" bgcolor="#FFFFFF">';
	//$cardrec[$key] .= '  <div align="center" style=" cursor:pointer" onclick="window.location.href=\'index.php?action=sendcard_to&card='.$val['cardid'].'\'">'; 
	$cardrec[$key] .= ' <div align="center" style=" cursor:pointer">';	
	//$cardrec[$key] .= ' <img src="'. $val['cardtmp'].'">';
	$cardrec[$key] .= ' <img src="'.$val['cardtmp'].'" onclick="bigger(\''.$val['cardpath'].'\',\'titel\','.$val['width'].','.$val['height'].'); return false;">';
	//$cardrec[$key] .= '  </div></td></tr></table>';
	$cardrec[$key] .= '  </div></td></tr>';
	$cardrec[$key] .= '  <tr><td width="200" bgcolor="#FFFFFF" align="center">';	
	$cardrec[$key] .= '  <a href="index.php?action=sendcard_to&card='.$val['cardid'].'"><img border="0" src="images/karte_bt.gif" /></a>';
	$cardrec[$key] .= '  </td></tr></table>';
}
$_SESSION['result_memrec'] = $cardrec;
//$_SESSION['resultHeader'] = " BIRTHDAY"; 
//$_SESSION['resulttype'] = 1;

$recordperpage = 20;
$page = $_GET['page'];
$arrTmp = $_SESSION['result_memrec'];
$count = count($arrTmp);
$cardrec = array_slice($arrTmp, $page*$recordperpage, $recordperpage);
$total_records = $count ; 
$pages = ceil($count / $recordperpage);
$page_number = "";
if($pages > 0){
	$page_number .= '['; 
	for($i=1; $i<=$pages; $i++){ 
		if($_REQUEST[page]==($i-1)){
			$page_number.= ' <a href="?action=sendcard&type=sending&page=' . ($i-1). '"><font color="red"><b>' . $i . '</b></font></a> '; 
		}else{
			$page_number.= ' <a href="?action=sendcard&page=' . ($i-1) . '">' . $i . '</a> '; 
		}
	}
	$page_number.= ']';
}
if($_REQUEST[page]>0){
	$page_number = ' <a href="?action=sendcard&type=sending&page=' . ($_REQUEST[page]-1).'"> prev</a> '.$page_number;
}
if($pages > 0 &&$_REQUEST[page]<$pages-1){
	$page_number .= ' <a href="?action=sendcard&type=sending&page=' . ($_REQUEST[page]+1).'"> next</a> ';
}	

$smarty->assign('cardrec', $cardrec);
$smarty->assign('rec', $rec);
$smarty->assign('page_number',$page_number);
//select template file//
$smarty->display('index.tpl');
?>
