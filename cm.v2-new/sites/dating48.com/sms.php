<?php
function sendSMSCode($handynr, $sms)
{
	if(($encoding = mb_detect_encoding($sms))=="UTF-8")
	{
		$sms = iconv("UTF-8", "ISO-8859-1", $sms);
	}

	if(substr($handynr,0,1)=="1")
		$country_code_length = 1;
	else
		$country_code_length = 2;

	//sendSMS_BULK_smscountry(substr($handynr,0,$country_code_length),substr($handynr,$country_code_length),$sms);
	sendSMS_BULK_budgetsms(substr($handynr,0,$country_code_length),substr($handynr,$country_code_length),$sms);
}

// using BudgetSMS.net
function sendSMS_BULK_budgetsms($country, $mobilenr, $message)
{
	if(strlen($country.$mobilenr)>10)
	{
		include('classes/budgetsms.class.php');
		$username="battersea12"; //your username
		$userid='8934';
		$handle="094c9daa20b8c4dd9e90e5b846e0808b"; //your password

		switch($country)
		{
			case "49":
			default:
				$senderid="4917639663377";
				break;
		}

		if(($encoding = mb_detect_encoding($message))=="UTF-8")
		{
			$message = iconv("UTF-8", "ISO-8859-1", $message);
		}
		 
		$SMS = new budgetSMS($username, $userid, $handle);

		$SMS->msg = convert($message);
		$SMS->to = $country.$mobilenr;
		$SMS->from = $senderid;
		$SMS->customid = date("YmdHis").rand(0,9);
		if ($SMS->sendSMS()) {
			$report = 'Sending SMS successful, SMSid: '.$SMS->result;
			$status = "Delivered.";
			$sms_id = $SMS->result;
		} else {
			$report = 'Sending SMS failed, errorcode: '.$SMS->result.'<br>'.$SMS->error($SMS->result);
			$status = "Failed. Errorcode: ".$SMS->result." ".$SMS->error($SMS->result);
			$sms_id = "";
		}

		if($report != '')
		{
			$_SESSION['report']=$report;
		}

		DBConnect::execute_q("INSERT INTO sms_log (from_number, to_number, message, sent_datetime, sms_id, provider_name, provider_account, status) VALUES ('".$senderid."', '".$country.$mobilenr."', '".convert($message)."', NOW(), '".$sms_id."', 'BudgetSMS', '".$username."', '".$status."')");
	}
}


function convert($text)
{
	/*$from = array(chr(148),chr(153),chr(129),chr(154),chr(132),chr(142),chr(225));
	$to = array("ö","Ö","ü","Ü","ä","Ä","ß");

	return str_replace($to,$from,$text);*/
	return $text;
}

// using SMSCountry.com
function sendSMS_BULK_smscountry($country, $mobilenr, $msg)
{
	$user="markvaughanb"; //your username
	$password="hOtL3aJFah"; //your password
	$messagetype="N"; //Type Of Your Message
	$DReports="Y"; //Delivery Reports
	$url="http://api.smscountry.com/SMSCwebservice_bulk.aspx";

	$mobilenumbers=$country.$mobilenr; //enter Mobile numbers comma seperated
	$message = $msg; //enter Your Message

	if(strlen($mobilenumbers)>10)
	{
		switch($country)
		{
			case "49":
			default:
				$senderid="4917639663377";
				break;
		}

		$message = urlencode($message);
		$ch = curl_init();
		if (!$ch){die("Couldn't initialize a cURL handle");}
		$ret = curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//If you are behind proxy then please uncomment below line and provide your proxy ip with port.
		// $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");

		$curlresponse = curl_exec($ch); // execute
		if(curl_errno($ch))
			$report = 'curl error : '. curl_error($ch);

		$status = "";
		$sms_id = "";
		if (substr($curlresponse, 0, 2) == 'OK')
		{
    		$arr = explode(':', $curlresponse);
    		$sms_id = $arr[1];
    	}
		else
		{
			$status = "Failed. ".$curlresponse;
    	}

		curl_close($ch); // close cURL handler

		DBConnect::execute_q("INSERT INTO sms_log (from_number, to_number, message, sent_datetime, sms_id, provider_name, provider_account, status) VALUES ('".$senderid."', '".$country.$mobilenr."', '".addslashes(urldecode($message))."', NOW(), '".$sms_id."', 'SMSCountry', '".$user."', '".$status."')");
	}
	else
	{
		//Wrong mobile number
		$report = "Wrong mobile number";
	}

	if($report != '')
	{
		$_SESSION['report']=$report;
	}
}
?>