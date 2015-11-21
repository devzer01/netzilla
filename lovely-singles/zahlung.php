<?php
require_once('classes/top.class.php');

$list = DBConnect::assoc_query_2D("select * from payment_log where reminder_date != '' and reminder_date NOT LIKE '0000-00-00' and reminder_date < now() AND sum_paid >= 30 order by rand()");
if($list)
{
	foreach($list as $item)
	{
		$id = funcs::getUserid($item['username']);
		echo sendMemberCancelEmail30Days($id, $item['ID'], $smarty);
		exit();
	}
}
//================================== [End] Check for membership that expired and not paid. ===================================//
echo "</pre>";

function sendMemberCancelEmail30Days($id, $log_id, &$smarty)
{
	$mail_from = "forderungsmanagement@tmp-sn.de";
	$sql = "SELECT t1.*, t2.id as member_id, t2.mobileno as member_mobileno, t2.email FROM payment_log t1, member t2 WHERE t1.username = t2.username AND t1.ID=".$log_id." ORDER BY t1.payday DESC";

	$entry = DBConnect::assoc_query_1D($sql);

	$entry['site'] = "Herzoase";
	$entry['site_url'] = URL_WEB;
	$entry['booking_number'] = "4-".$id."-".$log_id;
	list($year, $month, $day) = split('-', $entry['payday']);
	$entry['until'] = date("d.m.Y", mktime(0,0,0,$month,$day,$year)+7*24*60*60);
	$entry['total'] = $entry['sum_paid'] + 15;
	if($entry['paid_via'] == 4)
		$entry['total'] += 10;
	if($entry['new_type'] == 3)
		$entry['price_3_months'] = SILVER_DURATION_PRICE_3;
	elseif($entry['new_type'] == 2)
		$entry['price_3_months'] = GOLD_DURATION_PRICE_3;

	$sql = "SELECT * FROM member WHERE id = $id";
	$profile = DBConnect::assoc_query_1D($sql);
	$smarty->assign('profile', $profile);
	$smarty->assign('entry', $entry);
	return $message =  $smarty->fetch('membership_cancel_email_30days.tpl');

	//return funcs::sendMail('phai@server.westkit.com', "Zahlungsaufforderung ".$entry['site'].".", $message, $mail_from);
}
?>