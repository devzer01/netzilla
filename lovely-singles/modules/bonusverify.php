<?php
	/*if(!(isset($_SESSION['sess_bonus_ver'])))
	{
		header('Location: .');
		exit;
	}
	else
	{
		unset($_SESSION['sess_bonus_ver']);
	}*/

	if(isset($_POST['submit_hidden'])){
		if($_POST['act'] == 'bonusverify'){
			if(strlen(trim($_POST['bonus_ver_code'])) > 0){
				switch(funcs::verifyMemberBonus($_SESSION['sess_id'],$_POST['bonus_ver_code'])){
					case 1://complete comfirm
						//$_SESSION['sess_bonus_ver'] = true;
						header("location: .");	//go to mobileverify_successful page
						exit();
					break;
					case 4://verified
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_bonus_code_verified'));
					break;
					case 3://timeout
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_valid_bonus_code_timeout'));
					break;
					case 2://invalid code
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_valid_code'));
					break;
				}
			}
			else{
				$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_blank_valid_code'));
			}
		}
	}
	//select template file//
	$smarty->display('index.tpl');
?>