<?php 
$username = $_GET['username'];
$memid  = funcs::getUserid($username);	//get user id from username
$lonelyheart = funcs::getLonelyHeart($memid);	//get lonely heart ads
//get answer choice//
$lonelyheart[TABLE_LONELYHEART_TARGET] = funcs::getAnswerChoice($_SESSION['lang'],'', '$targetGroup', $lonelyheart[TABLE_LONELYHEART_TARGET]);
$lonelyheart[TABLE_LONELYHEART_CATEGORY] = funcs::getAnswerChoice($_SESSION['lang'],'', '$category', $lonelyheart[TABLE_LONELYHEART_CATEGORY]);
//send data to template//
$smarty->assign('targetGroup', funcs::getChoice($_SESSION['lang'],'','$targetGroup'));
$smarty->assign('category', funcs::getChoice($_SESSION['lang'],'','$category'));
$smarty->assign('lonelyheart', $lonelyheart);
//select template file//
$smarty->display('index.tpl'); 
?>