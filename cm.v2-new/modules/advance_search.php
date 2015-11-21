<?php 
//get member by search//
//step 1//
$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
$smarty->assign('date', funcs::getRangeAge(1,31));
$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
$smarty->assign('year', funcs::getYear(90, 12));
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
$smarty->assign('picyesno', funcs::getChoice($_SESSION['lang'],'','$picyesno'));
//step3//
$smarty->assign('age', funcs::getRangeAge());
//select template file//
$smarty->display('index.tpl'); 
?>