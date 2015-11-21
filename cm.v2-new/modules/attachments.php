<?php
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(!file_exists(SITE."templates/attachments-".$_GET['type'].".tpl"))
{
	$_GET['type'] = "list";
	//$_GET['type'] = "coins";
}
if(file_exists(SITE."templates/attachments-".$_GET['type'].".tpl"))
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
			case "gifts":
				$smarty->assign("id", $_POST['id']);
				$sql = "SELECT * FROM gift WHERE status = 1 AND id = " . $_POST['id'];
				$rs = mysql_query($sql);
				$smarty->assign('gift', mysql_fetch_assoc($rs));
				$html = $smarty->fetch("attachments-gift-display.tpl");
				$result = array("code"=>"", "id"=>$_POST['id'], "html" => $html);
				$result['code'] = "FINISHED";
				echo json_encode($result);
				break;
		}
	}
	else
	{
		
		if ($_GET['type'] == "gifts") {
			
			$sql = "SELECT * FROM gift WHERE status = 1";
			$rs_gift = mysql_query($sql);
			
			$list = array();
			while ($r_gift = mysql_fetch_assoc($rs_gift)) {
				$list[] = $r_gift;
			}			
			$smarty->assign('list_gifts', $list);
		}
		
		$smarty->display("attachments-".$_GET['type'].".tpl");
	}
}
?>