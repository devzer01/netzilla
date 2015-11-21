<?php

$_SESSION['lang'] = 'de';

setlocale(LC_TIME, 'en_UK.UTF8');
ini_set('allow_call_time_pass_reference', '1');

require_once('classes/DBconnect.php');
require_once('classes/config.php');

mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD) or die(mysql_error());
mysql_select_db(MYSQL_DATABASE) or die("Selecting of database error!");
mysql_query("SET NAMES UTF8");

$site_configs = DBconnect::assoc_query_2D("SELECT * FROM `".TABLE_CONFIG."`");

foreach($site_configs as $value)
{
	define($value['name'], $value['value']);
}

//require classes and libs//
require_once(SITE.'configs/'.$_SESSION['lang'].'.php');
require_once('classes/funcs.php');
require_once('classes/funcs2.php');
require_once(SITE.'sms.php');
require_once('classes/search.class.php');
require_once('Mail.php');
require_once('Mail/mime.php');
require_once('libs/SmartyPaginate.class.php');
require_once('classes/smarty_cli.php'); //the cli based class
require_once('classes/class.phpmailer.php');

//if(!isset($_SESSION['sess_id']) && isset($_GET['asession'])){

$smarty = new smarty_web();	//call smarty class

//require config language file//
$smarty->config_load($_SESSION['lang'].'.conf');