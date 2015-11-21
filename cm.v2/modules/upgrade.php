<?php
//check permission type//
$permission_lv = array(5);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

//when click submit button for upgrade member//
if(!empty($_POST['submit']) && isset($_POST['submit']))
{
	$save = $_POST;
	$save[TABLE_MEMBER_STATUS] = 2;
	if(funcs::updateProfile($_SESSION['sess_id'], $save))
	{
		$_SESSION['sess_mem'] = 1;
		$smarty->assign('section', 'blank');
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$complete'));
	}			
}
	
//send choice to template//
//step1//
$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
$smarty->assign('date', funcs::getRangeAge(1,31));
$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
$smarty->assign('year', funcs::getYear());
//step2//
$smarty->assign('appearance', funcs::getChoice($_SESSION['lang'],'$nocomment','$appearance'));
$smarty->assign('eyescolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$eyes_color'));
$smarty->assign('haircolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_color'));
$smarty->assign('hairlength', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_length'));
$smarty->assign('beard', funcs::getChoice($_SESSION['lang'],'$nocomment','$beard'));
$smarty->assign('zodiac', funcs::getChoice($_SESSION['lang'],'$nocomment','$zodiac'));
$smarty->assign('status', funcs::getChoice($_SESSION['lang'],'$nocomment','$status'));
$smarty->assign('sexuality', funcs::getChoice($_SESSION['lang'],'$nocomment','$sexuality'));
$smarty->assign('yesno', funcs::getChoice($_SESSION['lang'],'','$yesno'));
//step3//
$smarty->assign('age', funcs::getRangeAge(16, 65));

//send data profile to template//	
$smarty->display('index.tpl');
?>