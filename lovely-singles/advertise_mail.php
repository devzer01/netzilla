<?php
require_once('classes/top.class.php');

require_once('config.php');

class funcs
{	
	
	static function activate()
	{
		$username = $_GET['username'];
		$password = $_GET['password'];
		$code = $_GET['code'];
		$adv = $_GET['adv'];

		if(funcs::activateMember($username, $password, $code)) {	//check activate complete?
			
			if (!funcs::checkmobile($username)) {
				
				if ($adv == '1') {
					funcs::loginSite($username, $password);	//automatic login
					header("location: ?action=validCode2");	//go to first page
				}
				else {
					funcs::loginSite($username, $password);	//automatic login
					header("location: .");	//go to first page
				}	
			}
				
			elseif (!funcs::checkvalidated($username)) {
					
				if ($adv == '1') {
					funcs::loginSite($username, $password);	//automatic login
					header("location: ?action=validCode2");	//go to first page	
				}		
				else {
					funcs::loginSite($username, $password);	//automatic login
					header("location: ?action=validCode");	//go to first page				
				}
			}
			
			else {
				funcs::loginSite($username, $password);	//automatic login
				header("location: .");	//go to first page
			}
		}

		else {
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$activate_alert'));	//show activate error
			//select template file//
			$smarty->display('index.tpl');
		}
	}
	
	
	static function checkmobile($user)
	{
		$sql = "SELECT mobileno FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$no = DBconnect::retrieve_value($sql);

		return $no;
	}

	
	static function checkvalidated($user)
	{
		$sql = "SELECT validated FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$validated = DBconnect::retrieve_value($sql);

		return $validated;
	}
	

	static function loginSite($username, $password)
	{
		$member = DBconnect::login($password, $username, TABLE_MEMBER, TABLE_MEMBER_PASSWORD, TABLE_MEMBER_USERNAME);
		if(((int)$member[TABLE_MEMBER_ID] > 0) && ($member[TABLE_MEMBER_ISACTIVE] == 1))
		{
			$_SESSION['sess'] = session_id();
			$_SESSION['sess_id'] = $member[TABLE_MEMBER_ID];
			$status = $member[TABLE_MEMBER_STATUS];
			$_SESSION['tcheck'] = $member['tcheck'];

			if ($status == 3) {
				if (funcs::checkFor1DayGold($_SESSION['sess_id'])) {
					$status = 2;
					$_SESSION['sess_permission'] = $status;
				}
			}

			/*********************
			*1 : ADMIN
			*2 : MEMBERSHIP-GOLD
			*3 : MEMBERSHIP-SILVER
			*4 : MEMBERSHIP-BRONZE
			*5 : TEST-MEMBERSHIP
			*********************/
			$_SESSION['sess_permission'] = $status;
			switch($status){
				case 1:
					$_SESSION['sess_admin'] = 1;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 1;
					$_SESSION['payment_admin'] = 1;					
				break;
				case  2:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;							
				break;
				case  3:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;						
				break;
				case  4:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;						
				break;
				case  5:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 0;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;						
				break;
				case 8:
					$_SESSION['sess_admin'] = 1;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;						
				break;				
				case  9:
					$_SESSION['sess_admin'] = 1;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;						
				break;

			}
			$_SESSION['sess_username'] = $username;
			if($member[TABLE_MEMBER_SIGNIN_DATETIME] == '0000-00-00 00:00:00')
			{
				$_SESSION['sess_first'] = 1;
				$to_id = funcs::randomStartProfile($_SESSION['sess_id']);
				funcs::sendMessage($_SESSION['sess_id'],$to_id,'Erstanmeldung','Erstanmeldung',3);
				if($member['description'] != "")
				{
					funcs::addLonelyHeartFromDescription($member);
				}
			}
			else
				$_SESSION['sess_first'] = 0;

			/*----------------------Random visitor from database -------------------*/
			if((date("Y-m-d",time()) > $member[rand_visitor_datetime]) || ($member[rand_visitor] == ""))
			{
			$sql="SELECT *, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM member WHERE ((".TABLE_MEMBER_STATUS." != 1 and isactive='1' and picturepath != '')";
			/*$i=0;
			if(($member['lookpairs']==1)||($member['lookmen']==1)||($member['lookwomen']==1)){
			   $sql .= " and( ";
       			if ($member['lookpairs']==1){
               			 $sql.="(lookpairs = '1')";
                			$i=$i+1;
       			}
      			if($member['lookmen']==1){
                			 if($i > 0)
                			$sql.="or";
                			$sql.=" (gender='1' and ";
               			if($member['gender']==1)
                        		 $sql.="lookmen='1')";
                			 if($member['gender']==2)
                        		$sql.="lookwomen='1')";
                			$i=$i+1;
        			}
        			if($member['lookwomen']==1){
                			if($i > 0)
               			 $sql.="or";
                			$sql.=" (gender='2' and ";
                			if($member['gender']==1)
                       		 $sql.="lookmen='1')";
               			 if($member['gender']==2)
                        		$sql.="lookwomen='1')";
                			$i=$i+1;
        			}
				$sql.=")";
				}
				$limit = rand(2,4);
       			$sql .= ") order by rand() limit ".$limit;*/

			// [Start] Phai edited on 19/7/2550

			if($member['gender']==1)
			{
				if($member['sexuality'] == 1)
				{
					$sql .= " and gender=1";
				}
				elseif($member['sexuality'] == 2)
				{
					$sql .= " and gender=2";
				}
				else
				{
					$sql .= " and gender=2";
				}
			}
			else
			{
				if($member['sexuality'] == 1)
				{
					$sql .= " and gender = '2'";
				}
				elseif($member['sexuality'] == 2)
				{
					$sql .= " and gender = '1'";
				}
				else
				{
					$sql .= " and gender = '1'";
				}
			}

			$limit = rand(2,4);
			$sql .= ") order by rand() limit ".$limit;

			// [End] Phai edited on 19/7/2550

			$data = DBconnect::assoc_query_2D($sql);

			$rand_id = "";
			for($n=0; $data[$n]; $n++)
			{
				$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
				$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
				$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);

				if($n == 0) {

					$rand_id .= $data[$n]['id']; }

				else {
				$rand_id .= ";".$data[$n]['id']; }
			}

			$sql = "UPDATE `".TABLE_MEMBER."` SET rand_visitor= '$rand_id',rand_visitor_datetime='".funcs::getDateTime()."' WHERE ".TABLE_MEMBER_ID." = '".$member[id]."'";
			DBconnect::execute_q($sql);

			} //end if checkdate

			$sql = "UPDATE `".TABLE_MEMBER."` SET `".TABLE_MEMBER_SIGNIN_DATETIME."`= '".funcs::getDateTime()."'
						  WHERE `".TABLE_MEMBER_ID."` = '".$member[id]."'";
			DBconnect::execute_q($sql);
			$_SESSION['sql_sess'] = $sql;
			$sql = "INSERT INTO `".TABLE_MEMBER_SESSION."`
					SET `".TABLE_MEMBER_SESSION_MEMBER_ID."`=\"$member[id]\",
						`".TABLE_MEMBER_SESSION_SESSION_ID."`=\"$_SESSION[sess]\",
						`".TABLE_MEMBER_SESSION_SESSION_DATETIME."`='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);
			return true;
		}
		return false;
	}

	
	
	static function getMessageEmail_membership(&$smarty, $username)
	{

		$sql = "SELECT ".TABLE_MEMBER_PASSWORD." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$password = DBconnect::retrieve_value($sql);
		$sql = "SELECT validation_code FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$code = DBconnect::retrieve_value($sql);

		$smarty->assign('username', $username);
		$smarty->assign('password', $password);
		$smarty->assign('code', $code);
		$smarty->assign('url_web', URL_WEB);
		$message = $smarty->fetch('activate_message.tpl');

		return $message;
	}	
	
}	



	//	*** Willkommens Mail ***
	$sql = "UPDATE member SET isactive=0 AND validation_code='".funcs::randomPassword(6)."' WHERE id=1011595";
	DBConnect::execute($sql);
	//		   	           getMessageEmail_membership(&$smarty, $username)	   
	$mail_message = funcs::getMessageEmail_membership($smarty,'ph0enix');
	$mail_from = 'anmeldung@herzoase.com';
	$mail_subject = 'test.. anmeldung';
		if (funcs::sendMail('leap84@freenet.de', $mail_subject, $mail_message, $mail_from))
	{
		print "neuanmeldung Versandt.. ";
	}

	
?>