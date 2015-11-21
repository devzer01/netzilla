<?php
/**
 * start check bonus coin : NOI
 **/
$userid = $_SESSION['sess_id'];
$bonusid = funcs::checkBonus($userid);	//get profile data

if(($bonusid != "") && ($bonusid > 0))
{
	$smarty->assign('bonusid', $bonusid);
}
/**
 * end check bonus coin : NOI
 **/
?>