<?php
if(defined("LANGUAGE"))
	$_SESSION['lang'] = LANGUAGE;
else
	$_SESSION['lang'] = 'ger';
define('SERVER_URL','http://192.168.1.202/chat-tools/soap/soapserver.php');
define('SERVER_ID',14);

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
define('MYSQL_SERVER',"192.168.1.202");
define('MYSQL_USERNAME',"root");
define('MYSQL_PASSWORD',"netzillacompany");
define('MYSQL_DATABASE',"flirten48.net");

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
define('ADMIN_USERNAME_DISPLAY', "SUPPORT");
define('MAX_CHARACTERS', 140);
define('ENABLE_ADDITIONAL_SEARCH_RESULT', 1);
define('BONUS_AGE', 5);
define('ATTACHMENTS', 1);
define('ATTACHMENTS_COIN', 1);
define('MESSAGE_HISTORY_PERIOD', "6 WEEK");

//added by nick
define('TESTMODE', 1);
define('EMOTICON_PATH', '/var/www/cm.v2/sites/flirten48.net/images/emoticons');

//TODO: adjust to set the 
define('INVITE_REWARD_COIN', 20);
define('SOCIAL_ENABLED', 1);

//Obtained at https://cloud.google.com/console?redirected=true#/project/212752429147/apiui/app?show=allapp
define('OAUTH_GMAIL_REDIRECT', 'http://netzilla.no-ip.org/~boy/cm.v2/?action=oauthgmail');
define('GOOGLE_CLIENT_ID', '212752429147.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'dRF2M-H5wSQIauyFBOYoHY1i');
define('GOOGLE_DEVELOPER_KEY', 'AIzaSyDWIK5Hnvjp-eG56kYkiIJUyCaLHQkX5no');
define('GOOGLE_APPNAME', 'Flirten48.net Email Invitiation');

//Obtained at https://account.live.com/developers/applications
define('OAUTH_LIVE_REDIRECT', 'http://netzilla.no-ip.org/~boy/cm.v2/?action=oauthlive');
define('LIVE_CLIENT_ID', '000000004810B680');
define('LIVE_CLIENT_SECRET', '-0Cb0Y37VhIAMVy2fs12Z-0iNtHdk6uU');

//Obtained at http://developer.apps.yahoo.com/projects/MRgMyk52
define('OAUTH_YAHOO_REDIRECT', 'http://netzilla.no-ip.org/~boy/cm.v2/?action=oauthyahoo');
define('YAHOO_CLIENT_ID', 'dj0yJmk9VGg5YUw4b1ZtT1pZJmQ9WVdrOVRWSm5UWGxyTlRJbWNHbzlNVGN4TlRjME1qWXkmcz1jb25zdW1lcnNlY3JldCZ4PTdi');
define('YAHOO_SECRET', 'bd78286d805133c65bab9707175e77e08032c17d');


//Facebook
define('APP_ID', "396000770498144");
define('APP_SECRET', "b3c7ec169d06b2a576cd94de3702b0fe");
define('MY_URL', "http://netzilla.no-ip.org:10045/cm.v2/");
define('FACEBOOK_LOGIN_URL', 'https://www.facebook.com/dialog/oauth/?client_id='.APP_ID.'&redirect_uri='.MY_URL.'?action=fblogin&scope=email,user_birthday,user_location,user_about_me,publish_actions&state=');
?>
