<?php
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

			
			$sql = "SELECT * FROM gift WHERE status = 1 AND id = " . $_GET['id'];
			$rs_gift = mysql_query($sql);
			
			$r_gift = mysql_fetch_assoc($rs_gift);
			$smarty->assign('gift', $r_gift);
		
		
$smarty->display("attachments-gift-confirm.tpl");
