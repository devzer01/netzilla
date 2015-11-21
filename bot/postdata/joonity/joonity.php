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

class Joonity extends bot
{
	public function joonity($post)
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
																	"username" => "Agatha19",
																	"password" => "dichzulieben"
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
									"ageStart" => 23,
									"ageEnd" => 50,									
									"msg_type" => "pm",
									"send_test" => 0,									
									"start_city" => "Cardiff",									
									"version" => 1,
									"target" => "Male", //Male,Female,Gay,Lesbian								 
                                    "gender" => 2,//Egal=all,Frau=1,Mann=2
									"online" => 1,
									"order" => "e",
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

		$this->databaseName = "joonity";
		$this->userhash = "";

		//Login
			$this->usernameField = "nick";
			$this->indexURL = "http://joonity.de";
			$this->indexURLLoggedInKeyword = "logout";
			$this->loginURL = "http://joonity.de/profil/login";			
			$this->loginRetry = 3;
			$this->logoutURL = "http://joonity.de/profil/logout";
		
		//Search
			$this->searchIndex = "http://joonity.de/profil/suchen";
			$this->searchURL = "http://joonity.de/profil/suchen";
			$this->searchPageURL = "http://joonity.de/profil/suchergebnisse";
			
			$this->searchResultsPerPage = 20;		
		
		//Message
			$this->maiboxURL = "http://joonity.de/postfach/posteingang";
		
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
			"" => "alle Länder"
		);

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
		
		if($this->command['gender'] == 2){
			$target = "Male";
		}elseif($this->command['gender'] == 1){
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
			$this->savelog("Adding Login Data");
			$cookiePath = $this->getCookiePath($user['username']);
			
			$content = $this->getHTTPContent($this->loginURL, $this->loginURL, $cookiePath);			
			
		    $value = "";

			if($html = str_get_html($content)){
				
				$nodes = $html->find("input[type=hidden]");

				foreach ($nodes as $node) {	
					$value = $node->value;
				}
			}

			$login_arr = array(
				"nick"	=> $user['username'],
				"kennwort"	=> $user['password'],	
				"form"	=> $value,
				"submit" => "login"
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
		$this->newMessage=false;

		if($this->command['send_test'] == 1){
			/*
			$item = array(
				"username" => "elisa1989",
				"link" => "/people/elisa1989"
			);					
							
			$this->sendTestMessage($username, $item, $cookiePath);
			*/
			$this->savelog("Test Send Message.");
			$item = array(
				"username" => $this->command['receive'][0]['username'],
				"link" => "/people/".$this->command['receive'][0]['username'],
			);		
			$this->sendTestMessage($username, $item, $cookiePath);
			exit;
		}

		/*******************************/
		/****** Go to search page ******/
		/*******************************/

		$this->savelog("Go to SEARCH page.");
        
		$content = $this->getHTTPContent($this->searchIndex, $this->searchIndex, $cookiePath);
		
		$such_form = "";
		if($html = str_get_html($content)){
				
			$nodes = $html->find("input[type=hidden]");

			foreach ($nodes as $node) {	
				$such_form = $node->value;
			}
		}
		
		foreach($this->cities as $city_id => $city_name){
			
			$this->savelog("Search user from ".$city_name);
			$this->savelog("Search age: ".$this->command['ageStart']." - ". $this->command['ageEnd']);
			
			$search_arr = array(
					"geburtsdatum_b" => $this->command['ageEnd'],
					"geburtsdatum_v" => $this->command['ageStart'],
					"geschlecht_id" => $this->command['gender'],
					"land_id" => $city_id,	
					"nick" => "",	
					"online" => $this->command['online'],
					"plz" => "",	
					"such_form" => $such_form,
					"suchen" => "suchen",
					"umkreis"
				);

				$headers = array('Content-Type: application/x-www-form-urlencoded');
				$content = $this->getHTTPContent($this->searchURL, $this->searchIndex, $cookiePath, $search_arr, $headers);
				$content = $this->getHTTPContent($this->searchPageURL."?sort=&asc=DESC&count=".$this->searchResultsPerPage."&limit=0&key=chatten+flirten", $this->searchURL, $cookiePath);
				
				if(strpos($content, 'Es wurden keine Mitglieder gefunden')!==false)
				{	
					$this->savelog("No members were found. Please modify your search.");
				}
				else
				{
					$content = substr($content,strpos($content,'von')+4);
					$content = substr($content,0,strpos($content,'Mitglieder'));

					$totalMember = trim($content);
					$pageAmount = ceil($totalMember/$this->searchResultsPerPage);

					$this->savelog("There were about ".$totalMember." members (".$pageAmount." pages) found.");
					
					$result = $this->getMembersFromSearchResult($username, $pageAmount, $content, $cookiePath);
					
					if(count($result) > 0){
						foreach($result as $pid => $item)
						{
							if($this->command['version']==1){
								
								$this->savelog("Sending Message to: ".$item['username']);
								$this->work_sendMessage($username, $item, $cookiePath);								
							}
							elseif($this->command['version']==2) //visit
							{
								$this->work_visitProfile($username, $item, $cookiePath);
							}				
							else
							{
								$this->savelog("Wrong version selected.");
							}
						}

						foreach($result as $pid => $item)
						{

							if($this->command['version']==2)
							{
								$inbox = $this->getInboxMessages($username, $cookiePath);

								if(is_array($inbox))
								{
									$this->savelog("Found ".count($inbox)." inbox message(s)");
									$this->sleep(5);
									
									if(count($inbox)>=$this->inboxLimit)
									{	
										foreach($inbox as $key => $v)
										{
											$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
																			
											//If in runnig time period
											if($sleep_time==0)
											{	
												$this->work_sendMessage($username, $item, $cookiePath);
											}
											else
											{
												$this->savelog("Not in running time period.");
												$this->sleep($sleep_time);
												return true;
											}
										}										
									}
								}
							}
						}
						
					}

				}
		}

		$this->savelog("Job completed.");
		return true;
	}

	private function getMembersFromSearchResult($username, $pageAmount, $content, $cookiePath){
		$list = array();
		
		$limit = 0;

		for($i=0;$i<$pageAmount;$i++){				
			$content = $this->getHTTPContent($this->searchPageURL."?sort=&asc=DESC&count=".$this->searchResultsPerPage."&limit=".$limit."&key=chatten+flirten", $this->searchURL, $cookiePath);
			$limit += $this->searchResultsPerPage;

			if($html = str_get_html($content)){
				
				$links = $html->find("a.getZMBa");

				foreach ($links as $link) {
					$name = str_replace("/people/","",$link->href);
					
					if($name != "joonity Team"){
						$param = array(
							"username" => $name,
							"link" => $link->href
						);

						array_push($list, $param);
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
				//file_put_contents("login/".$username."-".date("YmdHis").".html",$content);

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
		$content = $this->getHTTPContent($this->indexURL."/".$item["username"], $this->searchRefererURL, $cookiePath);
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
					
					$content = $this->getHTTPContent($this->indexURL.$item["link"], $this->searchRefererURL, $cookiePath);
					$content = substr($content,strpos($content,'<div class="profilActionC">'));
					$content = substr($content,0,strpos($content,'Nachricht</a>')+13);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending message to ".$item["username"]);
					if(!$this->isAlreadySent($item["username"]) || $enableMoreThanOneMessage)
					{
						if($html = str_get_html($content)){				
							$nodes = $html->find("a");

							foreach ($nodes as $node) {	
								$urlsendmail = $this->indexURL.$node->href;
								$content = $this->getHTTPContent($urlsendmail, $this->indexURL.$item["link"], $cookiePath);	
								$content = substr($content,strpos($content,'<form name="form_js" method="POST">'));
								$content = substr($content,0,strpos($content,'</form>')+7);

								if($html = str_get_html($content)){
									
									$nodes = $html->find("input");
									$d = array();

									foreach ($nodes as $node) {	
										$d[$node->name] = $node->value;						
									}
								}
								
								$post_arr = array(
									"ausgang" => urlencode(1),
									"form" => urlencode($d["form"]),
									"nachricht" => utf8_decode($message),
									"nachricht_int" => urlencode($d["nachricht_int"]),
									"speichern" => urlencode("Nachricht senden")
								);

								$headers = array('Content-Type: application/x-www-form-urlencoded');
								$content = $this->getHTTPContent($urlsendmail, $urlsendmail, $cookiePath, $post_arr, $headers);
								
								$url_log = "URL => ".$urlsendmail."\nREFERER => ".$urlsendmail."\n";
								file_put_contents("sending/pm-".$username."-".$item["username"].".html",$url_log.$content);

								if(strpos($content, 'Nachricht erfolgreich gesendet')!==false)
								{
									$this->newMessage=true;
									$this->savelog("Sending message completed.");
									DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->lastSentTime = time();

									$return = true;
								}
								else
								{
									$this->newMessage = true;
									$this->savelog("Sending message failed.");							
									$this->lastSentTime = time();
									$return = true;
								}
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
		
		$item["username"] = "elisa1989";
		$item["link"] = "/people/elisa1989";

		$content = $this->getHTTPContent($this->indexURL.$item["link"], $this->searchRefererURL, $cookiePath);
		$content = substr($content,strpos($content,'<div class="profilActionC">'));
		$content = substr($content,0,strpos($content,'Nachricht</a>')+13);

		if($html = str_get_html($content)){				
			$nodes = $html->find("a");

			foreach ($nodes as $node) {	
				$urlsendmail = $this->indexURL.$node->href;
				$content = $this->getHTTPContent($urlsendmail, $this->indexURL.$item["link"], $cookiePath);	
				$content = substr($content,strpos($content,'<form name="form_js" method="POST">'));
				$content = substr($content,0,strpos($content,'</form>')+7);

				if($html = str_get_html($content)){
					
					$nodes = $html->find("input");
                    $d = array();

					foreach ($nodes as $node) {	
						$d[$node->name] = $node->value;						
					}
				}
				
				$post_arr = array(
					"ausgang" => urlencode(1),
					"form" => urlencode($d["form"]),
					"nachricht" => urlencode("freuemich punkt net"),
					"nachricht_int" => urlencode($d["nachricht_int"]),
					"speichern" => urlencode("Nachricht senden")
				);
							
				$headers = array('Content-Type: application/x-www-form-urlencoded');
				$content = $this->getHTTPContent($urlsendmail, $urlsendmail, $cookiePath, $post_arr, $headers);
				echo $content;
			}
		}
		exit;
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

	private function deleteAllOutboxMessages($cookiePath)
	{
		$content = $this->getHTTPContent($this->inboxURL, $this->searchRefererURL, $cookiePath);
		$content = substr($content,strpos($content,'var daten = eval'));
		$content = substr($content,0,strpos($content,'<script type="text/javascript" src="/js/pa.js'));

		$content = substr($content,strpos($content,'[{'));
		$content = substr($content,0,strpos($content,'}]}')+2);

		$json_request = (json_decode($content) != NULL) ? true : false;

		if($json_request == true){
				$msArr = json_decode($content);

				if(count($msArr)>0){
					foreach($msArr as $msNodes => $ms){
						
						$headers = array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8');
						$mailid = "1,".$ms->i.",";
						$param = array(
							"m" => $mailid
						);
						$content = $this->getHTTPContent($this->deleteInboxURL, $this->deleteInboxRefererURL, $cookiePath, $param, $headers);
				}
			}
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
		$content = $this->getHTTPContent($this->maiboxURL, $this->maiboxURL, $cookiePath);
		
		$content2 = substr($content,strpos($content,'<a href="/postfach/posteingang" class="mb5 dpB tdN"><b>'));
		$content2 = substr($content2,0,strpos($content2,'</b> Nachrichten'));
		
		$mailNumber = trim(str_replace('<a href="/postfach/posteingang" class="mb5 dpB tdN"><b>','', $content2));
		$pages = ceil($mailNumber/20);
		$page = 0;

		for($i=1;$i<=$pages;$i++){

			if($page > 0){
				$content = $this->getHTTPContent($this->maiboxURL."?limit=".$page, $this->maiboxURL, $cookiePath);
			}
			
			$content = substr($content,strpos($content,'</thead>')+8);
			$content = substr($content,0,strpos($content,'</table><div style="padding-left:20px;margin-bottom:10px;">Auswahl'));

			if($html = str_get_html($content)){
						
				$nodes = $html->find('tr');
				foreach ($nodes as $node) {
					$nodes2 = $node->find('td',3)->find('a');
					foreach ($nodes2 as $node2) {
						
						$user = str_replace("/people/","",$node2->href);
						$link = $node2->href;
						if($user != "joonity Team"){
							$message = array(
								"username" => $user,
								"link" => $link
							);
							array_push($list,$message);
						}
					}
				}
			}

			$page += 20;
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