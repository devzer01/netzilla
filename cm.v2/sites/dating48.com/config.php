<?php
if(defined("LANGUAGE"))
	$_SESSION['lang'] = LANGUAGE;
else
	$_SESSION['lang'] = 'eng';
define('SERVER_URL','http://soap.plickerz.com_/soapserver.php');
define('SERVER_ID',13);

//REGISTER MAIL
define('MAIL_REGISTER_HOST',"smtp.gmail.com");
define('MAIL_REGISTER_PORT',"587");
define('MAIL_REGISTER_USERNAME',"flirt48.net@gmail.com");
define('MAIL_REGISTER_PASSWORD',"cmbEDwmN4XlW");

//GENERAL MAIL
define('MAIL_HOST',"mail.yourbuddy24.com");
define('MAIL_PORT',"25");
define('MAIL_USERNAME',"noreply@yourbuddy24.com");
define('MAIL_PASSWORD',"0gHC6vEySry9");

//DATABASE
define('MYSQL_SERVER',"192.168.1.203");
define('MYSQL_USERNAME',"root");
define('MYSQL_PASSWORD',"netzillacompany");
define('MYSQL_DATABASE',"dating48.com");

//PREFERENCES
define('RECENT_CONTACTS',4);
define('RANDOM_CONTACTS',4);
define('LONELY_HEARTS_MALE',5);
define('LONELY_HEARTS_FEMALE',5);
define('NEWEST_MEMBERS_LIMIT',40);
define('NEWEST_MEMBERS_BOX_LIMIT',8);
define('SEARCH_RESULTS_PER_PAGE',14);
define('SEARCH_RESULTS_TOTAL_PAGES',9);
define('MAX_REAL_MEMBERS_ONLINE',5);
define('PHOTO_APPROVAL', 1);
define('ENABLE_PAYMENT', 0);
define('ADMIN_USERNAME_DISPLAY', "Team");
define('MAX_CHARACTERS', 140);
define('MESSAGE_HISTORY_PERIOD', "6 WEEK");
?>