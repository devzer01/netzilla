<?
session_start();
$_SESSION['lang'] = 'ger';
require_once('conf/'.$_SESSION['lang'].'.php');
require_once('classes/top.class.php'); 

//PAYPAL ZAHLUNG
if ($_GET['from'] == 'paypal'){
	
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-synch';
	
	$tx_token = $_GET['tx'];
	$auth_token = "u2ht5VJSqFZArfqSc5XEXmLuxG_HO8jsX-Q-8kz1vgjpv-FD9KqGa-u8y74";
	$req .= "&tx=$tx_token&at=$auth_token";
	
	// post back to PayPal system to validate
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
	// If possible, securely post back to paypal using HTTPS
	// Your PHP server will need to be SSL enabled
	// $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
	
	if (!$fp) {
	// HTTP ERROR
	} else {
	fputs ($fp, $header . $req);
	// read the body data 
	$res = '';
	$headerdone = false;
	while (!feof($fp)) {
	$line = fgets ($fp, 1024);
	if (strcmp($line, "\r\n") == 0) {
	// read the header
	$headerdone = true;
	}
	else if ($headerdone)
	{
	// header has been read. now read the contents
	$res .= $line;
	}
	}
	
	// parse the data
	$lines = explode("\n", $res);
	$keyarray = array();
	if (strcmp ($lines[0], "SUCCESS") == 0) {
	for ($i=1; $i<count($lines);$i++){
	list($key,$val) = explode("=", $lines[$i]);
	$keyarray[urldecode($key)] = urldecode($val);
	}
	// check the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your Primary PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment
	$firstname = $keyarray['first_name'];
	$lastname = $keyarray['last_name'];
	$itemname = $keyarray['item_name'];
	$amount = $keyarray['payment_gross'];
	$paylog_data_id = $keyarray['item_number'];
	$papallSuccess = true;
	
//	echo ("<p><h3>Thank you for your purchase!</h3></p>");
	
//	echo ("<b>Payment Details</b><br>\n");
//	echo ("<li>Name: $firstname $lastname</li>\n");
//	echo ("<li>Item: $itemname</li>\n");
//	echo ("<li>Amount: $amount</li>\n");
//	echo ("");
	}
	else if (strcmp ($lines[0], "FAIL") == 0) {
	// log for manual investigation
	}
	
	}
	
	fclose ($fp);
}




if ($_GET['paylog_id']){
	$paylog_data_id = $_GET['paylog_id'];
}

$sql = "SELECT * FROM ".TABLE_PAY_LOG." WHERE ID = $paylog_data_id";

$member_data = DBconnect::assoc_query_1D($sql);

$username = $member_data['username'];
$type = $member_data['new_type'];
$payment_until = $member_data['new_paid_until'];
$paid_via = $member_data['paid_via'];

if(funcs::loginSite($username, $member_data['password']))
{	
	if (count($_GET) > 1)
	    $params= $_GET;
	elseif (count($_POST) > 1)
	    $params= $_POST;
	else
	    $params= array();
	
	if (count($params) > 1){
	    $status= "failed";
		$section = 'failed_message';
	}
	else{
	    $status= "successful";
		$section = 'okay_message';
	}
	
	//Paypall new
	if ($papallSuccess){
	    $status= "successful";
		$section = 'okay_message';		
	}

	if ($status == 'successful'){

		$sql = "SELECT id FROM member WHERE username='$username'";
		$rec_userid =  DBconnect::retrieve_value($sql);

		$sql = "UPDATE member SET type = ".$type.", payment_received = NOW(), payment = '".$payment_until."' WHERE id =".$rec_userid;
		$check = DBconnect::execute_q($sql);
		if($check){
			//DBconnect::insert_row("history", "user_id, start_date, end_date, membership_type, paid_via", "'$rec_userid', NOW(), '$payment_until', '$type', '$paid_via'");	
			$sql = "UPDATE ".TABLE_PAY_LOG." SET ".TABLE_PAYLOG_PAID." = 1 WHERE id = $paylog_data_id";
			DBconnect::execute_q($sql);
		}
	}
	
	if ($status == 'failed'){
            $sql = "SELECT type FROM member WHERE username='$username'";
		    $type =  DBconnect::retrieve_value($sql);
            $sql = "UPDATE ".TABLE_PAY_LOG." SET errormsg = '".$_GET['ret_errormsg']."' WHERE id = $paylog_data_id";
			DBconnect::execute_q($sql);		
	}	
	
	$_SESSION['sess_permission'] = $type;

	if ($type == 3) {
		if (funcs::checkFor1DayGold($rec_userid)) {
			$type = 2;
			$_SESSION['sess_permission'] = $type;
		}
	}

	switch($type){
		case 1:
			$_SESSION['sess_admin'] = 1;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_superadmin'] = 1;					
		break;
		case  2:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_superadmin'] = 0;					
		break;
		case  3:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_superadmin'] = 0;					
		break;
		case  4:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_superadmin'] = 0;					
		break;
		case  5:
			$_SESSION['sess_admin'] = 0;
			$_SESSION['sess_mem'] = 0;
			$_SESSION['sess_superadmin'] = 0;
		break;
		case  9:
			$_SESSION['sess_admin'] = 1;
			$_SESSION['sess_mem'] = 1;
			$_SESSION['sess_superadmin'] = 0;					
		break;				
		
	}
	
	$smarty->assign('ret_errormsg', $_GET['ret_errormsg']);
	$smarty->assign("section",$section);
	$smarty->display('index.tpl');
}	    
    
    
?>
