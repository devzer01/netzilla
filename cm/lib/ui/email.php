<?php

/**
 * 
 * @author devzer0
 * @todo move core of html and text function to a single function and pass template from the specific function
 */

class ui_email {
	
	function getMembershipEmailHtml($username)
	{
		$smarty = basesmarty::getInstance();
		
		$member = new dbo_member();
		$info = $member->getBasicMemberInfo($username);
		
		$smarty->assign('username', $username);
		$smarty->assign('password', $info['password']);
		$smarty->assign('code', $info['validation_code']);
		$smarty->assign('url_web', URL_WEB);
		return $smarty->fetch('email/activate_message.tpl');
	}
	
	function getMembershipEmailText($username)	
	{
		$smarty = basesmarty::getInstance();
		
		$member = new dbo_member();
		$info = $member->getBasicMemberInfo($username);
		
		$smarty->assign('username', $username);
		$smarty->assign('password', $info['password']);
		$smarty->assign('code', $info['validation_code']);
		$smarty->assign('url_web', URL_WEB);
		
		if (!$reminder) {
			$message = $smarty->fetch('email/email_activate_text.tpl');
		} else {
			$message = $smarty->fetch('email/email_activate_text_reminder.tpl');
		}
		
		return $message;
	}
}