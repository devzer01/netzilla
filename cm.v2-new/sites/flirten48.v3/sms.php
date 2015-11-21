<?php
include_once('classes/budgetsms.class.php');
function sendSMSCode($handynr, $sms)
{
	if(($encoding = mb_detect_encoding($sms))=="UTF-8") $sms = iconv("UTF-8", "ISO-8859-1", $sms);

	$country_code_length = 2;
	if (substr($handynr,0,1)=="1") $country_code_length = 1;
	
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

function sms__unicode($message) {
  if (function_exists('iconv')) {
    $latin = @iconv('UTF-8', 'ISO-8859-1', $message);
    if (strcmp($latin, $message)) {
      $arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE', $message));
      return strtoupper($arr['hex']);
    }
  }
  return FALSE;
}

function convert($text)
{
	return $text;
}

// using BudgetSMS.net
function sendSMS_BULK_budgetsms($country, $mobilenr, $message)
{
	if(strlen($country.$mobilenr) < 10) return false;
	
	$username="battersea12"; //your username
	$userid='8934';
	$handle="094c9daa20b8c4dd9e90e5b846e0808b"; //your password

	switch($country)
	{
		case "49":
		default:
			$senderid="4915211070649";
			break;
	}
	
	if(($encoding = mb_detect_encoding($message))=="UTF-8") $message = iconv("UTF-8", "ISO-8859-1", $message);
		
	funcs::queueSMS($country.$mobilenr, $message, $senderid, 'budgetsms', $username, $userid, null, $handle, MYSQL_DATABASE);	

	return true;
}

// using SMSCountry.com
function sendSMS_BULK_smscountry($country, $mobilenr, $msg)
{
	if(strlen($mobilenumbers) < 10) return false;
	
	$user="markvaughanb"; //your username
	$password="battersea1"; //your password

	$mobilenumbers=$country.$mobilenr; //enter Mobile numbers comma seperated
	
	switch($country)
	{
		case "49":
		default:
			$senderid="4915211070649";
			break;
	}
	
	funcs::queueSMS($mobilenumbers, $msg, $senderid, 'smscountry', $user, null, $password, null, MYSQL_DATABASE);

	return true;
}

function sendSMS_BULK_clickatell($country, $mobilenr, $message)
{
	if(strlen($country.$mobilenr) < 10) return false;
	
	$user = "MarkVaughanB";
	$password = "fVOsssIUDKfYWb7V";
	$api_id = "3440936";
	$senderid = "4915211070649";

	$to = $country.$mobilenr;
	
	funcs::queueSMS($to, $message, $senderid, 'clickatell', $user, $api_id, $password, null, MYSQL_DATABASE);
	
	return true;
}