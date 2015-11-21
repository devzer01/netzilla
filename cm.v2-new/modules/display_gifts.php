<?php 

$user_id = $_SESSION['sess_id'];
$sender_id = $_SESSION['sess_id'];
$group = "";

if (isset($_GET['user_id']) && $_GET['user_id'] > 0) {
	$user_id = $_GET['user_id'];
	$group = " GROUP BY g.id";
} 

if (isset($_GET['username']) && trim($_GET['username']) != "") {
	$user_id = funcs::getUserid($_GET['username']);
	$group = " GROUP BY g.id";
}

$sql = "SELECT COUNT(*) AS cnt, mg.*, g.*, ms.username AS sender FROM member_gift AS mg "
     . "LEFT JOIN gift AS g ON g.id = mg.gift_id "
     . "LEFT JOIN member AS ms ON ms.id = mg.sender_id "
     . " WHERE mg.member_id = " . $user_id . " AND mg.sender_id = " . $sender_id . " " . $group;

$rs = mysql_query($sql);

$gifts = array();

while ($r = mysql_fetch_assoc($rs)) {
	$gifts[] = $r; 
}

$smarty->assign('gifts', $gifts);
$smarty->display('display_gifts.tpl');