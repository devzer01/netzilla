<?php

session_start();
$_SESSION['lang'] = 'ger';
require_once('conf/'.$_SESSION['lang'].'.php');
require_once('classes/top.class.php'); 

flush();
//print $_GET['gp_payment'];

if ($_GET['gp_payment']) {     
             
      if ($_GET['gp_payment'] == 'failed') {
          
         //Mail an kschwerdt
         funcs::sendMail("knut.schwerdtfeger@la-lee.de", "GP Zahlung failed", print_r($_GET, true), "gp_back@herzoase.com");


         $smarty->assign('gp_status', "Die Zahlung konnte nicht durchgeführt werden!");
         $smarty->assign('section', 'failed_message');
         $smarty->display('index.tpl');
      
      } else if ($_GET['gp_payment'] == 'success') {
      
//###########################################################################

   // Zahlungsstatus überprüfen...
   // $_GET['pay_id'] ==> aus payment_log kontaktid:werbecode auslesen (transaktionsnummer)
         if ($_GET['pay_id']) {
             
            $sql = "SELECT * FROM ".TABLE_PAY_LOG." WHERE ID = '".$_GET['pay_id']."'";
            $member_data = DBconnect::assoc_query_1D($sql);

            //Userdaten sammeln
            $username = $member_data['username'];
            $type = $member_data['new_type'];
            $payment_until = $member_data['new_paid_until'];
            $paid_via = $member_data['paid_via'];
            
            $member = DBconnect::assoc_query_1D("select email, gender from member where username = '".$username."'");
            $member_email = $member['email'];
            
            //MarktID holen
            $database = "herzoase";
            $dbhost = "localhost";
            $dbuser = "root";
            $dbpasswd = "";
            
            $conn = mysql_connect($dbhost, $dbuser, $dbpasswd);
            mysql_select_db("emailchat_center", $conn);
            
            $query = "select id from sites where name = '".$database."'";
            $result = mysql_query($query);
            
            if ($result)
                $marktId = mysql_fetch_array($result);
            else $marktId = '-1';
            
            mysql_select_db($database, $conn);

//            mysql_close($conn);
 
            if ($paid_via == '6')
               list($kontaktid, $werbecode) = preg_split('~[:]~U', $member_data['transaction_no'], -1, PREG_SPLIT_NO_EMPTY);
               
               
            // Statusabfrage an ClickPay zur Überprüfung
            $sendUrl = "https://www.albis-zahlungsdienste.de/checkonlineueberweisung.acgi?kontaktid=".$kontaktid."&werbecode=".$werbecode;
      
            $curl = curl_init($sendUrl);
      		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		   	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		   	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		   	$response = curl_exec($curl);
		   	curl_close($curl);
			
		   	parse_str($response);
            
            // wenn ok dann...   
            if ($Ergebnis == '0') {
                
                if(funcs::loginSite($username, $member_data['password'])) {
               
                  $sql = "SELECT id FROM member WHERE username = '".$username."'";
                  $rec_userid = DBconnect::retrieve_value($sql);
 
                  $sql = "UPDATE member SET type = ".$type.", payment_received = NOW(), payment = '".$payment_until."' WHERE id = '".$rec_userid."'";
                  $check = DBconnect::execute_q($sql);
                     
                  //Alles ok => Zahlung eintragen TODO: PayDay eintragen sowie new_paid_until setzen!!??
                  if ($check) {
                     $sql = "UPDATE ".TABLE_PAY_LOG." SET ".TABLE_PAYLOG_PAID." = '1' WHERE id = '".$_GET['pay_id']."'";
                     DBconnect::execute_q($sql);
                        
                    	$_SESSION['sess_permission'] = $type;

                    	if ($type == 3) {
                    		if (funcs::checkFor1DayGold($rec_userid)) {
                    			$type = 2;
                    			$_SESSION['sess_permission'] = $type;
                    		}
                    	}
                       
                    	switch($type) {
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
                     
                     //Bestätigungsmail an Mitglied schicken
                     
                     $mail_from = MAIL_FROM;
                     $domain = preg_split("/@/", $mail_from);
                     $mail_subject = "Ihre Giropay Onlineüberweisung";
               		$smarty->assign('domain', $domain[1]);
               		$smarty->assign('payday', $member_data['payday']);
               		$smarty->assign('sum_paid', $member_data['sum_paid']);
               		$smarty->assign('marktId', $marktId);
               		$smarty->assign('referenz', $_GET['referenz']);
                     $smarty->assign('pay_id', $_GET['pay_id']);
                     $smarty->assign('gender', $member['gender']);
                     $smarty->assign('real_name', $member_data['real_name']);
               		$smarty->assign('url_web', URL_WEB);
               		$mail_message =  $smarty->fetch('gp_mail.tpl');

               		funcs::sendMail($member_email, $mail_subject, $mail_message, $mail_from);
                    
                   //Mail an kschwerdt
                   funcs::sendMail("knut.schwerdtfeger@la-lee.de", "GP Zahlung", $mail_message, "gp_back@herzoase.com");

               		
                     $smarty->assign('section', 'okay_message');
                     $smarty->display('index.tpl');
                  }
               }
            } else {
               //Fehlermeldung eintragen
               $sql = "UPDATE ".TABLE_PAY_LOG." SET errormsg = '".$response."' WHERE ".TABLE_PAYLOG_ID." = '".$_GET['pay_id']."'";
               DBconnect::execute_q($sql);
               
               //Mail an kschwerdt
               funcs::sendMail("knut.schwerdtfeger@la-lee.de", "GP Zahlung", $response, "gp_back@herzoase.com");
                  
               $smarty->assign('gp_status', "Die Zahlung konnte nicht durchgeführt werden!");
               $smarty->assign('section', 'failed_message');
               $smarty->display('index.tpl');
            }
             
      	}
	   }
}   
    
?>
