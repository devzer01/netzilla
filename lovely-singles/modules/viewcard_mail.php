<?php
	$from = $_GET['from'];
	$email = $_GET['email'];
	$id = $_GET['id'];

	$sql = "SELECT t1.".TABLE_MEMBER_USERNAME.", t2.*, t3.".TABLE_CARD_CARDPATH." as image  
			FROM ".TABLE_MEMBER." t1, card_mail t2, ".TABLE_CARD." t3  
			WHERE t1.".TABLE_MEMBER_ID."=t2.parent_id 
			AND t3.".TABLE_CARD_ID."=t2.card_id 
			AND t2.parent_id=".$from." 
			AND t2.id=".$id." 
			AND t2.email='".$email."' 
			AND t2.isactive=1 
			";

	$data = DBconnect::assoc_query_1D($sql);

	$smarty->assign('message', $data);
	$smarty->display('index.tpl');
?>