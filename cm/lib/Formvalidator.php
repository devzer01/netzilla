<?php

require_once 'lib/dbo/member.php';

class FormValidator
{
	function validate($field, $type) {
		switch ($type) {
			case "email":
				$this->email($field);
			break;
			default:
				throw new Exception("Unknown Type");
			break;
		}
   	}

  	function email($field) {
  		$dbo_member = new dbo_member();
  		return !$dbo_member->isEmail($field);
	}
		 
	function text($field) {
		
	}
	
	function username($field) {		
		if ($field == ADMIN_USERNAME_DISPLAY) return false;
		if (strlen($field) < 6) return false;
		$dbo_member = new dbo_member();
		return !$dbo_member->isUsername($field);
	}
	
	function trimPost($fields = array())
	{
		foreach ($_POST as $key => &$val) {
			if (!empty($fields) && !in_array($key, $fields)) continue;
			$val = trim($val);
		}
	}
}