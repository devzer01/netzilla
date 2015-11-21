<?php
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$payment = $_POST['payment'];
$payment_history = funcs::getPaymentHistory($_SESSION['sess_id']);
//print_r($_POST);
if($_POST['payment'])
{
	$remain = funcs::dateDiff("-", $payment_history['new_paid_until'], date("Y-m-d"));
	if($remain > 14 && $payment_history['membership_type'] == 2) 
	{
		$smarty->assign("valid_more_14_days", 1);
	}
	else
	{
		$payment_text = "";
		$aboname = "Herzoase Anmeldung";
		$old_paid_until = $payment_history['new_paid_until'];
		if ($payment_history['new_paid_until']){
			$remain = (strtotime($payment_history['new_paid_until']) - mktime()) / 86400;		
		}
		else $remain = 0;


		if(($old_paid_until != "0000-00-00 00:00:00") && ($old_paid_until != "") && ($remain > 0)) //buy new to extend current membership
		{
			$new_paid_until_sql = "('{$old_paid_until}')";
			$old_paid_until = $payment_history['old_paid_until'];
		}
		else
		{
			$new_paid_until_sql = "NOW()";
		}
			
		if($_POST['mitglied'] == 3)
		{
			if($payment_history['membership_type'] == 2) // Gold member can not pay for Silver membership
			{
				header("location: ".$SERVER['HTTP_REFERER']);
				exit();
			}

			if($_POST['abo_silver'] == 1)
			{
				$extend = "+ INTERVAL 3 DAY";
				$gueltig_db = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+3, date("Y")));
				$preis = SILVER_DURATION_PRICE_1;
			}
			elseif($_POST['abo_silver'] == 2)
			{
				$extend = "+ INTERVAL 1 MONTH";
				$gueltig_db = date("Y-m-d",mktime(0, 0, 0, date("m")+1  , date("d"), date("Y")));
				$preis = SILVER_DURATION_PRICE_2;
			}
			elseif($_POST['abo_silver'] == 3)
			{
				$extend = "+ INTERVAL 3 MONTH";
				$gueltig_db = date("Y-m-d",mktime(0, 0, 0, date("m")+3  , date("d"), date("Y")));
				$preis = SILVER_DURATION_PRICE_3;
                
			}
		}
		elseif($_POST['mitglied'] == 2)
		{
			if($_POST['abo_gold'] == 2)
			{
				$extend = "+ INTERVAL 1 MONTH";
				$gueltig_db = date("Y-m-d",mktime(0, 0, 0, date("m")+1  , date("d"), date("Y")));
				$preis = GOLD_DURATION_PRICE_2;
			}
			elseif($_POST['abo_gold'] == 3)
			{
				$extend = "+ INTERVAL 3 MONTH";
				$gueltig_db = date("Y-m-d",mktime(0, 0, 0, date("m")+3  , date("d"), date("Y")));
				$preis = GOLD_DURATION_PRICE_3;
                
                if ($_POST['payment'] == 6)
                    $preis = GOLD_DURATION_PRICE_7;    //bei GiroPay-Zahlung
			}
			elseif($_POST['abo_gold'] == 4)
			{
				$extend = "+ INTERVAL 1 YEAR";
				$gueltig_db = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d"), date("Y")+1));
				$preis = GOLD_DURATION_PRICE_4;
                
                if ($_POST['payment'] == 6)
                    $preis = GOLD_DURATION_PRICE_8;    //bei GiroPay-Zahlung
			}
			elseif($_POST['abo_gold'] == 5)
			{
				$sql = "SELECT new_paid_until FROM ".TABLE_PAY_LOG." WHERE ".TABLE_MEMBER_USERNAME."=\"$_SESSION[sess_username]\"";
				$gueltig_db = DBconnect::retrieve_value($sql);
				
				$preis = GOLD_DURATION_PRICE_5;
			}
			elseif($_POST['abo_gold'] == 6)
			{
				$sql = "SELECT new_paid_until FROM ".TABLE_PAY_LOG." WHERE ".TABLE_MEMBER_USERNAME."=\"$_SESSION[sess_username]\"";
				$gueltig_db = DBconnect::retrieve_value($sql);
				$monatstest = (strtotime($gueltig_db) - mktime()) / 86400;
				
				if ($monatstest > 30)
				{
					$preis = GOLD_DURATION_PRICE_6;
				}
				else 
				{
					$preis = GOLD_DURATION_PRICE_5;
					$payment_text = "Da dein derzeitiges Abo innerhalb eines Monats ausläuft, kostet dich die Aufwertung zu GOLD lediglich ".GOLD_DURATION_PRICE_5." Euro!";					
				}
			}
		}

		$type = $_POST['mitglied'];

		$sql = "SELECT password, mobileno, type, payment FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."=\"$_SESSION[sess_username]\"";
		$member_data = DBconnect::assoc_query_1D($sql);
		
		//$sql = "DELETE FROM ".TABLE_PAY_LOG." WHERE ".TABLE_MEMBER_USERNAME."=\"$_SESSION[sess_username]\""; 
		//DBconnect::execute_q($sql);
		
		$sql = "INSERT INTO ".TABLE_PAY_LOG."
				SET ".TABLE_MEMBER_USERNAME."=\"$_SESSION[sess_username]\",
					".TABLE_MEMBER_PASSWORD."=\"$member_data[password]\",
					".TABLE_MEMBER_MOBILENO."=\"$member_data[mobileno]\",
					".TABLE_PAYLOG_OLDTYPE."=\"$member_data[type]\",
					".TABLE_PAYLOG_NEWTYPE."=\"$type\",
					".TABLE_PAYLOG_OLDPAYMENT."=\"$old_paid_until\",
					".TABLE_PAYLOG_NEWPAYMENT."={$new_paid_until_sql} {$extend},
					".TABLE_PAYLOG_PAIDVIA."=\"$_POST[payment]\",
					".TABLE_PAYLOG_SUM."=\"$preis\",
					".TABLE_PAYLOG_PAYDAY."= NOW()";

		if(($_POST['payment'] == 2) || ($_POST['payment'] == 3) || ($_POST['payment'] == 5))
		{
			$sql .= ", real_name = '{$_POST['real_name']}',
						real_street = '{$_POST['real_street']}',
						real_city = '{$_POST['real_city']}',
						real_plz = '{$_POST['real_plz']}',
						ip_address = '{$_SERVER['REMOTE_ADDR']}'
					";
		}

		if(($_POST['abo_gold'] == 5) || ($_POST['abo_gold'] == 6))
		{
			$sql .= ", cancelled = '{$payment_history['cancelled']}',
					cancelled_date = '{$payment_history['cancelled_date']}'";
		}

		DBconnect::execute($sql);
		
		$sql = "SELECT ".TABLE_PAYLOG_ID." FROM ".TABLE_PAY_LOG." WHERE ".TABLE_MEMBER_USERNAME."=\"$_SESSION[sess_username]\" order by ".TABLE_PAYLOG_ID." DESC Limit 1";
		$paylog_id = DBconnect::retrieve_value($sql);		

		$new_paid_until = DBConnect::retrieve_value("SELECT DATE(new_paid_until) FROM ".TABLE_PAY_LOG." ORDER BY ID DESC LIMIT 1");
		
		$gueltig = date("d.m.Y",strtotime($new_paid_until));
		$smarty->assign('dauer', $gueltig);
		$smarty->assign('preis', $preis);
		$smarty->assign('payment_text', $payment_text);
	}
}

if ($_POST['ccform_submit']){
	
	//blacklist check
	$sql = "SELECT * FROM kto_blacklist WHERE username =\"$_SESSION[sess_username]\"";
	$blacklist = DBconnect::assoc_query_1D($sql);	
	if ($blacklist == 0)
	{
		$sql = "SELECT * FROM kto_blacklist WHERE kto = ".ltrim($_POST[bank_accountnumber],'0')." AND blz =".ltrim($_POST[bank_code],'0');
		$blacklist = DBconnect::assoc_query_1D($sql);		
	}
	
	if ($blacklist){
		$smarty->assign('blacklist', $blacklist);
	}
	else
	{
		$sql = "SELECT ".TABLE_PAYLOG_ID." FROM ".TABLE_PAY_LOG." WHERE ".TABLE_MEMBER_USERNAME."=\"$_SESSION[sess_username]\" order by ".TABLE_PAYLOG_ID." DESC Limit 1";
		$paylog_id = DBconnect::retrieve_value($sql);
		
		$name = $_POST['addr_vorname'].' '.$_POST['addr_name'];
		
		$sql = "UPDATE ".TABLE_PAY_LOG."
				SET	".TABLE_PAYLOG_REALNAME."=\"$name\",
					".TABLE_PAYLOG_REALSTREET."=\"$_POST[addr_street]\",
					".TABLE_PAYLOG_REALZIP."=\"$_POST[addr_zip]\",
					".TABLE_PAYLOG_REALCITY."=\"$_POST[addr_city]\",
					".TABLE_PAYLOG_IP."=\"$_SERVER[REMOTE_ADDR]\",
					".TABLE_PAYLOG_BANKNAME."=\"$_POST[bank_name]\",
					".TABLE_PAYLOG_BANKCODE."=\"$_POST[bank_code]\",
					".TABLE_PAYLOG_ACCOUNT."=\"$_POST[bank_accountnumber]\",				
					".TABLE_PAYLOG_PAYDAY."= NOW() WHERE ".TABLE_PAYLOG_ID."=\"$paylog_id\"";
		//print_r($sql);
		DBconnect::execute($sql);
			
		$smarty->assign('paylog_id', $paylog_id);
		$smarty->assign('val_addr_name', $name);
		$smarty->assign('val_addr_street', $_POST['addr_street']);
		$smarty->assign('val_addr_country', $_POST['addr_country']);
		$smarty->assign('val_addr_zip', $_POST['addr_zip']);
		$smarty->assign('val_addr_city', $_POST['addr_city']);
		$smarty->assign('val_addr_email', $_POST['addr_email']);
		$smarty->assign('val_preis', $_POST['preis']);	
		$smarty->assign('val_trx_amount', $_POST['trx_amount']);
		$smarty->assign('val_trx_currency', $_POST['trx_currency']);	
		$smarty->assign('val_bank_name', $_POST['bank_name']);
		$smarty->assign('val_bank_code', $_POST['bank_code']);
		$smarty->assign('val_bank_accountnumber', $_POST['bank_accountnumber']);		
	}
}

$vzweck =  CENTER_ID."-".$_SESSION['sess_id']."-".$paylog_id;
//$vzweck =  SERVER_ID."-".$_SESSION['sess_id']."-".$_POST['mitglied']." ".$_SESSION['sess_username'];
//$vzweck =  SERVER_ID."-".$paylog_id."-".$_POST['mitglied']." ".$_SESSION['sess_username'];
$smarty->assign('aboname', $aboname);
$smarty->assign('vzweck', $vzweck);
$smarty->assign('payment', $payment);
$smarty->display('index.tpl');
?>