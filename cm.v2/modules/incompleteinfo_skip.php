<?php
//if((isset($_SESSION['sess_username'])) && ($_SESSION['sess_username'] != ""))
//{
	
	$permission_lv = array(1, 4, 8, 9); //jeab edited
	funcs::checkPermission($smarty, $permission_lv);	//check permission

	$userid = funcs::getUserid($_SESSION['sess_username']);
	$profile = funcs::getProfile($userid);	//get profile data

	$skip_step = funcs::getCurrentUserMobileNo();
	/*echo $skip_step;
	echo " :".$_SESSION['force'].":";*/

	if($skip_step=="Verified")
		header('Location: .');
	elseif($skip_step=="Step3")
		header('Location:?action=mobileverify_skip');

	if(isset($_POST['submit_form'])){
		$unwantArray = array('submit_form','looking_for','phone_code','phone_code2','phone_number');
		$postArray = array();
		
		foreach($_POST as $key => $val)
		{
			if(!(in_array($key, $unwantArray)))
			{
				$postArray[$key] = $val;
			}
		}

		//$postArray['waitver_mobileno'] = $_POST['phone_code'] . $_POST['phone_code2'] . $_POST['phone_number'];

		$str = $_POST['phone_code2'];
		$zeropos = strpos($str,'0'); 

		if($zeropos === false || $zeropos > 0)
			$clr_phone_code = $str;
		else
			$clr_phone_code = substr($str,1,strlen($str)-1);

		$postArray['waitver_mobileno'] = funcs::getCountryCode($profile['country'], true) . $clr_phone_code . $_POST['phone_number']; //$_POST['country']
		
		if($_POST['looking_for']=='1')
			$postArray['lookmen'] = 1;
		else
			$postArray['lookwomen'] = 1;
		
		/*echo "<pre>";
		print_r($postArray);
		echo "</pre>";
		exit();*/
		funcs::completeInfo($postArray);
		//sms
		if(funcs::checkVcodeCount($userid))
		{
			if($postArray['waitver_mobileno'] != ""){
				$dial_number = $postArray['waitver_mobileno'];
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
				sendSMSCode($dial_number, $smsmsg);
				funcs::increaseVcodeCount($userid);
			}
		}

		/*$next_url = $_GET['nextstep'];
		header('Location:?action='.$next_url);*/

		header('Location:?action=mobileverify_skip');
		exit;
	}



	$vcodeCount = funcs::checkVcodeCount($userid);
	if(!$vcodeCount)
		$smarty->assign('text4', funcs::getText($_SESSION['lang'], '$limit_code_resend'));
	
	$countryCode = funcs::getCountryCodeByUsername($_SESSION['sess_username']); 
	$countryCodeHidden = funcs::getCountryCodeByUsername($_SESSION['sess_username'], true); 
	$currentNumber = funcs::getWaitVerifyMobile($_SESSION['sess_username']);
	
	if(strcmp($countryCode,$countryCodeHidden))
	{
		if (substr($currentNumber, 0, strlen($countryCodeHidden) ) == $countryCodeHidden) {
			$currentNumber = $countryCode . substr($currentNumber, strlen($countryCodeHidden), strlen($currentNumber));
		}
	}
	
	include('modules/checkprofile.php');
	$smarty->assign('total_score', number_format($total_score));
	$smarty->assign('progress_score', $progress_final);

	$smarty->assign('currentNumber', $currentNumber);
	$smarty->assign('vcodeCount', $vcodeCount);

	$save['countrycode'] = funcs::getCountryCode($profile['country']);
	$smarty->assign('save', $save);

	include('modules/inc_visitor.php');

	//select template file//
	//$smarty->display('mobileverify.tpl');
	$smarty->display('index.tpl');
/*}
else
{
	header('location: .');
	exit;
}*/
?>