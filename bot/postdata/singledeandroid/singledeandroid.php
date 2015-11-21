<?php
require_once('bot.php');
require_once('DBconnect.php');
require_once('simple_html_dom.php');
require_once('pop_lib.php');
require_once('meetone_lib.php');
require_once('pop3.php');


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

class singledeandroid extends bot 
{
	private $_table_prefix = 'singledeandroid_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://api.meetone.com/api/phoneapi.php';
	
	protected $tracker_token = null;
	
	private static $mac_address_vals = array(
			"0", "1", "2", "3", "4", "5", "6", "7",
			"8", "9", "A", "B", "C", "D", "E", "F"
	);
	
	protected $device_id = null;
	
	protected $mac_addr = null;
	
	protected $session = null;
	
	protected $profile = null;
	
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
													'username' => 'katjaheidenreich@gmx.net',
													'password' => 'kunter7bunt'
													),
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
									"start_h" => 8,
									"start_m" => 00,
									"end_h" => 23,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"minAge" => 18, //svon
									"maxAge" => 21, //sbis
									"gender" => 6, //ib
									"pref_gender" => 5,
									"status" => "all",
									"country_code" => 'DE',
									"region_id" => 1364,
									"action" => "send",
									"create_account" => 1
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 138;
		}
		
		$this->usernameField = 'username';
		$this->loginURL = "http://www.fischkopf.de/";
		$this->loginActionURL = "http://www.fischkopf.de/index.php?page=account&aktion=login";
		$this->loginRefererURL = "http://www.fischkopf.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.fischkopf.de/index.php?page=account&aktion=logout";
		$this->indexURL = "http://www.fischkopf.de/index.php?page=account";
		$this->indexURLLoggedInKeyword = 'Logout';
		$this->searchURL = "http://www.fischkopf.de/index.php?page=suchen&sres=1";
		$this->searchActionURL = 'http://www.fischkopf.de/index.php?page=suchen';
		$this->searchRefererURL = "http://www.fischkopf.de/index.php?page=suchen";
		$this->searchResultsPerPage = 10;
		$this->profileURL = "http://www.pof.de/de_viewprofile.aspx?profile_id=";
		$this->sendMessagePageURL = "";
		$this->sendMessageURL = "";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:25.0) Gecko/20100101 Firefox/25.0";
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
		$this->count_msg = 0;
		
		$this->zipcodes = array(
				"1" => array(
						"short" => array(
								"01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089"
						),
						"long" => array(
								"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
						)
				),
				"2" => array(
						"short" => array(
								"1010", "4040", "5020", "6020", "7000", "8010", "9020"
						),
						"long" => array(
								"1010", "4040", "5020", "6020", "7000", "8010", "9020"
						)
				),
				"3" => array(
						"short" => array(
								"8045", "6300", "9000", "3150", "8200", "6023", "9217"
						),
						"long" => array(
								"8045", "6300", "9000", "3150", "8200", "6023", "9217"
						)
				)
		);
		
		$target = "Male";
		if($this->command['gender'] == '2'){
			$target = "Female";
		}

		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='".$target."'");
			$this->messagesPartTemp[$i] = array();
		}
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	
				"username" => $user['username'],
				"password" => $user['password'],
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	function getPopServerFromEmail($username)
	{
		$host = "";
		if (preg_match("/gmx/", $username)) {
			$host = "ssl://pop.gmx.net";
		} else if(preg_match("/outlook|hotmail|live/", $username)) {
			$host = "ssl://pop-mail.outlook.com";
		} else if (preg_match("/web.de/", $username)) {
			$host = "pop3.web.de";
		}
		
		return $host;
	}
	
	function clear_inbox($username, $password) {
		
		$host = $this->getPopServerFromEmail($username);
	
		$res = pop_open($host, 995, $username, $password);
	
		if ($res === false) return false;
		
		$msg_count = pop_messagecount($res);
	
		$this->savelog(sprintf("Found %d Message(s)\n", $msg_count));
	
		for ($i=1; $i<= $msg_count; $i++) {
			pop_delete($res, $i);
			$this->savelog(sprintf("Deleting Message %d", $i));
		}
	
		pop_close($res);
		
		return true;
	}
	
	
	function getActiveLink($username, $password)
	{
		$message = $this->get_message($username, $password, 2);
	
		preg_match('/http:\/\/single.de\/verification\/([^"]+)"/', $message, $matches);
		
		return "http://single.de/verification/" . $matches[1];
	}
	
	
	function get_message($username, $password, $count = 1) {
	
		$host = $this->getPopServerFromEmail($username);
		$res = pop_open($host, 995, $username, $password);
	
		$this->savelog(sprintf("Connected to host \n"));
	
		$message = "";
	
		if (pop_messagecount($res) == $count) {
			$this->savelog("One message found on mail box");
			$message = pop_message($res, $count);
		}
	
		pop_close($res);
	
		return $message;
	
	}
	
	public function getDeviceId()
	{
		if (is_null($this->device_id)) $this->device_id = bin2hex(file_get_contents('/dev/urandom', false, null, 0, 8));
		return $this->device_id;
	}
	
	public function getMacAddr()
	{
		if (is_null($this->mac_addr)) $this->mac_addr = $this->generateMacAddress();
		return $this->mac_addr;
	}
	
	public function generateMacAddress()
	{
		$vals = self::$mac_address_vals;
		if (count($vals) >= 1) {
			$mac = array("00"); // set first two digits manually
			while (count($mac) < 6) {
				shuffle($vals);
				$mac[] = $vals[0] . $vals[1];
			}
			$mac = implode(":", $mac);
		}
		return $mac;
	}
	
	public function initInstall()
	{		
		$this->savelog("Report install to adjust.io");
		
		$url = "https://app.adjust.io/startup";
		$headers = array('Client-SDK: android2.0.1', 'Accept-Language: en');
		$useragent = "de.freenet.singlede 1.2.0 phone sdk android 19 en US normal long high 720 1280";
		$data = "android_id=" . $this->getDeviceId() . "&mac_sha1=" . sha1($this->getMacAddr()) . "&app_token=h75j73f9x7b5&created_at=" . date("c", strtotime("-12 hour")) . "&mac_md5=" . md5($this->getMacAddr()) . "&session_count=1";		
		                    
		$response = $this->getHTTPContent($url, $useragent, $data, $headers);
		
		$json = json_decode($response, true);
		
		$this->tracker_token = $json['tracker_token'];
		
		$this->savelog("Install token received : " . $this->tracker_token);
	}
	
	public function proxyReport()
	{
		$this->savelog("Proxy Report request");
		
		$url = "https://abakus.mobil.freenet.de/cgi-bin/ivw/CP/freenet_mobil/apps/android/single.de/1.2.0/suche/index.html?rd=" . rand(0, 2147483647);
		$headers = array('Client-SDK: android2.0.1', 'Accept-Language: en', '');
		$useragent = "Apache-HttpClient/UNAVAILABLE (java 1.4)";
		$data = "android_id=" . $this->getDeviceId() . "&mac_sha1=" . sha1($this->getMacAddr()) . "&app_token=h75j73f9x7b5&created_at=" . date("c", time()) . "&mac_md5=" . md5($this->getMacAddr()) . "&session_count=1";
		
		$response = $this->getHTTPContent($url, $useragent, $data, $headers, true); //return header

		list($header, $body) = explode("\r\n\r\n", $response, 2);
		
		$headers = explode("\r\n", $header);
		
		foreach ($headers as $header) {
			if (preg_match("/^I:/", $header)) {
				$this->savelog("Proxy Report Response received: " . $header);
			}
		}
		
	}
	
	public function oauth2()
	{
		$this->savelog("oauth2 request");
		
		$url = "https://api.single.de/v2/oauth2/access_tokens";
		$headers = array('Content-type: application/json; charset=UTF-8');
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		$data = '{"grant_type":"client_credentials","client_id":"test_mobile_with_client_credentials","client_secret":"z32u#mfCe6maDQX_ZgTk"}';
		
		$response = $this->getHTTPContent($url, $useragent, $data, $headers); //return header no
		
		$this->savelog($response);
		
		$json = json_decode($response, true);
		
		$this->session = $json;
		
		$this->savelog("access token received " . $response);
		$this->savelog("token expire time " . date("Y-m-d H:i:s", $json['expires']));
	}
	
	public function oauth2user($username, $password)
	{
		$this->savelog("oauth2 user request");
	
		$url = "https://api.single.de/v2/oauth2/access_tokens";
		$headers = array('Content-type: application/json; charset=UTF-8');
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		$data = '{"grant_type":"password","client_id":"test_mobile_client","client_secret":"8H#5D-DjS-f9V#c.js","username":"' . $username . '","password":"' . $password . '"}';
	
		$response = $this->getHTTPContent($url, $useragent, $data, $headers); //return header no
	
		$json = json_decode($response, true);
	
		$this->session = $json;
	
		$this->savelog("access token received " . $response);
		$this->savelog("token expire time " . date("Y-m-d H:i:s", $json['expires']));
	}
	
	public function visitprofile($userid)
	{
		
		$this->savelog("visit profile");
		
		$url = "https://api.single.de/v2/profiles/" . $this->profile['profile']['profile_id'] . "/visits/";
		$headers = array('Content-type: application/json; charset=UTF-8', 'Authorization: Bearer ' . $this->session['access_token']);
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		$data = '{"visited_profile_id":' . $userid . '}';
		
		$response = $this->getHTTPContent($url, $useragent, $data, $headers); //return header no
	}
	
	public function sendMessage($rcpt, $message)
	{
		$this->savelog("sending message");
		
		$url = "https://api.single.de/v2/profiles/" . $this->profile['profile']['profile_id'] . "/messages/";
		$headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $this->session['access_token']);
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		$data = 'author_id=' . $this->profile['profile']['profile_id'] . '&recipient_id=' . $rcpt . '&subject=androidApp&coins=1&body=' . $message;
		
		$response = $this->getHTTPContent($url, $useragent, $data, $headers); //return header no
		
		$json = json_decode($response, true);
		
		$this->savelog("spam level " . $json['message']['spamlevel']);
		
		if (isset($json['message']['recipients'][0]['message_id'])) {
			return true;
		}
		
		if (isset($json['error']) && $json['error_code'] == 401) {
			$this->savelog("Profile Locked");
			return false;
		}
		
		$this->savelog("Error sending message " . $response);
		return false;		
	}

	public function getrandomLocation()
	{
		$handle = new SQLite3("singlede.db");
		$result = $handle->query("SELECT * FROM locationdb WHERE _id >= (abs(random()) % (SELECT max(_id) FROM locationdb)) LIMIT 1");
		return $result->fetchArray(SQLITE3_ASSOC);
	}
	
	public function getLocationFromCityName($city)
	{
		$handle = new SQLite3("singlede.db");
		$result = $handle->query("SELECT * FROM locationdb WHERE name = '" . $city . "' LIMIT 1");
		return $result->fetchArray(SQLITE3_ASSOC);
	}
	
	public function getCityNames()
	{
		$handle = new SQLite3("singlede.db");
		$result = $handle->query("SELECT _id, name FROM locationdb");
		$rows = array();
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$rows[] = $row;
		}
		
		return $rows;
	}
	
	
	public function createProfile($username, $email, $password, $zip, $city, $gender, $pref_gender, $birthday, $age_from, $age_to)
	{
		$this->savelog("create profile request");
		
		$url = "https://api.single.de/v2/profiles/";
		$headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $this->session['access_token']);
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		$data = 'nickname=' . $username . '&email=' . $email . '&password=' . $password . '&zip=' . $zip . '&city=' . $city . '&gender=' . $gender . '&preferred_gender=' . $pref_gender . '&date_of_birth=' . $birthday . '&age_min=' . $age_from . '&age_max=' . $age_to;
		$this->savelog($data);
		$response = $this->getHTTPContent($url, $useragent, $data, $headers); //return header no
		
		$json = json_decode($response, true);
		
		$locale_gender = "Female";
		if ($gender == 5) $locale_gender = "Male";
		
		$this->profile = $json;
		
		if (isset($json['error'])) {
			
			if ($json['error_code'] == 400) {
				$this->savelog($response);
				$this->savelog("Error, email account already has profile, password unknown, adding a disabled profile");
				$sql = "INSERT INTO user_profiles (userid, email, username, password, status, site_id, sex, created_datetime) "
				. "VALUES (0, '" . $email . "', '" . $username . "', '" . $password . "', 'false', " . $this->siteID . ", '" . $locale_gender ."', NOW())";
				DBconnect::execute($sql);
			} else {
				$this->savelog("Error creating profile " . $response);
			}
			return false;
		} else {
			$this->savelog("Profile created, member_id " . $json['profile']['profile_id']);
			$this->user_id = $json['profile']['profile_id'];
			$sql = "INSERT INTO user_profiles (userid, email, username, password, status, site_id, sex, extra, created_datetime) "
			. "VALUES (" . $this->user_id . ", '" . $email . "', '" . $username . "', '" . $password . "', 'true', " . $this->siteID . ", '" . $locale_gender ."', '" . mysql_real_escape_string($response) . "', NOW())";
			$this->savelog($sql);
			DBconnect::execute($sql);
			return true;			
		}
	}
	
	public function ga()
	{
		/**
		 * 03-04 23:41:59.154: D/org.apache.http.wire(1230): >> "GET /collect?ul=en-us&ht=1393994507606&sr=720x1280&a=0&sc=start&aid=de.freenet.singlede&cid=fcb3e6bd-e1ff-451b-8eac-d13fc6a603fe&av=1.2.0&v=1&t=appview&an=Single.de&tid=UA-27344538-5&_u=.nUWlMB&_v=ma1b5&cd=de.freenet.singlede.MainActivity&qt=10516&z=1 HTTP/1.1[EOL]"
03-04 23:41:59.244: D/org.apache.http.wire(1230): >> "User-Agent: GoogleAnalytics/2.0 (Linux; U; Android 4.4.2; en-us; sdk Build/KK)[EOL]"
03-04 23:41:59.294: D/org.apache.http.wire(1230): >> "Host: ssl.google-analytics.com[EOL]"
03-04 23:41:59.384: D/org.apache.http.wire(1230): >> "Content-Length: 0[EOL]"
03-04 23:41:59.404: D/org.apache.http.wire(1230): >> "Connection: Keep-Alive[EOL]"
03-04 23:41:59.644: D/org.apache.http.wire(1230): >> "[EOL]"
03-04 23:41:59.764: D/org.apache.http.wire(1230): << "HTTP/1.1 200 OK[EOL]"
03-04 23:41:59.764: D/org.apache.http.wire(1230): << "Pragma: no-cache[EOL]"
03-04 23:41:59.794: D/org.apache.http.wire(1230): << "Expires: Mon, 07 Aug 1995 23:30:00 GMT[EOL]"
03-04 23:41:59.804: D/org.apache.http.wire(1230): << "Access-Control-Allow-Origin: *[EOL]"
03-04 23:41:59.804: D/org.apache.http.wire(1230): << "Last-Modified: Sun, 17 May 1998 03:00:00 GMT[EOL]"
03-04 23:41:59.804: D/org.apache.http.wire(1230): << "X-Content-Type-Options: nosniff[EOL]"
03-04 23:41:59.834: D/org.apache.http.wire(1230): << "Content-Type: image/gif[EOL]"
03-04 23:41:59.844: D/org.apache.http.wire(1230): << "Date: Thu, 27 Feb 2014 07:35:53 GMT[EOL]"
03-04 23:41:59.844: D/org.apache.http.wire(1230): << "Server: Golfe2[EOL]"
03-04 23:41:59.844: D/org.apache.http.wire(1230): << "Content-Length: 35[EOL]"
03-04 23:41:59.844: D/org.apache.http.wire(1230): << "Cache-Control: private, no-cache, no-cache=Set-Cookie, proxy-revalidate[EOL]"
03-04 23:41:59.844: D/org.apache.http.wire(1230): << "Age: 507966[EOL]"
03-04 23:41:59.884: D/org.apache.http.wire(1230): << "Alternate-Protocol: 443:quic[EOL]"

		 */
	}
	
	
	public function attributes()
	{
		$this->savelog("attribute request");
		
		$url = "https://api.single.de/v2/attributes";
		$headers = array('Authorization: Bearer ' . $this->session['access_token']);
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		//$data = '{"grant_type":"client_credentials","client_id":"test_mobile_with_client_credentials","client_secret":"z32u#mfCe6maDQX_ZgTk"}';
		
		$response = $this->getHTTPContent($url, $useragent, null, $headers); //return header no
		
		$json = json_decode($response, true);
		
		//print_r($json);
		
		$this->savelog("attributes received ");
	}
	
	public function loadProfilesWithImages($gender, $lon, $lat, $page, $agemin, $agemax)
	{
		$this->savelog("load profiles  page " . $page);
		
		$url = "https://api.single.de/v2/profiles/?filter=gender:" . $gender . ";age_min:" . $agemin . ";distance:150;lon:" . $lon . ";age_max:" . $agemax . ";lat:" . $lat . "&limit=32&page=" . $page . "&sort=lastactivity&sort_direction=DESC";
		$this->savelog("Profile URL " . $url);
		$headers = array('Authorization: Bearer ' . $this->session['access_token']);
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		
		$response = $this->getHTTPContent($url, $useragent, null, $headers); //return header no
		
		return json_decode($response, true);
	}
	
	public function loadProfiles()
	{
		$this->savelog("load profiles top singles");
		
		$url = "https://api.single.de/v2/profiles/?filter=age_min:25;gender:6;topsingle:1;lon:13.410991;age_max:45;lat:52.521659&limit=12&page=1&sort=relevance&sort_direction=ASC";
		$headers = array('Authorization: Bearer ' . $this->session['access_token']);
		$useragent = "Single.de/1.2.0 (Linux; U; Android)";
		//$data = '{"grant_type":"client_credentials","client_id":"test_mobile_with_client_credentials","client_secret":"z32u#mfCe6maDQX_ZgTk"}';
		
		$response = $this->getHTTPContent($url, $useragent, null, $headers); //return header no
		
		$json = json_decode($response, true);
		
		print_r($json);
		
		$this->savelog("profiles with images received");		
	}
	
	
	public function startCreateAccount()
	{
		$this->initInstall();
		
		$this->proxyReport();
		
		$this->oauth2();
		
		$this->attributes();
		
		$registry = Registry::getInstance();
		
		if ($registry->get('master') == null) {
			$account = $this->getNextEmailAccount();
			if (empty($account)) {
				$this->savelog("No More Emails Found");
				return false;
			}
			$registry->set('master', $account['id']);
			$this->savelog("Got Main Email Account "  . $account['user']);
		} else {
			if ($this->hasMoreEmailAlias($registry->get('master'))) {
				$account = $this->getNextEmailAliasAccount($registry->get('master'));
				$this->savelog("Alias Found, "  . $account['user'] . " - Alias - " . $account['alias']);
			} else {
				$account = $this->getNextEmailAccount();
				$registry->set('master', $account['id']);
				$this->savelog("No Alias Found, Got New Account "  . $account['user']);
			}
		}
				
		
		
		$this->savelog("Login into mailbox and deleting old entries, this may take a while - [" . $account['user'] . "]");
		
		$ret = $this->clear_inbox($account['user'], $account['password']);
		
		if ($ret === false) {
			$this->removeEmailAccount($account['user']);
			$this->savelog("Email Account had problems, waiting for next");
			return false;
		}
		
		$email = $account['user'];
		if (isset($account['alias'])) {
			$email = $account['alias'];
		}
		
		list($name, $host) = preg_split("/@/", $email, 2);
		
		$dob = getBirthDayFromAge($this->command['minAge'] + 2);
		
		$this->savelog("Generate a random birthday based on age range " . $dob);
		
		$location = $this->getLocationFromCityName($this->command['profile_city']);
		
		//$this->savelog("Picking a random city in Region - " . $city_name);
		
		$ret = $this->createProfile($name, $email, strrev($account['password']), $location['zip'], $location['name'], $this->command['gender'], $this->command['pref_gender'], $dob, 18, 60);
		
		if ($ret) {
			$this->savelog("Account created, Waiting for activation email arrival");
			$this->sleep(120);
			
			$link = $this->getActiveLink($account['user'], $account['password']);
			
			$this->savelog("Got Activation Link " . $link);
			
			$this->performActivation($link, $email, strrev($account['password']));
			
			
		} else {
			$this->savelog("Account creation failed");
		}
		
		return $ret;
		
	}
	
	public function performActivation($link, $user, $password)
	{
		$useragent = "Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19";
		
		$html = $this->getHTTPContent($link, $useragent);
		
		$this->sleep(10);
		
		$obj = str_get_html($html);
		
		$post['callback'] = $obj->find("input[name=callback]", 0)->value;
		$post['world'] = $obj->find("input[name=world]", 0)->value;
		$post['username'] = $user;
		$post['password'] = $password;
		
		$content = $this->getHTTPContent("https://auth.single.de/default/login.php", $useragent, $post);
		
		$this->sleep(10);

		//TODO: add more checks to see if the activation was successful
		
		return true;
	}

	
	public function createAccount($user, $password, $name, $gender, $dob, $country_code, $region_id, $city_id) 
	{
		$year = date("Y", strtotime($dob));
		$month = date("m", strtotime($dob));
		$day = date("d", strtotime($dob));
		$signup = "http://api.meetone.com/api/phoneapi.php?format=json&service=member&action=signUpAccount"
		        . "&sex=$gender&firstName=$name&password=$password&password2=$password&email=$user&dateOfBirth_day=$day&dateOfBirth_month=$month&dateOfBirth_year=$year&countryCode=$country_code&regionID=$region_id&cityID=$city_id&origin=&acceptConditions=1&height=&deviceType=android";
		
		$signup_resp = file_get_contents($signup);
		$json = json_decode($signup_resp, true);
		
		if (isset($json['authorization']) && $json['authorization']['result'] == 'true') {
			$this->session_id = $json['authorization']['sessionId'];
			$this->user_id = $json['memberId'];
			$this->savelog("Created account for : ".$user);
			
			$sql = "INSERT INTO user_profiles (userid, username, password, status, site_id, sex, created_datetime) "
			     . "VALUES (" . $this->user_id . ", '" . $user . "', '" . $password . "', 'true', " . $this->siteID . ", '" . $gender ."', NOW())";
			$this->savelog($sql);
			DBconnect::execute($sql); 
			return true;
		} else {
			if ($json['errors'][0]['code'] == 2002) {
				$this->savelog("Error, email account already has profile, password unknown, adding a disabled profile");
				$sql = "INSERT INTO user_profiles (userid, username, password, status, site_id, sex, created_datetime) "
				. "VALUES (0, '" . $user . "', '" . $password . "', 'false', " . $this->siteID . ", '" . $gender ."', NOW())";
				DBconnect::execute($sql);
				return false;
			} else {
				$this->savelog($signup_resp);
			}
		}
		
		return false;
	}
	
	public function work()
	{

		$this->savelog("Job criterias => Target age: ".((empty($this->command['minAge'])) ? $this->command['minAge'] : $this->command['minAge'])." to ".((empty($this->command['maxAge'])) ? $this->command['maxAge'] : $this->command['maxAge']));
		$this->savelog("Job started.");
		$usernameFrom = $this->profile['profile']['nickname'];

		$page = 1;
		
		for($age=$this->command['minAge']; $age<=$this->command['maxAge']; $age++)
		{	
			
			$this->oauth2user($this->profile['profile']['email'], $this->profile['profile']['password']);
			
			$profiles = $this->loadProfilesWithImages($this->command['pref_gender'], $this->profile['profile']['lon'], $this->profile['profile']['lat'], $page, $age, $age+1);
			
			if (!isset($profiles['profiles'])) {
				$this->savelog("Search rendered no results " . $profiles);
				continue;
			}
			
			$this->savelog("Found ".count($profiles['profiles'])." member(s)");
			
			for ($i=0; $i< count($profiles['profiles']); $i++) {
				
				$member_id = $profiles['profiles'][$i]['profile_id'];
				
				if ($profiles['profiles'][$i]['preferred_gender'] != $this->command['gender']) continue;
				
				if (isset($this->command['online_user']) && $this->command['online_user'] == 1) {
					if (!$profiles['profiles'][$i]['is_online']) continue;
				}
				
				if (isset($this->command['mobile_user']) && $this->command['mobile_user'] == 1) {
					if ($profiles['profiles'][$i]['lastactivity_mobile_timestamp'] < 0) continue;
				}
				
				if (isset($this->command['new_members']) && $this->command['new_members'] == 1) {
					$delta = time() - $profiles['profiles'][$i]['created_timestamp'];
					$days = $delta / (60 * 60 * 24);
					
					if ($days > 30) continue;
				}
				
				if (isset($this->command['active_user']) && $this->command['active_user'] == 1) {
					$delta = time() - $profiles['profiles'][$i]['lastactivity_timestamp'];
					$days = $delta / (60 * 60);
						
					if ($days > 48) continue;
				}
				
				
				$this->visitprofile($member_id);
				
				$this->sleep(5);
				
				$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
				//If in runnig time period
				if($sleep_time==0) {
					
					$username = $member_id . $profiles['profiles'][$i]['nickname'];
					
					if(!$this->isAlreadySent($username)) {
						///reserve this user, so no other bot can send msg to
						$this->savelog("Reserving profile to send message: ".$username);
						if($this->reserveUser($username))
						{								
							//RANDOM SUBJECT AND MESSAGE
							$this->savelog("Random new subject and message");
							$this->currentSubject = rand(0,count($this->command['messages'])-1);
							$this->currentMessage = rand(0,count($this->_message)-1);
					
							//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
							if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
							{
								//RANDOM SUBJECT AND MESSAGE
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
								
							if(time() < ($this->lastSentTime + $this->messageSendingInterval)) $this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
							
							$this->savelog("Sending message to ".$username);
								
							if(!$this->isAlreadySent($username))
							{
					
								if($this->sendMessage($member_id, $message))
								{
									DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($username)."','".$usernameFrom."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->savelog("Sending message completed.");
									$this->lastSentTime = time();
					
									if(isset($this->command['logout_after_sent']) && $this->command['logout_after_sent'] == "Y"){
										if(++$this->count_msg >= $this->command['messages_logout']){
											return true;
										}
									}
								}
								else
								{
									$this->savelog("Sending message failed.");
									return false;
								}
							}
												
					} else {
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$username);
					}
						
					$this->cancelReservedUser($username);
					$this->sleep(2);
					
					} else {
						$this->savelog("Already send message to profile: ".$username);
					} 
				} else {
					$this->savelog("Not in running time period.");
					$this->sleep($sleep_time);
				}
			}
		}
		
		$this->savelog("Job completed.");
		return true;
	}

	
	private function getOnlineMembersFromXml($type)
	{
		$this->savelog("Trying to read xml from site - " . $type);
		
		$xml = file_get_contents("http://www.poppen.de/xml/normalUsers_" .$type . ".xml");
		
		$xml = simplexml_load_string($xml);

		$list = array();
	
		foreach ($xml->{$type}->guy as $guy) {
	        $list[] = array('uid' => (string) $guy->id, 'username' => (string) $guy->nickname , 'link' => '');
		}
				
		return $list;
		
	}
	
	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		$html = str_get_html($content);
		if(!empty($html)) {
			foreach($html->find('div.kategoriediv') as $div) {
				$list[] = array(
					'username' => $div->find("a.black",0)->href,
					'uid' => $div->find("a.black",0)->href,
					'link' => $div->find("a.black",0)->href
				);
			}
		}
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
			// die('SESS ID : '.$this->_session_id);
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

	public function getNewProfile() {
		$this->loginArr = array();

		$this->savelog("Site ID : ". $this->siteID);
		$fetch = DBConnect::assoc_query_2D("SELECT * FROM user_profiles WHERE status != 'false' AND site_id=".$this->siteID." AND in_use = 'false' ORDER BY rand() LIMIT 1");
		foreach ($fetch as $f) {
			$this->addLoginData(array(
					array(
							'username' => $f['username'],
							'password' => $f['password']
					)
			));
		}
		
		
		$this->savelog('New profile account is '. $fetch[0]['username']);
		$this->currentUser=0;
	}

	private function json_validate($json, $assoc_array = FALSE)
	{
	    // decode the JSON data
	    $result = json_decode($json, $assoc_array);

	    // switch and check possible JSON errors
	    switch (json_last_error()) {
	        case JSON_ERROR_NONE:
	            $error = ''; // JSON is valid
	            break;
	        case JSON_ERROR_DEPTH:
	            $error = 'Maximum stack depth exceeded.';
	            break;
	        case JSON_ERROR_STATE_MISMATCH:
	            $error = 'Underflow or the modes mismatch.';
	            break;
	        case JSON_ERROR_CTRL_CHAR:
	            $error = 'Unexpected control character found.';
	            break;
	        case JSON_ERROR_SYNTAX:
	            $error = 'Syntax error, malformed JSON.';
	            break;
	        // only PHP 5.3+
	        case JSON_ERROR_UTF8:
	            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
	            break;
	        default:
	            $error = 'Unknown JSON error occured.';
	            break;
	    }

	    if($error !== '') {
	    	$object = new stdClass();
	    	$object->error = $error;
	        return $object;
	    } else {
	    	return $result;
	    }
	}
	
	public function resetPLZ()
	{
		$this->command['start_plz'] = "00000";
	}
	
}