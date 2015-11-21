<?php
if(defined("LANGUAGE"))
	$_SESSION['lang'] = LANGUAGE;
else
	$_SESSION['lang'] = 'ger';
define('SERVER_URL','http://server/chat-tools/soap/soapserver.php');
define('SERVER_ID',13);

//REGISTER MAIL
define('MAIL_REGISTER_HOST',"email-smtp.us-east-1.amazonaws.com");
define('MAIL_REGISTER_PORT',587);
define('MAIL_REGISTER_USERNAME',"AKIAJE2LLTCS3XUSYX4A");
define('MAIL_REGISTER_PASSWORD','Aiv/IE2M46fRTj8pC6lwCKf77G76KUcM3diJ6rP5eHMz');
define('MAIL_REPLYTO_EMAIL',"no-reply@flirt48.net");
define('MAIL_REPLYTO_NAME',"Flirt48.Net Activation");

//GENERAL MAIL
define('MAIL_HOST',"mail.yourbuddy24.com");
define('MAIL_PORT',"25");
define('MAIL_USERNAME',"noreply@yourbuddy24.com");
define('MAIL_PASSWORD',"0gHC6vEySry9");

//DATABASE
define('MYSQL_SERVER',"192.168.1.203");
define('MYSQL_USERNAME',"root");
define('MYSQL_PASSWORD',"netzillacompany");
define('MYSQL_DATABASE',"flirt48.net");

//PREFERENCES
define('RECENT_CONTACTS',4);
define('RANDOM_CONTACTS',4);
define('LONELY_HEARTS_MALE',5);
define('LONELY_HEARTS_FEMALE',5);
define('NEWEST_MEMBERS_LIMIT',40);
define('NEWEST_MEMBERS_BOX_LIMIT',8);
define('SEARCH_RESULTS_PER_PAGE',16);
define('SEARCH_RESULTS_TOTAL_PAGES',9);
define('MAX_REAL_MEMBERS_ONLINE',5);
define('PHOTO_APPROVAL', 1);
define('DESCRIPTION_APPROVAL', 1);
define('ENABLE_PAYMENT', 1);
define('ENABLE_PAYMENT_PAYMENTWALL', 1);
define('ENABLE_PAYMENT_REALEX', 0);
define('ENABLE_PAYMENT_CCBILL', 0);
define('ENABLE_PAYMENT_UKASH', 0);
define('ENABLE_PAYMENT_WORLDPAY', 1);
define('ENABLE_PAYMENT_PAYSAFECARD', 1);
define('REALEX_TEST_MODE', 0);
define('ADMIN_USERNAME_DISPLAY', "SUPPORT");
if(isset($_SESSION['sess_id']) && $_SESSION['sess_id']==1)
	define('MAX_CHARACTERS', 2000);
else
	define('MAX_CHARACTERS', 140);
define('ENABLE_ADDITIONAL_SEARCH_RESULT', 1);
define('BONUS_AGE', 5);
define('ATTACHMENTS', 1);
define('ATTACHMENTS_COIN', 1);
define('MESSAGE_HISTORY_PERIOD', "6 WEEK");

define('TESTMODE', 1);
define('EMOTICON_PATH', '/var/www/cm.v2/sites/flirten48.net/images/emoticons');
define('GIFT_PATH', '/var/www/cm.v2/sites/flirten48.net/images/gifts');
define('APP_URL', 'http://192.168.1.202/');
define('ENABLE_SMILEY', 1);
define('ENABLE_STICKER', 1);

//Facebook
define('APP_ID', "396000770498144");
define('APP_SECRET', "b3c7ec169d06b2a576cd94de3702b0fe");
define('MY_URL', "http://netzilla.no-ip.org/cm.v2/");
define('FACEBOOK_LOGIN_URL', 'https://www.facebook.com/dialog/oauth/?client_id='.APP_ID.'&redirect_uri='.MY_URL.'?action=fblogin&scope=email,user_birthday,user_location,user_about_me,publish_actions&state=');
?>
