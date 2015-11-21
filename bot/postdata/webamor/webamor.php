<?php
require_once('bot.php');

/*******************************/
/************ TO DO ************/
/*******************************/
/*
- change all URL variables in flirtfever()
- change login post array in addLoginData()
- change search post array in work()
- ...
*/
/*******************************/
/************ / TO DO ************/
/*******************************/

class Webamor extends bot
{
	public $sendmsg_total = 0;
	public $databaseName = "webamor";
	public function webamor($post)
	{
		if(is_array($post) && count($post))
		{
			ignore_user_abort(true);
			$commandID = $post['id'];
			$runCount = $post['run_count'];
			$botID = $post['server'];
			$siteID = $post['site'];
			$this->command = $this->mb_unserialize($post['command']);
			$post['command'] = $this->command;
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			$this->command = array(
									"profiles" => array(
															array(
																	"username" => "mauseschatz25",
																	"password" => "ichbinAnaid"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "freuemich punkt net"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"ageStart" => 18,
									"ageEnd" => 30,									
									"msg_type" => "gb",
									"send_test" => 0,															
									"version" => 2,
									"target" => "Male", //Male,Female,Gay,Lesbian
                                    "gender" => "Mann", // Mann,Frau
									"search" => 2,
									"distance" => 201,
									"affection" => 0,
									"lookfor" => 0,
									"blsu" => "alle",
									"onl" => "v",
                                    "suib" => "Mann",
									"sucht" => "Frau", //Mann,Frau
									"picture" => 1,
									"plz_option" => 2,
									"start_page" => 1,
									//"full_msg" => 1,																
									"action" => "send",
									'logout_after_sent' => 'Y',
								    'messages_logout' => 1,
								    'wait_for_login' => 1,
								    'login_by' => 1,
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 69;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->databaseName = "webamor";
		$this->userhash = "";

		//Login
			$this->usernameField = "user";
			$this->indexURL = "http://www.web-amor.de";
			$this->indexURLLoggedInKeyword = "logout";
			$this->loginURL = "http://www.web-amor.de";	
			$this->loginRefer = "http://www.web-amor.de";
			$this->loginRetry = 3;
			$this->logoutURL = "http://www.web-amor.de";
		
		//Search
			$this->searchIndex = "http://www.web-amor.de/suche.php";
			$this->searchURL = "http://www.web-amor.de/suchergebn.php";
			$this->searchPageURL = "http://joonity.de/profil/suchergebnisse";
			$this->searchResultsPerPage = 48;		
		
		//Message
			$this->messageURL = "http://www.flirtmit.de/alpha/mitglieder/profilpopup/formneu.php";
			$this->inboxURL = "http://www.elflirt.de/messages.php";
			$this->outboxURL = "http://www.elflirt.de/messages_out.php?utype=0";
			$this->onlineUserUrl = "http://www.elflirt.de/ef_all_onlineuser.php";
			$this->newUserUrl = "http://www.elflirt.de/ef_alluser.php";
		
		//Guestbook
			$this->sendGuestbookPageURL = "";

		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 6.1; rv:20.0) Gecko/20100101 Firefox/20.0";
		$this->commandID = $commandID;
		$this->workCount = 1;
		$this->siteID = $siteID;
		$this->botID = $botID;
		$this->runCount = $runCount;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->message="";
		$this->newMessage=true;		

		$this->cities = array(
			"0" => "All Country",
			"1" => "Deutschland",
			"2" => "Österreich",
			"3" => "Schweiz",
			"4" => "Liechtenstein"
		);

		$this->plz["1"] = array("01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089");

		if($this->command['version']==1)
		{
			$this->preMessage = array(	"Hallo du bist süsss, gefällst mir, bin aber leider nicht mehr hier aber bei",
										"na du Nettes profil, wenn du magst, is nicht mein login, komm doch zu",
										"hallo, find dich süss, bin aber jetzt offline und nur noch bei",
										"hallo bist du auch single? Ich auch, lust auf ein cafe?  Is der login meiner siss, komm doch zu",
										"hey habe dich gefunden, Find dich nett, komm dich in",
										"hallo nettes profil, habe lust dich kennen zu lernen, aber nicht hier, bin bei",
										"bist du ein netter? Hast du lust zu chatten?, dies is aber nicht mein account, du findest mich bei",
										"ups na was gibt’s? auch einsam? Ok ich auch, was machen wir denn schönes? Bin leider nichtmehr hier, komm nach",
										"nicht mehr online warum? Ok wenn du magst können wir ja chatten und uns beschnuppern vielleicht n cafe am we? Komm zu",
										"kommst aus meiner nähe was?  (-; würde dich gerne kennen lernen, der account is aber von meiner siss, such doch nach",
										"was bist du denn für einer? Find dich nett, auch solo? Mal chatten? Ok bin nun aber offline komm doch zu",
										"oh gerade noch ne mail von dir bevor ich auslogge, komme hier nicht mehr zu viele anschreiben, hätte aber lust dich kennen lzu lernen, bin bei",
										"mir geht’s gut und dir? Was gibbet denn? Bist süss, lust auf n cafe? Am we? Bin nun aber weg hier zuviele anschreiben, bin aber auf",
										"nett nett, stimmt oder? Lust zu chatten? Aber nicht hier ok? Viele spinner, komm zu mir wenn du magst auf",
										"wohnst um die ecke, gerne möchte ich mit dir chatten, aber nicht hier, zu viel los, habe bilder von mir auf",
										"hey stehst du auch auf blind dates? Ich schon wenn du magst habe bilder von mir, auch erotische in",
										"stehe auf aubenteuer du auch? Kommst ja aus meiner nähe, ah is nicht mein logion dieser aber bin bei",
										"bin ein wilde, abenteuer mag ich du auch? Blind dates? Am we vielleicht, bin ich zu direkt? Ok schau meine bilder an und entscheide selber ok? Hier bei",
										"dachte ich schreib dir mal, bin schon lange solo, bin grade in deine ecke gezogen vielleicht zeigst du mir hier wo man was machen kann? Bin hier aber weg, zuviele zuschrifeten komm doch zu",
										"ja wer bist du? So ein zufall kommst wohnst ja gleich um die ecke, magst du mir zeigen wo man hier was machen kann? Am we? Bin hier aber jetzt nicht mehr, komm zu",
										"was suchst du denn? Ich ers mal chatten abenteuer mal schauen, aber nicht hier, zuviele spinner, du bist süss, komm doch zu mir zu",
										"blind date? Abenteuer? Wenn du magst ich stehe auf sowas, habe bilder online, is hier leider verboten aber schau in mein album bei",
										"stehst du auch auf bilder tausch? Leider darf man das hier nicht, wenn du welche sehen willst von mir geh zu",
										"nettes profil, hast du noch mehr bilder? Hier kann ich meine nicht hochladen, verbotem, deswegen komme ich auch nicht wieder, komm zu",
										"schüchtern bin ich nicht eher in abenteuer laune, wohnst ja nicht weit weg, wenn du lust hast…. Ok schau dir ers mal meine bilder an und entscheide ok? Sind bei",
										"hey single oder schon vergeben?was machst du denn so? ach vergessen, meine schwester lies mich kurz online, wenn du chatten magst bin bei",
										"hi wmag dein profil, lust zu ….sorry bin nicht mehr hier, zuviel los, aber finde dich nett, komm zu",
										"hui soviele zuschriften, ich muss gehen, is doof hier, aber dich finde ich nett, vielleicht klapts ja und is ja nicht weit weg zu dir ;-) bin bei",
										"warum soviele nachrichten das ist zu viel, schreib nur dir zurück, bist nett, aber wie gesagt bin hier weg, würde mich aber freuen wenn du zu mir kommst auf",
										"danke bist ja ein netter ;-) aber für freuen nicht das richtige hier, bin wieder weg, aber dich find ich gut, würde mich freuen wenn du mir schreibst auf",
										"olla la über 40 nachrichten, das ist ja belästigung, aber du gefällst mir, lust auf ein beschnuppern? Bin hier ers mal weg, aber komm zu",
										"hallo bin neu hier aber auch wieder weg, viele idioten, aber dich will ich kennen lernen, komm zu",
										"man oh man 43 nachrichten in 1h das ist zuviel, bin weg hier, aber du bist anders oder? Also ich hätte schon lust du bist ja auch fast nachbar ;-) würde mich freuen wenn du zu mir kommst zu",
										"hallo wo kommst du her? Ach um die ecke, ok lust zu chatten mal n cafe? Aber nicht hier ok zu viele leute und nicht angenhem für faruen. Bin auf",
										"hi, wie geht’s dir? Schon was gefunden? Bin gerade 1h hier und muss leider kündigen, echt krass, aber dich mag ich irgendwie, wenn du magst bin auf",
										"na endlich mal jemand nettes, ist echt viel hier vier mich, ich gehe hier auch wieder, wenn du magst bin auf",
										"netter typ gefällst mir aber bitte nicht hier ok? Komm auf",
										"du bist ja ein süsser, wenn du willst ass chatten bei",
										"lust auf geile bilder? Schau in mein albun in",
										"hey kommzu",
										"hi du luist auf  kennen lernen ja ers mal beschnuppern ok aber im ruhigen kreise ok? Und nur bei",
										"menn bist du süss ;-) las mal chatten aber bei",
										"hi, wasmachst du denn so? lange schon alleine? Ich bin gerade hier her gezogen kenne noch niemanden, hier is aber zu heavy bin bei",
										"hey du, und wie geht es dir? Bin gerde neu hier in der stadt magst mir was zeigen? Hier bitte nicht, zu viele spinnde, nur bei",
										"ja wunderbar, nettes profil, was suchst du genau? Wenn du magst cafe trinken gehen? Ok findest mich immer bei",
										"wohnst ja hier, du sag mal  las mal cafe trinken am we ja? Wenn du mich suchst bin immer bei",
										"nicht immer so stürmich aber egal was machst du am we? Zeigst mir die stadt? Bin neu hergezogen, du erreichst mich bei",
										"hi, bin neu hier in der stadt, zeigst mir was? Is leider das prifil meiner schwester, bin aber hier",
										"ok komm zu",
										"hast du lust auf ein qoicki am we in der stadt treffen beschnuppern und mal schauen? Habe bilder sind hier nicht erlaubt aber auf",
										"tutut der zug ist abgefahren für alle spinner hier aber nicht für dich bist nett, was machst am wochenende? Bilder von mir bei",
										"ok ok gerne kennen lernen chatten und sehen was passiert ok? Hier werde ich aber immer belästigt, bin nur noch bei",
										"halli hallo ja ich war auf deinem profil, kommst aus der umgebung, single? Dan lass treffen ja? Melde mich hier jetzt aber ab, geht gar nicht, freumich wenn du kommst zu",
										"moin moin was geht ab? Ja würde dich gerne näher kennen lernen aber nicht bei kwick, find ich doof, bin das letzte mal hier, bin bei",
										"na bischen direkt oder? Aber das mag ich, bist n netter glaube ich, bin bei",
										"hallo hallo, na warum schreibst du mir? Was willst duß das selbe wie ich oder? Ok bilder hier",
										"oh warum schreibst du mir? Is nicht mein prfil is von siss, wenn du mich kennen lernen willst bin bei",
										"guck guck, ja las mal chatten und vieleciht mal treffen am we ja? Melde mich bei kwick aber nun ab, weiss warum nicht lustig, würde mich aber freuen wenn du mich besuchst bei",
										"einen schönen guten tag, lust auf ein abenteuer? Wenn du mir gegen ein kleines taschengeld einen schönen abend bereitest bin ich für alles bereit, meine bilder sind hier nicht erlaubt, aber schau sie dir an bei"
										);
			$this->postMessage = array(	"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg"
										);
		}
		else
		{
			$this->preMessage = array(
									"Danke für deine Nachricht süss, gefällst mir, bin aber leider nicht mehr hier aber bei",
									"was gibt es denn? Nettes profil, wenn du magst, is nicht mein login, komm doch zu",
									"hallo, find dich süss, bin aber jetzt offline und nur noch bei",
									"danke, nett bist du auch single? Ich auch, lust auf ein cafe?  Is der login meiner siss, komm doch zu",
									"hui wie kommst das du mir schreibst? Find dich nett, komm dich in",
									"klasse endlich schreibt mir jemand, habe lust dich kennen zu lernen, aber nicht hier, bin bei",
									"du bist ja ein netter, ja habe auch lust zu chatten, is aber nicht mein account, du findest mich bei",
									"ups na was gibt’s? auch einsam? Ok ich auch, was machen wir denn schönes? Bin leider nichtmehr hier, komm nach",
									"nicht mehr online warum? Ok wenn du magst können wir ja chatten und uns beschnuppern vielleicht n cafe am we? Komm zu",
									"holla kaum auf deim profil schon schreibst du mir (-; würde dich gerne kennen lernen, der account is aber von meiner siss, such doch nach",
									"was bist du denn für einer? Find dich nett, auch solo? Mal chatten? Ok bin nun aber offline komm doch zu",
									"oh gerade noch ne mail von dir bevor ich auslogge, komme hier nicht mehr zu viele anschreiben, hätte aber lust dich kennen lzu lernen, bin bei",
									"mir geht’s gut und dir? Was gibbet denn? Bist süss, lust auf n cafe? Am we? Bin nun aber weg hier zuviele anschreiben, bin aber auf",
									"danke, wie kommts? Lust zu chatten? Aber nicht hier ok? Viele spinner, komm zu mir wenn du magst auf",
									"gerne möchte ich mit dir chatten, aber nicht hier, zu viel los, habe bilder von mir auf",
									"hey stehst du auch auf blind dates? Ich schon wenn du magst habe bilder von mir, auch erotische in",
									"supi, stehe auf aubenteuer du auch? Kommst ja aus meiner nähe, ah is nicht mein logion dieser aber bin bei",
									"abenteuer mag ich du auch? Blind dates? Am we vielleicht, bin ich zu direkt? Ok schau meine bilder an und entscheide selber ok? Hier bei",
									"das ist lieb, danke, bin schon lange solo, bin grade in deine ecke gezogen vielleicht zeigst du mir hier wo man was machen kann? Bin hier aber weg, zuviele zuschrifeten komm doch zu",
									"ja wer bist du? So ein zufall kommst wohnst ja gleich um die ecke, magst du mir zeigen wo man hier was machen kann? Am we? Bin hier aber jetzt nicht mehr, komm zu",
									"lieb von dir, was suchst du denn? Ich ers mal chatten abenteuer mal schauen, aber nicht hier, zuviele spinner, du bist süss, komm doch zu mir zu",
									"blind date? Abenteuer? Wenn du magst ich stehe auf sowas, habe bilder online, is hier leider verboten aber schau in mein album bei",
									"stehst du auch auf bilder tausch? Leider darf man das hier nicht, wenn du welche sehen willst von mir geh zu",
									"klar mag ich dichkennen lernen, hast du noch mehr bilder? Hier kann ich meine nicht hochladen, verbotem, deswegen komme ich auch nicht wieder, komm zu",
									"schüchtern bin ich nicht eher in abenteuer laune, wohnst ja nicht weit weg, wenn du lust hast…. Ok schau dir ers mal meine bilder an und entscheide ok? Sind bei",
									"na du, is ja lieb das du mir schreibst was machst du denn so? ach vergessen, meine schwester lies mich kurz online, wenn du chatten magst bin bei",
									"sorry bin nicht mehr hier, zuviel los, aber finde dich nett, komm zu",
									"hui soviele zuschriften, ich muss gehen, is doof hier, aber dich finde ich nett, vielleicht klapts ja und is ja nicht weit weg zu dir ;-) bin bei",
									"warum soviele nachrichten das ist zu viel, schreib nur dir zurück, bist nett, aber wie gesagt bin hier weg, würde mich aber freuen wenn du zu mir kommst auf",
									"danke bist ja ein netter ;-) aber für freuen nicht das richtige hier, bin wieder weg, aber dich find ich gut, würde mich freuen wenn du mir schreibst auf",
									"olla la über 40 nachrichten, das ist ja belästigung, aber du gefällst mir, lust auf ein beschnuppern? Bin hier ers mal weg, aber komm zu",
									"danke, ich bin auch auf der suche, bin neu hier aber auch wieder weg, viele idioten, aber dich will ich kennen lernen, komm zu",
									"man oh man 43 nachrichten in 1h das ist zuviel, bin weg hier, aber du bist anders oder? Also ich hätte schon lust du bist ja auch fast nachbar ;-) würde mich freuen wenn du zu mir kommst zu",
									"auch hallo wo kommst du her? Ach um die ecke, ok lust zu chatten mal n cafe? Aber nicht hier ok zu viele leute und nicht angenhem für faruen. Bin auf",
									"auch hi, wie geht’s dir? Schon was gefunden? Bin gerade 1h hier und muss leider kündigen, echt krass, aber dich mag ich irgendwie, wenn du magst bin auf",
									"na endlich mal jemand nettes, ist echt viel hier vier mich, ich gehe hier auch wieder, wenn du magst bin auf",
									"netter typ gefällst mir aber bitte nicht hier ok? Komm auf",
									"du bist ja ein süsser, wenn du willst ass chatten bei",
									"lust auf geile bilder? Schau in mein albun in",
									"hey kommzu",
									"ok kennen lernen ja ers mal beschnuppern ok aber im ruhigen kreise ok? Und nur bei",
									"menn bist du süss ;-) las mal chatten aber bei",
									"hallo ja danke, und wie geht es dir? Bin gerde neu hier in der stadt magst mir was zeigen? Hier bitte nicht, zu viele spinnde, nur bei",
									"ja wunderbar, nettes profil, was suchst du genau? Wenn du magst cafe trinken gehen? Ok findest mich immer bei",
									"ok las mal cafe trinken am we ja? Wenn du mich suchst bin immer bei",
									"nicht immer so stürmich aber egal was machst du am we? Zeigst mir die stadt? Bin neu hergezogen, du erreichst mich bei",
									"hi, bin neu hier in der stadt, zeigst mir was? Is leider das prifil meiner schwester, bin aber hier",
									"ok komm zu",
									"hast du lust auf ein qoicki am we in der stadt treffen beschnuppern und mal schauen? Habe bilder sind hier nicht erlaubt aber auf",
									"tutut der zug ist abgefahren für alle spinner hier aber nicht für dich bist nett, was machst am wochenende? Bilder von mir bei",
									"ok ok gerne kennen lernen chatten und sehen was passiert ok? Hier werde ich aber immer belästigt, bin nur noch bei",
									"halli hallo ja ich war auf deinem profil, kommst aus der umgebung, single? Dan lass treffen ja? Melde mich hier jetzt aber ab, geht gar nicht, freumich wenn du kommst zu",
									"moin moin was geht ab? Ja würde dich gerne näher kennen lernen aber nicht bei kwick, find ich doof, bin das letzte mal hier, bin bei",
									"na bischen direkt oder? Aber das mag ich, bist n netter glaube ich, bin bei",
									"hallo hallo, na warum schreibst du mir? Was willst duß das selbe wie ich oder? Ok bilder hier",
									"oh warum schreibst du mir? Is nicht mein prfil is von siss, wenn du mich kennen lernen willst bin bei",
									"guck guck, ja las mal chatten und vieleciht mal treffen am we ja? Melde mich bei kwick aber nun ab, weiss warum nicht lustig, würde mich aber freuen wenn du mich besuchst bei",
									"einen schönen guten tag, lust auf ein abenteuer? Wenn du mir gegen ein kleines taschengeld einen schönen abend bereitest bin ich für alles bereit, meine bilder sind hier nicht erlaubt, aber schau sie dir an bei"
									);
			$this->postMessage = array(
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg"
									);
		}


		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		if($this->command['sucht'] == "Mann"){
			$target = "Male";
		}elseif($this->command['sucht'] == "Frau"){
			$target = "Female";
		}

		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='".$target."'");
			$this->messagesPartTemp[$i] = array();
		}
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				"user" => $user['username'],
				"password" => $user['password'],
				"x" =>	rand(7,17),
				"y" =>	rand(6,16)
			);	
				
			array_push($this->loginArr, $login_arr);
			
		}
	}
	
	private function getMessagePart($part)
	{
		if(count($this->messagesPartTemp[$part])==0)
		{
			shuffle($this->messagesPart[$part]);
		}
		elseif(count($this->messagesPart[$part])==0)
		{
			$this->messagesPart[$part] = $this->messagesPartTemp[$part];
			shuffle($this->messagesPart[$part]);
			$this->messagesPartTemp[$part] = array();
		}

		$msg = array_pop($this->messagesPart[$part]);
		array_push($this->messagesPartTemp[$part], $msg);
		return $msg;
	}

	private function getLocationID($location, $cookiePath)
	{
		/*
		$content = $this->getHTTPContent($this->locationQueryURL.$location, $this->searchPageURL, $cookiePath);
		$content = json_decode($content);
		if(is_object($content))
			return $content->locid;
		else
			return false;
		*/
		return 7063;
	}

	private function getUserID($content)
	{
		$userid = substr($content, strpos($content, "var user_info =")+16);
		$userid = substr($userid, 0, strpos($userid, "}")+1);
		$userid = json_decode($userid);

		if(is_object($userid))
			return $userid->userid;
		else
			return false;
	}

	private function getAuthCode($content)
	{
		$content=substr($content,strpos($content,"authid=")+7);
		$content=substr($content,0,strpos($content,"&"));
		return $content;
	}
	
	public function getKeyPositionInArray($haystack, $keyNeedle)
	{
		$i = 0;
		foreach($haystack as $key => $value)
		{
			if($value == $keyNeedle)
			{
				return $i;
			}
			$i++;
		}
	}
	
	public function work() {
		$this->savelog("Job started, bot version ".$this->command['version']);
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$this->savelog("Go to SEARCH page.");
		for($age = $this->command['ageStart']; $age <= $this->command['ageEnd']; $age++ )
		{
			$headers = array('Content-Type: application/x-www-form-urlencoded');
			$content = $this->getHTTPContent($this->searchIndex, $this->indexURL, $cookiePath, array(
				'T26.x'	=> rand(30,40),
				'T26.y'	=> rand(20,30),
				'etid' => $this->param["etid"],
				'zz' => $this->param["zz"]
			), $headers);
			
			$page=1;
			$list=array();
			$first_username = '';
				
			do {
				
				$this->savelog("Search for Target age: ".$age." to ".$age." / page ".$page);
		
				$search_arr = array(
					"abis" =>	$age,
					"avon" =>	$age,
					// "bene" =>	$username,
					// "bld" => "",	
					"blsu" =>	$this->command['blsu'],
					"enplz" =>	"01067",
					"entfern" => "alle km",
					"etid" =>	$this->param["etid"],
					// "gadb" =>	"gadb",
					// "nick" =>	"",
					// "nick2" =>	"",
					// "onl" =>	$this->command['onl'],
					"plz" =>	"",
					// "sf" =>	"szh",
					// "sf1" =>	"",
					// "sff" =>	"als Liste",
					"snae" =>	$page,
					"stadt" =>	"",
					"sucht" =>	$this->command['sucht'],	
					"suib" =>	$this->command['suib'],
					"x" => rand(100, 110),
					"y" => rand(12 , 22),
					"zz" =>	$this->param["zz"]
				);
		
				$headers = array('Content-Type: application/x-www-form-urlencoded');
				$content = $this->getHTTPContent('http://www.web-amor.de/suchergebn.php', $this->searchIndex, $cookiePath, $search_arr, $headers);
				
				// What ???
				if($html = str_get_html($content)){
					$nodes = $html->find(".schrift2");
					$num = array();
					foreach ($nodes as $node) {
						if($node->innertext != "Entfernung" && $node->innertext != "Name" && $node->innertext != "Ort" &&  $node->innertext != "Alter" && $node->innertext != "zuletzt hier" && $node->innertext != "als Liste"){
							$num[] = $node->innertext;					
						}
					}
				}
				
				$result = $this->getMembersFromSearchResult($username, $content);
				if(count($result)) {
						
					$this->savelog('Found '.count($result). ' Users on Page '.$page);
					foreach($result as $mid => $item){
									
						if($this->command['version'] == 2) {	
							// Visit Profile
							$this->work_visitProfile($username, $item);	
						}
						
						$this->work_sendMessage($username, $item, $cookiePath);	
					}
				}
									
					
				if($this->command['version'] == 2) {	
					
					// Get Inbox Message and Sent Message response
					$inbox = $this->getInboxMessages($username, $cookiePath);
					
					if(is_array($inbox))
					{
						$this->savelog("Found ".count($inbox)." inbox message(s)");
						$this->sleep(5);
									
						if(count($inbox)>=$this->inboxLimit)
						{
							foreach($inbox as $key => $item) {
								$this->work_sendMessage($username, $item, $cookiePath);
							}
							$this->deleteAllOutboxMessages($username, $cookiePath);
						}
					}
				}
				
				// go to one of the profiles in search result, not in sent database
				// send gustbook message
				// save sent message with username in database
				$page++;

			} while(count($list)>=$this->searchResultsPerPage);
			
			sleep(10);
		}
	}

	// public function work()
	// {		
		// $this->savelog("Job started, bot version ".$this->command['version']);
		// $username = $this->loginArr[$this->currentUser][$this->usernameField];
		// $cookiePath = $this->getCookiePath($username);
		// list($subject, $message)=$this->getMessage($this->newMessage);
		// $this->newMessage = false;
// 
		// if($this->command['send_test'] == 1){
			// $this->savelog("Test Send Message.");
			// $item = array(
				// "username" => $this->command['receive'][0]['username'],
				// "link" => "profile.php?id=".$this->command['receive'][0]['userid'],
				// "gender" => ""
			// );
// 			
			// $this->sendTestMessage($username, $item, $cookiePath);
			// exit;
		// }
// 				
		// /*******************************/
		// /****** Go to search page ******/
		// /*******************************/
// 
		// $this->savelog("Go to SEARCH page.");
//         
		// $headers = array('Content-Type: application/x-www-form-urlencoded');
		// $content = $this->getHTTPContent($this->searchIndex, $this->indexURL, $cookiePath, $this->param, $headers);
		// $this->savelog("Go to Pages: 1");
// 
		// $search_arr = array(
			// "abis" =>	$this->command['ageEnd'],
			// "avon" =>	$this->command['ageStart'],
			// "bene" =>	$username,
			// "bld" => "",	
			// "blsu" =>	$this->command['blsu'],
			// "enplz" =>	"03185",
			// "entfern" => "alle km",
			// "etid" =>	$this->param["etid"],
			// "gadb" =>	"gadb",
			// "nick" =>	"",
			// "nick2" =>	"",
			// "onl" =>	$this->command['onl'],
			// "plz" =>	"",
			// "sf" =>	"szh",
			// "sf1" =>	"",
			// "sff" =>	"als Liste",
			// "snae" =>	1,
			// "stadt" =>	"",
			// "sucht" =>	$this->command['sucht'],	
			// "suib" =>	$this->command['suib'],
			// "zz" =>	$this->param["zz"]
		// );
// 
		// $headers = array('Content-Type: application/x-www-form-urlencoded');
		// $content = $this->getHTTPContent($this->searchURL, $this->searchIndex, $cookiePath, $search_arr, $headers);
// 		
		// if($html = str_get_html($content)){
// 					
			// $nodes = $html->find(".schrift2");
			// $num = array();
			// foreach ($nodes as $node) {
				// if($node->innertext != "Entfernung" && $node->innertext != "Name" && $node->innertext != "Ort" &&  $node->innertext != "Alter" && $node->innertext != "zuletzt hier" && $node->innertext != "als Liste"){
					// $num[] = $node->innertext;					
				// }
			// }
		// }
// 
		// $result = $this->getMembersFromSearchResult($username, $content);
// 		
		// foreach($result as $mid => $item){
// 						
			// if($this->command['version']==1){
				// $this->work_sendMessage($username, $item, $cookiePath);						
			// }
			// elseif($this->command['version']==2)
			// {
				// $this->work_visitProfile($username, $item);				
			// }
			// else
			// {
				// $this->savelog("Wrong version selected.");
			// }
// 
		// }
// 		
		// if($this->command['version']==1)
		// {
			// $this->deleteAllOutboxMessages($username, $cookiePath);			
		// }				
		// elseif($this->command['version']==2)
		// {
			// $inbox = $this->getInboxMessages($username, $cookiePath);
// 			
			// if(is_array($inbox))
			// {
				// $this->savelog("Found ".count($inbox)." inbox message(s)");
				// $this->sleep(5);
// 							
				// if(count($inbox)>=$this->inboxLimit)
				// {
					// foreach($inbox as $key => $item)
					// {
						// $sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
// 						
						// //If in runnig time period
						// if($sleep_time==0)
						// {
							// if(!$this->work_sendMessage($username, $item, $cookiePath))
								// return false;
						// }
						// else
						// {
							// $this->savelog("Not in running time period.");
							// $this->sleep($sleep_time);
								// return true;
						// }
					// }
// 										
					// $this->deleteAllOutboxMessages($username, $cookiePath);
				// }
			// }
		// }
// 
		// //Pages 2
// 		
		  // if($num >= 2){
// 			
			// for($i=2;$i<= max($num);$i++){
// 
				// $this->savelog("Go to Pages: ". $i);
// 
				// $search_arr = array(
					// "abis" =>	$this->command['ageEnd'],
					// "avon" =>	$this->command['ageStart'],
					// "bene" =>	$username,
					// "bld" => "",	
					// "blsu" =>	$this->command['blsu'],
					// "enplz" =>	"03185",
					// "entfern" => "alle km",
					// "etid" =>	$this->param["etid"],
					// "gadb" =>	"gadb",
					// "nick" =>	"",
					// "nick2" =>	"",
					// "onl" =>	$this->command['onl'],
					// "plz" =>	"",
					// "sf" =>	"szh",
					// "sf1" => "",
					// "sff" => "als Liste",
					// "snae" =>	$i,
					// "stadt" =>	"",
					// "sucht" =>	$this->command['sucht'],			
					// "suib" =>	$this->command['suib'],
					// "zz" =>	$this->param["zz"]
				// );
// 
				// $headers = array('Content-Type: application/x-www-form-urlencoded');
				// $content = $this->getHTTPContent($this->searchURL, $this->searchIndex, $cookiePath, $search_arr, $headers);						
// 
				// $result = $this->getMembersFromSearchResult($username, $content);
// 				
				// foreach($result as $mid => $item){
// 						
					// if($this->command['version']==1){
						// $this->work_sendMessage($username, $item, $cookiePath);						
					// }
					// elseif($this->command['version']==2)
					// {
						// $this->work_visitProfile($username, $item);						
					// }
					// else
					// {
						// $this->savelog("Wrong version selected.");
					// }
				// }
// 
				// if($this->command['version']==1)
				// {
					// $this->deleteAllOutboxMessages($username, $cookiePath);			
				// }				
				// elseif($this->command['version']==2)
				// {
					// $inbox = $this->getInboxMessages($username, $cookiePath);
// 					
					// if(is_array($inbox))
					// {
						// $this->savelog("Found ".count($inbox)." inbox message(s)");
						// $this->sleep(5);
// 									
						// if(count($inbox)>=$this->inboxLimit)
						// {
							// foreach($inbox as $key => $item)
							// {
								// $sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
// 								
								// //If in runnig time period
								// if($sleep_time==0)
								// {
									// if(!$this->work_sendMessage($username, $item, $cookiePath))
										// return false;
								// }
								// else
								// {
									// $this->savelog("Not in running time period.");
									// $this->sleep($sleep_time);
										// return true;
								// }
							// }
// 												
							// $this->deleteAllOutboxMessages($username, $cookiePath);
						// }
					// }
				// }
			// }
		  // }
// 
		// $this->savelog("Job completed.");
		// return true;
	// }

	private function getMembersFromSearchResult($username, $content){
		$list = array();
		
		$loop = true;
		$count = 0;

		while($loop == true){
					
			if(strpos($content, 'ben['.$count.']')!==false)
			{
				$c_name = substr($content,strpos($content,"ben[".$count."]='"));
				$c_name = substr($c_name,0,strpos($c_name,"icb[".$count."]"));
				$vowels = array("';","ben[".$count."]='","jti[".$count."]=",";","icb[".$count."]='");
				$c_name = str_replace($vowels,"",utf8_decode($c_name));
				
				$tid = substr($content,strpos($content,"jti[".$count."]="));
				$tid = substr($tid,0,strpos($tid,"upd[".$count."]"));
				$tid = trim(str_replace($vowels,"",$tid));

				$icb = substr($content,strpos($content,"icb[".$count."]='"));
				$icb = substr($icb,0,strpos($icb,"bild[".$count."]="));
				$icb = trim(str_replace($vowels,"",$icb));

				array_push($list, 
					array(
						"username" => trim($c_name),
						"gender" => $icb,
						"tid" => $tid
					)
				);

			}else{
				$loop = false;
			}

			$count++;
		}	
		var_dump($list);
		return $list;
	}
	
	private function work_vote($item,$cookiePath){
		$this->savelog("Go to vote user: ".$item["username"]);
		$content = $this->getHTTPContent($this->indexURL."/".$item["username"]."?vote", $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	public function getAction(){
		return $this->command['action'];
	}

	public function getSiteID(){
		return $this->siteID;
	}

	public function checkLogin($username, $password){
		
		$this->loginArr = array();
		$this->addLoginData(array(array("username"=>$username, "password"=>$password)));
		$this->currentUser=0;
		$cookiePath = $this->getCookiePath($username);
		$content = $this->getHTTPContent($this->indexURL, $this->indexURL, $cookiePath);

		if(!($this->isLoggedIn($username,$content)))
		{
			$this->savelog("This profile: ".$username." does not log in.");
			// count try to login
			for($count_login=1; $count_login<=$this->loginRetry; $count_login++)
			{
				if($this->tor_new_identity($this->proxy_ip,$this->proxy_control_port,'bot'))
					$this->savelog("New Tor Identity request completed.");
				else
					$this->savelog("New Tor Identity request failed.");

				$this->savelog("Logging in.");
				$content = $this->getHTTPContent($this->loginURL, $this->loginRefererURL, $cookiePath, $this->loginArr[$this->currentUser]);	
				file_put_contents("login/".$username."-".date("YmdHis").".html",$content);

				if(!($this->isLoggedIn($username,$content)))
				{
					$this->savelog("Log in failed with profile: ".$username);
					$this->savelog("Log in failed $count_login times.");

					if($count_login>($this->loginRetry-1))
					{
						return false;
					}
					else
					{
						$sleep_time = 120; // 2 mins

						$this->savelog("Sleep after log in failed for ". $this->secondToTextTime($sleep_time));
						$this->sleep($sleep_time);
					}
				}
				else
				{
					$this->savelog("Logged in with profile: ".$username);
					return true;
				}
			}
		}
		else
		{
			$this->savelog("This profile: ".$username." has been logged in.");
			return true;
		}
	}

	private function work_visitProfile($username, $item)
	{
		$cookiePath = $this->getCookiePath($username);
		$this->savelog("Go to profile page: ".$item["username"]);
		$param = array(
			"ben" => trim($item["username"]),
			"bene" => $username,
			"etid" => $this->param["etid"],
			"zz" =>	$this->param["zz"]
			);
		
		$headers = array('Content-Type: application/x-www-form-urlencoded');
		$content = $this->getHTTPContent("http://www.web-amor.de/mitglied.php", "http://www.web-amor.de/suchergebn.php", $cookiePath, $param, $headers);		
		$this->sleep(5);
		return $content;
	}

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
	}

	private function getMessage($new=true)
	{
		if($new)
		{
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$this->currentSubject = rand(0,count($this->command['messages'])-1);
			$this->currentMessage = rand(0,count($this->command['messages'])-1);

			$subject = $this->command['messages'][$this->currentSubject]['subject'];
			$message = $this->command['messages'][$this->currentMessage]['message'];

			$this->message = "";
			for($i=1; $i<=$this->totalPart; $i++)
			{
				$this->message .= $this->getMessagePart($i)." ";
				if($i == ($this->totalPart/2))
				{
					$this->message .= $message." ";
				}
			}
			$this->subject=$subject;
		}
		return array($this->subject, $this->message);
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		
		$return = true;
		// If not already sent
		if(!$this->isAlreadySent($item["username"]) || $enableMoreThanOneMessage)
		{
			if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
			{
				//RANDOM SUBJECT AND MESSAGE
				$this->savelog("Random new subject and message");
				$this->currentSubject = rand(0,count($this->command['messages'])-1);
				$this->currentMessage = rand(0,count($this->command['messages'])-1);

				//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
				$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
				$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);
			}
			else
			{
				list($subject, $message)=$this->getMessage($this->newMessage);
			}

			$this->savelog("Message is : ".utf8_decode($message));

			// Go to profile page
				$content = $this->work_visitProfile($username, $item);
				
				//$links = substr($content,strpos($content,'<input name="abu" value="abu" type="hidden">'));
				//$links = substr($links,0,strpos($links,"'nase','height=450,width=600"));
	
				$zf1 = substr($content,strpos($content,"'".$username."'"));
				$zf1 = substr($zf1,0,strpos($zf1,"</script>"));

				$zf1 = substr($zf1,strpos($zf1,"naz[68]="));
				$zf1 = substr($zf1,0,strpos($zf1,";"));
				$vowels = array(";","naz[68]=");
				$zf1 = str_replace($vowels,"",$zf1);							

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item["username"]);
			if($this->reserveUser($item["username"]))
			{				
				/////// Go to send message page ////////

				$this->savelog("Go to send message page: ".$item["username"]);

				if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
					$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
				}

				$this->savelog("Sending message to ".$item["username"]);
				if(!$this->isAlreadySent($item["username"]) || $enableMoreThanOneMessage)
				{
					
					if($this->command['msg_type']=="pm")
					{
						
						$content = $this->getHTTPContent("http://www.web-amor.de/nachri.php?tid=".$item["tid"]."&etid=".$this->param["etid"]."&zz=".$this->param["zz"]."&zf1=".$zf1."&ben=".$item["username"]."&bene=".$username, "http://www.web-amor.de/mitglied.php", $cookiePath);
						
						$param = array();

						if($html = str_get_html($content)){
				
							$nodes = $html->find("input[type=hidden]");

							foreach ($nodes as $node) {	
								if($node->name == "bbe"){
									$param[$node->name] = trim($username);
								}elseif($node->name == "nachrss"){
									$param[$node->name] = utf8_decode($message);
								}else{
									$param[$node->name] = trim($node->value);
								}
							}
						}	
						

						$headers = array('Content-Type: application/x-www-form-urlencoded');
						$content = $this->getHTTPContent("http://www.web-amor.de/nachr.php", "http://www.web-amor.de/mitglied.php", $cookiePath, $param, $headers);
						
						if(strpos($content, 'Nachricht an')!==false)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$this->sendmsg_total++;
							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
						}
						else
						{
							$this->newMessage = true;
							$this->savelog("Sending message failed.");
							$json = json_decode($content);
							if(isset($json->fail))
								$this->savelog("Fail: ".$json->fail);
							$this->lastSentTime = time();
							$return = true;
						}

					}elseif($this->command['msg_type']=="gb"){
						
						$this->savelog("Go to sign guestbook page: ".$item['username']);

						$param = array(
							"ben" => $item["username"],
							"bene" => $username,
							"egl" => $item["gender"],
							"etid" => $this->param["etid"],
							"gbein" => utf8_decode($message),
							"gzz" => $zf1,
							"tid" => $item["tid"],
							"zz" =>	$this->param["zz"]
						);

						if(time() < ($this->lastSentTime + $this->messageSendingInterval))
							$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
						
						$this->savelog("Signing guestbook to ".$item['username']);

						$headers = array('Content-Type: application/x-www-form-urlencoded');
						$content = $this->getHTTPContent("http://www.web-amor.de/gbspei.php", "http://www.web-amor.de/gbeintr1.html", $cookiePath, $param, $headers);
						file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);
						
						if(strpos($content, 'INSERT INTO gbuch SET gbanwen')!==false)
						{
							$this->newMessage=true;
							$this->savelog("Signing guestbook completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$return = true;
							$this->sendmsg_total++;
						}else{
							$this->newMessage=false;
							$this->savelog("Signing guestbook failed.");												
							$this->lastSentTime = time();
							$return = true;
						}
					}

				}
				else
				{
					$this->newMessage=false;
					$this->cancelReservedUser($item["username"]);
					$this->savelog("Sending message failed. This profile reserved by other bot: ".$item["username"]);
					$return = true;
				}				
			
				$this->cancelReservedUser($item["username"]);
				$this->sleep(2);
				
				/* Logout after send x message completed */
				if($this->command['logout_after_sent'] == "Y"){
					if($this->sendmsg_total >= $this->command['messages_logout']){
						$this->sendmsg_total = 0;
						$this->logout();
						$this->savelog('Logout after sent '.$this->command['messages_logout'].' messages(s) completed');
						$this->savelog("Get a new Profile for Send Message");
						$this->getNewProfile();
						$this->sleep(($this->command['wait_for_login']*60));
						$this->login();
						$this->work();
					}
				}
				/* End of logout */
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item["username"]);
			$return = true;
		}
		return $return;
	}

	private function sendTestMessage($username, $item, $cookiePath){
		
		if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
		{
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$this->currentSubject = rand(0,count($this->command['messages'])-1);
			$this->currentMessage = rand(0,count($this->command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
			$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);
		}
		else
		{
			list($subject, $message)=$this->getMessage($this->newMessage);
		}

		$this->savelog("Message is : ".utf8_decode($message));

		// Go to profile page
		$content = $this->work_visitProfile($username, $item, $cookiePath);

		$this->savelog("Go to send message page: ".$item["username"]);

		if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
			$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
		}

		$this->savelog("Sending message to ".$item["username"]);
		
		$id = str_replace("profile.php?id=","",$item["link"]);	
		$param = array(
			"title" => $subject,
			"message" => $message,
			"receiver" => $id,
			"submit" => "abschicken"
		);

		$content = $this->getHTTPContent($this->indexURL."/messages.php?toDo=write&action=new&id=".$id."&utype=0", $this->searchIndex, $cookiePath,$param);						
		$url_log = "URL => ".$this->messageURL."\nREFERER => ".$this->messageURL."\n";
		file_put_contents("sending/pm-".$username."-".$item["username"].".html",$url_log.$content);

		if(strpos($content, 'Deine Flirtmail wurde erfolgreich gesendet') !== false)
		{
			$this->newMessage=true;
			$this->savelog("Sending message completed.");
			//DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			$this->lastSentTime = time();
							
			$return = true;
		}
		else
		{
			$this->newMessage=false;
			$this->savelog("Sending message failed.");							
			$this->lastSentTime = time();
			$return = true;
		}

		return $return;
	}

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->databaseName."_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->databaseName."_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
			DBConnect::execute_q($sql);			
			return true;
		}
		elseif($server==$this->botID)
		{
			$this->savelog("Already Reserved: ".$username);
			return true;
		}
		else
		{
			$this->savelog("Already Reserved by other bot: ".$username);
			return false;
		}
	}

	private function cancelReservedUser($username)
	{
		DBConnect::execute_q("DELETE FROM ".$this->databaseName."_reservation WHERE username='".$username."' AND server=".$this->botID);
	}

	private function deleteAllOutboxMessages($username, $cookiePath)
	{
		$pages = 0;		
		$content = $this->getHTTPContent("http://www.web-amor.de/ssend.php?etid=".$this->param["etid"]."&snae=1&esv=svom&zz=".$this->param["zz"], "http://www.web-amor.de/postfach.php", $cookiePath);
		

		$loop = true;
		$count = 1;

		while($loop == true){

			if(strpos($content, 'parent.nd['.$count.']') !== false)
			{
				
				$content = substr($content,strpos($content,"parent.nd[".$count."]="));
				$content = substr($content,0,strpos($content,"parent.vw"));				
				$vowels = array(";","parent.nd[".$count."]=");
				$content = trim(str_replace($vowels,"",$content));
				$content = $this->getHTTPContent("http://www.web-amor.de/slosch.php?gls=".$content.",&etid=".$this->param["etid"]."&snae=1&esv=svom&zz=".$this->param["zz"], "http://www.web-amor.de/postfach.php", $cookiePath);
		
			}else{
				$loop = false;
			}

			$count ++;

		}
	}

	private function getOutboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving outbox messages.");
		$content = $this->getHTTPContent($this->outboxURL, $this->indexURL, $cookiePath);
		
		$content = substr($content, strpos($content, '<ul id="messages"'));
		$content = substr($content, 0, strpos($content, '</ul>')+5);
		$parser = $this->convertToXML($username, "outbox", $content);

		if(isset($parser->document->ul[0]) && isset($parser->document->ul[0]->li))
		{
			foreach($parser->document->ul[0]->li as $item)
			{
				$message = array(
					"message" => str_replace("checkbox_","",$item->input[0]->tagAttrs['id'])
				);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteOutboxURL."?".http_build_query($delete_arr), $this->deleteOutboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}

	private function getInboxMessages($username, $cookiePath)
	{

		$list = array();
		$this->savelog("Receiving inbox messages.");
		
		$param = array(
			 "etid" => $this->param["etid"],
			 "zz" => $this->param["zz"]
			);

		$headers = array('Content-Type: application/x-www-form-urlencoded');
		$content = $this->getHTTPContent("http://www.web-amor.de/postfach.php", "http://www.web-amor.de", $cookiePath, $param, $headers);
		
		$pages = 1;
		$endloop = false;		

		while($endloop == false){	
			
			$content = $this->getHTTPContent("http://www.web-amor.de/sempf.php?etid=".$this->param["etid"]."&snae=".$pages."&esv=svom&zz=".$this->param["zz"], "http://www.web-amor.de/postfach.php", $cookiePath);			

			if(strpos($content, "vw[1]") !== false){
				$num = 1;
				$loop_num = false;

				while($loop_num == false){
					if(strpos($content, "vw[".$num."]") !== false){						
						$profile = substr($content, strpos($content, "vw[".$num."]"));
						$profile = substr($profile, 0, strpos($profile, "gl[".$num."]"));
						
						$vowels = array("parent.","vw[".$num."]='","';");
						$profile = trim(str_replace($vowels,"",$profile));
						
						if($profile != "webamor"){
							
							//$c2 = $this->work_visitProfile($username, array("username" => $profile));
							$param = array(
								"ben" => $profile,
								"bene" => $username,
								"etid" => $this->param["etid"],
								"zz" =>	$this->param["zz"]
								);
							
							$cookiePath = $this->getCookiePath($username);
							$headers = array('Content-Type: application/x-www-form-urlencoded');
							$c2 = $this->getHTTPContent("http://www.web-amor.de/mitglied.php", "http://www.web-amor.de/suchergebn.php", $cookiePath, $param, $headers);
							
							$p2 = substr($c2, strpos($c2, '<form name="allgz" method="POST" action="http://www.web-amor.de/mitglied.php">'));
							$p2 = substr($p2, 0, strpos($p2, '</form>'));
							
							$g2 = substr($c2, strpos($c2, "g[2]='"));
							$g2 = substr($g2, 0, strpos($g2, "naz[68]"));
							$vowels = array("parent.","vw[".$num."]='","';","g[2]='");
							$g2 = trim(str_replace($vowels,"",$g2));

							if($html = str_get_html($p2)){
					
								$nodes = $html->find("input[type=hidden]");

								foreach ($nodes as $node) {	
									if($node->name == "tid"){
										$tid = trim($node->value);
									}
								}
							}

							array_push($list,
								array(
									"username" => $profile,
								    "gender" => $g2,
									"tid" => $tid
								)
							);
						}						
					}else{
						$loop_num = true;
					}
					$num++;
				}
			}else{
				$endloop = true;
			}
			$pages ++;
			
		}
		
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteInboxURL."?".http_build_query($delete_arr), $this->deleteInboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}
	
	public function getNewProfile($forceNew = FALSE) {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$this->savelog("Site ID : ". $this->siteID);
		$fetch = array();
		$this->loginArr = array();
	
		if($this->command['login_by'] == 1 || $forceNew === TRUE ){
			$row = botutil::getNewProfile($this->siteID, $username, $this->command, $this);
			$fetch[0] = $row;
		}else{
			$sql = "select id, username, password from user_profiles where (site_id='".$this->siteID."') AND (status='true') AND (username='".$username."') LIMIT 1";
			$fetch = DBConnect::assoc_query_2D($sql);
		}
		
		if (isset($this->command['debug']) && $this->command['debug'] == 1) {
			$this->savelog(serialize($fetch));
		}
	
		
		if(empty($fetch[0]['username'])) {
			$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!!");
			$this->savelog('FINISHED');
			die();
		} else {
			$this->addLoginData(array(
				array(
					'username' => $fetch[0]['username'],
					'password' => $fetch[0]['password']
				)
			));
			$this->savelog('New profile account is '. $fetch[0]['username']);
			$this->currentUser=0;
		}
	}
	
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {			
			$content = $this->getHTTPContent('http://www.web-amor.de/mitgliedw.php?bene='.$username.'&etid='.$this->param['etid'].'&ben='.$profile.'&zz='.$this->param['zz'], $this->indexURL, $cookiePath);
			if(!strpos($content,'Mitglied hat sich hier abgemeldet')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
?>