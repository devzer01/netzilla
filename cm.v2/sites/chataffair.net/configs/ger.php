<?php
	class ger
	{
		static $category = array(
					1 => 'Affäre',
					2 => 'Freundschaft',
					3 => 'One Night Stand',
					4 => 'Beziehung');

		static $targetGroup = array(
					1 => 'Männer',
					2 => 'Frauen',
					3 => 'Paare',
					4 => 'Gruppen');

		static $KM_Name = "Chataffair.net";

		static $KM_Website = "Chataffair.net";
		
    	static $wemissu = "Wir vermissen dich auf <a href='http://www.Chataffair.net'>Chataffair.net!</a> Unten findest du die Profile von 4 Mitgliedern, die deinen Vorstellungen hoffentlich ensprechen.";

		static $Name = "Name";

		static $Plz = "PLZ";

		static $Age = "Alter";

		static $Civil_status = "Beziehungsstatus";

		static $Height = "Größe";

		static $Descr = "Beschreibung";

		static $Appearance = "Aussehen";

		static $City = "Stadt";

		static $Year = "Jahre";

		static $sql_connect_alert = 'Can\'t connect MYSQL.';

		static $sql_database_alert = 'Can\'t connect database.';

		static $yesno = array(
					1 => 'Ja',
					0 => 'Nein');

		static $picyesno = array(
					1 => 'Ja',
					0 => 'Egal');

		static $nocomment = array(
						0 => 'Kein Kommentar');

		static $month = array(
					1  => 'Januar',
                    2  => 'Februar',
                    3  => 'März',
                    4  => 'April',
					5  => 'Mai',
					6  => 'Juni',
					7  => 'Juli',
					8  => 'August',
					9  => 'September',
					10 => 'Oktober',
					11 => 'November',
					12 => 'Dezember');

		static $gender = array(
					1 => 'Mann',
					2 => 'Frau');

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
						1 => 'schlank',
						2 => 'normal',
						3 => 'sportlich',
						4 => 'mollig',
						5 => 'rubenshaft');

		static $eyes_color = array(
						1 => 'braun',
						2 => 'blau',
						3 => 'grün',
						4 => 'andere');

		static $hair_color = array(
						1 => 'schwarz',
						2 => 'braun',
						3 => 'blond',
						4 => 'rot',
						5 => 'andere');

		static $hair_length = array(
						1 => 'keine',
						2 => 'kurz',
						3 => 'mittel',
						4 => 'lang');

		static $beard = array(
					1 => 'keinen',
                    2 => 'wenig',
                    3 => 'Vollbart',
                    4 => 'Schnurrbart');

		static $zodiac = array(
					1 => 'Wassermann',
					2 => 'Fische',
					3 => 'Widder',
					4 => 'Stier',
					5 => 'Zwillinge',
					6 => 'Krebs',
					7 => 'Löwe',
					8 => 'Jungfrau',
					9 => 'Waage',
					10 => 'Skorpion',
					11 => 'Schütze',
					12 => 'Steinbock');

		static $sexuality = array(
						1 => 'Homo',
						2 => 'Hetero',
						3 => 'Bisexuell');

		static $status = array(
					1 => 'Single',
                    2 => 'Getrennt',
                    3 => 'Geschieden',
                    4 => 'Verwitwet',
                    5 => 'Verheiratet',
                    6 => 'Beziehung');

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
		
		static $msg_ban_alert = 'Sms has Ban Message.';

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

		static $email_testmember_subject = 'Chataffair.net: Dein Benutzername und Passwort';

		static $email_missing ='Wir vermissen dich auf Chataffair.net!';

		static $email_reminder_subject = 'Es liegt eine neue Nachricht für dich bereit bei Chataffair.net';

		static $validation = 'Validation Code';

		static $register_membership_complete = 'Herzlich Willkommen! Du hast dich erfolgreich auf www.Chataffair.net registriert und wirst gleich zu deinem Nutzerprofil weiter geleitet!';

		static $register_testmembership_complete = 'Eine Registrierung Mail wurde an Ihre e-mail adresse gesendet. Bitte bestätigen Sie Ihre Rechnung, indem Sie auf den Link in dieser e-mail zur Verfügung gestellt!';
		
		static $register_testmembership_complete1 = 'Bitte überprüfe auch deinen SPAM- oder Junkmail-Ordner in deinem Email-Client ob unsere Mail dort eingegangen ist und füge den Absender gegebenenfalls als vertrauenswürdigen Absender hinzu, damit wir dich auch in Zukunft immer auf dem Laufenden halten können!';

		static $register_testmembership_complete2 = 'Bitte bestätige deine Daten durch Anklicken des darin enthaltenen Links!';

		static $register_testmembership_complete3 = 'Vielen Dank für deine Registrierung bei Chataffair.net. Eine Email ist bereits an die<br/>von dir angegebene Email-
		adresse unterwegs.';
		
		static $phone_number_guide_subject = "How to send SMS";
		static $phone_number_guide = "Bitte bedenke, dass du nur Nachrichten per SMS an deinen Kontakt verschicken kannst, wenn du folgende Schritte ausgeführt hast:<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Du musst deine Mobilfunkrufnummer auf dieser Internetseite verifiziert haben.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Wenn du an nur einen Kontakt Nachrichten verschickst, dann kannst du problemlos eingehende SMS einfach nur beantworten, solltest du jedoch mit mehreren Kontakten per SMS schreiben, so setze bitte den Namen des Empfängerkontaktes gefolgt von einem : an den Anfang jeder deiner SMS (Beispiel: 'nickname: DEIN TEXT').";
		
		static $err_blank_valid_code = 'Du erhältst innerhalb weniger Sekunden einen Validierungscode. Bitte trage diesen in dem unten stehenden Feld ein, um deine Handynummer zu verifizieren!';
		static $err_valid_code = 'Der Validierungscode scheint falsch zu sein, bitte überprüfe deine Eingaben und versuche es erneut!';
		static $err_valid_code_timeout = 'Die Zeit für die Validierung deiner Handynummer ist leider abgelaufen, bitte klicke auf den unten stehenden Link um dir einen neuen Validierungscode auf dein Handy schicken zu lassen!';
		static $register_error = 'Dein Benutzername oder deine Email Adresse sind bereits registriert';
		static $err_usrname_format = 'Dein Benutzername enthält ungültige Zeichen! Bitte wähle einen anderen Benutzernamen aus und versuche es erneut!';
		static $err_age_limit = 'Leider bist du zu jung, um unseren Service nutzen zu können!';
		static $valid_code_resend = 'Es wurde ein neuer Verifizierungscode an die angegebene Handynummer versendet, bitte habe nur wenige Sekunden Geduld!';
		static $limit_code_resend = 'Diese Funktion wurde bereits zum dritten Mal genutzt und ist jetzt nicht mehr verfügbar!';
		static $err_blank_phone = 'Please fill your phone number';
		static $err_require = "Bitte vervollständige deine Angaben!";	

		static $mobile_valid_success = 'Herzlichen Glückwunsch! Du hast deine Handynummer erfolgreich verifiziert!';

		static $forget_error = 'Bitte trage die Email-Adresse ein!';

		static $email_not_in_database = 'Diese E-Mail-Konto ist nicht in unserem System.';
		static $resend_activation_error = 'Dieses Konto wurde bereits aktiviert.';

		static $complete = 'Diese Nachricht wurde versendet!';

		static $writemessage_error = 'Bitte trage deinen Mitgliedsnamen ein!';
		static $sms_subject = 'SMS';

		static $allow = 'Diese Funktion ist nur f&uuml;r ';

		static $Administrator = 'Administrator';

		static $Membership_Gold = 'VIP-Mitglieder';

		static $Membership_Silver = 'Premium Mitglieder';

		static $Membership_Bronze = 'Standard Mitglieder';

		static $Test_Membership = 'Test-Mitgliedschaft';

		static $and = 'und';

		static $reply_message = 'Hallo $receiver,

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

		static $reply_subject2 = 'Ich möchte dich kennenlernen';

		static $fotoalbum_alert = 'Bitte nur Bilder im Format .jpg or .jpeg hochladen!';

		static $activate_alert = 'Username, Passwort oder Validierungscode ist falsch.';
		
		static $activate_ready = 'Ihre Registrierung wurde bereits zu einem früherem Zeitpunkt abgeschlossen.';

		static $ecard_subject = 'Eine Chataffair.net E-Card für dich!';

		static $ecard_message = 'Hallo! <br> <br> Du hast eine E-Card von $username erhalten. Drücke auf den nachfolgenden Link und erfahre was dein Freund dir schreibt:';

		static $ecard_send_subject = 'Deine Chataffair.net E-Card wurde erfolgreich versandt';

		static $ecard_send_message = 'Deine Chataffair.net E-Card wurde erfolgreich versandt!';

		static $edit_alert = 'Das Profil befindet sich bereits in Bearbeitung!';

		static $view_ecard = 'E-Card ansehen';

		static $first_time_inbox_subject = "Willkommen bei Chataffair.net";
		static $first_time_inbox_message = "Hallo und herzlich Willkommen bei Chataffair.net, dem Flirtportal zum Kennenlernen, Flirten und Verlieben.
  
  Wenn du Kummer, Sorgen oder Probleme haben solltest, dann darfst du dich jederzeit gerne vertrauensvoll an mich wenden. Du kannst mich nahezu Alles fragen was dir auf der Seele brennt und mir auch gerne eine Nachricht schreiben, wenn du mal nicht weiter weißt.
  
  Ich wünsche dir hier viel Spaß und viel Erfolg bei deiner Suche.

   
  Liebe Grüße von deiner Gabi, der \"Kummerkastentante\"";

		static $err_valid_bonus_code_timeout = 'Leider ist dieser Bonuscode bereits abgelaufen!';

		static $err_bonus_code_verified = "Dieser Bonuscode wurde bereits eingelöst.";

		static $bonus_SMS_message = "Willkommen bei [URL]. Du hast einen Bonuscode per eMail erhalten. Löse diesen gleich ein um Freecoins zu erhalten: [bonus_code]";

		static $bonus_message_subject = "Hol dir deinen persönlichen Bonuscode!";
		
		static $bonus_message_content = "Willkommen bei [KM_Website]! Für dich wurde ein Bonuscode hinterlegt, den du sofort einlösen kannst, nachdem du dich eingeloggt hast. Dein persönlicher Bonuscode lautet: [bonus_code]<br/><br/><a href=\"[URL]\" style=\"color:#d20000; text-decoration: none;\">Klicke einfach auf diesen Link und hole dir noch heute deine Gratiscoins!</a>";

		static $mobile_verify_message = "Willkommen bei Chataffair.net, bitte gebe diesen Verifizierungscode ein: ";

		static $progress_bar_profile = "Vollständiges_Profil";

		static $progress_bar_photo = "Foto_Album";

		static $progress_bar_ads = " Kontaktanzeigen";

		static $progress_bar_mobile = "Telefonnummer";

		static $progress_bar_mobile_text = "Klicken Sie auf das Telefon zu überprüfen";

		static $twice_sms_reminder = "Wenn du an ein bestimmtes Profil SMS senden m๖chtest, dann setze bitte den Profilnamen an den Anfang deiner SMS, z.B. 'Angel23:'";
		
		
		
		static $adminsendemail_intro = "Du hast eine Nachricht vom System Admin auf [KM_Website] erhalten!";
		
		static $adminsendemail_subject = "Hol dir deinen persönlichen Bonuscode!";
		
		static $adminsendemail_content = "Willkommen bei [KM_Website]! Für dich wurde ein Bonuscode hinterlegt, den du sofort einlösen kannst, nachdem du dich eingeloggt hast. Dein persönlicher Bonuscode lautet: [bonus_code]<br/><br/> <a href=\"[URL]\" style=\"color: #d20000; text-decoration: underline;\">Klicke einfach auf diesen Link und hole dir noch heute deine Gratiscoins!</a>";
		
		static $adminsendemail_footer1 = "Das Team von [KM_Website] wünscht dir viel Spaß!";
		
		static $adminsendemail_footer2 = "Fussnote: Du erhälst diese Nachricht, weil du dich bei [KM_Website] registriert hast. ";
		
		static $adminsendemail_footer3 = "Diese Nachricht wurde automatisch generiert, also beantworte diese bitte nicht.";



		static $sendmessage_email_coin = "Versende jetzt eine Nachricht über E-Mail [PROFILE_NAME] für nur [COIN_COSTS] Coins!";
		static $sendmessage_sms_coin = "Versende jetzt eine Nachricht über SMS [PROFILE_NAME] für nur [COIN_COSTS] Coins!";



		static $emailAfterEmail_subject = "Du hast eine Nachricht [PROFILE_NAME] auf [KM_Website] erhalten!";
		
		static $back_button = "zurück";
		
		static $next_button = "vor";

		static $not_enough_coin = "Du hast nicht mehr genug COINS um SMS zu versenden, bitte buche dein COIN-Konto auf unter: http://www.Chataffair.net.";




		static $coin_statistics = "Fehlermeldung";

		static $admin_manage_contents = "admin_manage_contents";

		static $username_incorrect = 'Benutzername ist falsch.';

		//changepassword.php
		static $chpd1 = "Neues Passwort gespeichert!";
		static $chpd2 = "Diese Passwörter stimmen nicht überein. Versuchen Sie es erneut?";
		static $chpd3 = "Bitte geben Sie Ihr neues Passwort.";
		static $chpd4 = "Das aktuelle Passwort ist nicht korrekt.";
		static $chpd5 = "Bitte geben Sie Ihr aktuelles Passwort.";

		//image_dir.php
		static $img_dir1 = "Wir erlauben keine andere Verzeichnis zu durchsuchen!";

		//mymessage.php
		static $mymsg1 = "Nicht genug Münzen, um diese Nachrichten zu senden.";
		static $mymsg2 = "";
		static $mymsg3 = "Keine Standard Nachrichten mehr vorhanden.";

		//policy-popup.php
		static $plc_popup1 = "Öffnen Sie ein Popup-Fenster";

		//payportal_gp.php
		static $payportal1 = "Konto \/ Bankverbindung wird nicht unterstützt";
		static $payportal2 = "Bank wird nicht unterstützt";
		static $payportal3 = "Die Zahlungsanfrage konnte nicht gesendet werden. Einige Angaben sind fehlerhaft!";

		//register.php
		static $register1 = "Dein Mobilfunknummer ist bereits registriert";
		static $register2 = "Leider sind einige Fehler bei der Anmeldung, bitte versuchen Sie es erneut.";

		//search_new.php
		static $search_new1 = "Bitte geben Sie Ihre Kriterien";

		//sms_validcode2.php
		static $sms_validcode1 = "Die Nummer wurde gespeichert und der Validierungscode an dich versandt.";
		static $sms_validcode2 = "Validation Code ist nicht korrekt";
		static $sms_validcode3 = "Der Validierungscode wurde erneut an dich gesendet! Bitte trage ihn nun auf der linken Seite ein.";

		//mymessate.php
		static $mobile_ver_required = "Sie müssen sich Handy-Nummer vor dem Senden von SMS überprüfen";

		//ajaxRequest.php
		static $newmessage = "Neu";

		static $reserved_usernames = array("about", "ac", "access", "account", "accounts", "activate", "ad", "add", "address", "adm", "admin", "administration", "administrator", "adult", "advertising", "ae", "af", "affiliate", "affiliates", "ag", "ai", "ajax", "al", "am", "an", "analytics", "android", "anon", "anonymous", "ao", "api", "app", "apple", "apps", "aq", "ar", "arabic", "archive", "archives", "as", "at", "atom", "au", "auth", "authentication", "avatar", "aw", "awadhi", "ax", "az", "azerbaijani", "ba", "backup", "banner", "banners", "bb", "bd", "be", "bengali", "better", "bf", "bg", "bh", "bhojpuri", "bi", "billing", "bin", "bj", "blog", "blogs", "bm", "bn", "bo", "board", "bot", "bots", "br", "bs", "bt", "burmese", "business", "bv", "bw", "by", "bz", "ca", "cache", "cadastro", "calendar", "campaign", "cancel", "careers", "cart", "cc", "cd", "cf", "cg", "cgi", "ch", "changelog", "chat", "chataffair", "checkout", "chinese", "ci", "ck", "cl", "client", "cliente", "cm", "cn", "co", "code", "codereview", "comercial", "compare", "compras", "config", "configuration", "connect", "contact", "contest", "cr", "create", "cs", "css", "cu", "cv", "cvs", "cx", "cy", "cz", "dashboard", "data", "db", "dd", "de", "delete", "demo", "design", "designer", "dev", "devel", "dir", "direct", "direct_messages", "directory", "dj", "dk", "dm", "do", "doc", "docs", "documentation", "domain", "download", "downloads", "dutch", "dz", "ec", "ecommerce", "edit", "editor", "edits", "ee", "eg", "eh", "email", "employment", "english", "enterprise", "er", "es", "et", "eu", "exchange", "facebook", "faq", "farsi", "favorite", "favorites", "feed", "feedback", "feeds", "fi", "file", "files", "fj", "fk", "fleet", "fleets", 'flirt48', "flog", "fm", "fo", "follow", "followers", "following", "forum", "forums", "fr", "free", "french", "friend", "friends", "ftp", "ga", "gadget", "gadgets", "games", "gan", "gb", "gd", "ge", "german", "gf", "gg", "gh", "gi", "gist", "git", "github", "gl", "gm", "gn", "google", "gp", "gq", "gr", "group", "groups", "gs", "gt", "gu", "guest", "gujarati", "gw", "gy", "hakka", "hausa", "help", "hindi", "hk", "hm", "hn", "home", "homepage", "host", "hosting", "hostmaster", "hostname", "hpg", "hr", "ht", "html", "http", "httpd", "https", "hu", "id", "idea", "ideas", "ie", "il", "im", "image", "images", "imap", "img", "in", "index", "indice", "info", "information", "intranet", "invitations", "invite", "io", "ipad", "iphone", "iq", "ir", "irc", "is", "it", "italian", "japanese", "java", "javanese", "javascript", "je", "jinyu", "jm", "jo", "job", "jobs", "jp", "js", "json", "kannada", "ke", "kg", "kh", "ki", "km", "kn", "knowledgebase", "korean", "kp", "kr", "kw", "ky", "kz", "la", "language", "languages", "lb", "lc", "li", "list", "lists", "lk", "local", "localhost", "log", "login", "logout", "logs", "lr", "ls", "lt", "lu", "lv", "ly", "ma", "mail", "mail1", "mail2", "mail3", "mail4", "mail5", "mailer", "mailing", "maithili", "malayalam", "manager", "mandarin", "map", "maps", "marathi", "marketing", "master", "mc", "md", "me", "media", "message", "messenger", "mg", "mh", "microblog", "microblogs", "min-nan", "mine", "mis", "mk", "ml", "mm", "mn", "mo", "mob", "mobile", "mobilemail", "movie", "movies", "mp", "mp3", "mq", "mr", "ms", "msg", "msn", "mt", "mu", "music", "musicas", "mv", "mw", "mx", "my", "mysql", "mz", "na", "name", "named", "nc", "ne", "net", "network", "new", "news", "newsletter", "nf", "ng", "ni", "nick", "nickname", "nl", "no", "notes", "noticias", "np", "nr", "ns", "ns1", "ns2", "ns3", "ns4", "nu", "nz", "oauth", "oauth_clients", "offers", "old", "om", "online", "openid", "operator", "order", "orders", "organizations", "oriya", "pa", "page", "pager", "pages", "panel", "panjabi", "password", "pda", "pe", "perl", "pf", "pg", "ph", "photo", "photoalbum", "photos", "php", "pic", "pics", "pk", "pl", "plans", "plugin", "plugins", "pm", "pn", "polish", "pop", "pop3", "popular", "portuguese", "post", "postfix", "postmaster", "posts", "pr", "privacy", "profile", "project", "projects", "promo", "ps", "pt", "pub", "public", "put", "pw", "py", "python", "qa", "random", "re", "recruitment", "register", "registration", "remove", "replies", "repo", "ro", "romanian", "root", "rs", "rss", "ru", "ruby", "russian", "rw", "sa", "sale", "sales", "sample", "samples", "save", "sb", "sc", "script", "scripts", "sd", "se", "search", "secure", "security", "send", "serbo-croatian", "service", "sessions", "setting", "settings", "setup", "sftp", "sg", "sh", "shop", "si", "signin", "signup", "sindhi", "site", "sitemap", "sites", "sj", "sk", "sl", "sm", "smtp", "sn", "so", "soporte", "spanish", "sql", "sr", "ss", "ssh", "ssl", "ssladmin", "ssladministrator", "sslwebmaster", "st", "stage", "staging", "start", "stat", "static", "stats", "status", "store", "stores", "stories", "styleguide", "su", "subdomain", "subscribe", "subscriptions", "sunda", "suporte", "support", "sv", "svn", "sy", "sysadmin", "sysadministrator", "system", "sz", "tablet", "tablets", "talk", "tamil", "task", "tasks", "tc", "td", "tech", "telnet", "telugu", "terms", "test", "test1", "test2", "test3", "teste", "tests", "tf", "tg", "th", "thai", "theme", "themes", "tj", "tk", "tl", "tm", "tmp", "tn", "to", "todo", "tools", "tour", "tp", "tr", "translations", "trends", "tt", "turkish", "tv", "tw", "twitter", "twittr", "tz", "ua", "ug", "uk", "ukrainian", "unfollow", "unsubscribe", "update", "upload", "urdu", "url", "us", "usage", "user", "username", "usuario", "uy", "uz", "va", "vc", "ve", "vendas", "vg", "vi", "video", "videos", "vietnamese", "visitor", "vn", "vu", "weather", "web", "webmail", "webmaster", "website", "websites", "webstats", "wf", "widget", "widgets", "wiki", "win", "workshop", "ws", "wu", "ww", "wws", "www", "www1", "www2", "www3", "www4", "www5", "www6", "www7", "wwws", "wwww", "xfn", "xiang", "xml", "xmpp", "xmppSuggest", "xpg", "xxx", "yaml", "ye", "yml", "yoruba", "you", "yourdomain", "yourname", "yoursite", "yourusername", "yt", "yu", "za", "zm", "zw");
	}
?>