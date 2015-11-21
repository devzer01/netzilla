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

class ktosexy extends bot
{
	public function ktosexy($post)
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
																	"username" => "IngaZinser93",
																	"password" => "cizubipoyu"
																	//"username" => "umuller10",
																	//"password" => "sawibaboru"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "Hallo :)"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_logout" => 2,
									"login_by" => 1,
									"runningDays" => 2,
									"messages_per_hour" => 90,
									"within" => 10,
									"logout_after_sent" => "Y",
									"wait_for_login" => 2,												
									"age_from" => 25,
									"age_to" => 63,
									"version" => 1,
									"online" => 0,
									"gender" => "m",
									"msg_type" => "pm",
									"send_test" => 0,
									"umkreis" => 100,
									"start_city" => "München",
									"options" => array(
														//"foto" => "true",
														//"online" => "true",
														//"new" => "true"
														),
									//"action" => "search"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 35;
		}

		$this->token = "";
		$this->usernameField = "username";
		$this->loginURL = "http://www.ktosexy.de/favoriten-stream.php";
		$this->loginRefererURL = "http://www.ktosexy.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.ktosexy.de/logout.php";
		$this->indexURL = "http://www.ktosexy.de/index.php";
		$this->indexURLLoggedInKeyword = "/mails.php";
		
		$this->searchURL = "http://www.ktosexy.de/do.php";
		$this->searchRefererURL = "http://www.ktosexy.de/user.php";
		$this->searchResultsPerPage = 150;
		$this->profileURL = "http://www.ktosexy.de/do.php";
		$this->sendMessageURL = "http://www.ktosexy.de/do.php";
		$this->sendGuestbookURL = "http://www.ktosexy.de/do.php";
		$this->outboxURL = "http://www.ktosexy.de/do_mail.php";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 6.1; rv:17.0) Gecko/20100101 Firefox/17.0";
		$this->commandID = $commandID;
		$this->runCount = $runCount;
		//$this->siteID = $siteID;
		$this->siteID = $siteID;
		$this->botID = $botID;
		
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->count_msg = 0;
		$this->breakloop = false;

		$this->addLoginData($this->command['profiles']);
		$this->timeWorking = 60*$this->command['within'];
		$this->messageSendingInterval = (60*(60*$this->command['within'])) / $this->command['messages_per_hour'];
		$this->cities = array(
								"Berlin", "Hamburg", "München", "Köln", "Frankfurt am Main", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Bremen", "Leipzig", "Dresden", "Hannover", "Nürnberg", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mannheim", "Karlsruhe", "Münster", "Wiesbaden", "Augsburg", "Aachen", "Mönchengladbach", "Gelsenkirchen", "Braunschweig", "Chemnitz", "Krefeld", "Magdeburg", "Freiburg im Breisgau", "Oberhausen", "Lübeck", "Erfurt", "Rostock", "Mainz", "Kassel", "Hagen", "Hamm", "Saarbrücken", "Ludwigshafen am Rhein", "Osnabrück", "Herne", "Oldenburg", "Leverkusen", "Solingen", "Potsdam", "Neuss", "Heidelberg", "Darmstadt", "Paderborn", "Regensburg", "Würzburg", "Ingolstadt", "Heilbronn", "Ulm", "Wolfsburg", "Göttingen", "Pforzheim", "Recklinghausen", "Bottrop", "Fürth", "Bremerhaven", "Reutlingen", "Remscheid", "Koblenz", "Erlangen", "Bergisch Gladbach", "Trier", "Jena", "Moers", "Siegen", "Hildesheim", "Cottbus", "Salzgitter", "Dessau-Roßlau", "Gera", "Görlitz", "Kaiserslautern", "Plauen", "Schwerin", "Wilhelmshafen", "Witten", "Zwickau"
								);
		$this->city_tmp = array();
		$this->city_tmp['Berlin'] = array("lon" => "13.5941279201636","lat" => "52.5441203468715");
		$this->city_tmp['Hamburg'] = array("lon" => "10.2099960288534", "lat" => "53.6199642442417");
		$this->city_tmp['München'] = array("lon" => "11.6007597051586", "lat" => "48.1215281201163");
		$this->city_tmp['Köln'] = array("lon" => "6.95323649210595", "lat" => "50.9225551674677");
		$this->city_tmp['Frankfurt am Main'] = array("lon" => "8.57433723759791", "lat" => "50.1213740466241");
		$this->city_tmp['Stuttgart'] = array("lon" => "9.21061506132984", "lat" => "48.7854294100079");
		$this->city_tmp['Düsseldorf'] = array("lon" => "6.77285093516085", "lat" => "51.2263665950437");
		$this->city_tmp['Dortmund'] = array("lon" => "7.50118293669465", "lat" => "51.5054480087816");
		$this->city_tmp['Essen'] = array("lon" => "7.08800559404098", "lat" => "51.3882305863979");
		$this->city_tmp['Bremen'] = array("lon" => "8.70886487454693", "lat" => "53.1533550358095");
		$this->city_tmp['Leipzig'] = array("lon" => "12.3344688082617", "lat" => "51.3409795431399");
		$this->city_tmp['Dresden'] = array("lon" => "13.7995859170998", "lat" => "51.0175665126765");
		$this->city_tmp['Hannover'] = array("lon" => "9.68994368553092", "lat" => "52.3317759992014");
		$this->city_tmp['Nürnberg'] = array("lon" => "11.098875559782", "lat" => "49.4581144599882");
		$this->city_tmp['Duisburg'] = array("lon" => "6.79569847832406", "lat" => "51.4428653196956");
		$this->city_tmp['Bochum'] = array("lon" => "7.22833274759835", "lat" => "51.4289234226658");
		$this->city_tmp['Wuppertal'] = array("lon" => "7.224029235301", "lat" => "51.2500247788829");
		$this->city_tmp['Bonn'] = array("lon" => "7.17242347894212", "lat" => "50.7420962858041");
		$this->city_tmp['Bielefeld'] = array("lon" => "8.51837708469853", "lat" => "52.0085964565407");
		$this->city_tmp['Mannheim'] = array("lon" => "8.53507339912746", "lat" => "49.4309878457503");
		$this->city_tmp['Karlsruhe'] = array("lon" => "8.47889141753469", "lat" => "48.9934183718416");
		$this->city_tmp['Münster'] = array("lon" => "7.6104239179556", "lat" => "51.9454494348638");
		$this->city_tmp['Wiesbaden'] = array("lon" => "8.23409866553248", "lat" => "50.0605158539295");
		$this->city_tmp['Augsburg'] = array("lon" => "10.8775157428934", "lat" => "48.3951355506071");
		$this->city_tmp['Aachen'] = array("lon" => "6.08777482381591", "lat" => "50.7771714836654");
		$this->city_tmp['Mönchengladbach'] = array("lon" => "6.4707941258135", "lat" => "51.2278729806533");
		$this->city_tmp['Gelsenkirchen'] = array("lon" => "7.08174970144914", "lat" => "51.5593155331453");
		$this->city_tmp['Braunschweig'] = array("lon" => "10.4749927587944", "lat" => "52.3108596506242");
		$this->city_tmp['Chemnitz'] = array("lon" => "12.9369546750296", "lat" => "50.7701145615889");
		$this->city_tmp['Krefeld'] = array("lon" => "6.50741988192233", "lat" => "51.3754107762924");
		$this->city_tmp['Magdeburg'] = array("lon" => "11.6343191362759", "lat" => "52.0895758020018");
		$this->city_tmp['Freiburg im Breisgau'] = array("lon" => "7.81114316076893", "lat" => "48.0223772086392");
		$this->city_tmp['Oberhausen'] = array("lon" => "6.83449025390201", "lat" => "51.474406722268");
		$this->city_tmp['Lübeck'] = array("lon" => "10.737477220216", "lat" => "53.8761363281056");
		$this->city_tmp['Erfurt'] = array("lon" => "11.0386644792733", "lat" => "50.9574741050028");
		$this->city_tmp['Rostock'] = array("lon" => "12.0447794120369", "lat" => "54.1021219475795");
		$this->city_tmp['Mainz'] = array("lon" => "8.23489104992393", "lat" => "49.9799714185744");
		$this->city_tmp['Kassel'] = array("lon" => "9.46315860321241", "lat" => "51.3183475344302");
		$this->city_tmp['Hagen'] = array("lon" => "7.48177199237869", "lat" => "51.3616546683804");
		$this->city_tmp['Hamm'] = array("lon" => "7.83400578477152", "lat" => "51.7163262422842");
		$this->city_tmp['Saarbrücken'] = array("lon" => "6.96585489732232", "lat" => "49.222627271049");
		$this->city_tmp['Ludwigshafen am Rhein'] = array("lon" => "8.43329939023741", "lat" => "49.4793356954453");
		$this->city_tmp['Osnabrück'] = array("lon" => "8.01970758413348", "lat" => "52.31335818412");
		$this->city_tmp['Herne'] = array("lon" => "7.20837125558115", "lat" => "51.5515186342515");
		$this->city_tmp['Oldenburg'] = array("lon" => "8.16549348161633", "lat" => "53.1246564499464");
		$this->city_tmp['Leverkusen'] = array("lon" => "7.04322587650709", "lat" => "51.0754852653995");
		$this->city_tmp['Solingen'] = array("lon" => "7.04154377520159", "lat" => "51.1897099457931");
		$this->city_tmp['Potsdam'] = array("lon" => "13.0087823451167", "lat" => "52.3888375191114");
		$this->city_tmp['Neuss'] = array("lon" => "6.66519971616975", "lat" => "51.2181826816457");
		$this->city_tmp['Heidelberg'] = array("lon" => "8.62844225487409", "lat" => "49.417401814007");
		$this->city_tmp['Darmstadt'] = array("lon" => "8.62871858543462", "lat" => "49.8828182992966");
		$this->city_tmp['Paderborn'] = array("lon" => "8.74883290880765", "lat" => "51.7379560831686");
		$this->city_tmp['Regensburg'] = array("lon" => "12.1499496866685", "lat" => "49.0047153544862");
		$this->city_tmp['Würzburg'] = array("lon" => "9.95548308798997", "lat" => "49.7426894329617");
		$this->city_tmp['Ingolstadt'] = array("lon" => "11.4443219748188", "lat" => "48.7866569926313");
		$this->city_tmp['Heilbronn'] = array("lon" => "9.21565027598595", "lat" => "49.1406908406261");
		$this->city_tmp['Ulm'] = array("lon" => "9.95438844539575", "lat" => "48.4205421379081");
		$this->city_tmp['Wolfsburg'] = array("lon" => "10.8374712874489", "lat" => "52.3833962876959");
		$this->city_tmp['Göttingen'] = array("lon" => "9.98307014046028", "lat" => "51.5690220452944");
		$this->city_tmp['Pforzheim'] = array("lon" => "8.66600144238097", "lat" => "48.8572186882373");
		$this->city_tmp['Recklinghausen'] = array("lon" => "7.1910639169044", "lat" => "51.5711391232255");
		$this->city_tmp['Bottrop'] = array("lon" => "6.96327771352459", "lat" => "51.5230598808213");
		$this->city_tmp['Fürth'] = array("lon" => "10.9965911978345", "lat" => "49.5038465582548");
		$this->city_tmp['Bremerhaven'] = array("lon" => "8.55490896452177", "lat" => "53.5667627576582");
		$this->city_tmp['Reutlingen'] = array("lon" => "9.19097234298789", "lat" => "48.4812072428984");
		$this->city_tmp['Remscheid'] = array("lon" => "7.19740467086028", "lat" => "51.1984254579985");
		$this->city_tmp['Koblenz'] = array("lon" => "7.52670973114586", "lat" => "50.3533900804921");
		$this->city_tmp['Erlangen'] = array("lon" => "11.0070567554197", "lat" => "49.5587473230474");
		$this->city_tmp['Bergisch Gladbach'] = array("lon" => "7.13149239861184", "lat" => "50.9511650879268");
		$this->city_tmp['Trier'] = array("lon" => "6.67476241169769", "lat" => "49.80212678172");
		$this->city_tmp['Jena'] = array("lon" => "11.5991979164673", "lat" => "50.9525831163874");
		$this->city_tmp['Moers'] = array("lon" => "6.65481724522878", "lat" => "51.4650679190879");
		$this->city_tmp['Siegen'] = array("lon" => "7.99219673217902", "lat" => "50.8441054230778");
		$this->city_tmp['Hildesheim'] = array("lon" => "9.95132245434122", "lat" => "52.1479081715605");
		$this->city_tmp['Cottbus'] = array("lon" => "14.3084496695284", "lat" => "51.7386575406352");
		$this->city_tmp['Salzgitter'] = array("lon" => "10.3675638179399", "lat" => "52.1236652957787");
		$this->city_tmp['Gera'] = array("lon" => "12.0430157263032", "lat" => "50.8768343494099");
		$this->city_tmp['Görlitz'] = array("lon" => "14.9841912137216", "lat" => "51.1468640705067");
		$this->city_tmp['Kaiserslautern'] = array("lon" => "7.76943564717589", "lat" => "49.4389045835822");
		$this->city_tmp['Plauen'] = array("lon" => "12.0986247751092", "lat" => "50.4804942293455");
		$this->city_tmp['Schwerin'] = array("lon" => "11.4110439798182", "lat" => "53.6021672992816");
		$this->city_tmp['Witten'] = array("lon" => "7.37262563789524", "lat" => "51.432757277894");
		$this->city_tmp['Zwickau'] = array("lon" => "12.4694099890743", "lat" => "50.6954720638055");


		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		$this->count_member = 0;
		
		if($this->command['gender'] == "m"){
			$target = "Male";
		}elseif($this->command['gender'] == "w"){
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
	
	public function resetPLZ()
	{
		$this->command['start_plz'] = "00000";
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	
				"username" => $user['username'],
				"passwort" => $user['password']
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

	public function work()
	{
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$time_working = time()+$this->timeWorking;
		
		$this->savelog("Start Bot version: ".$this->command['version']);
		
		if($this->command['online'] == 1){			
			$this->savelog("Go to Search Online Page.");
		}else{
			$this->savelog("Go to Search Page.");
		}
		
		$content = $this->getHTTPContent("http://www.ktosexy.de/user.php", "http://www.ktosexy.de/index.php", $cookiePath);
		$this->token = $this->getToken($content);
		$this->count_msg = 0;
		$this->breakloop = false;

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$cities = $this->cities;
			if($key = array_search($this->command['start_city'], $cities))
			{
				$cities = array_slice($cities, $key);
			}

			foreach($cities as $city)
			{				
				$list=array();
				$log_distance = ", City: ".$city.", distance: ".$this->command['umkreis'];
				$latlon = $this->city_tmp[$city];
				if(!$latlon)
				{
					$this->savelog("Unable to get location for ".$city);
					continue;
				}
				
				$sex = ($this->command['gender'] == "m")? "Male" : "Female";
				$this->savelog("Search for gender: ".$sex.", age: ".$age." to ".($age+1).$log_distance);

				$search_option = array(
					"act" => "usersuche",
					"alterbis" => $age+1,
					"altervon" => $age,
					"foto" => "false",
					"lon" => $latlon['lon'],
					"lat" => $latlon['lat'],
					"m" => ($this->command['gender']=="m")?"true":"false",
					"neu" => "false",
					"online" => ($this->command['online']== 1)?"true":"false",
					"tkn" => $this->token,
					"umkreis" => $this->command['umkreis'],
					"username" => "",	
					"w" => ($this->command['gender']=="w")?"true":"false"
				);

				$headers = array("Content-Type:application/x-www-form-urlencoded");
				$content = $this->getHTTPContent("http://www.ktosexy.de/do.php", "http://www.ktosexy.de/user.php", $cookiePath, $search_option, $headers);
				
				$result = $this->getMembersFromSearchResult($content);
				$this->savelog("There were about ".count($result)." members found.");
				
				if(count($result) > 0){
					if($this->command['version']==1){					
								
						foreach($result as $mid => $item){			
							if($this->command['logout_after_sent'] == "Y"){
								if($this->count_msg >= $this->command['messages_logout']){
									break 3;
								}
							}

							$return = $this->work_sendMessage($username, $item, $cookiePath);
							
							if($this->breakloop == true){
								break 3;
							}
						}

					}
					elseif($this->command['version']==2)
					{
						foreach($result as $mid => $item){
							
							$item = array("userid" => "1265681","username" => "EveHilde21");

							//Step :: 1
								$this->savelog("Go to profile page: ".$item['username']);
								$profile_arr = array(
											"tkn" => $this->token,
											"act" => "profil",
											"userid" => $item['userid']
										);

								$content = $this->getHTTPContent($this->profileURL, $this->searchRefererURL, $cookiePath, $profile_arr);
								$this->sleep(5);

							//Step :: 2
								$this->work_first_sendMessage($username, $item, $cookiePath);

							//Step :: 3
								$inbox = $this->getInboxMessages($cookiePath);

								if(is_array($inbox))
								{
									$this->savelog("Found ".count($inbox)." inbox message(s)");
									$this->sleep(5);
														
									foreach($inbox as $key => $v)
									{
										if($this->command['logout_after_sent'] == "Y"){
											if($this->count_msg >= $this->command['messages_logout']){
												break 4;
											}
										}

										$this->work_sendMessage($username, $v, $cookiePath);

										if($this->breakloop == true){
											break 4;
										}
									}
								}
						}
					}
					else
					{
						$this->savelog("Wrong version selected.");
					}
				}
			}
		}

		$this->savelog("Job completed.");
		return true;
	}

	private function getToken($content)
	{
		
		$token = substr($content, strpos($content, "$.ajaxSetup({data: {tkn: '")+26);
		$token = substr($token, 0, strpos($token, "'"));
		$token = stripslashes($token);
		return $token;
	}

	private function getLatLon($username, $token, $city, $cookiePath)
	{
		$cookiePath = $this->getCookiePath($username);

		$result = array();
		$search_arr = array(
				"tkn" => $token,
				"act" => "ort_autocomplete",
				"ort" => $city
		);
		
		$headers = array("X-Requested-With: XMLHttpRequest","Content-Type: application/x-www-form-urlencoded");
		$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr, $headers);
		$content = json_decode($content);

		if(is_array($content) && isset($content[0][4]))
		{
			$result['lat'] = $content[0][4];
			$result['lon'] = $content[0][3];
		}		

		return $result;
	}

	private function getMembersFromSearchResult($content){
		$list = array();
		$content = json_decode($content);

		if(is_array($content) && count($content))
		{
			foreach($content as $item)
			{
				$profile = array(
					"username" => $item[1],
					"userid" => $item[0]
				);
				array_push($list,$profile);
			}
		}

		return $list;
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
	
	private function work_first_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = false;
		// If not already sent
		if(!$this->isAlreadySentFirst($item['userid']) || $enableMoreThanOneMessage)
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send first message: ".$item['username']);
			if($this->reserveUser($item['username'], $item['userid']))
			{
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$profile_arr = array(
							"tkn" => $this->token,
							"act" => "profil",
							"userid" => $item['userid']
						);

				$content = $this->getHTTPContent($this->profileURL, $this->searchRefererURL, $cookiePath, $profile_arr);
				$this->sleep(5);
				
				$subject = "Kurznachricht von '".$item["username"]."'";
				$words = array("hallo","hi","hallo wie gehts?","klpf klopf","sag mal guten tag","schau nu mal so rum","hy","hey");
				$random_key = array_rand($words,8);
				$message = $words[$random_key[0]];
				
				$this->savelog("Message is : ".$message);

					$message_arr = array(
						"tkn" => $this->token,
						"act" => 'mail_send',
						"anid" => $item['userid'],
						"usertext" => $message,
					);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending first message to ".$item['username']);
					
					if(!$this->isAlreadySentFirst($item['userid']) || $enableMoreThanOneMessage)
					{
						$headers = array("Content-Type:application/x-www-form-urlencoded","X-Requested-With:XMLHttpRequest");					

						$content = $this->getHTTPContent("http://www.ktosexy.de/do.php", "http://www.ktosexy.de/mails.php", $cookiePath, $message_arr, $headers);
						file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['userid'].".html",$content);

						$content = json_decode($content);

						if($content->status){
							
							if($content->status == "1")
							{
								$this->savelog("Sending first message completed.");
								DBConnect::execute_q("INSERT INTO ktosexy_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime,first) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW(),'Y')");
								$this->lastSentTime = time();								
								$return = true;
								$this->newMessage = true;
							}

						}else if($content[0]=="red"){
							
							if(strpos(strip_tags($content[1]), "Du kannst maximal 5 neue User pro Tag anschreiben")!==false)
							{
								$this->savelog("You can write a maximum of 5 new users per day");								
								$this->newMessage = false;
								$this->lastSentTime = time();
								$return = false;
								$this->breakloop = true;
							}else{
								if(strpos(strip_tags($content[1]), "Dieser User hat dich auf die Ignore-Liste gesetzt")!==false)
								{
									$this->newMessage = false;
									$this->lastSentTime = time();
									$return = false;
								}
								else
								{
									$this->savelog("This profile '".$username."' is blocked.");								
									$this->newMessage = false;
									$this->lastSentTime = time();
									$return = false;								
								}
							}

						}
						else
						{
							$this->savelog("Sending first message failed.");
							$this->lastSentTime = time();
							$return = false;
							$this->newMessage = false;
						}
					}
					else
					{
						$this->newMessage = false;
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending first message failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}

				$this->cancelReservedUser($item['userid']);
				$this->sleep(5);
			}
		}
		else
		{
			$this->savelog("Already send first message to profile: ".$item['username']);
			$return = true;
		}
		return $return;
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = false;
		// If not already sent
		if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username'], $item['userid']))
			{
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$profile_arr = array(
							"tkn" => $this->token,
							"act" => "profil",
							"userid" => $item['userid']
						);

				$content = $this->getHTTPContent($this->profileURL, $this->searchRefererURL, $cookiePath, $profile_arr);
				$this->sleep(5);
				
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
						"tkn" => $this->token,
						"act" => 'mail_send',
						"anid" => $item['userid'],
						"usertext" => $message,
					);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending message to ".$item['username']);
					
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$headers = array("Content-Type:application/x-www-form-urlencoded","X-Requested-With:XMLHttpRequest");					

						$content = $this->getHTTPContent("http://www.ktosexy.de/do.php", "http://www.ktosexy.de/mails.php", $cookiePath, $message_arr, $headers);
						file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['userid'].".html",$content);

						$content = json_decode($content);

						if($content->status){
							
							if($content->status == "1")
							{
								$this->savelog("Sending message completed.");
								DBConnect::execute_q("INSERT INTO ktosexy_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								$this->count_msg++;
								$return = true;
								$this->newMessage = true;
							}

						}else if($content[0]=="red"){
							
							$this->savelog("Sending first message failed. ".utf8_decode(strip_tags($content[1])));
							
							if(strpos(strip_tags($content[1]), "Du kannst maximal 5 neue User pro Tag anschreiben")!==false)
							{
								$this->savelog("You can write a maximum of 5 new users per day");								
								$this->newMessage = false;
								$this->lastSentTime = time();
								$return = false;
								$this->breakloop = true;
							}else{
								if(strpos(strip_tags($content[1]), "Dieser User hat dich auf die Ignore-Liste gesetzt")!==false)
								{
									$this->newMessage = false;
									$this->lastSentTime = time();
									$return = false;
								}
								else
								{
									$this->savelog("This profile '".$username."' is blocked.");								
									$this->newMessage = false;
									$this->lastSentTime = time();
									$return = false;								
								}
							}

						}
						else
						{
							$this->savelog("Sending message failed.");
							$this->lastSentTime = time();
							$return = false;
							$this->newMessage = false;
						}
					}
					else
					{
						$this->newMessage = false;
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}

				$this->cancelReservedUser($item['userid']);
				$this->sleep(5);
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']);
			$return = true;
		}
		return $return;
	}

	private function sendTestMessage($username, $cookiePath){
		$this->savelog("Sending test message.");
		$profiles = DBConnect::assoc_query_1D("SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".$this->siteID);
		if($this->command['gender']=="maennlich")
		{
			$receiverId			= $profiles['male_id'];
			$receiverUsername	= $profiles['male_user'];
		}
		else
		{
			$receiverId			= $profiles['female_id'];
			$receiverUsername	= $profiles['female_user'];
		}

		if(($receiverId!='') && ($receiverUsername!=''))
		{
			$item = array(	"username" => $receiverUsername,
							"userid" => $receiverId
							);

			$this->work_sendMessage($username, $item, $cookiePath, true);
		}
		else
		{
			$this->savelog("Get test profile failed.");
		}
	}
	
	private function isAlreadySentFirst($userid)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ktosexy_sent_messages WHERE to_userid='".$userid."' and first='Y'");

		if($sent)
			return true;
		else
			return false;
	}

	private function isAlreadySent($userid)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ktosexy_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}
	
	private function getInboxMessages($cookiePath)
	{
		$list = array();

		$content = $this->getHTTPContent("http://www.ktosexy.de/mails.php", "http://www.ktosexy.de/favoriten-stream.php", $cookiePath);
		$content = substr($content, strpos($content, "mailkontakte_daten = (")+22);
		$content = substr($content, 0, strpos($content, ");"));

		$content = json_decode($content);
print_r($content);
		if(is_array($content) && count($content))
		{
			foreach($content as $item)
			{
				$profile = array(
					"username" => $item[3],
					"userid" => $item[2]
				);

				array_push($list,$profile);
			}
		}
		return $list;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ktosexy_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO ktosexy_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
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

	private function cancelReservedUser($userid)
	{
		DBConnect::execute_q("DELETE FROM ktosexy_reservation WHERE userid=".$userid." AND server=".$this->botID);
	}

	private function deleteAllOutboxMessages($username, $cookiePath)
	{
		while($list = $this->getOutboxMessages($username, $cookiePath))
		{
			$this->savelog("Found ".count($list)." outbox messages.");
			foreach($list as $message)
			{
				$this->deleteOutboxMessage($username, $message, $cookiePath);
			}
		}
	}

	private function getOutboxMessages($username, $cookiePath)
	{
		$this->savelog("Receiving outbox messages.");
		$offset = 0;

		$outboxArr = array(
							"act" => "mailkontakte",
							"jsonp_callback" => "jQuery18003338546466547996_1351745261225",
							"_" => time().rand(0,9).rand(0,9).rand(0,9),
							"ab" => $offset
							);
		$outboxURL = $this->outboxURL."?".http_build_query($outboxArr);
		$content = $this->getHTTPContent($outboxURL, $this->outboxURL, $cookiePath);

		$content = substr($content, strpos($content, '['));
		$content = substr($content, 0, strrpos($content, ']')+1); 
		$messages = json_decode($content);

		if(is_array($messages) && count($messages))
			return $messages;
		else
		{
			$this->savelog("No outbox message.");
			return false;
		}
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message[0]);
		$ch = curl_init();
		$delete_arr = array(
								"act" => "delmail_all",
								"id" => $message[0]
							);

		$content = $this->getHTTPContent($this->outboxURL, $this->outboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message[0]." completed.");
		$this->savelog("Deleting contact id: ".$message[0]);

		$delete_arr = array(
								"act" => "delkontakt",
								"id" => $message[0]
							);
		
		$content = $this->getHTTPContent($this->outboxURL, $this->outboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting contact id: ".$message[0]." completed.");
		curl_close($ch);
	}
	
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.ktosexy.de/'.$profile, $this->indexURL, $cookiePath);
			if(strpos($content, $profile)) {
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
