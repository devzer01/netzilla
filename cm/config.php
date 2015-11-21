<?php 
date_default_timezone_set('Europe/Berlin');

define('APP_PATH', '/cm.v2-mobile');
define('ADMIN_USERNAME', 'bigbrother');
define('ADMIN_USERNAME_DISPLAY', 'support');
define('FREECOINS', 30);
define('URL_WEB', 'http://localhost/cm.v2');
define('MOBILE_WEB', 'http://localhost/cm.v2-mobile');
define('DESCRIPTION_APPROVAL', 1);
define('SITE', 'm.flirt48.net');
define('ERROR_NO_COIN', 1);
define('ERROR_OK', 0);
define('SERVER_URL','http://192.168.1.202/chat-tools/soap/soapserver.php');
define('SERVER_ID', 13);

define('MAIL_REGISTER_HOST',"email-smtp.us-east-1.amazonaws.com");
define('MAIL_REGISTER_PORT',587);
define('MAIL_REGISTER_USERNAME',"AKIAJE2LLTCS3XUSYX4A");
define('MAIL_REGISTER_PASSWORD','Aiv/IE2M46fRTj8pC6lwCKf77G76KUcM3diJ6rP5eHMz');
define('MAIL_REPLYTO_EMAIL',"no-reply@flirt48.net");
define('MAIL_REPLYTO_NAME',"Flirt48.Net Activation");

define('MAIL_REGISTER_HOST2',"email-smtp.eu-west-1.amazonaws.com");
define('MAIL_REGISTER_PORT2',587);
define('MAIL_REGISTER_USERNAME2',"AKIAI7WJJOTS5HPFJ3HA");
define('MAIL_REGISTER_PASSWORD2','AsUbku1OWQ1pzTbXVhOdA8b5Qs/q4AxyzxCS0ozLj415');
define('MAIL_REPLYTO_EMAIL2',"no-reply@reminder.flirt48.net");
define('MAIL_REPLYTO_NAME2',"Flirt48.Net");

//GENERAL MAIL
define('MAIL_HOST',"mail.yourbuddy24.com");
define('MAIL_PORT',"25");
define('MAIL_USERNAME',"noreply@yourbuddy24.com");
define('MAIL_PASSWORD',"0gHC6vEySry9");
define('ENABLED_MAIL_QUEUE', 1);


/**
 * constants
 */

define('GENDER_MALE', 1);
define('GENDER_FEMALE', 2);

ini_set('display_errors', 1);
ini_set('error_log', '/var/log/php.log');
ini_set('memory_limit', '-1');