<?php
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$userid = funcs::getUserid($_GET['username']);

if(($userid == $_SESSION['sess_id']) || !$userid)
{
	echo funcs::getText($_SESSION['lang'], '$username_incorrect');
}
elseif($userid>0)
{
	if(isset($_POST['coins']))
	{
		if(is_numeric($_POST['coins']))
		{
			if($_POST['coins']>=COIN_EMAIL)
			{
				$current_coins = DBConnect::retrieve_value("SELECT coin FROM member WHERE id=".$_SESSION['sess_id']);
				if($_POST['coins'] > $current_coins)
				{
					echo funcs::getText($_SESSION['lang'], '$not_enough_coin_to_send');
				}
				elseif(($_POST['coins'] % COIN_EMAIL)>0)
				{
					echo "Please send coins amount in the power of ".COIN_EMAIL.".\r\ne.g. ".COIN_EMAIL.", ".(COIN_EMAIL*2).", ".(COIN_EMAIL*3).", ".(COIN_EMAIL*4)." ...";
				}
				else
				{
					sendCoins($_POST['coins'], $_SESSION['sess_id'], $userid, $_GET['username']);
					echo "FINISHED";
				}
			}
			else
			{
				echo str_replace("COIN_EMAIL", COIN_EMAIL, funcs::getText($_SESSION['lang'], '$invalid_minimum_coins_amount'));
			}
		}
		else
		{
			echo funcs::getText($_SESSION['lang'], '$invalid_coins_amount');
		}
	}
	else
	{
		$smarty->display('send-coins.tpl');
	}
}

function sendCoins($coins, $from_id, $to_id, $to_username)
{
	$fakesender = DBConnect::retrieve_value("SELECT fake FROM member WHERE id=".$from_id);
	$fakereceiver = DBConnect::retrieve_value("SELECT fake FROM member WHERE id=".$to_id);

	if(!$fakesender)
		DBConnect::execute_q("UPDATE member SET coin=coin-".$coins." WHERE id=".$from_id);
	if(!$fakereceiver)
		DBConnect::execute_q("UPDATE member SET coin=coin+".$coins." WHERE id=".$to_id);

	$subject = funcs::getText($_SESSION['lang'], '$send_coins');
	$message = str_replace("COIN_AMOUNT", $coins, funcs::getText($_SESSION['lang'], '$send_coins_message'));
	funcs::sendMessage($from_id, $to_username, $subject, $message, 0, 1);
}
?>