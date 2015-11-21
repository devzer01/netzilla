<?php
include_once('classes/budgetsms.class.php');
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

	if(!defined("SMS_PROVIDER"))
	{
		DBConnect::execute_q("INSERT INTO config (`name`, `value`) VALUES ('SMS_PROVIDER', 'smscountry')");
		define('SMS_PROVIDER', 'smscountry');
		$site_configs_filename = "classes/site_configs.inc.php";
		if(file_exists($site_configs_filename))
		{
			unlink($site_configs_filename);
		}
	}

	switch(SMS_PROVIDER)
	{
		case "clickatell":
			sendSMS_BULK_clickatell(substr($handynr,0,$country_code_length),substr($handynr,$country_code_length),$sms);
			break;
		case "budgetsms":
			sendSMS_BULK_budgetsms(substr($handynr,0,$country_code_length),substr($handynr,$country_code_length),$sms);
			break;
		case "smscountry":
		default:
			sendSMS_BULK_smscountry(substr($handynr,0,$country_code_length),substr($handynr,$country_code_length),$sms);
			break;
	}
}

function convert($text)
{
	/*$from = array(chr(148),chr(153),chr(129),chr(154),chr(132),chr(142),chr(225));
	$to = array("ö","Ö","ü","Ü","ä","Ä","ß");

	return str_replace($to,$from,$text);*/
	return $text;
}

// using BudgetSMS.net
function sendSMS_BULK_budgetsms($country, $mobilenr, $message)
{
	if(strlen($country.$mobilenr)>10)
	{
		$username="battersea12"; //your username
		$userid='8934';
		$handle="094c9daa20b8c4dd9e90e5b846e0808b"; //your password

		switch($country)
		{
			case "49":
			default:
				$senderid="491793491666";
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

// using SMSCountry.com
function sendSMS_BULK_smscountry($country, $mobilenr, $msg)
{
	$user="markvaughanb"; //your username
	$password="battersea1"; //your password
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
				$senderid="491793491666";
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

function sendSMS_BULK_clickatell($country, $mobilenr, $message)
{
	if(strlen($country.$mobilenr)>10)
	{
		$user = "MarkVaughanB";
		$password = "fVOsssIUDKfYWb7V";
		$api_id = "3440936";
		$baseurl ="http://api.clickatell.com";
		$senderid = "491793491666";

		$text = urlencode($message);
		$to = $country.$mobilenr;

		// auth call
		$url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

		// do auth call
		$ret = file($url);

		// explode our response. return string is on first line of the data returned
		$sess = explode(":",$ret[0]);
		if ($sess[0] == "OK") {
			$sess_id = trim($sess[1]); // remove any whitespace
			$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&req_feat=1&concat=3&from=".$senderid;

			// do sendmsg call
			$ret = file($url);
			$send = explode(":",$ret[0]);

			if ($send[0] == "ID") {
				$report = "successnmessage ID: ". $send[1];
			} else {
				$report = "send message failed";
			}
		}
		else {
			$report = "Authentication failure: ". $ret[0];
		}
		if($report != '')
		{
			$_SESSION['report']=$report;
		}

		DBConnect::execute_q("INSERT INTO sms_log (from_number, to_number, message, sent_datetime, sms_id, provider_name, provider_account, status) VALUES ('".$senderid."', '".$country.$mobilenr."', '".convert($message)."', NOW(), '".$send[1]."', 'Clickatell', '".$user."', '".$report."')");
	}
}
?>