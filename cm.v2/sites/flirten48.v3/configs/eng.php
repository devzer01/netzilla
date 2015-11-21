<?php
	class eng
	{
		static $category = array(
					1 => 'affair',
					2 => 'friendship',
					3 => 'one night stand',
					4 => 'relationship');

		static $targetGroup = array(
					1 => 'men',
					2 => 'women',
					3 => 'couples',
					4 => 'groups');

		static $KM_Name = "Flirten48.net";

		static $KM_Website = "Flirten48.net";
		
		static $wemissu = "We miss you on <a href='http://www.Flirten48.net'>Flirten48.net!</a> below are 4 profiles which may match your search criteria.";

		static $Name = "Name";

		static $Plz = "Area";

		static $Age = "Age";

		static $Civil_status = "Marital status";

		static $Height = "Height";

		static $Descr = "Description";

		static $Appearance = "Appearance";

		static $City = "City";

		static $Year = "Age";

		static $sql_connect_alert = 'Can\'t connect MYSQL.';

		static $sql_database_alert = 'Can\'t connect database.';

		static $yesno = array(
					1 => 'Yes',
					0 => 'No');

		static $picyesno = array(
					1 => 'Yes',
					0 => 'No');

		static $nocomment = array(
						0 => 'No Comment');

		static $month = array(
					1  => 'January',
                    2  => 'February',
                    3  => 'March',
                    4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December');

		static $gender = array(
					1 => 'Male',
					2 => 'Female');

		static $height = array(
					0 => "Please select",
					1 => "4' 0'' (122cm)",
					2 => "4' 1'' (125cm)",
					3 => "4' 2'' (127cm)",
					4 => "4' 3'' (130cm)",
					5 => "4' 4'' (132cm)",
					6 => "4' 5'' (135cm)",
					7 => "4' 6'' (137cm)",
					8 => "4' 7'' (140cm)",
					9 => "4' 8'' (142cm)",
					10 => "4' 9'' (145cm)",
					11 => "4' 10'' (147cm)",
					12 => "4' 11'' (150cm)",
					13 => "5' 0'' (153cm)",
					14 => "5' 1'' (155cm)",
					15 => "5' 2'' (158cm)",
					16 => "5' 3'' (160cm)",
					17 => "5' 4'' (163cm)",
					18 => "5' 5'' (165cm)",
					19 => "5' 6'' (168cm)",
					20 => "5' 7'' (170cm)",
					21 => "5' 8'' (173cm)",
					22 => "5' 9'' (175cm)",
					23 => "5' 10'' (178cm)",
					24 => "5' 11'' (180cm)",
					25 => "6' 0'' (183cm)",
					26 => "6' 1'' (185cm)",
					27 => "6' 2'' (188cm)",
					28 => "6' 3'' (191cm)",
					29 => "6' 4'' (193cm)",
					30 => "6' 5'' (196cm)",
					31 => "6' 6'' (198cm)",
					32 => "6' 7'' (201cm)",
					33 => "6' 8'' (203cm)",
					34 => "6' 9'' (206cm)",
					35 => "6' 10'' (208cm)",
					36 => "6' 11'' (211cm)",
					37 => "7' 0'' (213cm)");

		static $weight = array(
					0 => "Please select",
					1 => "80 lbs. (36kg)",
					2 => "85 lbs. (39kg)",
					3 => "90 lbs. (41kg)",
					4 => "95 lbs. (43kg)",
					5 => "100 lbs. (45kg)",
					6 => "105 lbs. (48kg)",
					7 => "110 lbs. (50kg)",
					8 => "115 lbs. (52kg)",
					9 => "120 lbs. (54kg)",
					10 => "125 lbs. (57kg)",
					11 => "130 lbs. (59kg)",
					12 => "135 lbs. (61kg)",
					13 => "140 lbs. (64kg)",
					14 => "145 lbs. (66kg)",
					15 => "150 lbs. (68kg)",
					16 => "155 lbs. (70kg)",
					17 => "160 lbs. (73kg)",
					18 => "165 lbs. (75kg)",
					19 => "170 lbs. (77kg)",
					20 => "175 lbs. (79kg)",
					21 => "180 lbs. (82kg)",
					22 => "185 lbs. (84kg)",
					23 => "190 lbs. (86kg)",
					24 => "195 lbs. (88kg)",
					25 => "200 lbs. (91kg)",
					26 => "205 lbs. (93kg)",
					27 => "210 lbs. (95kg)",
					28 => "215 lbs. (98kg)",
					29 => "220 lbs. (100kg)",
					30 => "225 lbs. (102kg)",
					31 => "230 lbs. (104kg)",
					32 => "235 lbs. (107kg)",
					33 => "240 lbs. (109kg)",
					34 => "245 lbs. (111kg)",
					35 => "250 lbs. (113kg)",
					36 => "255 lbs. (116kg)",
					37 => "260 lbs. (118kg)",
					38 => "265 lbs. (120kg)",
					39 => "270 lbs. (122kg)",
					40 => "275 lbs. (125kg)",
					41 => "280 lbs. (127kg)",
					42 => "285 lbs. (129kg)",
					43 => "290 lbs. (132kg)",
					44 => "295 lbs. (134kg)",
					45 => "300 lbs. (136kg)",
					46 => "300+ lbs. (136kg+)");

		static $appearance = array(
						1 => 'Thin',
						2 => 'Average',
						3 => 'Sporty',
						4 => 'Chubby',
						5 => 'Fat');

		static $eyes_color = array(
						1 => 'Brown',
						2 => 'Blue',
						3 => 'Green',
						4 => 'Other');

		static $hair_color = array(
						1 => 'Black',
						2 => 'Brown',
						3 => 'Blonde',
						4 => 'Red',
						5 => 'Other');

		static $hair_length = array(
						1 => 'Hairless',
						2 => 'Short',
						3 => 'Medium',
						4 => 'Long');

		static $beard = array(
					1 => 'No Beard',
                    2 => 'Some',
                    3 => 'Full Beard',
                    4 => 'Mustache Only');

		static $zodiac = array(
					1 => 'Aquarius',
					2 => 'Pisces',
					3 => 'Aries',
					4 => 'Taurus',
					5 => 'Gemini',
					6 => 'Cancer',
					7 => 'Leo',
					8 => 'Virgo',
					9 => 'Libra',
					10 => 'Scorpio',
					11 => 'Sagittarius',
					12 => 'Capricorn');

		static $sexuality = array(
						1 => 'Homosexual',
						2 => 'Heterosexual',
						3 => 'Bisexual');

		static $status = array(
					1 => 'Single',
                    2 => 'Separated',
                    3 => 'Divorced',
                    4 => 'Widowed',
                    5 => 'Married',
                    6 => 'In a Relationship');

		static $phoneCode = array(
				'0150'	=> '0150',
				'0151'	=> '0151',
				'0152'	=> '0152',
				'01520'	=> '01520',
				'0155'	=> '0155',
				'01550'	=> '01550',
				'0157'	=> '0157',
				'0159'	=> '0159',
				'0160'	=> '0160',
				'0161'	=> '0161',
				'0162'	=> '0162',
				'0163'	=> '0163',
				'0169'	=> '0169',
				'0170'	=> '0170',
				'0171'	=> '0171',
				'0172'	=> '0172',
				'0173'	=> '0173',
				'0174'	=> '0174',
				'0175'	=> '0175',
				'0176'	=> '0176',
				'0177'	=> '0177',
				'0178'	=> '0178',
				'0179'	=> '0179');
		
		static $msg_ban_alert = 'Sms has band message.';

		static $msg_not_free_alert = 'Not Free message Display.';

		static $msg_valid_alert = 'Valid Code.';

		static $try1 = 'You have only  ';

		static $try2 = ' time to insert  Code.';

		static $try3 = 'Message is Band.';

		static $free0 = 'Message 0g';

		static $free1 = 'Message  1ghg';

		static $free2 = 'Message  2hg';

		static $free3 = 'Message  3hg';

		static $free4 = 'Message  4h';

		static $username = 'Username';

		static $password = 'Password';

		static $email_testmember_subject = 'Flirten48.net: Your username and password';
		static $email_delete_picture_subject = "Picture deleted";

		static $email_missing ='We miss you on Flirten48.net!';

		static $email_reminder_subject = 'There is a new message for you on Flirten48.net!';

		static $validation = 'Validation Code';

		static $register_membership_complete = 'Complete';

		static $register_testmembership_complete = 'A registration mail was sent to your e-mail address. Please confirm your account by clicking the link provided in this e-mail!';
		
		static $register_testmembership_complete1 = 'Please check your SPAM/Junk folder, if you do not see the e-mail. Please add us to <strong>your trusted sender list</strong>, so that you will see our updates every time in the future!';

		static $register_testmembership_complete2 = 'A registration mail was sent to your e-mail address.';

		static $register_testmembership_complete3 = 'Please confirm your account by clicking the link provided in this e-mail!';
		

		static $phone_number_guide_subject = "How to send text messages";
		static $phone_number_guide = "Please be adviced, if you want to send text messages to your favourite contacts, the following steps have to be done:<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Your mobile phone number has to be verified on this website.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. If you want to send text messages to only one profile on this webpage you don`t need to do anything additionally, but if you are writing text messages to different contacts on this website using our text message service, you will have to put the name of your prefered text message recepient at the beginning of your text message (Example: 'nickname: YOUR TEXT').";
	

		static $err_blank_valid_code = 'Please fill in verification code.';
		static $err_valid_code = 'Please fill in correct verification code.';
		static $err_valid_code_timeout = 'Your verification time has expired, please resend the verification code.';
		static $register_error = 'The username or e-mail has already been issued.';
		static $err_usrname_format = 'Username format is incorrect.';
		static $err_age_limit = "Due to your age, you're unable to use our service.";
		static $valid_code_resend = 'The system has sent a new verification code to your phone.';
		static $limit_code_resend = 'Sorry, the limit is 3 issues for your verification code.';
		static $err_blank_phone = 'Please fill in your phone number.';
		static $err_require = "Please fill in the required information.";

		static $mobile_valid_success = 'Successfully registered.';

		static $forget_error = 'Incorrect e-mail.';

		static $email_not_in_database = 'This e-mail account is not in our system.';
		static $resend_activation_error = 'This account has been activated already.';

		static $complete = 'This message was sent!';

		static $writemessage_error = 'No Username.';
		static $sms_subject = 'TEXT MESSAGE';

		static $allow = 'This page can only be accessed by members of this site.';

		static $Administrator = 'Administrator';

		static $Membership_Gold = 'Membership-Gold';

		static $Membership_Silver = 'Membership-Silver';

		static $Membership_Bronze = 'Membership-Bronze';

		static $Test_Membership = 'Test-Membership';

		static $and = 'and';

		static $reply_message = 'Hello $receiver,

danke für Deine Nachricht. Ich werde mich so schnell wie möglich bei Dir melden.

Liebe Grüße
$sender';

		static $reply_message1 = 'Hallo $receiver,

danke für Deine Nachricht. Ich werde mich so schnell wie möglich bei Dir melden.

Liebe Grüße
$sender';
		static $reply_message2 = 'Hallo $receiver,

Ich bin leider noch nicht freigeschaltet, warte bitte auf mich.

Liebe Grüße
$sender';
		static $reply_message3 = 'Hallo $receiver,

Ich habe auch Interesse an dir! Habe bitte etwas Geduld, bis mein account freigeschaltet ist.

Liebe Grüße
$sender';

		static $standard_message1 = 'Hallo, ich möchte gerne mit dir in Kontakt treten, hast du Lust?

Liebe Grüße
$sender';

		static $standard_message2 = 'Hallo, habe dein Profil gelesen und würde gerne mit dir in Kontakt treten. Bitte melde dich.

Liebe Grüße
$sender';

		static $standard_message3 = 'Hallo, ich habe deine Kontaktanzeige gelesen und  würde gerne mit dir in Kontakt treten. Bitte melde dich.

Liebe Grüße
$sender';

		static $reply_subject = 'Antwort auf deine Nachricht';

		static $reply_subject2 = "I'd like to meet you";

		static $fotoalbum_alert = 'Please upload .jpg or .jpeg files only.';

		static $activate_alert = 'Username, password or validation code is incorrect.';
		
		static $activate_ready = 'Your registration is complete.';

		static $ecard_subject = 'You received an e-card from one of our members.';

		static $ecard_message = 'You received an e-card from $username';

		static $ecard_send_subject = 'E-card sent successfully';

		static $ecard_send_message = 'E-card sent successfully';

		static $edit_alert = "Can't edit now. Another person is editing this member now.";

		static $view_ecard = 'View Card';

		static $first_time_inbox_subject = "Welcome to Flirten48.net";
		static $first_time_inbox_message = "Hello and welcome to Flirten48.net, your personal account to chat, flirt, relax and make new friends.<br/>If you want to chat with someone about your problems or emotional distress, feel free to contact me directly or take a look at my suggestion box. You will find more information about me, such as my diary and my photo album.<br/><br/>I hope you will send me a short message, if you need advice on how to continue.<br/><br/>I hope you have a good time while you are here and success with what you are looking for.<br/><br/>Your 'agony aunt' Gabi ...";

		static $err_valid_bonus_code_timeout = 'Sorry, verification period has expired.';

		static $err_bonus_code_verified = "Sorry, this code has already been verified.";

		static $bonus_SMS_message = "Welcome to Flirten48.net, use this code to verify your BONUS!";

		static $bonus_message_subject = "Get your Bonus code now for FREE!";

		static $bonus_message_content = "<a href=\"[URL]\" class=\"link\">Click here</a> to verify your bonus.";

		static $mobile_verify_message = "Welcome to Flirten48.net, please enter this verification code on the website: ";

		static $progress_bar_profile = "Complete_Profile";

		static $progress_bar_photo = "Photo_Album";

		static $progress_bar_ads = "Lonely_heart_ads";

		static $progress_bar_mobile = "Phone_Number";

		static $progress_bar_mobile_text = "Click to verify <br/>Phone number";

		static $twice_sms_reminder = "If you want to send an SMS to a particular member, please insert their profile name at the beginning of your SMS, e.g. 'Angel23:'";
		
		
		
		static $adminsendemail_intro = "You have received a message from our System Administrator on [KM_Website]!";

		static $adminsendemail_subject = "Get your Bonus code now, for FREE!";

		static $adminsendemail_content = "Welcome to [KM_Website]! There is a bonus code available for you, which you can redeem instantly after you have logged in. Your personal bonus code is as follows: [bonus_code]<br/><br/><a href=\"[URL]\" style=\"color: #FFFFFF; text-decoration: underline;\">Simply click this link do get your free coins!</a>";

		static $adminsendemail_footer1 = "Enjoy your time on [KM_Website]!";

		static $adminsendemail_footer2 = "Note: You are receiving this message because you have signed up with [KM_Website]. ";

		static $adminsendemail_footer3 = "This message was generated automatically, please do not reply to this e-mail.";



		static $sendmessage_email_coin = "Send a message via e-mail [PROFILE_NAME] for only [COIN_COSTS] coins!";
		static $sendmessage_sms_coin = "Send a message via text message or via e-mail [PROFILE_NAME] for only [COIN_COSTS] coins!";



		static $emailAfterEmail_subject = "You have received a message from [PROFILE_NAME] on [KM_Website]!";
		
		static $back_button = "back";
		
		static $next_button = "next";

		static $not_enough_coin = "You don't have enought COIN to send SMS, please refill the COIN in http://www.Flirten48.net.";




		static $coin_statistics = "Error Message";

		static $admin_manage_contents = "admin_manage_contents";

		static $username_incorrect = 'Username is incorrect.';

		//changepassword.php
		static $chpd1 = "New password saved!";
		static $chpd2 = "These passwords don't match. Try again?";
		static $chpd3 = "Please enter your new password.";
		static $chpd4 = "The current password is incorrect.";
		static $chpd5 = "Please enter your current password.";

		//image_dir.php
		static $img_dir1 = "We don't allow to browse other directory!";

		//mymessage.php
		static $mymsg1 = "Not enough coins to send these messages.";
		static $mymsg2 = "";
		static $mymsg3 = "No standard messages disappeared.";

		//policy-popup.php
		static $plc_popup1 = "Open a popup window";

		//payportal_gp.php
		static $payportal1 = "Account \/ bank account is not supported";
		static $payportal2 = "Bank is not supported";
		static $payportal3 = "The payment request could not be sent. Some information is incorrect!";

		//register.php
		static $register1 = "Your mobile number is already registered";
		static $register2 = "Sorry, there are some errors with the registration, please try again.";

		//search_new.php
		static $search_new1 = "Please specify your criteria";

		//sms_validcode2.php
		static $sms_validcode1 = "The number has been stored and shipped the validation code to you.";
		static $sms_validcode2 = "Validation code is incorrect";
		static $sms_validcode3 = "The validation code has been resent to you! Please wear it now on the left side.";

		//mymessate.php
		static $mobile_ver_required = "You need to verify mobile number before sending SMS";

		//ajaxRequest.php
		static $newmessage = "New";

		static $reserved_usernames = array("about", "ac", "access", "account", "accounts", "activate", "ad", "add", "address", "adm", "admin", "administration", "administrator", "adult", "advertising", "ae", "af", "affiliate", "affiliates", "ag", "ai", "ajax", "al", "am", "an", "analytics", "android", "anon", "anonymous", "ao", "api", "app", "apple", "apps", "aq", "ar", "arabic", "archive", "archives", "as", "at", "atom", "au", "auth", "authentication", "avatar", "aw", "awadhi", "ax", "az", "azerbaijani", "ba", "backup", "banner", "banners", "bb", "bd", "be", "bengali", "better", "bf", "bg", "bh", "bhojpuri", "bi", "billing", "bin", "bj", "blog", "blogs", "bm", "bn", "bo", "board", "bot", "bots", "br", "bs", "bt", "burmese", "business", "bv", "bw", "by", "bz", "ca", "cache", "cadastro", "calendar", "campaign", "cancel", "careers", "cart", "cc", "cd", "cf", "cg", "cgi", "ch", "changelog", "chat", "checkout", "chinese", "ci", "ck", "cl", "client", "cliente", "cm", "cn", "co", "code", "codereview", "comercial", "compare", "compras", "config", "configuration", "connect", "contact", "contest", "cr", "create", "cs", "css", "cu", "cv", "cvs", "cx", "cy", "cz", "dashboard", "data", "db", "dd", "de", "delete", "demo", "design", "designer", "dev", "devel", "dir", "direct", "direct_messages", "directory", "dj", "dk", "dm", "do", "doc", "docs", "documentation", "domain", "download", "downloads", "dutch", "dz", "ec", "ecommerce", "edit", "editor", "edits", "ee", "eg", "eh", "email", "employment", "english", "enterprise", "er", "es", "et", "eu", "exchange", "facebook", "faq", "farsi", "favorite", "favorites", "feed", "feedback", "feeds", "fi", "file", "files", "fj", "fk", "fleet", "fleets", 'Flirten48', "flog", "fm", "fo", "follow", "followers", "following", "forum", "forums", "fr", "free", "french", "friend", "friends", "ftp", "ga", "gadget", "gadgets", "games", "gan", "gb", "gd", "ge", "german", "gf", "gg", "gh", "gi", "gist", "git", "github", "gl", "gm", "gn", "google", "gp", "gq", "gr", "group", "groups", "gs", "gt", "gu", "guest", "gujarati", "gw", "gy", "hakka", "hausa", "help", "hindi", "hk", "hm", "hn", "home", "homepage", "host", "hosting", "hostmaster", "hostname", "hpg", "hr", "ht", "html", "http", "httpd", "https", "hu", "id", "idea", "ideas", "ie", "il", "im", "image", "images", "imap", "img", "in", "index", "indice", "info", "information", "intranet", "invitations", "invite", "io", "ipad", "iphone", "iq", "ir", "irc", "is", "it", "italian", "japanese", "java", "javanese", "javascript", "je", "jinyu", "jm", "jo", "job", "jobs", "jp", "js", "json", "kannada", "ke", "kg", "kh", "ki", "km", "kn", "knowledgebase", "korean", "kp", "kr", "kw", "ky", "kz", "la", "language", "languages", "lb", "lc", "li", "list", "lists", "lk", "local", "localhost", "log", "login", "logout", "logs", "lr", "ls", "lt", "lu", "lv", "ly", "ma", "mail", "mail1", "mail2", "mail3", "mail4", "mail5", "mailer", "mailing", "maithili", "malayalam", "manager", "mandarin", "map", "maps", "marathi", "marketing", "master", "mc", "md", "me", "media", "message", "messenger", "mg", "mh", "microblog", "microblogs", "min-nan", "mine", "mis", "mk", "ml", "mm", "mn", "mo", "mob", "mobile", "mobilemail", "movie", "movies", "mp", "mp3", "mq", "mr", "ms", "msg", "msn", "mt", "mu", "music", "musicas", "mv", "mw", "mx", "my", "mysql", "mz", "na", "name", "named", "nc", "ne", "net", "network", "new", "news", "newsletter", "nf", "ng", "ni", "nick", "nickname", "nl", "no", "notes", "noticias", "np", "nr", "ns", "ns1", "ns2", "ns3", "ns4", "nu", "nz", "oauth", "oauth_clients", "offers", "old", "om", "online", "openid", "operator", "order", "orders", "organizations", "oriya", "pa", "page", "pager", "pages", "panel", "panjabi", "password", "pda", "pe", "perl", "pf", "pg", "ph", "photo", "photoalbum", "photos", "php", "pic", "pics", "pk", "pl", "plans", "plugin", "plugins", "pm", "pn", "polish", "pop", "pop3", "popular", "portuguese", "post", "postfix", "postmaster", "posts", "pr", "privacy", "profile", "project", "projects", "promo", "ps", "pt", "pub", "public", "put", "pw", "py", "python", "qa", "random", "re", "recruitment", "register", "registration", "remove", "replies", "repo", "ro", "romanian", "root", "rs", "rss", "ru", "ruby", "russian", "rw", "sa", "sale", "sales", "sample", "samples", "save", "sb", "sc", "script", "scripts", "sd", "se", "search", "secure", "security", "send", "serbo-croatian", "service", "sessions", "setting", "settings", "setup", "sftp", "sg", "sh", "shop", "si", "signin", "signup", "sindhi", "site", "sitemap", "sites", "sj", "sk", "sl", "sm", "smtp", "sn", "so", "soporte", "spanish", "sql", "sr", "ss", "ssh", "ssl", "ssladmin", "ssladministrator", "sslwebmaster", "st", "stage", "staging", "start", "stat", "static", "stats", "status", "store", "stores", "stories", "styleguide", "su", "subdomain", "subscribe", "subscriptions", "sunda", "suporte", "support", "sv", "svn", "sy", "sysadmin", "sysadministrator", "system", "sz", "tablet", "tablets", "talk", "tamil", "task", "tasks", "tc", "td", "tech", "telnet", "telugu", "terms", "test", "test1", "test2", "test3", "teste", "tests", "tf", "tg", "th", "thai", "theme", "themes", "tj", "tk", "tl", "tm", "tmp", "tn", "to", "todo", "tools", "tour", "tp", "tr", "translations", "trends", "tt", "turkish", "tv", "tw", "twitter", "twittr", "tz", "ua", "ug", "uk", "ukrainian", "unfollow", "unsubscribe", "update", "upload", "urdu", "url", "us", "usage", "user", "username", "usuario", "uy", "uz", "va", "vc", "ve", "vendas", "vg", "vi", "video", "videos", "vietnamese", "visitor", "vn", "vu", "weather", "web", "webmail", "webmaster", "website", "websites", "webstats", "wf", "widget", "widgets", "wiki", "win", "workshop", "ws", "wu", "ww", "wws", "www", "www1", "www2", "www3", "www4", "www5", "www6", "www7", "wwws", "wwww", "xfn", "xiang", "xml", "xmpp", "xmppSuggest", "xpg", "xxx", "yaml", "ye", "yml", "yoruba", "you", "yourdomain", "yourname", "yoursite", "yourusername", "yt", "yu", "za", "zm", "zw");
	}
?>