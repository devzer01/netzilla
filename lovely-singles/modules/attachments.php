<?php
$permission_lv = array(1,2,3,4,8,9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(!file_exists("templates/attachments-".$_GET['type'].".tpl"))
{
	//$_GET['type'] = "list";
	$_GET['type'] = "coins";
}
if(file_exists("templates/attachments-".$_GET['type'].".tpl"))
{
	if(isset($_POST) && count($_POST))
	{
		switch($_GET['type'])
		{
			case "coins":
				$smarty->assign("coins", $_POST['coins']);
				$html = $smarty->fetch("attachments-coins-display.tpl");
				$result = array("code"=>"", "amount"=>$_POST['coins'], "html" => $html);
				$result['code'] = "FINISHED";
				echo json_encode($result);
				break;
		}
	}
	else
	{
		$smarty->display("attachments-".$_GET['type'].".tpl");
	}
}
?>