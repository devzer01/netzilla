<?php
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

require_once 'HTTP/Request.php';

if ($_POST['gpform_submit']) {

// Zahlungsdaten eintragen
      $sql = "SELECT ".TABLE_PAYLOG_ID." FROM ".TABLE_PAY_LOG." WHERE ".TABLE_MEMBER_USERNAME." = '".$_SESSION[sess_username]."' order by ".TABLE_PAYLOG_ID." DESC Limit 1";
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
//		DBconnect::execute($sql);
	
//GiroPay-Daten sammeln
   	$name = $_POST['addr_vorname'].' '.$_POST['addr_name'];
   	
   	$sql = "select id from member where username = '".$_SESSION[sess_username]."'";
      $member_id = DBconnect::retrieve_value($sql);	
   	
   	$referenz = $member_id;
   	
   	$haendlerId = '30048';
   	$haendlerCode = 'V3uSWpi7AR';
   	
   	$bruttobetrag = $_POST['preis'];
   	$waehrung = $_POST['trx_currency'];
   	
   	$blz = $_POST['bank_code'];
   	$kto = $_POST['bank_accountnumber'];
   	
   	$backURL = URL_WEB."/gp_back.php?gp_payment=failed&referenz=".$member_id."&pay_id=".$paylog_id;
   	$successURL = URL_WEB."/gp_back.php?gp_payment=success&referenz=".$member_id."&pay_id=".$paylog_id;
   	$failURL = "";
   	$errorURL = "";
   	
   	
//GiroPay-Anfrage starten
      $address = "https://www.albis-zahlungsdienste.de/onlineueberweisung.acgi";
      
      $sendURL = $address."?referenz=".$referenz
                     . '&haendlerid='.$haendlerId
							. '&haendlercode='.$haendlerCode
							. '&bruttobetrag='.number_format($bruttobetrag, 2, ',', '')
							. '&waehrung=' . $waehrung
							. '&blz=' . $blz
                     . '&kontonummer=' . $kto
							. '&kontoinhaber=' . rawurlencode($name)
							
							. '&BackURL=' . rawurlencode($backURL)
							. '&SuccessURL=' . rawurlencode($successURL)
							. '&FailURL=' . rawurlencode($backURL)
							. '&ErrorURL=' . rawurlencode($backURL);
	
    
    //print $sendURL."<br>";
    //print rawurlencode($sendURL)."<br>";
     
//Abschicken und Antwort parsen

      $curl = curl_init($sendURL);
			curl_setopt($curl, CURLOPT_FAILONERROR, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
			$response = curl_exec($curl);
			curl_close($curl);
			
			parse_str($response);
       
//Kunde weiterleiten

         if ($status == 'OK') {
         
            //Vorgangsnummer und -kennwort in payment_log eintragen
            $sql = "UPDATE ".TABLE_PAY_LOG." SET transaction_no = '".$kontaktid.":".$werbecode."' WHERE ".TABLE_PAYLOG_ID." = '".$paylog_id."'";
            DBconnect::execute_q($sql);
            
            //Kunden zum Bezahlen weiterleiten
            header('Location: '.$URL);
      
         } else {
            //Ergebnis in payment_log eintragen
            $sql = "UPDATE ".TABLE_PAY_LOG." SET errormsg = '".$response."' WHERE ".TABLE_PAYLOG_ID." = '".$paylog_id."'";
            DBconnect::execute_q($sql);
            
            //StatusCode auswerten
            if ($Statuscode == '0')
                $statusmessage = funcs::getText($_SESSION['lang'], '$payportal1');
            else if ($Statuscode == '1')
                $statusmessage = funcs::getText($_SESSION['lang'], '$payportal2');
            
            if (!$statusmessage)
               $statusmessage = funcs::getText($_SESSION['lang'], '$payportal3');
               
            //Mail an kschwerdt
            //funcs::sendMail("knut.schwerdtfeger@la-lee.de", "GP Zahlung", $response, "payportal_gp@lovely-singles.com");
               
            $smarty->assign('gp_status', $statusmessage);
            $smarty->assign('section', 'failed_message');
            $smarty->display('index.tpl');
         }
 
}


?>