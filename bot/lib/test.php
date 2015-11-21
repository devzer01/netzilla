<?php

require_once "botutils.php";

class Testbot {
	
	public $command = array();
	
	public function __construct() {
		$this->command = array(
			'site_id' => 99,
			'target_cm' => 'yourbuddy24.com',
			'profile_type' => rand(1,4),
			'messages' => array
	        (
	                array(
	                    'id' => 1452,
	                    'subject' => 'hallo',
	                    'message' => 'flirt 66 net' 
	                )
	
	        )
		);
	}
	
	public function showText() {
		$lang = (empty($_GET['lang'])) ? 'EN' : $_GET['lang'];
		var_dump(botutil::getMessageText($this, 'Male', $lang));
	}
}

$app = new Testbot;
$app->showText();
