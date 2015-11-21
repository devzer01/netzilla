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
									"ageStart" => 21,
									"ageEnd" => 24,									
									"msg_type" => "pm",
									"send_test" => 0,															
									"version" => 1,
									"target" => "Male", //Male,Female,Gay,Lesbian
                                    "gender" => 0,
									"search" => 2,
									"distance" => 201,
									"affection" => 0,
									"lookfor" => 0,
									"country" => 1,
									"picture" => 1,
									"plz_option" => 2,
									"start_page" => 1,
									//"full_msg" => 1,																
									"action" => "send"
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 54;
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
			$this->searchIndex = "http://www.elflirt.de/ef_search.php?toDo=search&gender=0&agefrom=18&ageto=65&picture=1&utype=0&utype=0";
			$this->searchURL = "http://www.elflirt.de/ef_search.php?toDo=search&utype=0#userlist";
			$this->searchPageURL = "http://joonity.de/profil/suchergebnisse";
			
			$this->searchResultsPerPage = 10;		
		
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
		
		if($this->command['gender'] == 1){
			$target = "Male";
		}elseif($this->command['gender'] == 0){
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
				"x" =>	12,
				"y" =>	13
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

	public function work()
	{		
		$this->savelog("Job started, bot version ".$this->command['version']);
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);
		$this->newMessage = false;

		if($this->command['send_test'] == 1){
			$this->savelog("Test Send Message.");
			$item = array(
				"username" => $this->command['receive'][0]['username'],
				"link" => "profile.php?id=".$this->command['receive'][0]['userid'],
				"gender" => ""
			);
			
			$this->sendTestMessage($username, $item, $cookiePath);
			exit;
		}
				
		/*******************************/
		/****** Go to search page ******/
		/*******************************/

		$this->savelog("Go to SEARCH page.");
        
		$content = $this->getHTTPContent($this->indexURL, $this->indexURL, $cookiePath);	
		
		//Get User ID
			$content = substr($content,strpos($content,'<form name="suchh" method="POST" action="http://www.web-amor.de/suche.php">'));
			$content = substr($content,0,strpos($content,'<form name="zuchat" method="POST" action="http://www.web-amor.de/chat.php">'));	
			
			$param = array();

			if($html = str_get_html($content)){
					
				$nodes = $html->find("input[type=hidden]");

				foreach ($nodes as $node) {	
					$param[$node->name] = $node->value;
				}
			}
		print_r($param);
			$headers = array('Content-Type: application/x-www-form-urlencoded');
			$content = $this->getHTTPContent($this->logoutURL, $this->logoutURL, $cookiePath, $param, $headers);
		echo $content;exit;
		$this->savelog("Job completed.");
		return true;
	}

	private function getMembersFromSearchResult($username, $content, $cookiePath){
		$list = array();
		
		if($html = str_get_html($content)){
					
			$nodes = $html->find("tr");

			foreach ($nodes as $node) {	
				
				$gender = "";
				
				$content = $node->outertext;
				$content = substr($content,strpos($content,'<h3>'));
				$content = substr($content,0,strpos($content,'</h3>'));

				if(strpos($content, 'images/icons/female.png') !== false)
				{
					$gender = "female";
				}else{
					$gender = "male";
				}

				foreach ($node->find("a") as $a) {	

					$members = array();

					if(strpos($a->href, 'profile.php?id') !== false)
					{					
						$members["username"] = trim(str_replace("&nbsp;","",$a->plaintext));
						$members["link"] = $a->href;
						$members["gender"] = $gender;
						array_push($list, $members);
					}
				}
				
			}
			
		}

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

		if(!($this->isLoggedIn($username)))
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
echo $content;exit;
				if(!($this->isLoggedIn($username)))
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

	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item["username"]);
		$content = $this->getHTTPContent($this->indexURL."/".$item["link"], $this->searchRefererURL, $cookiePath);
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
			$content = $this->work_visitProfile($username, $item, $cookiePath);

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
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							
							$return = true;

						}elseif(strpos($content, 'Wir finanzieren') !== false){
							
							$this->savelog("Found capcha");
							$this->sleep(5);
							
							$content=substr($content,strpos($content,'<form action="captcha_break.php" method="POST" name="cpt_break">'));
							$content=substr($content,0,strpos($content,'<div class="footer"></div>'));
							
							$param = array();

							if($html = str_get_html($content)){
				
								$nodes = $html->find("input[type=hidden]");

								foreach ($nodes as $node) {	
									$param[$node->name] = $node->value;
								}
							}
														

							$headers = array('Content-Type: application/x-www-form-urlencoded');
							$content = $this->getHTTPContent($this->indexURL."/captcha_break.php", $this->searchIndex, $cookiePath, $param, $headers);
				
							$id = str_replace("profile.php?id=","",$item["link"]);	
							$param = array(
								"title" => $subject,
								"message" => $message,
								"receiver" => $id,
								"submit" => "abschicken"
							);
							
							$this->savelog("Resend message to ".$item["username"]);

							$content = $this->getHTTPContent($this->indexURL."/messages.php?toDo=write&action=new&id=".$id."&utype=0", $this->searchIndex, $cookiePath,$param);						
							$url_log = "URL => ".$this->messageURL."\nREFERER => ".$this->messageURL."\n";
							file_put_contents("sending/pm-".$username."-".$item["username"].".html",$url_log.$content);

							if(strpos($content, 'Deine Flirtmail wurde erfolgreich gesendet') !== false)
							{
							
								$this->newMessage=true;
								$this->savelog("Sending message completed.");
								DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								
								$return = true;

							}else{

								$this->newMessage = true;
								$this->savelog("Sending message failed.");							
								$this->lastSentTime = time();
								$return = true;

							}
							
						}else{

							$this->newMessage = true;
							$this->savelog("Sending message failed.");							
							$this->lastSentTime = time();
							$return = true;

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

	private function deleteAllOutboxMessages($username, $uid, $cookiePath)
	{
		$pages = 0;		
		$endloop = false;

		while($endloop == false){
			$url = $this->indexURL."/messages_out.php?id=".$uid;			
			$content = $this->getHTTPContent($url, $this->messageURL, $cookiePath);

			if(strpos($content, 'Du hast keine Flirtmails im Postausgang') !== false){
				$endloop = true;
			}else{
				$content = substr($content,strpos($content,'<div class="tablediv">'));
				$content = substr($content,0,strpos($content,'</form>'));
				
				if($html = str_get_html($content)){				
							
					foreach($html->find('input[type=checkbox]') as $checkbox) {					
						
						$headers = array('Content-Type: application/x-www-form-urlencoded');
						$param = array( "checkbox[]" => $checkbox->value);
						$this->savelog("delete out message: ".$checkbox->value);
						$content = $this->getHTTPContent($this->indexURL."/messages_out.php?toDo=delcheckbox&role=1", $this->messageURL, $cookiePath, $param, $headers);
						
					}

				}
			}

			$pages ++;
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

	private function getInboxMessages($username, $uid, $cookiePath)
	{
		// kristin_88
		// profile.php?id=100471

		$list = array();
		$this->savelog("Receiving inbox messages.");
		
		//http://www.elflirt.de/messages.php?utype=0
		//http://www.elflirt.de/messages.php?id=100310&page=1

		$pages = 0;
		$endloop = false;		

		while($endloop == false){	
			
			if($pages > 0){
				$param = "?id=".$uid."&page=".$pages;
			}else{
				$param = "?utype=0";
			}

			$content = $this->getHTTPContent($this->inboxURL.$param, $this->messageURL, $cookiePath);			

			if(strpos($content, 'Du hast momentan <strong>keine Flirtmails</strong> in Deinem Postfach') !== false){
				$endloop = true;
			}else{
				
				$content = substr($content, strpos($content, '<div class="tablediv">'));
				$content = substr($content, 0, strpos($content, '</form>'));

				if($html = str_get_html($content)){				
							
					foreach($html->find('span.small,a') as $nodes) {
						if(strpos($nodes->href, 'profile.php?id') !== false){
							if(strpos($nodes->href, 'profile.php?id') !== false){
								
								$profile = array(
									"username" => $nodes->plaintext,
									"link" => $nodes->href
								);
								array_push($list,$profile);
							}
						}
					}

				}
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
}
?>