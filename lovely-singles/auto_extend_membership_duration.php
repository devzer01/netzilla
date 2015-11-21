<?php
require_once('classes/top.class.php');
$testing = true;

echo "<pre>";

$sql = "SELECT id, username,type,email, DATE(payment) as payment, DATE(payment_received) as payment_received FROM ".TABLE_MEMBER." WHERE (payment < CURDATE()) AND (payment_received <> '0000-00-00')";
$list = DBConnect::assoc_query_2D($sql);

$arr = array();
$rate=0;
if(is_array($list))
{
	if(count($list) > 0)
	{
		foreach($list as $member)
		{
			$info = funcs::getPaymentHistory($member['id']);			
			
			if($info['cancelled'] == 0)
			{
				echo $member['username']." abgelaufen am ".$member['payment'];
				$duration = funcs::dateDiff("-", $member['payment'], $member['payment_received']);
				echo " => derzeitige Dauer: ".$duration." Tage.";					
				
				if($duration == 3)
				{
					echo " Verlängert um 1 Monat ";
					$extend = "1 MONTH";	
					$pay = "30";
					$rate = 2;
				}
				elseif($duration < 32)
				{
					echo " Verlängert um 3 Monate ";
					$extend = "3 MONTH";
						
					if($member['type'] == '2'){
						$pay = "90";
					}else if($member['type'] == '3'){
						$pay = "70";
					}
						
					$rate = 3;
				}
				elseif($duration < 95)
				{
					echo " Verlängert um 1 Jahr ";
					$extend = "1 YEAR";
					
					if($member['type'] == '2'){
						$pay = "210";
					}else if($member['type'] == '3'){
						$pay = "170";
					}
					$rate = 4;
				}
				else
				{
					echo " Verlängert um 1 Jahr ";
					$extend = "1 YEAR";
					$pay = "210";
					$rate = 4;
				}
			}
			else
			{
				echo $member['username']." lläuft aus am: ".$member['payment'];
				$duration = funcs::dateDiff("-", $member['payment'], $member['payment_received']);
				echo " => derzeitige Dauer: ".$duration." Tage.";				

				//----- Start Get Number Date Before Expires -----//
					
				//$history_details = funcs::getHistoryDetails($member['username']);
				
				list($sy, $sm, $sd) = explode("-", $info['cancelled_date']);
				$startdate = sprintf("%01d",$sd)." ".funcs::changeFormatDate($sm)." ".$sy;

				list($ey, $em, $ed) = explode("-", $info['end_date']);									
				$enddate = sprintf("%01d", $ed)." ".funcs::changeFormatDate($em)." ".$ey;				
				
				//----- End Get Number Date Before Expires -----//

				if($duration == 3){
					
						echo " Verlängert um 1 Monat ";
						$extend = "1 MONTH";
						$pay = "30";
						$rate = 2;					

				}elseif($duration < 32){

					$date_before_expires = funcs::getDateDiff('ww', $startdate, $enddate, false);	
					
					if($date_before_expires < 1){
						echo " Verlängert um 3 Monate ";
						$extend = "3 MONTH";
						
						if($member['type'] == '2'){
							$pay = "90";
						}else if($member['type'] == '3'){
							$pay = "70";
						}
						
						$rate = 3;
					}

				}elseif($duration < 95){

					$date_before_expires = funcs::getDateDiff('ww', $startdate, $enddate, false);
					echo "[$startdate, $enddate] $date_before_expires";
					if($date_before_expires < 2){
						echo " Verlängert um 1 Jahr ";
						$extend = "1 YEAR";
						
						if($member['type'] == '2'){
							$pay = "210";
						}else if($member['type'] == '3'){
							$pay = "170";
						}
						$rate = 4;
					}
				}else{
					
					$date1 = mktime(0, 0, 0, $em, $ed, $ey);
					$date2 = mktime(0, 0, 0, $sm, $sd, $sy);

					$date_before_expires = ($date1 - $date2) / 86400;					
					if($date_before_expires < 31){
					echo " Verlängert um 1 Jahr ";
					$extend = "1 YEAR";
					$pay = "210";
					$rate = 4;
					}
				}
			}
					
			//$message .= funcs::get_message_extend($member['username'],$member['type'],$extend,$pay,$message);
			if($testing == false)
			{
				$sql = "SELECT paid_via FROM payment_log WHERE username = '$member[username]' and payment_complete = '1' ORDER BY ID DESC LIMIT 1";
				
				$last_payment = DBConnect::retrieve_value($sql);
				
				if($last_payment == 2 || $last_payment == 3){
					funcs::send_memberExtend_customer($smarty,$member['username'],$member['email'],$member['type'],$extend,$pay);	
				}
			}
			$sql = "SELECT (payment + INTERVAL ".$extend.") as new FROM ".TABLE_MEMBER." WHERE id=".$member['id'];
			echo DBConnect::retrieve_value($sql);
			echo "\n";

			if($testing == false)
			{
				/*$sql = "UPDATE ".TABLE_MEMBER." SET payment = (payment + INTERVAL ".$extend.") WHERE id=".$member['id'];
				DBConnect::execute($sql);*/
				if($rate > 0){
					funcs::insertpayment($member['id'],$member['type'],$rate,0,0);				
				}
			}
			
			$sql = "SELECT p.*,m.ID as m_id,m.username as m_username,m.mobileno as m_mobileno FROM payment_log as p INNER JOIN member as m ON m.username = p.username WHERE m.username = '".$member[username]."'";
			$rs = DBConnect::assoc_query_1D($sql);
			array_push($arr, $rs);
			
		}
		if((count($arr) > 0) && ($testing == false)) {
			funcs::send_memberExtend_admin($smarty,'payment@tmp-sn.de',$member[type],$arr);
			//funcs::send_memberExtend_admin($smarty,'tananarak7@yahoo.com',$member[type],$arr);
		}
		elseif(($message != "") && ($testing == true))
		{
			echo "\nEmail an payment@tmp-sn.de:";
			echo "\n".$message;
		}
	}
	else
	{
		echo "Heute ist keine Mitgliedschaft ausgelaufen";
	}
}
else
{
	echo "Datenbank Probleme.<br>$sql";
}
echo "</pre>";
?>