<?php
require_once('bot.php');
require_once('simple_html_dom.php');

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

class planetromeo extends bot
{
	private $sendmsg_total = 0;
	private $_table_prefix = 'planetromeo_';
	private $_searchResultId = 0;
	public $rootDomain = 'https://www.planetromeo.com/';
	public $sendMessageActionURL = 'https://www.planetromeo.com/00000000000000000000000000000000/msg/index.php?action=send&ie7fix=';
	private $nextSearchPage = 'https://www.planetromeo.com/00000000000000000000000000000000/search/index.php?action=showPage&searchType=userDetail&searchResultId=';
	public $_sex = array(
		'0' => 'Man',
		'1' => 'Woman',
		'2' => 'Both'
	);
	public $_message = array(	"Hallo du bist süsss, gefällst mir, bin aber leider nicht mehr hier aber bei",
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
	public function __construct($post)
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
			$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			$this->command = array(
									"profiles" => array(
															array(
																	"username" => "agatha2O14",
																	"password" => "ledig1414A"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "Hallo"
																),
															array(
																	"subject" => "Hallo",
																	"message" => "Hallo"
																)
														),
									"start_h" => 10,
									"start_m" => 00,
									"end_h" => 19,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"age_from" => 52,
									"age_to" => 70,
									"gender" => 1,
									"status" => "all",
									"country" => 101,
									//"action" => "check"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 99;
		}
		$this->usernameField = 'username';
		$this->loginURL = "https://www.planetromeo.com/main/login.php";
		$this->loginActionURL = 'https://www.planetromeo.com';
		$this->loginRefererURL = "https://www.planetromeo.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "https://www.planetromeo.com/00000000000000000000000000000000/main/logout/";
		$this->indexURL = "https://www.planetromeo.com/00000000000000000000000000000000/main/bottom.php";
		$this->indexURLLoggedInKeyword = '/main/logout/';
		$this->searchURL = "https://www.planetromeo.com/00000000000000000000000000000000/search/?action=showForm&searchType=userDetail";
		$this->searchActionURL = 'https://www.planetromeo.com/00000000000000000000000000000000/search/index.php?action=execute&searchType=userDetail&returnTo=';
		$this->searchRefererURL = "https://www.planetromeo.com/";
		$this->searchResultsPerPage = 12;
		$this->profileURL = "https://www.planetromeo.com/00000000000000000000000000000000/auswertung/setcard/index.php?set=";
		//$this->profileURL = "http://www.flirt1.net/search_results.php?display=profile&name=";
		$this->sendMessagePageURL = "https://www.planetromeo.com/00000000000000000000000000000000/msg/?uid=";
		$this->sendMessageURL = "https://www.planetromeo.com/00000000000000000000000000000000/msg/?uid=";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:24.0) Gecko/20100101 Firefox/26.0";
		$this->commandID = $commandID;
		$this->runCount = $runCount;
		$this->botID = $botID;
		$this->siteID = $siteID;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->subject="";
		$this->message="";
		
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		$target = "Female";
		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='".$target."'");
			$this->messagesPartTemp[$i] = array();
		}
		
		parent::bot();
		
		$username = $this->loginArr[0][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$this->getHTTPContent('https://www.planetromeo.com/', $this->rootDomain, $cookiePath);
		$this->getHTTPContent('https://www.planetromeo.com/00000000000000000000000000000000/main/left.php', $this->rootDomain, $cookiePath);
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	
				"PHPSESSID" => "",
				"salt" => "",
				"resX" => "1920",
  				"resY" => "1080",
  				"secure" => "",
  				"initCallSec" => "1",
				"username" => $user['username'],
				"passwort" => $user['password'],
				"status" => "-1",
				"erinnern" => "",
				"jugendfrei" => "",
				"" => "Login"
			);

			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		$this->savelog("Job criterias => Target age: ".$this->command['alter1']." to ".$this->command['alter2'].", page ".$page);
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		if(empty($this->command['age_from'])) {
			$this->command['age_from'] = $this->command['alter1'];
		}
		if(empty($this->command['age_to'])) {
			$this->command['age_to'] = $this->command['alter2'];
		}

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$page=1;
			$list=array();
			$first_username = '';
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
					"alter1" => $age,
					"alter2" => $age,
					"besuch" => 'an',
					"continent" => ((empty($this->command['gewicht'])) ? 1 : $this->command['continent']),
					"country" => ((empty($this->command['country'])) ? 101 : $this->command['country']),
					"area" => ((empty($this->command['area'])) ? 0 : $this->command['area']),
					"displayHobby" => -1,
					"displaySex" => -1,
					"gewicht" => ((empty($this->command['gewicht'])) ? 0 : $this->command['gewicht']),
					"gewicht2" => ((empty($this->command['gewicht2'])) ? 0 : $this->command['gewicht2']),
					"hoehe1" => ((empty($this->command['hoehe1'])) ? 0 : $this->command['hoehe1']),
					"hoehe2" => ((empty($this->command['hoehe2'])) ? 0 : $this->command['hoehe2']),
					"language" => "",
					"lookingFor" => ((empty($this->command['lookingFor'])) ? "For..." : $this->command['lookingFor']),
					"onlinestatus" => ((empty($this->command['onlinestatus'])) ? array(2,6,1,3,4,7,8) : $this->command['onlinestatus']),
					"savedSearchName" => "",
					"search_zodiac" => "0",
					"sortierung" => ((empty($this->command['sortierung'])) ? 'login' : $this->command['sortierung']),
					"stadt" => ((empty($this->command['stadt'])) ? '' : $this->command['stadt']),
					"stichwort" => ((empty($this->command['stichwort'])) ? '' : $this->command['stichwort']),
					"username" => ((empty($this->command['username'])) ? '' : $this->command['username']),
					// "" => "Search users",
					// "f" => (($page-1)*$this->searchResultsPerPage)
				);

				// if(isset($this->command['options']))
				// {
					// foreach($this->command['options'] as $key=>$value)
					// {
						// $search_arr[$key]=$value;
					// }
				// }
				
				/**
				 	PRE-SEARCH
				**/
				$this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath);				
				/**
				 	END PRE SEARCH
				**/

				$this->savelog("Search for Target age: ".$age." to ".$age.", page ".$page);
				if($page != 1) {
					// $content = $this->getHTTPContent($this->nextSearchPage . $this->searchResultId . '&resultPage='. $page, $this->searchURL, $cookiePath, $search_arr);
					
					$content = $this->getHTTPContent('https://www.planetromeo.com/00000000000000000000000000000000/search/index.php?action=showPage&searchType=userDetail&searchResultId=0&resultPage=&resultPage='. $page, $this->searchURL, $cookiePath, $search_arr);
					
				} else {
					$return = $this->getHTTPContent('https://www.planetromeo.com/00000000000000000000000000000000/search/index.php?action=execute&searchType=userDetail&returnTo=', $this->searchURL, $cookiePath, $search_arr, TRUE);
					$content = $return['content'];
				}
				file_put_contents("search/".$username."-search-".$page.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $page, $content);

				if(is_array($list))
				{
					$this->savelog("Found ".count($list)." member(s)");
					if(count($list))
					{
						if($list[0]['username'] == $first_username)
						{
							$list = array();
							break;
						}
						if($page == 1)
						{
							$first_username = $list[0]['username'];
						}

						$this->sleep(5);
						foreach($list as $item)
						{
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								// If not already sent
								if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
								{
									///reserve this user, so no other bot can send msg to
									$this->savelog("Reserving profile to send message: ".$item['username']);
									if($this->reserveUser($item['username']))
									{
										// Go to profile page
										$this->savelog("Go to profile page: ".$item['username']);
										$content = $this->getHTTPContent($this->profileURL.$item['uid'], $this->searchURL."?".http_build_query($search_arr), $cookiePath);
										//$content = $this->getHTTPContent($this->profileURL.$item['username'], $this->searchURL."?".http_build_query($search_arr), $cookiePath);
										$item['userid'] = substr($content, strpos($content, "nachricht_schreiben.php?id=")+27);
										$item['userid'] = substr($item['userid'], 0, strpos($item['userid'], "\""));

										$this->sleep(5);

										/***********************************/
										/***** Go to send message page *****/
										/***********************************/
										$this->savelog("Go to send message page: ".$item['username']);
										$content = $this->getHTTPContent($this->sendMessagePageURL.$item['uid'], $this->profileURL.$item['uid'], $cookiePath);
										$this->sleep(5);

										/************************/
										/***** Send message *****/
										/************************/
										//RANDOM SUBJECT AND MESSAGE
										$this->savelog("Random new subject and message");
										$this->currentSubject = rand(0,count($this->command['messages'])-1);
										$this->currentMessage = rand(0,count($this->_message)-1);

										//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
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

										$this->savelog("Message is : ".$message);
										$message_arr = array(
											"page_from" => $this->searchURL,
											//"page_from" => $this->profileURL.$item['username'],
											"_charset_" => "utf8",
											"xsrfprotect" => "",
											"uid" => $item['uid'],
											"senderId" => "",
											"text" => $message,
											"" => "Send",
											"bestaetigung" => 1,
										);
										/**
											PRE HACK BEFORE SEND MESSAGE FOR PLANETROMEO.COM 
										**/
										$content = $this->getHTTPContent($this->sendMessageURL. $item['uid'],$item['url'], $cookiePath);
										$html = str_get_html($content);

										$message_arr['xsrfprotect'] = $html->find("input[name=xsrfprotect]",0)->value;
										$message_arr['uid']= $html->find("input[name=uid]",0)->value; 
										$message_arr['senderId']= $html->find("input[name=senderId]",0)->value; 

										/**
											END OF HACK
										*/
										
										if(time() < ($this->lastSentTime + $this->messageSendingInterval))
											$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
										$this->savelog("Sending message to ".$item['username']);
										if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
										{
											$content = $this->getHTTPContent($this->sendMessageActionURL, $this->sendMessagePageURL.$item['uid'], $cookiePath, $message_arr);
											file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);

											if(!strpos($content, "Your Message has NOT been delivered!"))
											{
												DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
												$this->savelog("Sending message completed.");
												$this->lastSentTime = time();
												$this->sendmsg_total++;
											}
											else
											{
												$this->savelog("Sending message failed.");
											}
										}
										else
										{
											$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
										}
										$this->cancelReservedUser($item['username']);
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
									$this->savelog("Already send message to profile: ".$item['username']);
								}
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

				// go to one of the profiles in search result, not in sent database
				// send gustbook message
				// save sent message with username in database

				$page++;
			}
			while(count($list)>=$this->searchResultsPerPage);
		}

		$this->savelog("Job completed.");
		return true;
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		$html = str_get_html($content);
		parse_str($html->find('table.searchLayout a',4)->href, $output);
		$this->searchResultId = $output['searchResultId'];
		if(!empty($html)) {
			$i = 0;
			foreach($html->find('td.resHeadline a') as $a) {
				if(($i%4) == 0) {
					$username = $a->plaintext;
					$link = 'http://www.planetromeo.com/00000000000000000000000000000000/'.str_replace('../', '', $a->href);
					$u = parse_url($link);
					parse_str($u['query'],$output);
					if(!empty($username)){
						array_push($list, 
							array(
								"uid" => $output['set'],
								"username" => $username,
								"link" => $link,
								"searchResultId" => $output['searchResultId'],
							)
						);
					}
				}
				$i++;
			}
		}
		var_dump($list);
		return $list;
	}

	public function getAction()
	{
		return $this->command['action'];
	}

	public function getSiteID()
	{
		return $this->siteID;
	}

	public function checkLogin($username, $password)
	{
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

				die(print_r($this->loginArr[$this->currentUser]));
				$this->savelog("Logging in.");
				$content = $this->getHTTPContent($this->loginURL, $this->loginRefererURL, $cookiePath, $this->loginArr[$this->currentUser]);
				file_put_contents("login/".$username."-".date("YmdHis").".html",$content);

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

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->_table_prefix."sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->_table_prefix."reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->_table_prefix."reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM ".$this->_table_prefix."reservation WHERE username='".$username."' AND server=".$this->botID);
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
	
	public function getNewProfile($forceNew = FALSE) {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$this->savelog("Site ID : ". $this->siteID);
		$fetch = array();
	
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
}
?>