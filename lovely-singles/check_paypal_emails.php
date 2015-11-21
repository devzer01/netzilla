<?php
require_once('classes/top.class.php');

define("ENABLE_TOPUP", 1);

/* connect to gmail */
$hostname = '{mail.k-and-b-ltd.com:143/novalidate-cert}INBOX';
$username = 'payment@k-and-b-ltd.com';
$password = '78pUJcn1';

/* try to connect */
$inbox = imap_open($hostname,$username,$password, OP_READONLY) or die('Cannot connect: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox,'FROM "member@paypal.com"');

/* if emails are returned, cycle through each... */
if($emails) {
	/* begin output var */
	$output = '<table>';

	/* put the newest emails on top */
	rsort($emails);

	/* for every email... */
	foreach($emails as $email_number) {
		/* get information specific to this email */
		$uid = imap_uid($inbox,$email_number);
		if($email = DBConnect::assoc_query_1D("SELECT header, overview, customer_name, customer_email, username, udate, coins FROM emails WHERE uid=".$uid))
		{
			$customer_name = $email['customer_name'];
			$customer_email = $email['customer_email'];
			$username = $email['username'];
			$coins = $email['coins'];
			$udate = $email['udate'];
		}
		else
		{
			$header = imap_headerinfo($inbox,$email_number);
			$overview = imap_fetch_overview($inbox,$email_number,0);
			$body = imap_fetchbody($inbox,$email_number,2);

			$customer_name = str_replace(" via PayPal <member@paypal.com>","", utf8_encode(iconv_mime_decode($overview[0]->from, 1, "ISO-8859-1")));
			$customer_email = $header->reply_toaddress;
			$body = quoted_printable_decode($body);
			$username = getUsername($body);
			$udate = $overview[0]->udate;
			$coins = null;
			if($user_id = DBConnect::retrieve_value("SELECT 1 FROM member WHERE username='".$username."'"))
			{
				$coins = getCoins($username);
			}
			$coins = $coins?$coins:0;

			$sql = "INSERT INTO emails (email, uid, header, overview, customer_name, customer_email, username, coins, udate) VALUES('".$username."', ".$uid.", '".serialize($header)."', '".serialize($overview)."', '".$customer_name."', '".$customer_email."', '".$username."', ".$coins.",'".$udate."')";
			file_put_contents("emails/".$uid.".eml", $body);
			DBConnect::execute_q($sql);

			if((ENABLE_TOPUP === 1) && $user_id && ($coins>0))
			{
				$user_id = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'");

				DBConnect::execute_q("UPDATE member SET coin=coin+".$coins." WHERE username='".$username."'");

				//get current coin value
				$coinVal = funcs::checkCoin($username);

				//insert coin log
				$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$user_id."','payment',".$coins.",".$coinVal.", NOW())";
				DBconnect::execute($sqlAddCoinLog);

				//reset warning_sms
				$sqlResetWarningSMS = "DELETE FROM warning_sms WHERE userid=".$user_id;
				DBconnect::execute($sqlResetWarningSMS);

				$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
				DBConnect::execute_q("INSERT INTO purchases_log (user_id,package_id,price,coin_amount,currency,purchase_datetime, ip, purchase_finished, payment_method, payment_type, purchase_finished_date) VALUES (".$user_id.",0,50,".$coins.",'".$currency."',NOW(),'".$_SERVER['REMOTE_ADDR']."', 1, 'Paypal', 'Manual', NOW())");

				DBconnect::execute_q("UPDATE emails SET finished=1 WHERE uid=".$uid);
			}
		}

		$output .= "<tr>";
		$output .= "<td>".$uid."</td>";
		$output .= "<td>".$customer_name."</td>";
		$output .= "<td>".$customer_email."</td>";
		$output .= "<td>".$username."</td>";
		$output .= "<td>".$coins."</td>";
		$output .= "<td>".date("Y-m-d H:i:s P", $udate)."</td>";
		$output .= "</tr>";
	}
	$output .= "</table>";

	if(isset($_GET['verbose']) && ($_GET['verbose']=="1"))
		echo $output;
}

/* close the connection */
imap_close($inbox);

function convertToXML($content)
{
	$search = '/\<!--(.*?)--\>/is';
	$replace = '';
	$content = preg_replace( $search, $replace, $content );
	$tidy_config = array(	'clean' => true,
							'output-xhtml' => true,
							'show-body-only' => true,
							'wrap' => 0,
							'indent' => true,
							'indent-spaces' => 4
				 );
	$content = tidy_parse_string($content, $tidy_config, 'UTF8');
	$content->cleanRepair( );
	$content = str_replace("&nbsp;"," ",$content);

	$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";

	$parser = new XMLParser($xml);
	$parser->Parse();
	return $parser;
}

function getUsername($content)
{
	if(strpos($content,'Dein Nutzername::') !== false)
	{
		$content = substr($content,strpos($content,'Dein Nutzername::')+18);
		$content = substr($content,0,strpos($content,'</td>'));
		$content = strip_tags($content);
		return trim($content);
	}
	else
	{
		return "";
	}
}

function getCoins($username)
{
	$columns = DBConnect::row_retrieve_2D_conv_1D("SHOW COLUMNS FROM coin_package");

	if(in_array("from_signup_date", $columns))
	{
		$signup_date = DBConnect::retrieve_value("SELECT signup_datetime FROM member WHERE username='".$username."' AND username != ''");

		$last_package_date = DBConnect::retrieve_value("SELECT MAX(c.from_signup_date) FROM coin_package c LEFT JOIN purchases_log l ON l.package_id=c.id LEFT JOIN member m ON m.id=l.user_id WHERE m.username='".$username."' AND l.urchase_finished=1");
		$max = DBConnect::retrieve_value("SELECT MAX(from_signup_date) FROM coin_package");

		if(!$last_package_date)
			$signup_date = $max;
		else
			$signup_date = $last_package_date;
		$package_date = DBConnect::assoc_query_2D("SELECT from_signup_date FROM coin_package WHERE from_signup_date<'".$signup_date."' GROUP BY from_signup_date ORDER BY from_signup_date DESC");

		$sql = "SELECT coin FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' AND paypal!='' LIMIT 1";
	}
	else
	{
		$sql = "SELECT 650";
	}
	return DBConnect::retrieve_value($sql);
}
?>