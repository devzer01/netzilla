<?php
if(defined("LANGUAGE"))
	$_SESSION['lang'] = LANGUAGE;
else
	$_SESSION['lang'] = 'ger';
define('SERVER_URL','http://192.168.1.253/chat-tools/soap/soapserver.php');
define('SERVER_ID',13);

//REGISTER MAIL
define('MAIL_REGISTER_HOST',"yourbuddy24.com");
define('MAIL_REGISTER_PORT',"25");
define('MAIL_REGISTER_USERNAME',"noreply@yourbuddy24.com");
define('MAIL_REGISTER_PASSWORD',"0gHC6vEySry9");
define('MAIL_REPLYTO_EMAIL',"noreply@yourbuddy24.com");
define('MAIL_REPLYTO_NAME',"activation");

//GENERAL MAIL
define('MAIL_HOST',"mail.yourbuddy24.com");
define('MAIL_PORT',"25");
define('MAIL_USERNAME',"noreply@yourbuddy24.com");
define('MAIL_PASSWORD',"0gHC6vEySry9");

//DATABASE
define('MYSQL_SERVER',"192.168.1.203");
define('MYSQL_USERNAME',"root");
define('MYSQL_PASSWORD',"netzillacompany");
define('MYSQL_DATABASE',"flirtino.net");

//PREFERENCES
define('RECENT_CONTACTS',4);
define('RANDOM_CONTACTS',4);
define('LONELY_HEARTS_MALE',5);
define('LONELY_HEARTS_FEMALE',5);
define('NEWEST_MEMBERS_LIMIT',40);
define('NEWEST_MEMBERS_BOX_LIMIT',8);
define('SEARCH_RESULTS_PER_PAGE',24);
define('SEARCH_RESULTS_TOTAL_PAGES',9);
define('MAX_REAL_MEMBERS_ONLINE',6);
define('PHOTO_APPROVAL', 1);
define('DESCRIPTION_APPROVAL', 1);
define('ENABLE_PAYMENT', 1);
define('ENABLE_PAYMENT_WORLDPAY', 1);
define('ENABLE_PAYMENT_UKASH', 1);
define('ENABLE_PAYMENT_CCBILL_CREDIT', 0);
define('ENABLE_PAYMENT_CCBILL_DIRECTPAY', 1);
define('ENABLE_PAYMENT_CCBILL_EUDEBIT', 0);
define('ENABLE_PAYMENT_PAYSAFECARD', 1);
define('ENABLE_PAYMENT_VEROTEL', 0);
define('ADMIN_USERNAME_DISPLAY', "SUPPORT");
define('MAX_CHARACTERS', 140);
define('ENABLE_ADDITIONAL_SEARCH_RESULT', 1);
define('BONUS_AGE', 5);
define('ATTACHMENTS', 1);
define('ATTACHMENTS_COIN', 1);
define('MESSAGE_HISTORY_PERIOD', "6 WEEK");

//added by nick
define('TESTMODE', 1);
define('EMOTICON_PATH', '/var/www/cm.v2/sites/yourbuddy24.com/images/emoticons');

//Facebook
define('APP_ID', "552444838159746");
define('APP_SECRET', "84a796add9f8165fe359ea5cc555854d");
define('MY_URL', "http://netzilla.no-ip.org/cm.v2/");
define('FACEBOOK_LOGIN_URL', 'https://www.facebook.com/dialog/oauth/?client_id='.APP_ID.'&redirect_uri='.MY_URL.'?action=fblogin&scope=email,user_birthday,user_location,user_about_me,publish_actions&state=');
?>
