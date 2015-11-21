<?php
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	header("location: .");
	exit;
}
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(isset($_POST['submit_hidden'])){
	if($_POST['act'] == 'bonusverify'){
		if(strlen(trim($_POST['bonus_ver_code'])) > 0){
			switch(funcs::verifyMemberBonus($_SESSION['sess_id'],$_POST['bonus_ver_code'])){
				case 1://complete comfirm
					echo "FINISHED";
				break;
				case 4://verified
					echo funcs::getText($_SESSION['lang'], '$err_bonus_code_verified');
				break;
				case 3://timeout
					echo funcs::getText($_SESSION['lang'], '$err_valid_bonus_code_timeout');
				break;
				case 2://invalid code
					echo funcs::getText($_SESSION['lang'], '$err_valid_code');
				break;
			}
		}
		else{
			echo funcs::getText($_SESSION['lang'], '$err_blank_valid_code');
		}
	}
	exit();
}
else
{
	$smarty->display('bonusverify_step2.tpl');
}
?>