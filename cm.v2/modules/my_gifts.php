<?php 

$user_id = $_SESSION['sess_id'];
$group = "";

$sql = "SELECT mg.*, g.*, ms.username AS sender, ms.picturepath, COUNT( * ) AS times FROM `member_gift` AS mg "
     . "LEFT JOIN `gift` AS g ON g.id = mg.gift_id "
     . "LEFT JOIN `member` AS ms ON ms.id = mg.sender_id "
     . "WHERE mg.member_id = " . $user_id . " GROUP BY g.id, mg.sender_id "
     . "ORDER BY `mg`.`created` DESC,`times` DESC ";

$rs = mysql_query($sql);

$mygifts = array();

while ($r = mysql_fetch_assoc($rs)) {
	
	if(empty($mygifts[$r['gift_id']]['info'])){
		$mygifts[$r['gift_id']]['info'] = $r;
	}
	
	$mygifts[$r['gift_id']]['senders'][$r['sender_id']] = $r;
}		


$smarty->assign('mygifts', $mygifts);
$smarty->display('my_gifts.tpl');