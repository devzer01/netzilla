<?php
require_once('classes/top.class.php'); 
//require_once('classes/test_funcs.php');
////require_once('classes/test_funcs2.php');
$username = "superadmin";
$password = "123123";
$str = "assddfghjklkjgfss jdfdjhiruuu ";

//echo $mess = test_funcs::split_word($str);
 $sql = "SELECT ".TABLE_MEMBER_USERNAME.",".TABLE_MEMBER_PICTURE." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='$username'";
$sender= DBconnect::assoc_query_1D($sql);
//echo $sender[username];
//echo $sender[picturepath];
/*foreach($sender as $value)
{
echo $value['username']."<br>";
echo $value['picturepath'];
}*/
//echo URL_WEB;
//echo URL_WEB."/images/p_foot_r.jpg";
$userid = 8;

$from = 15;
$password="123123";
//funcs::getMessageEmail_membership(&$smarty, $username);
//funcs::emailAfterEmail(&$smarty, $userid,$from, $str);


funcs::getMessageEmail_membership(&$smarty, $username);
//funcs::getMessageEmail_Forgot(&$smarty,$username, $password)
//funcs::getMessageEmail_Forgot(&$smarty,$username, $password)

//$smarty->assign('account', funcs2::visit_account());

?>
