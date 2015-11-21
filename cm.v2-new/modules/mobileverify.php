<?php
//if((isset($_SESSION['sess_username'])) && ($_SESSION['sess_username'] != ""))
//{
	$permission_lv = array(1, 4, 8, 9); //jeab edited
	funcs::checkPermission($smarty, $permission_lv);	//check permission

	$userid = funcs::getUserid($_SESSION['sess_username']);
	//$permission_lv = array(1, 4);	//define type permission can open this page.
	//funcs::checkPermission(&$smarty, $permission_lv);	//check permission

	/*if(funcs::checkmobile($_SESSION['sess_username'])){
		header("location: .");	//go to first page
		exit();	
	}*/
	
	if(isset($_POST['submit_hidden'])){
		if($_POST['act'] == 'mobileverify'){
			if($_POST['mobile_cancel'] == '1'){
				header("location: .");	//go to first page
				exit();
			}
			elseif(strlen(trim($_POST['mobile_ver_code'])) > 0)
			{
				switch(funcs::verifyMobile($_SESSION['sess_username'],$_POST['mobile_ver_code'])){
					case 1://complete comfirm
						//$_SESSION['sess_mobile_ver'] = true; //move to modules/activate.php
						/*$row = funcs::getNecessaryInfo($_SESSION['sess_username']);
						$username = $_SESSION['sess_username'];
						$password = $row['password'];
						$code = $row['validation_code'];
						funcs::activateMember($username, $password, $code);
						funcs::loginSite($username, $password);	//automatic login
						//$_SESSION['sess_username'] = null;
						//unset($_SESSION['sess_username']);*/
						header("location:?action=mobileverify_successful"); //after verify successful they should go to first page
						exit();
					break;
					case 2:
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_blank_valid_code'));
					break;
					case 3://wrong verify code
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_valid_code'));
					break;
					case 4://timeout
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_valid_code_timeout'));
					break;
				}
			}
			else
			{
				$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_blank_valid_code'));
			}
		}
		elseif($_POST['act'] == 'wrongnumber')
		{
			header('Location:?action=incompleteinfo');
			exit();	
			/*if((strlen(trim($_POST['phone_code2'])) > 0) && (strlen(trim($_POST['phone_number'])) > 0))
			{
				$str = $_POST['phone_code2'];
				$zeropos = strpos($str,'0'); 
				
				if($zeropos === false || $zeropos > 0)
					$clr_phone_code = $str;
				else
					$clr_phone_code = substr($str,1,strlen($str)-1);

				$postArray['waitver_mobileno'] = funcs::getCountryCodeByUsername($_SESSION['sess_username'], true) . $clr_phone_code . $_POST['phone_number'];
		
				if(funcs::checkVcodeCount($_SESSION['sess_id']))
				{
					funcs::completeInfo($postArray);

					$dial_number = $postArray['waitver_mobileno'];
					$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
					sendSMSCode($dial_number, $smsmsg);
					funcs::increaseVcodeCount($_SESSION['sess_id']);
					$smarty->assign('text2', funcs::getText($_SESSION['lang'], '$valid_code_resend'));
				}
				else
				{
					$smarty->assign('text2', funcs::getText($_SESSION['lang'], '$limit_code_resend'));
				}
				
			}
			else
			{
				$smarty->assign('text2', funcs::getText($_SESSION['lang'], '$err_blank_phone'));
				$smarty->assign('save', $_POST);
			}*/
		}
		elseif($_POST['act'] == 'resendmobileverify')
		{
			if(funcs::checkVcodeCount($userid))
			{
				$waitMobileNo = funcs::getWaitVerifyMobile($_SESSION['sess_username']);
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
				sendSMSCode($waitMobileNo, $smsmsg);
				funcs::increaseVcodeCount($userid);
				$smarty->assign('text3', funcs::getText($_SESSION['lang'], '$valid_code_resend'));
			}
			else
			{
				$smarty->assign('text3', funcs::getText($_SESSION['lang'], '$limit_code_resend'));
			}
		}
	}

	$vcodeCount = funcs::checkVcodeCount($userid);
	if(!$vcodeCount)
		$smarty->assign('text4', funcs::getText($_SESSION['lang'], '$limit_code_resend'));
	
	$countryCode = funcs::getCountryCodeByUsername($_SESSION['sess_username']); 
	$countryCodeHidden = funcs::getCountryCodeByUsername($_SESSION['sess_username'], true); 
	$currentNumber = funcs::getWaitVerifyMobile($_SESSION['sess_username']);
	
	if(strcmp($countryCode,$countryCodeHidden))
	{
		/*echo $countryCode."<br/>";
		echo $countryCodeHidden."<br/>";
		echo $currentNumber."<br/>";*/

		if (substr($currentNumber, 0, strlen($countryCodeHidden) ) == $countryCodeHidden) {
			$currentNumber = $countryCode . substr($currentNumber, strlen($countryCodeHidden), strlen($currentNumber));
		}
	}
	
	$smarty->assign('currentNumber', $currentNumber);
	$smarty->assign('vcodeCount', $vcodeCount);
	//select template file//
	$smarty->display('mobileverify.tpl');
	//$smarty->display('index.tpl');
/*}
else
{
	header('location: .');
	exit;
}*/
?>