<?php
require_once('bot.php');

/*******************************/
/************ TO DO ************/
/*******************************/
/*
- change all URL variables in netlog_de()
- change login post array in addLoginData()
- change search post array in work()
- ...
*/
/*******************************/
/************ / TO DO ************/
/*******************************/

class netlog_de extends bot
{
	public function netlog_de($post)
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
																	"username" => "tiffanyliver",
																	"password" => "thtl19"
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
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"age_from" => 20,
									"age_to" => 20,
									"gender" => "m",
									"country" => "DE",
									"region" => -1,
									"o" => 1,
									//"action" => "search"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 0;
		}

		$this->usernameField = "nickname";
		$this->loginURL = "http://de.netlog.com/go/login";
		$this->loginRefererURL = "http://de.netlog.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://de.netlog.com/go/login/view=loggedout&didlogout=2";
		$this->indexURL = "http://de.netlog.com/";
		$this->indexURLLoggedInKeyword = "/go/login/action=logout";
		$this->searchURL = "http://de.netlog.com/go/ajax/action=getFilterResults";
		$this->searchRefererURL = "http://de.netlog.com/go/search/view=advanced";
		$this->searchResultsPerPage = 35;
		$this->profileURL = "http://de.netlog.com/";
		$this->sendMessageURL = "http://de.netlog.com/go/ajax/comments";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19";
		$this->commandID = $commandID;
		$this->runCount = $runCount;
		$this->siteID = $siteID;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	"action" => "login",
								"target" => "/",
								"remember" => "YES",
								"nickname" => $user['username'],
								"password" => $user['password'],
								"login" => "log in"
								);

			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		//$this->sleep(10);

		$page=1;
		$list=array();
		do
		{
			for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
										"type" => "",
										"q" => "",
										"page" => $page,
										"v" => "g",
										"g" => $this->command['gender'],
										"a" => "c",
										"aa" => $age,
										"az" => $age,
										"c" => $this->command['country'],
										"r" => $this->command['region'],
										"poc" => "",
										"reset" => "Filter zurücksetzen"
									);
				if(isset($this->command['o']))
					$search_arr['o'] = $this->command['o'];
				if(isset($this->command['ol']))
					$search_arr['ol'] = $this->command['ol'];
				if(isset($this->command['p']))
					$search_arr['p'] = $this->command['p'];

				$this->savelog("Search for gender: ".$this->command['gender'].", country: ".$this->command['country'].", age: ".$age.", page ".$page.(isset($this->command['o'])?" [Is now online]":""));
				$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
				file_put_contents("search/".$username."-search-".$page.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $page, $content);

				if(is_array($list))
				{
					$this->savelog("Found ".count($list)." member(s)");
					exit;
					$this->sleep(10);
					foreach($list as $item)
					{
						$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
						//If in runnig time period
						if($sleep_time==0)
						{
							// If not already sent
							if(!DBConnect::retrieve_value("SELECT id FROM netlog_de_sent_messages WHERE to_username='".$item['username']."'"))
							{
								// Go to profile page
								$this->savelog("Go to profile page: ".$item['username']);
								$content = $this->getHTTPContent($this->profileURL.$item['username'], $this->searchRefererURL, $cookiePath);

								// If guestbook enabled
								if(strpos($content, "Sign guestbook")!==false)
								{
									$this->sleep(5);

									/*************************************/
									/***** Go to sign guestbook page *****/
									/*************************************/
									$this->savelog("Go to sign guestbook page: ".$item['username']);
									$content = $this->getHTTPContent($this->profileURL.$item['username']."/guestbook/#writeComment", $this->searchRefererURL, $cookiePath);
									$this->sleep(5);

									/**************************/
									/***** Sign guestbook *****/
									/**************************/
									//RANDOM SUBJECT AND MESSAGE
									$this->savelog("Random new subject and message");
									$this->currentSubject = rand(0,count($this->command['messages'])-1);
									$this->currentMessage = rand(0,count($this->command['messages'])-1);

									//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
									$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
									$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

									$guestbook_arr = array(
															"action" => "addComment",
															"itemID" => $this->getInputValue("itemID", $content),
															"itemDistro" => "en",
															"type" => "GUESTBOOK",
															"ownerUserID" => $this->getInputValue("ownerUserID", $content),
															"quote_nickname" => "",
															"commentView" => "ITEM_ONE",
															"csrftoken_addcomment" => $this->getInputValue("csrftoken_addcomment", $content),
															"message" => $message,
															"postcomment" => "Add message"
															);
									$this->savelog("Signing guestbook: ".$item['username']);
									$content = $this->getHTTPContent($this->sendMessageURL, $this->profileURL.$item['username']."/guestbook/#writeComment", $cookiePath, $guestbook_arr);
									file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);

									if(strpos($content, '<div id="commentList" class="clear">')!==false)
									{
										$this->savelog("Signing guestbook completed.");
									}
									else
									{
										$this->savelog("Signing guestbook failed.");
									}
									DBConnect::execute_q("INSERT INTO netlog_de_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$item['username']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->sleep(2);
								}
								else
								{
									$this->savelog("Profile ".$item['username']." has disabled guestbook signing.");
								}
							}
							else
							{
								$this->savelog("Already sign guestbook for profile: ".$item['username']);
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

				// go to one of the profiles in search result, not in sent database
				// send gustbook message
				// save sent message with username in database

				$page++;
			}
		}
		while(count($list)>=$this->searchResultsPerPage);

		$this->savelog("Job completed.");
		return true;
	}

	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<ul class="profileList clearfix">'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'</ul>')+5);

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->ul[0]))
		{
			foreach($parser->document->ul[0]->li as $item)
			{
				$profile = array(
									"username" => $item->div[0]->a[1]->span[0]->tagData
								);
				array_push($list,$profile);
			}
		}
		return $list;
	}
}
?>