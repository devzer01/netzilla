<?php
//check permission type//
$permission_lv = array(1, 2, 3, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission
$userid = $_SESSION['sess_id'];

if(isset($_POST['submit_button']))
{
	$save = $_POST;

	if($_POST['old_password'])
	{
		if(DBConnect::retrieve_value("SELECT password FROM member WHERE id=".$_SESSION['sess_id']." AND password='".$_POST['old_password']."'"))
		{
			if($_POST['password'])
			{
				if($_POST['password']==$_POST['confirm_password'])
				{
					DBConnect::execute_q("UPDATE member SET password='".$_POST['password']."' WHERE id=".$_SESSION['sess_id']);
					$_SESSION['changepassword_error'] = funcs::getText($_SESSION['lang'], '$chpd1');
				}
				else
					$_SESSION['changepassword_error'] = funcs::getText($_SESSION['lang'], '$chpd2');
			}
			else
			{
				$_SESSION['changepassword_error'] = funcs::getText($_SESSION['lang'], '$chpd3');
			}
		}
		else
		{
			$_SESSION['changepassword_error'] = funcs::getText($_SESSION['lang'], '$chpd4');
		}
	}
	else
	{
		$_SESSION['changepassword_error'] = funcs::getText($_SESSION['lang'], '$chpd5');
	}
	header("Location: ?action=changepassword");
	exit();
}
if($_SESSION['changepassword_error'])
{
	$smarty->assign("error",$_SESSION['changepassword_error']);
	$_SESSION['changepassword_error'] = "";
}
$smarty->assign("submenu","editprofile");
$smarty->display('index.tpl');
?>