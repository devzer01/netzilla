<?php
date_default_timezone_set('Europe/Berlin');
define('TABLE_CONFIG', 'config'); //config table config in database
define('SERVER_ID', 4);
define('SERVER_URL','http://192.168.1.202/chat-tools/soap/soapserver.php');
//GENERAL MAIL
define('MAIL_HOST',"lovely-singles.com");
define('MAIL_PORT',"25");
define('MAIL_USERNAME',"no-reply@lovely-singles.com");
define('MAIL_PASSWORD',"YgVjU6JvcwJl");

if(isset($_SESSION['sess_id']) && $_SESSION['sess_id']==1)
	define('MAX_CHARACTERS', 2000);
else
	define('MAX_CHARACTERS', 140);

//get emailchat_center_server_id
$database = "cm01";
$dbhost = "192.168.1.203";
$dbuser = "root";
$dbpasswd = "netzillacompany";
            
$conn = mysql_connect($dbhost, $dbuser, $dbpasswd);
mysql_select_db("emailchat_center", $conn);
            
$query = "select id from sites where name = '".$database."'";
$result = mysql_query($query);
            
if ($result)
    $centerId = mysql_fetch_array($result);
            
//mysql_select_db($database, $conn);
mysql_close($conn);

define('CENTER_ID', $centerId[0]);


/*CONFIG DATABASE*/
$mysql_server = '192.168.1.203'; //config mysql host
$mysql_username = 'root'; //config mysql username
$mysql_password = 'netzillacompany'; //config mysql password
$mysql_db = 'lovely-singles.com'; //config mysql database
//$mysql_db = 'koma_herzoase'; //config mysql database
@mysql_connect($mysql_server, $mysql_username, $mysql_password) or die(funcs::getText($_SESSION['lang'],'$sql_connect_alert')); //connect mysql
@mysql_select_db($mysql_db) or die(funcs::getText($_SESSION['lang'], '$sql_database_alert')); //connect database
mysql_query("SET NAMES UTF8");
/**
* General purpose class for config web.
*
* The main purpose of the class is to get config everything of web from database.
* @package General classes
*/
class config
{
	/**
	* Get config of website from database.
	* @return define config.
	*/
	function config()
	{
		//get config from database//
		//if((count($_SESSION['config'])==0) && !isset($_SESSION['config']))
			//$_SESSION['config'] = DBconnect::assoc_query_2D("SELECT * FROM `".TABLE_CONFIG."`");
		foreach(DBconnect::assoc_query_2D("SELECT * FROM `".TABLE_CONFIG."`") as $value)
			define($value['name'], $value['value']);	
	}
}
new config();	//call config class

define('APP_ID', "1402008886734100");
define('APP_SECRET', "e8bce1d53f28acc3275146458c6f3709");
define('MY_URL', "http://netzilla.no-ip.org/lovely-singles.com/");
define('FACEBOOK_LOGIN_URL', 'https://www.facebook.com/dialog/oauth/?client_id='.APP_ID.'&redirect_uri='.MY_URL.'?action=fblogin&scope=email,user_birthday,user_location,user_about_me,publish_actions&state=');


define('MAIL_REGISTER_HOST',"email-smtp.us-east-1.amazonaws.com");
define('MAIL_REGISTER_PORT',587);
define('MAIL_REGISTER_USERNAME',"AKIAJE2LLTCS3XUSYX4A");
define('MAIL_REGISTER_PASSWORD','Aiv/IE2M46fRTj8pC6lwCKf77G76KUcM3diJ6rP5eHMz');
define('MAIL_REPLYTO_EMAIL',"no-reply@lovely-singles.com");
define('MAIL_REPLYTO_NAME',"Lovely-Singles.com Activation");
