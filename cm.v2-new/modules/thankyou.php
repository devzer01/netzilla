<?php 
//select template file//
$card = $_SESSION['card']['id'];
$sql = "SELECT ".TABLE_CARD_ID." AS cardid ,"
			.TABLE_CARD_CARDTMP." AS cardtmp ,"
			.TABLE_CARD_CARDPATH." AS cardpath ,"
			.TABLE_CARD_CARDSHOW." AS cardshow   FROM ".TABLE_CARD
			." WHERE ".TABLE_CARD_ID." = '$card'"
			." ORDER BY " .TABLE_CARD_ID." ASC";
$rec = DBconnect::assoc_query_1D($sql);
$rec['card'] = $_SESSION['card'];
//$_SESSION['card']='';

$smarty->assign("card",$rec);
$smarty->display('index.tpl');
?>
