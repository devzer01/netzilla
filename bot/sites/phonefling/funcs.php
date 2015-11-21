<?php
require_once("XMLParser.php");
class funcs
{
	static function getCookiePath($username)
	{
		return dirname($_SERVER['SCRIPT_FILENAME'])."/cookies/".$username.".txt";
	}

	static function checkCurrentProfile($comman_profiles, $current_profile)
	{
		funcs::savelog("Random new profile");
		if(count($comman_profiles)>0)
		{
			if($current_profile<count($comman_profiles)-1)
				$current_profile++;
			else
				$current_profile=0;
			funcs::savelog("Profile index: ".$current_profile);
			return $current_profile;
		}
		else
		{
			funcs::savelog("All profiles are unable to log in");
			funcs::savelog("FINISHED");
			exit;
		}
	}

	static function memberlogin($username, $password, $loginURL, $loginRefererURL, $currentIndex, $total)
	{
		$viewstate = self::getViewState($username,$loginRefererURL);
		$cookie_path = self::getCookiePath($username);
		//$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7"; 
		$postData = array(	"__EVENTTARGET" => "",
							"__EVENTARGUMENT" => "",
							"__VIEWSTATEFIELDCOUNT" => "2",
							"__VIEWSTATE" => $viewstate,
							"__VIEWSTATE1" => "bmQ=",
							"txtUserName" => $username,
							"txtpassword" => $password,
							"imgLogin.x" => "22",
							"imgLogin.y" => "8",
							"txtMaskedMobileNumber_text" => "(___) ___-____",
							"txtMaskedMobileNumber" => "(___) ___-____",
							"__SCROLLPOSITIONX" => "0",
							"__SCROLLPOSITIONY" => "0"
							);
		/*echo "<pre>";
		print_r($postData);
		echo "</pre>";
		die("Log in");*/
		
		$postData = http_build_query($postData);
		$need_login = true;

		if(!(self::isLoggedIn($username)))//if($need_login)
		{
			for($count_login = 1; $count_login <= 6; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
				curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

				$result = curl_exec($ch);
				//echo "<div style='border:solid 1px #F00'>".$result."</div>"; die('<br/>Log in result');
				curl_close($ch);

				$cookie = self::parse_curl_cookie($cookie_path);
				//echo $_SERVER["REQUEST_URI"];

				if((strpos($result, "Welcome:")!==false) && (strpos($result, $username)!==false)) //if($cookie['PHPSESSID']['value']!="")
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $count_login times.");
					
					if($count_login==6)
					{
						self::savelog("User ".$username." tried to login ".$count_login." times. This username would be deleted.");
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=".SITE_ID." LIMIT 1";	
						DBConnect::execute_q($sql);
						if($currentIndex==$total)
						{
							self::savelog("FINISHED");
							exit;
						}
						else
							return false;
					}
					else
					{
						if($count_login==3)
							$sleep_time = 600; // 10 mins
						else
							$sleep_time = 120; // 2 mins

						self::savelog("Sleep after log in failed for ". self::secondToTextTime($sleep_time));
						self::sleep($sleep_time);
					}
				}
			}
		}
		else
			return true;
	}

	static function isLoggedIn($username)
	{
		self::savelog("Checking login status for profile: ".$username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.phonefling.com/Default.aspx");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.phonefling.com/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.phonefling.com', 'Origin: http://www.phonefling.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$indexpage = curl_exec($ch);
		curl_close($ch);

		if((strpos($indexpage, "Welcome:")!==false) && (strpos($indexpage, $username)!==false))//If logged in
			$loggedin = true;
		else
			$loggedin = false;

		return $loggedin;
	}

	static function keepLogIn($username, $password, $loginURL, $loginRefererURL)
	{
		$viewstate = self::getViewState($username,$loginRefererURL);
		$cookie_path = self::getCookiePath($username);
		//$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7"; 
		$postData = array(	"__EVENTTARGET" => "",
							"__EVENTARGUMENT" => "",
							"__VIEWSTATEFIELDCOUNT" => "2",
							"__VIEWSTATE" => $viewstate,
							"__VIEWSTATE1" => "bmQ=",
							"txtUserName" => $username,
							"txtpassword" => $password,
							"imgLogin.x" => "22",
							"imgLogin.y" => "8",
							"txtMaskedMobileNumber_text" => "(___) ___-____",
							"txtMaskedMobileNumber" => "(___) ___-____",
							"__SCROLLPOSITIONX" => "0",
							"__SCROLLPOSITIONY" => "0"
							);
		/*echo "<pre>";
		print_r($postData);
		echo "</pre>";
		die("Log in");*/
		
		$postData = http_build_query($postData);
		$need_login = true;

		if(!(self::isLoggedIn($username)))//if($need_login)
		{
			for($count_login = 1; $count_login <= 3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
				curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				//echo "<div style='border:solid 1px #F00'>".$result."</div>"; die('<br/>Log in result');
				curl_close($ch);

				//echo $result;

				$cookie = self::parse_curl_cookie($cookie_path);
				//echo $_SERVER["REQUEST_URI"];

				if((strpos($result, "Welcome:")!==false) && (strpos($result, $username)!==false)) //if($cookie['PHPSESSID']['value']!="")
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $count_login times.");
					if($count_login==3)
					{
						self::savelog("User ".$username." tried to login 3 times. This username would be deleted.");
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=20 LIMIT 1";						
						DBConnect::execute_q($sql);
						funcs::savelog("Couldn't login.");
						return false;
					}
				}
			}
		}
		else
			return true;
	}

	static function getUnLogInUser()
	{
		$sql = "SELECT `username` , `password` FROM `user_profiles` WHERE `status` != 'banded' AND site_id =".SITE_ID;
		return DBConnect::assoc_query_2D($sql);
	}

	static function setUserToReuse($username, $password)
	{
		$sql = "UPDATE `user_profiles` SET `status` = 'true' WHERE `username` = '".$username."' AND `password` = '".$password."' AND site_id =".SITE_ID;
		DBConnect::execute_q($sql);
		funcs::savelog($username.":".$password." can be reuse");
	}

	static function getViewState($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $loginRefererURL);
		curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		//echo $result.'<br/>-------------<br/>'; 

		$result=substr($result,strpos($result,"id=\"__VIEWSTATE\""));
		$result=str_replace("id=\"__VIEWSTATE\" value=\"","",$result);
		$result=trim(substr($result,0,strpos($result,"\" />")));
		//echo $result;
		
		//die('<br/>-------------<br/>ViewState');
		return $result;
	}

	static function isCookieValid($username)
	{
		$cookie_path = self::getCookiePath($username);
		$cookie = self::parse_curl_cookie($cookie_path);
		if(isset($cookie['Username']))
			return true;
		else
			return false;
		/*$diff = (int)$cookie['Username']['expired']-time();
		if($diff > (1*60*60))
		{
			return true;
		}
		else
		{
			return false;
		}*/
	}

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page)
	{
		$viewstate = self::getViewState($username,$searchReferer);
		$cookie_path = self::getCookiePath($username);
		//$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7";

		$country = explode(":",$searchData['country']);
		
		$postData = array(	"__EVENTTARGET" => "ctl00\$MC\$lnkRS",
							"__EVENTARGUMENT" => "",
							"__VIEWSTATEFIELDCOUNT" => "52",
							"__VIEWSTATE" => "/wEPDwUKMTI2NjU4NTM4MA9kFgJmD2QWAgIBD2QWEgINDxYCHgRocmVmBQUvaG9tZWQCEQ9kFgQCAQ9kFiZmDw8WAh4EVGV4dAUE",
							"__VIEWSTATE1" => "SG9tZWRkAgEPFgIeBWNsYXNzBQhzdWJsaW5rMhYCAgEPDxYCHwEFCE1lc3NhZ2VzZGQCAg8PFgIfAQUGU2VhcmNoZGQCAw8WAh4H",
							"__VIEWSTATE2" => "b25jbGljawVZamF2YXNjcmlwdDpvcGVuVmlkZW9jaGF0V2luZG93KCcnLCAnaE96OFFtRlVaMHVvdng2bVN2eVh3TDZuWmdtUmlR",
							"__VIEWSTATE3" => "VmYnLCAnJyk7IHJldHVybiBmYWxzZTsWAgIBDw8WAh8BBQpWaWRlbyBDaGF0ZGQCBA8WAh8ABRIvdXNlci9BcHJpbGZvcmV2ZXIW",
							"__VIEWSTATE4" => "AgIBDw8WAh8BBQdQcm9maWxlZGQCBQ8PFgIfAQUMTXkgUGljcy9WaWRzZGQCBg8PFgIfAQUGR3JvdXBzZGQCBw8PFgIfAQUFQmxv",
							"__VIEWSTATE5" => "Z3NkZAIIDw8WAh8BBQlHYWxsZXJpZXNkZAIJDw8WAh8BBQpPbmxpbmUgTm93ZGQCCg8PFgIfAQUKTXkgQWNjb3VudGRkAg0PDxYC",
							"__VIEWSTATE6" => "HwEFBUdpcmxzZGQCDg8PFgIfAQUER3V5c2RkAg8PDxYCHwEFB0NvdXBsZXNkZAIQDw8WAh8BBQtHYXkgQ291cGxlc2RkAhEPDxYC",
							"__VIEWSTATE7" => "HwEFD0xlc2JpYW4gQ291cGxlc2RkAhIPDxYCHwEFBkdyb3Vwc2RkAhMPFgIfAAUSL3VzZXIvQXByaWxmb3JldmVyZAIbDxYCHwIF",
							"__VIEWSTATE8" => "CHN1YmxpbmsyZAIDDw8WAh4HVmlzaWJsZWhkZAITD2QWCgIDDw8WAh8BBQxBcHJpbGZvcmV2ZXJkZAIFDw8WAh8BBQhzaWduIG91",
							"__VIEWSTATE9" => "dGRkAgkPDxYCHwEFDXRlbGwgYSBmcmllbmRkZAILDw8WAh8BBQpjb250YWN0IHVzZGQCDQ8PFgIfAQUEaGVscGRkAhUPZBYmAgUP",
							"__VIEWSTATE10" => "DxYCHwEFCFNlZWtpbmc6ZGQCBw8QDxYGHgtfIURhdGFCb3VuZGceDkRhdGFWYWx1ZUZpZWxkBQtOYW1lVmFsdWVJRB4NRGF0YVRl",
							"__VIEWSTATE11" => "eHRGaWVsZAUJTmFtZVZhbHVlZBAVAgRNYWxlBkZlbWFsZRUCATQBNRQrAwJnZ2RkAgsPDxYCHwEFBEFnZTpkZAINDxQrAAIUKwAH",
							"__VIEWSTATE12" => "DxYGHwEFAjE4Hg1PcmlnaW5hbFZhbHVlBwAAAAAAADJAHg1MYWJlbENzc0NsYXNzBR5yYWRMYWJlbENzc19EZWZhdWx0ICBpbnB1",
							"__VIEWSTATE13" => "dENlbGxkFgYeBVdpZHRoGwAAAAAAAD5AAQAAAB4IQ3NzQ2xhc3MFHnJhZEhvdmVyQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB4EXyFT",
							"__VIEWSTATE14" => "QgKCAhYGHwobAAAAAAAAPkABAAAAHwsFIHJhZEludmFsaWRDc3NfRGVmYXVsdCAgaW5wdXRDZWxsHwwCggIWBh8KGwAAAAAAAD5A",
							"__VIEWSTATE15" => "AQAAAB8LBSByYWRGb2N1c2VkQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB8MAoICFgYfChsAAAAAAAA+QAEAAAAfCwUgcmFkRW5hYmxl",
							"__VIEWSTATE16" => "ZENzc19EZWZhdWx0ICBpbnB1dENlbGwfDAKCAhYGHwobAAAAAAAAPkABAAAAHwsFIXJhZERpc2FibGVkQ3NzX0RlZmF1bHQgIGlu",
							"__VIEWSTATE17" => "cHV0Q2VsbB8MAoICFgYfChsAAAAAAAA+QAEAAAAfCwUlcmFkRW1wdHlNZXNzYWdlQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB8MAoIC",
							"__VIEWSTATE18" => "FgYfChsAAAAAAAA+QAEAAAAfCwUWcmFkTmVnYXRpdmVDc3NfRGVmYXVsdB8MAoICZAIQDw8WAh8BBQJ0b2RkAhIPFCsAAhQrAAcP",
							"__VIEWSTATE19" => "FgYfAQUCNTAfCAcAAAAAAABJQB8JBR5yYWRMYWJlbENzc19EZWZhdWx0ICBpbnB1dENlbGxkFgYfCwUpcmFkSG92ZXJDc3NfRGVm",
							"__VIEWSTATE20" => "YXVsdCBUZXh0Qm94X1JlZyBpbnB1dENlbGwfChsAAAAAAAA+QAEAAAAfDAKCAhYGHwsFK3JhZEludmFsaWRDc3NfRGVmYXVsdCBU",
							"__VIEWSTATE21" => "ZXh0Qm94X1JlZyBpbnB1dENlbGwfChsAAAAAAAA+QAEAAAAfDAKCAhYGHwsFK3JhZEZvY3VzZWRDc3NfRGVmYXVsdCBUZXh0Qm94",
							"__VIEWSTATE22" => "X1JlZyBpbnB1dENlbGwfChsAAAAAAAA+QAEAAAAfDAKCAhYGHwsFK3JhZEVuYWJsZWRDc3NfRGVmYXVsdCBUZXh0Qm94X1JlZyBp",
							"__VIEWSTATE23" => "bnB1dENlbGwfChsAAAAAAAA+QAEAAAAfDAKCAhYGHwsFLHJhZERpc2FibGVkQ3NzX0RlZmF1bHQgVGV4dEJveF9SZWcgaW5wdXRD",
							"__VIEWSTATE24" => "ZWxsHwobAAAAAAAAPkABAAAAHwwCggIWBh8LBTByYWRFbXB0eU1lc3NhZ2VDc3NfRGVmYXVsdCBUZXh0Qm94X1JlZyBpbnB1dENl",
							"__VIEWSTATE25" => "bGwfChsAAAAAAAA+QAEAAAAfDAKCAhYGHwsFInJhZE5lZ2F0aXZlQ3NzX0RlZmF1bHQgVGV4dEJveF9SZWcfChsAAAAAAAA+QAEA",
							"__VIEWSTATE26" => "AAAfDAKCAmQCFA8PFgIfAQUJTG9jYXRpb246ZGQCFg8UKwACDxYKHwVnHwYFCUNvdW50cnlJRB4FVmFsdWUFAzg0MB8BBQ1Vbml0",
							"__VIEWSTATE27" => "ZWQgU3RhdGVzHwcFC0NvdW50cnlOYW1lZBQrABUUKwABDxYGHwEFCkV2ZXJ5d2hlcmUfDQUDODk1HghTZWxlY3RlZGhkFCsAAQ8W",
							"__VIEWSTATE28" => "Bh8BBQ1Vbml0ZWQgU3RhdGVzHw0FAzg0MB8OZ2QUKwABDxYGHwEFDlVuaXRlZCBLaW5nZG9tHw0FAzgyNh8OaGQUKwABDxYGHwEF",
							"__VIEWSTATE29" => "CUF1c3RyYWxpYR8NBQIzNh8OaGQUKwABDxYGHwEFBkNhbmFkYR8NBQMxMjQfDmhkFCsAAQ8WBh8BBQZGcmFuY2UfDQUDMjUwHw5o",
							"__VIEWSTATE30" => "ZBQrAAEPFgYfAQUHR2VybWFueR8NBQMyNzYfDmhkFCsAAQ8WBh8BBQZHcmVlY2UfDQUDMzAwHw5oZBQrAAEPFgYfAQUFSW5kaWEf",
							"__VIEWSTATE31" => "DQUDMzU2Hw5oZBQrAAEPFgYfAQUHSXJlbGFuZB8NBQMzNzIfDmhkFCsAAQ8WBh8BBQZJc3JhZWwfDQUDMzc2Hw5oZBQrAAEPFgYf",
							"__VIEWSTATE32" => "AQUFSXRhbHkfDQUDMzgwHw5oZBQrAAEPFgYfAQUFSmFwYW4fDQUDMzkyHw5oZBQrAAEPFgYfAQUFS2VueWEfDQUDNDA0Hw5oZBQr",
							"__VIEWSTATE33" => "AAEPFgYfAQUGTW9uYWNvHw0FAzQ5Mh8OaGQUKwABDxYGHwEFC05ldyBaZWFsYW5kHw0FAzU1NB8OaGQUKwABDxYGHwEFC1BoaWxp",
							"__VIEWSTATE34" => "cHBpbmVzHw0FAzYwOB8OaGQUKwABDxYGHwEFCFBvcnR1Z2FsHw0FAzYyMB8OaGQUKwABDxYGHwEFC1B1ZXJ0byBSaWNvHw0FAzYz",
							"__VIEWSTATE35" => "MB8OaGQUKwABDxYGHwEFDFNvdXRoIEFmcmljYR8NBQM3MTAfDmhkFCsAAQ8WBh8BBQVTcGFpbh8NBQM3MjQfDmhkZAIYDw8WAh8B",
							"__VIEWSTATE36" => "BQdXaXRoaW46ZGQCGg8UKwACDxYKHwVnHwYFC05hbWVWYWx1ZUlEHw0FAzE0NB8BBQhBbnl3aGVyZR8HBQlOYW1lVmFsdWVkFCsA",
							"__VIEWSTATE37" => "BxQrAAEPFgYfAQUIMTAgTWlsZXMfDQUDMTE1Hw5oZBQrAAEPFgYfAQUIMjUgTWlsZXMfDQUDMTE2Hw5oZBQrAAEPFgYfAQUINTAg",
							"__VIEWSTATE38" => "TWlsZXMfDQUDMTE3Hw5oZBQrAAEPFgYfAQUJMTAwIE1pbGVzHw0FAzExOB8OaGQUKwABDxYGHwEFCTI1MCBNaWxlcx8NBQMxMTkf",
							"__VIEWSTATE39" => "DmhkFCsAAQ8WBh8BBQk1MDAgTWlsZXMfDQUDMTIwHw5oZBQrAAEPFgYfAQUIQW55d2hlcmUfDQUDMTQ0Hw5nZGQCHA8PFgIfAQUH",
							"__VIEWSTATE40" => "b2YgWmlwOmRkAiIPDxYCHwEFCk9ubHkgUGljczpkZAIkDxQrAAIPFgofBwUJTmFtZVZhbHVlHwYFC05hbWVWYWx1ZUlEHwEFDEFs",
							"__VIEWSTATE41" => "bCBQcm9maWxlcx8NBQQxMDc5HwVnZBQrAAIUKwABDxYGHwEFDEFsbCBQcm9maWxlcx8NBQQxMDc5Hw5nZBQrAAEPFgYfAQUST25s",
							"__VIEWSTATE42" => "eSBXaXRoIFBpY3R1cmVzHw0FBDEwODAfDmhkZAImDw8WAh8BBQZTZWFyY2hkZAIsDw8WAh8BBQxTY3JlZW4gTmFtZTpkZAIuDxQr",
							"__VIEWSTATE43" => "AAJkZGQCMA8PFgIeDEVycm9yTWVzc2FnZQVxPGltYWdlIHNyYz0nLi4vLi4vaW1hZ2VzL2N1c3RvbUVycm9yLnBuZydzdHlsZT0n",
							"__VIEWSTATE44" => "d2lkdGg6MTBweDtoZWlnaHQ6MTBweDsnPiAmbmJzcDsmbmJzcDtQbGVhc2UgZW50ZXIgYSBzY3JlZW4gbmFtZS5kZAIyDw8WAh8B",
							"__VIEWSTATE45" => "BQZTZWFyY2hkZAI2D2QWAgILD2QWAgIBDzwrAAkAZAIXDw8WAh8BBQdjb21wYW55ZGQCGQ8PFgIfAQUMdGVybXMgb2YgdXNlZGQC",
							"__VIEWSTATE46" => "Gw8PFgIfAQUOcHJpdmFjeSBwb2xpY3lkZAIdDw8WAh8BBRFhZHZlcnRpc2Ugd2l0aCB1c2RkAh8PDxYCHwEFCmFmZmlsaWF0ZXNk",
							"__VIEWSTATE47" => "ZBgFBR5fX0NvbnRyb2xzUmVxdWlyZVBvc3RCYWNrS2V5X18WBwUQY3RsMDAkTUMkQ0JMUlMkMAUQY3RsMDAkTUMkQ0JMUlMkMQUQ",
							"__VIEWSTATE48" => "Y3RsMDAkTUMkQ0JMUlMkMQUPY3RsMDAkTUMkY21iUlNMBRBjdGwwMCRNQyRjbWJNaWxlBRBjdGwwMCRNQyRjbWJSU1dQBRBjdGww",
							"__VIEWSTATE49" => "MCRNQyRjbWJVU1NOBRBjdGwwMCRNQyRjbWJVU1NODxQrAAJlZWQFEGN0bDAwJE1DJGNtYlJTV1APFCsAAgUMQWxsIFByb2ZpbGVz",
							"__VIEWSTATE50" => "BQQxMDc5ZAUQY3RsMDAkTUMkY21iTWlsZQ8UKwACBQhBbnl3aGVyZQUDMTQ0ZAUPY3RsMDAkTUMkY21iUlNMDxQrAAIFDVVuaXRl",
							"__VIEWSTATE51" => "ZCBTdGF0ZXMFAzg0MGQ=",
							"ctl00\$hdnSMUID" => "",
							"ctl00\$hdnIsAsync" => "",
							"ctl00\$hPGID" => "",
							"ctl00\$hdnPT" => "30000",
							"ctl00\$MC\$CBLRS$0" => "on",
							"ctl00\$MC\$txtRSAF" => "18",
							"ctl00\$MC\$txtRSAT" => "50",
							/*"ctl00\$MC\$cmbRSL_Input" => "United States",
							"ctl00\$MC\$cmbRSL_value" => "840",
							"ctl00\$MC\$cmbRSL_text" => "United States",*/
							"ctl00\$MC\$cmbRSL_Input" => $country[1],
							"ctl00\$MC\$cmbRSL_value" => $country[0],
							"ctl00\$MC\$cmbRSL_text" => $country[1],
							"ctl00\$MC\$cmbRSL_clientWidth" => "127px",
							"ctl00\$MC\$cmbRSL_clientHeight" => "19px",
							"ctl00\$MC\$cmbMile_Input" => "Anywhere",
							"ctl00\$MC\$cmbMile_value" => "144",
							"ctl00\$MC\$cmbMile_text" => "Anywhere",
							"ctl00\$MC\$cmbMile_clientWidth" => "52px",
							"ctl00\$MC\$cmbMile_clientHeight" => "19px",
							"ctl00\$MC\$txtZP" => "",
							"ctl00\$MC\$cmbRSWP_Input" => "All Profiles",
							"ctl00\$MC\$cmbRSWP_value" => "1079",
							"ctl00\$MC\$cmbRSWP_text" => "All Profiles",
							"ctl00\$MC\$cmbRSWP_clientWidth" => "127px",
							"ctl00\$MC\$cmbRSWP_clientHeight" => "19px",
							"ctl00\$MC\$cmbUSSN_Input" => "",
							"ctl00\$MC\$cmbUSSN_value" => "",
							"ctl00\$MC\$cmbUSSN_text" => "",
							"ctl00\$MC\$cmbUSSN_clientWidth" => "127px",
							"ctl00\$MC\$cmbUSSN_clientHeight" => "19px",
							"ctl00\$MC\$hdfMin" => "",
							"ctl00\$MC\$hdfMax" => ""
							
						);
		/*United Kingdom
		$postData = array(	"__EVENTARGUMENT" => "",
							"__EVENTTARGET" => "ctl00\$MC\$lnkRS",
							"__VIEWSTATE" => $viewstate,
							"__VIEWSTATE1" => "SG9tZWRkAgEPFgIeBWNsYXNzBQhzdWJsaW5rMhYCAgEPDxYCHwEFCE1lc3NhZ2VzZGQCAg8PFgIfAQUGU2VhcmNoZGQCAw8WAh4H",
							"__VIEWSTATE10" => "Bw8QDxYGHgtfIURhdGFCb3VuZGceDkRhdGFWYWx1ZUZpZWxkBQtOYW1lVmFsdWVJRB4NRGF0YVRleHRGaWVsZAUJTmFtZVZhbHVl",
							"__VIEWSTATE11" => "ZBAVAgRNYWxlBkZlbWFsZRUCATQBNRQrAwJnZ2RkAgsPDxYCHwEFBEFnZTpkZAINDxQrAAIUKwAHDxYGHwEFAjE4Hg1PcmlnaW5h",
							"__VIEWSTATE12" => "bFZhbHVlBwAAAAAAADJAHg1MYWJlbENzc0NsYXNzBR5yYWRMYWJlbENzc19EZWZhdWx0ICBpbnB1dENlbGxkFgYeBVdpZHRoGwAA",
							"__VIEWSTATE13" => "AAAAAD5AAQAAAB4IQ3NzQ2xhc3MFHnJhZEhvdmVyQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB4EXyFTQgKCAhYGHwobAAAAAAAAPkAB",
							"__VIEWSTATE14" => "AAAAHwsFIHJhZEludmFsaWRDc3NfRGVmYXVsdCAgaW5wdXRDZWxsHwwCggIWBh8KGwAAAAAAAD5AAQAAAB8LBSByYWRGb2N1c2Vk",
							"__VIEWSTATE15" => "Q3NzX0RlZmF1bHQgIGlucHV0Q2VsbB8MAoICFgYfChsAAAAAAAA+QAEAAAAfCwUgcmFkRW5hYmxlZENzc19EZWZhdWx0ICBpbnB1",
							"__VIEWSTATE16" => "dENlbGwfDAKCAhYGHwobAAAAAAAAPkABAAAAHwsFIXJhZERpc2FibGVkQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB8MAoICFgYfChsA",
							"__VIEWSTATE17" => "AAAAAAA+QAEAAAAfCwUlcmFkRW1wdHlNZXNzYWdlQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB8MAoICFgYfChsAAAAAAAA+QAEAAAAf",
							"__VIEWSTATE18" => "CwUWcmFkTmVnYXRpdmVDc3NfRGVmYXVsdB8MAoICZAIQDw8WAh8BBQJ0b2RkAhIPFCsAAhQrAAcPFgYfAQUCNTAfCAcAAAAAAABJ",
							"__VIEWSTATE19" => "QB8JBR5yYWRMYWJlbENzc19EZWZhdWx0ICBpbnB1dENlbGxkFgYfCwUpcmFkSG92ZXJDc3NfRGVmYXVsdCBUZXh0Qm94X1JlZyBp",
							"__VIEWSTATE2" => "b25jbGljawVZamF2YXNjcmlwdDpvcGVuVmlkZW9jaGF0V2luZG93KCcnLCAnaE96OFFtRlVaMHVvdng2bVN2eVh3TDZuWmdtUmlR",
							"__VIEWSTATE20" => "bnB1dENlbGwfChsAAAAAAAA+QAEAAAAfDAKCAhYGHwsFK3JhZEludmFsaWRDc3NfRGVmYXVsdCBUZXh0Qm94X1JlZyBpbnB1dENl",
							"__VIEWSTATE21" => "bGwfChsAAAAAAAA+QAEAAAAfDAKCAhYGHwsFK3JhZEZvY3VzZWRDc3NfRGVmYXVsdCBUZXh0Qm94X1JlZyBpbnB1dENlbGwfChsA",
							"__VIEWSTATE22" => "AAAAAAA+QAEAAAAfDAKCAhYGHwsFK3JhZEVuYWJsZWRDc3NfRGVmYXVsdCBUZXh0Qm94X1JlZyBpbnB1dENlbGwfChsAAAAAAAA+",
							"__VIEWSTATE23" => "QAEAAAAfDAKCAhYGHwsFLHJhZERpc2FibGVkQ3NzX0RlZmF1bHQgVGV4dEJveF9SZWcgaW5wdXRDZWxsHwobAAAAAAAAPkABAAAA",
							"__VIEWSTATE24" => "HwwCggIWBh8LBTByYWRFbXB0eU1lc3NhZ2VDc3NfRGVmYXVsdCBUZXh0Qm94X1JlZyBpbnB1dENlbGwfChsAAAAAAAA+QAEAAAAf",
							"__VIEWSTATE25" => "DAKCAhYGHwsFInJhZE5lZ2F0aXZlQ3NzX0RlZmF1bHQgVGV4dEJveF9SZWcfChsAAAAAAAA+QAEAAAAfDAKCAmQCFA8PFgIfAQUJ",
							"__VIEWSTATE26" => "TG9jYXRpb246ZGQCFg8UKwACDxYKHwVnHwYFCUNvdW50cnlJRB4FVmFsdWUFAzgyNh8BBQ5Vbml0ZWQgS2luZ2RvbR8HBQtDb3Vu",
							"__VIEWSTATE27" => "dHJ5TmFtZWQUKwAVFCsAAQ8WBh8BBQpFdmVyeXdoZXJlHw0FAzg5NR4IU2VsZWN0ZWRoZBQrAAEPFgYfAQUNVW5pdGVkIFN0YXRl",
							"__VIEWSTATE28" => "cx8NBQM4NDAfDmhkFCsAAQ8WBh8BBQ5Vbml0ZWQgS2luZ2RvbR8NBQM4MjYfDmdkFCsAAQ8WBh8BBQlBdXN0cmFsaWEfDQUCMzYf",
							"__VIEWSTATE29" => "DmhkFCsAAQ8WBh8BBQZDYW5hZGEfDQUDMTI0Hw5oZBQrAAEPFgYfAQUGRnJhbmNlHw0FAzI1MB8OaGQUKwABDxYGHwEFB0dlcm1h",
							"__VIEWSTATE3" => "VmYnLCAnJyk7IHJldHVybiBmYWxzZTsWAgIBDw8WAh8BBQpWaWRlbyBDaGF0ZGQCBA8WAh8ABRIvdXNlci9BcHJpbGZvcmV2ZXIW",
							"__VIEWSTATE30" => "bnkfDQUDMjc2Hw5oZBQrAAEPFgYfAQUGR3JlZWNlHw0FAzMwMB8OaGQUKwABDxYGHwEFBUluZGlhHw0FAzM1Nh8OaGQUKwABDxYG",
							"__VIEWSTATE31" => "HwEFB0lyZWxhbmQfDQUDMzcyHw5oZBQrAAEPFgYfAQUGSXNyYWVsHw0FAzM3Nh8OaGQUKwABDxYGHwEFBUl0YWx5Hw0FAzM4MB8O",
							"__VIEWSTATE32" => "aGQUKwABDxYGHwEFBUphcGFuHw0FAzM5Mh8OaGQUKwABDxYGHwEFBUtlbnlhHw0FAzQwNB8OaGQUKwABDxYGHwEFBk1vbmFjbx8N",
							"__VIEWSTATE33" => "BQM0OTIfDmhkFCsAAQ8WBh8BBQtOZXcgWmVhbGFuZB8NBQM1NTQfDmhkFCsAAQ8WBh8BBQtQaGlsaXBwaW5lcx8NBQM2MDgfDmhk",
							"__VIEWSTATE34" => "FCsAAQ8WBh8BBQhQb3J0dWdhbB8NBQM2MjAfDmhkFCsAAQ8WBh8BBQtQdWVydG8gUmljbx8NBQM2MzAfDmhkFCsAAQ8WBh8BBQxT",
							"__VIEWSTATE35" => "b3V0aCBBZnJpY2EfDQUDNzEwHw5oZBQrAAEPFgYfAQUFU3BhaW4fDQUDNzI0Hw5oZGQCGA8PFgIfAQUHV2l0aGluOmRkAhoPFCsA",
							"__VIEWSTATE36" => "Ag8WCh8FZx8GBQtOYW1lVmFsdWVJRB8NBQMxMTUfAQUIMTAgTWlsZXMfBwUJTmFtZVZhbHVlZBQrAAcUKwABDxYGHwEFCDEwIE1p",
							"__VIEWSTATE37" => "bGVzHw0FAzExNR8OZ2QUKwABDxYGHwEFCDI1IE1pbGVzHw0FAzExNh8OaGQUKwABDxYGHwEFCDUwIE1pbGVzHw0FAzExNx8OaGQU",
							"__VIEWSTATE38" => "KwABDxYGHwEFCTEwMCBNaWxlcx8NBQMxMTgfDmhkFCsAAQ8WBh8BBQkyNTAgTWlsZXMfDQUDMTE5Hw5oZBQrAAEPFgYfAQUJNTAw",
							"__VIEWSTATE39" => "IE1pbGVzHw0FAzEyMB8OaGQUKwABDxYGHwEFCEFueXdoZXJlHw0FAzE0NB8OaGRkAhwPDxYCHwEFB29mIFppcDpkZAIiDw8WAh8B",
							"__VIEWSTATE4" => "AgIBDw8WAh8BBQdQcm9maWxlZGQCBQ8PFgIfAQUMTXkgUGljcy9WaWRzZGQCBg8PFgIfAQUGR3JvdXBzZGQCBw8PFgIfAQUFQmxv",
							"__VIEWSTATE40" => "BQpPbmx5IFBpY3M6ZGQCJA8UKwACDxYKHwcFCU5hbWVWYWx1ZR8GBQtOYW1lVmFsdWVJRB8BBQxBbGwgUHJvZmlsZXMfDQUEMTA3",
							"__VIEWSTATE41" => "OR8FZ2QUKwACFCsAAQ8WBh8BBQxBbGwgUHJvZmlsZXMfDQUEMTA3OR8OZ2QUKwABDxYGHwEFEk9ubHkgV2l0aCBQaWN0dXJlcx8N",
							"__VIEWSTATE42" => "BQQxMDgwHw5oZGQCJg8PFgIfAQUGU2VhcmNoZGQCLA8PFgIfAQUMU2NyZWVuIE5hbWU6ZGQCLg8UKwACZGRkAjAPDxYCHgxFcnJv",
							"__VIEWSTATE43" => "ck1lc3NhZ2UFcTxpbWFnZSBzcmM9Jy4uLy4uL2ltYWdlcy9jdXN0b21FcnJvci5wbmcnc3R5bGU9J3dpZHRoOjEwcHg7aGVpZ2h0",
							"__VIEWSTATE44" => "OjEwcHg7Jz4gJm5ic3A7Jm5ic3A7UGxlYXNlIGVudGVyIGEgc2NyZWVuIG5hbWUuZGQCMg8PFgIfAQUGU2VhcmNoZGQCNg9kFgIC",
							"__VIEWSTATE45" => "Cw9kFgICAQ88KwAJAGQCFw8PFgIfAQUHY29tcGFueWRkAhkPDxYCHwEFDHRlcm1zIG9mIHVzZWRkAhsPDxYCHwEFDnByaXZhY3kg",
							"__VIEWSTATE46" => "cG9saWN5ZGQCHQ8PFgIfAQURYWR2ZXJ0aXNlIHdpdGggdXNkZAIfDw8WAh8BBQphZmZpbGlhdGVzZGQYBQUeX19Db250cm9sc1Jl",
							"__VIEWSTATE47" => "cXVpcmVQb3N0QmFja0tleV9fFgcFEGN0bDAwJE1DJENCTFJTJDAFEGN0bDAwJE1DJENCTFJTJDEFEGN0bDAwJE1DJENCTFJTJDEF",
							"__VIEWSTATE48" => "D2N0bDAwJE1DJGNtYlJTTAUQY3RsMDAkTUMkY21iTWlsZQUQY3RsMDAkTUMkY21iUlNXUAUQY3RsMDAkTUMkY21iVVNTTgUQY3Rs",
							"__VIEWSTATE49" => "MDAkTUMkY21iVVNTTg8UKwACZWVkBRBjdGwwMCRNQyRjbWJSU1dQDxQrAAIFDEFsbCBQcm9maWxlcwUEMTA3OWQFEGN0bDAwJE1D",
							"__VIEWSTATE5" => "Z3NkZAIIDw8WAh8BBQlHYWxsZXJpZXNkZAIJDw8WAh8BBQpPbmxpbmUgTm93ZGQCCg8PFgIfAQUKTXkgQWNjb3VudGRkAg0PDxYC",
							"__VIEWSTATE50" => "JGNtYk1pbGUPFCsAAgUIMTAgTWlsZXMFAzExNWQFD2N0bDAwJE1DJGNtYlJTTA8UKwACBQ5Vbml0ZWQgS2luZ2RvbQUDODI2ZA==",
							"__VIEWSTATE6" => "HwEFBUdpcmxzZGQCDg8PFgIfAQUER3V5c2RkAg8PDxYCHwEFB0NvdXBsZXNkZAIQDw8WAh8BBQtHYXkgQ291cGxlc2RkAhEPDxYC",
							"__VIEWSTATE7" => "HwEFD0xlc2JpYW4gQ291cGxlc2RkAhIPDxYCHwEFBkdyb3Vwc2RkAhMPFgIfAAUSL3VzZXIvQXByaWxmb3JldmVyZAIDDw8WAh4H",
							"__VIEWSTATE8" => "VmlzaWJsZWhkZAITD2QWCgIDDw8WAh8BBQxBcHJpbGZvcmV2ZXJkZAIFDw8WAh8BBQhzaWduIG91dGRkAgkPDxYCHwEFDXRlbGwg",
							"__VIEWSTATE9" => "YSBmcmllbmRkZAILDw8WAh8BBQpjb250YWN0IHVzZGQCDQ8PFgIfAQUEaGVscGRkAhUPZBYmAgUPDxYCHwEFCFNlZWtpbmc6ZGQC",
							"__VIEWSTATEFIELDCOUNT" => "51",
							"ctl00\$MC\$CBLRS\$0" => "on",
							"ctl00\$MC\$cmbMile_Input" => "10 Miles",
							"ctl00\$MC\$cmbMile_clientHeight" => "19px",
							"ctl00\$MC\$cmbMile_clientWidth" => "",
							"ctl00\$MC\$cmbMile_text" => "10 Miles",
							"ctl00\$MC\$cmbMile_value" => "115",
							"ctl00\$MC\$cmbRSL_Input" => "United Kingdom",
							"ctl00\$MC\$cmbRSL_clientHeight" => "19px",
							"ctl00\$MC\$cmbRSL_clientWidth" => "127px",
							"ctl00\$MC\$cmbRSL_text" => "United Kingdom",
							"ctl00\$MC\$cmbRSL_value" => "826",
							"ctl00\$MC\$cmbRSWP_Input" => "All Profiles",
							"ctl00\$MC\$cmbRSWP_clientHeight" => "19px",
							"ctl00\$MC\$cmbRSWP_clientWidth" => "127px",
							"ctl00\$MC\$cmbRSWP_text" => "All Profiles",
							"ctl00\$MC\$cmbRSWP_value" => "1079",
							"ctl00\$MC\$cmbUSSN_Input" => "",
							"ctl00\$MC\$cmbUSSN_clientHeight" => "19px",
							"ctl00\$MC\$cmbUSSN_clientWidth" => "127px",
							"ctl00\$MC\$cmbUSSN_text" => "",
							"ctl00\$MC\$cmbUSSN_value" => "",
							"ctl00\$MC\$hdfMax" => "",
							"ctl00\$MC\$hdfMin" => "",
							"ctl00\$MC\$txtRSAF" => "18",
							"ctl00\$MC\$txtRSAT" => "50",
							"ctl00\$MC\$txtZP" => "",
							"ctl00\$hPGID" => "",
							"ctl00\$hdnIsAsync" => "",
							"ctl00\$hdnPT" => "30000",
							"ctl00\$hdnSMUID" => ""
							);*/

		/*echo "<pre>";
		print_r($postData);
		echo "</pre>";*/

		$postData = http_build_query($postData);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $searchURL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch); 
		//echo "<div style='border:solid 1px #F00'>".$result."</div>"; die('<br/>Search result');
		curl_close($ch);

		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//$content=file_get_contents("sample-search-content.html");
		//Find NEXT url

		$content = substr($content,strpos($content,'<table id="ctl00_MC_dtlResult" cellspacing="0" align="Center" border="0" style="width:100%;border-collapse:collapse;">'));
		$content = substr($content,0,strpos($content,'<span id="ctl00_MC_lblResult" class="formlabels">'));
		$content = str_replace("&","&amp;",$content);
		$content = str_replace("SELECTED","",$content); 
		$content = str_replace("&gt;","",$content); 
		
		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		
		//echo $xml; die;
		$parser = new XMLParser($xml);//--
		$parser->Parse();//--
		//echo $parser->GenerateXML(); die();

		$list = array();
		//tagData tagParents tagChildren tagAttrs tagName tagAttrs['class']
	
		if(isset($parser->document->table))
		{
			foreach($parser->document->table as $table)
			{
				for($i=0; $i<10; $i++)
				{
					for($j=0; $j<3; $j++)
					{
						$user_id = trim($table->tr[$i]->td[$j]->table[0]->tr[0]->td[0]->div[0]->div[0]->table[0]->tr[0]->td[0]->input[1]->tagAttrs['value']);
						$user_name = trim($table->tr[$i]->td[$j]->table[0]->tr[0]->td[0]->div[0]->div[0]->table[0]->tr[0]->td[1]->a[0]->span[0]->tagData);
						$user_img = trim($table->tr[$i]->td[$j]->table[0]->tr[0]->td[0]->div[0]->div[0]->table[0]->tr[0]->td[0]->a[0]->img[0]->tagAttrs['src']);
						$user_img = ($user_img=="Images/DefaultSmall.jpg") ? "" : $user_img;
						array_push($list,array('username' => $user_name, 'userid' => $user_id, 'pic' => $user_img));
						//echo $user_id." : ".$user_img." : ".$user_name."<br/>";
						//die();
					}
				}
			}
		}

		/*echo "<pre>";
		print_r($list);
		echo "</pre>";*/
		$searchData = array();
		return array($list,$searchData);
	}

	static function saveMembers($list,$post)
	{
		foreach($list as $member)
		{
			//echo "INSERT INTO phonefling_member (username, userid, gender, pic, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['search_sex']."', '".$member['pic']."', NOW())"."<br/>";
			DBConnect::execute_q("INSERT INTO phonefling_member (username, userid, gender, pic, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['search_sex']."', '".$member['pic']."', NOW())");
		}
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
	}


	static function getMembers($post,$amount)
	{
		$sql = "SELECT username FROM phonefling_member WHERE gender='".$post['search_sex']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username,userid FROM phonefling_member WHERE gender='".$post['search_sex']."' AND username NOT IN (SELECT to_username FROM phonefling_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY DATE(created_datetime) DESC, id ASC LIMIT 1";

		$member = DBConnect::assoc_query_1D($sql);
		if(!(is_array($member)))
			funcs::savelog($sql);

		return $member;
	}

	static function sendMessage($from, $username, $to, $subject, $message, $sendMessageURL)
	{
		$cookie_path = self::getCookiePath($from);
		$sendMessageReferer = $sendMessageURL.$to;
		$sendMessageURL = $sendMessageReferer; //same as referer url (It's unique for each user)
		$viewstate = self::getViewState($from,$sendMessageReferer);
		//$viewstate = self::getViewStateSendMessage($from,$sendMessageReferer);
		$sendMessagePostData = array(	"__EVENTTARGET" => "ctl00\$MC\$lnkSend",
										"__EVENTARGUMENT" => "",
										"__VIEWSTATEFIELDCOUNT" => "29",
										"__VIEWSTATE" => $viewstate,
										"__VIEWSTATE1" => "BEhvbWVkZAIBDxYCHgVjbGFzcwUIc3VibGluazIWAgIBDw8WAh8BBQhNZXNzYWdlc2RkAgIPDxYCHwEFBlNlYXJjaGRkAgMPFgIe",
										"__VIEWSTATE2" => "B29uY2xpY2sFWWphdmFzY3JpcHQ6b3BlblZpZGVvY2hhdFdpbmRvdygnJywgJ2hPejhRbUZVWjB1b3Z4Nm1TdnlYd0w2blpnbVJp",
										"__VIEWSTATE3" => "UVZmJywgJycpOyByZXR1cm4gZmFsc2U7FgICAQ8PFgIfAQUKVmlkZW8gQ2hhdGRkAgQPFgIfAAUSL3VzZXIvQXByaWxmb3JldmVy",
										"__VIEWSTATE4" => "FgICAQ8PFgIfAQUHUHJvZmlsZWRkAgUPDxYCHwEFDE15IFBpY3MvVmlkc2RkAgYPDxYCHwEFBkdyb3Vwc2RkAgcPDxYCHwEFBUJs",
										"__VIEWSTATE5" => "b2dzZGQCCA8PFgIfAQUJR2FsbGVyaWVzZGQCCQ8PFgIfAQUKT25saW5lIE5vd2RkAgoPDxYCHwEFCk15IEFjY291bnRkZAINDw8W",
										"__VIEWSTATE6" => "Ah8BBQVHaXJsc2RkAg4PDxYCHwEFBEd1eXNkZAIPDw8WAh8BBQdDb3VwbGVzZGQCEA8PFgIfAQULR2F5IENvdXBsZXNkZAIRDw8W",
										"__VIEWSTATE7" => "Ah8BBQ9MZXNiaWFuIENvdXBsZXNkZAISDw8WAh8BBQZHcm91cHNkZAITDxYCHwAFEi91c2VyL0FwcmlsZm9yZXZlcmQCGw8WAh8C",
										"__VIEWSTATE8" => "BQhzdWJsaW5rMmQCAw8PFgIeB1Zpc2libGVoZGQCEw9kFgoCAw8PFgIfAQUMQXByaWxmb3JldmVyZGQCBQ8PFgIfAQUIc2lnbiBv",
										"__VIEWSTATE9" => "dXRkZAIJDw8WAh8BBQ10ZWxsIGEgZnJpZW5kZGQCCw8PFgIfAQUKY29udGFjdCB1c2RkAg0PDxYCHwEFBGhlbHBkZAIVD2QWAgIC",
										"__VIEWSTATE10" => "D2QWFgIFDxYCHwAFDi91c2VyL21lYXNtZW1lZAIJDw8WAh8BBQNUbzpkZAILDxQrAAIPFgQfAQUIbWVhc21lbWUeB0VuYWJsZWRo",
										"__VIEWSTATE11" => "ZGRkAg0PDxYCHgxFcnJvck1lc3NhZ2UFbjxpbWFnZSBzcmM9Jy4uLy4uL2ltYWdlcy9jdXN0b21FcnJvci5wbmcnc3R5bGU9J3dp",
										"__VIEWSTATE12" => "ZHRoOjEwcHg7aGVpZ2h0OjEwcHg7Jz4gJm5ic3A7Jm5ic3A7UGxlYXNlIEVudGVyIFNjcmVlbiBOYW1lZGQCDw8PFgIfBgWuATxp",
										"__VIEWSTATE13" => "bWFnZSBzcmM9Jy4uLy4uL2ltYWdlcy9jdXN0b21FcnJvci5wbmcnc3R5bGU9J3dpZHRoOjEwcHg7aGVpZ2h0OjEwcHg7Jz4gJm5i",
										"__VIEWSTATE14" => "c3A7Jm5ic3A7V2UncmUgc29ycnksIGluY29ycmVjdCBzY3JlZW4gbmFtZS4gUGxlYXNlIG1ha2Ugc3VyZSB5b3UgZW50ZXIgeW91",
										"__VIEWSTATE15" => "ciBjb3JyZWN0IHNjcmVlbiBuYW1lLmRkAhEPDxYCHwYFwgE8aW1hZ2Ugc3JjPScuLi8uLi9pbWFnZXMvY3VzdG9tRXJyb3IucG5n",
										"__VIEWSTATE16" => "J3N0eWxlPSd3aWR0aDoxMHB4O2hlaWdodDoxMHB4Oyc+ICZuYnNwOyZuYnNwO1dlJ3JlIHNvcnJ5LCB5b3UgcHJvdmlkZWQgU2Ny",
										"__VIEWSTATE17" => "ZWVuIG5hbWUgaXMgaW4geW91ciBCbG9jayBsaXN0LiBQbGVhc2UgbWFrZSBzdXJlIHlvdSBlbnRlciBjb3JyZWN0IHNjcmVlbiBu",
										"__VIEWSTATE18" => "YW1lLmRkAhMPDxYCHwEFCFN1YmplY3Q6ZGQCFQ8UKwAHDxYCHg1MYWJlbENzc0NsYXNzBR5yYWRMYWJlbENzc19EZWZhdWx0ICBp",
										"__VIEWSTATE19" => "bnB1dENlbGxkFgYeBVdpZHRoGwAAAAAAgHZAAQAAAB4IQ3NzQ2xhc3MFHnJhZEhvdmVyQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB4E",
										"__VIEWSTATE20" => "XyFTQgKCAhYGHwgbAAAAAACAdkABAAAAHwkFIHJhZEludmFsaWRDc3NfRGVmYXVsdCAgaW5wdXRDZWxsHwoCggIWBh8IGwAAAAAA",
										"__VIEWSTATE21" => "gHZAAQAAAB8JBSByYWRGb2N1c2VkQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB8KAoICFgYfCBsAAAAAAIB2QAEAAAAfCQUgcmFkRW5h",
										"__VIEWSTATE22" => "YmxlZENzc19EZWZhdWx0ICBpbnB1dENlbGwfCgKCAhYGHwgbAAAAAACAdkABAAAAHwkFIXJhZERpc2FibGVkQ3NzX0RlZmF1bHQg",
										"__VIEWSTATE23" => "IGlucHV0Q2VsbB8KAoICFgYfCBsAAAAAAIB2QAEAAAAfCQUlcmFkRW1wdHlNZXNzYWdlQ3NzX0RlZmF1bHQgIGlucHV0Q2VsbB8K",
										"__VIEWSTATE24" => "AoICZAIXDw8WAh8GBW08aW1hZ2Ugc3JjPScuLi8uLi9pbWFnZXMvY3VzdG9tRXJyb3IucG5nJ3N0eWxlPSd3aWR0aDoxMHB4O2hl",
										"__VIEWSTATE25" => "aWdodDoxMHB4Oyc+ICZuYnNwOyZuYnNwO1BsZWFzZSBlbnRlciBhIHN1YmplY3QuZGQCGQ8PFgIfAQUITWVzc2FnZTpkZAIdDw8W",
										"__VIEWSTATE26" => "Ah8BBQRTZW5kZGQCFw8PFgIfAQUHY29tcGFueWRkAhkPDxYCHwEFDHRlcm1zIG9mIHVzZWRkAhsPDxYCHwEFDnByaXZhY3kgcG9s",
										"__VIEWSTATE27" => "aWN5ZGQCHQ8PFgIfAQURYWR2ZXJ0aXNlIHdpdGggdXNkZAIfDw8WAh8BBQphZmZpbGlhdGVzZGQYAgUeX19Db250cm9sc1JlcXVp",
										"__VIEWSTATE28" => "cmVQb3N0QmFja0tleV9fFgEFDmN0bDAwJE1DJGNtYlRvBQ5jdGwwMCRNQyRjbWJUbw8UKwACBQhtZWFzbWVtZWVk",
										"ctl00\$hdnSMUID" => "",
										"ctl00\$hdnIsAsync" => "",
										"ctl00\$hPGID" => "",
										"ctl00\$hdnPT" => "30000",
										"ctl00\$MC\$hdfMsg" => "Message Sent successfully",
										"ctl00\$MC\$hdfFBC" => false,
										"ctl00\$MC\$cmbTo_value" => "",
										"ctl00\$MC\$cmbTo_text" => str_replace(" ","",$username),
										"ctl00\$MC\$cmbTo_clientWidth" => "337px",
										"ctl00\$MC\$cmbTo_clientHeight" => "19px",
										"ctl00\$MC\$txtSUB" => $subject,
										"ctl00\$MC\$txtRMS" => $message
									);
		$sendMessagePostData = http_build_query($sendMessagePostData);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		//file_put_contents("logs/".ID."_sent.log",$result);
		//echo $result; //die("<br/>Sent Message");
		curl_close($ch);

		if(strpos($result, "Message Sent successfully")>-1)
		{
			funcs::savelog("Sent message to ".$username);
			DBConnect::execute_q("INSERT INTO phonefling_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$username."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$username." failed");
			DBConnect::execute_q("DELETE FROM phonefling_member WHERE username='".$username."'");
			return false;
		}
	}
	
	static function checkInboxMessage($username, $inboxURL, $from)
	{
		$content = self::checkInboxPage($username, $inboxURL, $from);
		return self::getInboxMessagesFromInboxPage($content);
	}

	static function sleep($time)
	{
		$sleep_time = $time;
		while($sleep_time>=60)
		{
			if($sleep_time!=$time)
			{
				$txt_time = funcs::secondToTextTime($sleep_time);
				funcs::savelog("Still sleeping [".$txt_time." left]");
			}
			sleep(60);
			$sleep_time-=60;

		}
		sleep($sleep_time);
	}

	static function secondToTextTime($seconds)
	{
		$h = (int)($seconds / 3600);
		$m = (int)(($seconds - $h*3600) / 60);
		$s = (int)($seconds - $h*3600 - $m*60);
		return (($h)?(($h<10)?("0".$h):$h):"00").":".(($m)?(($m<10)?("0".$m):$m):"00").":".(($s)?(($s<10)?("0".$s):$s):"00");
	}

	static function checkRunningTime($start_time, $end_time)
	{
		$unx_current_time = strtotime(date('Y-m-d H:i:s'));
		$unx_start_day_time = strtotime(date('Y-m-d').'00:00:00');
		$unx_end_day_time = strtotime(date('Y-m-d').'24:00:00');
		$unx_start_time = $start_time; // strtotime();
		$unx_end_time = $end_time; //strtotime();
		
		
		if($unx_end_time>=$unx_start_time)
		{	//Check if current time is not in start and end time then do sleep time below otherwise follow while loop structure in send-message.php
			//Ex. Sending Time 10:00:00 - 17:00:00 AND Sleeping Time 17:00:01 - 09:59:59
			//Ex. Sending Time 22:00:00 - 04:00:00 AND Sleeping Time 04:00:01 - 21:59:59
			if(!(($unx_start_time<=$unx_current_time) && ($unx_end_time>=$unx_current_time)))
			{
				$sleep_time = ($unx_start_time-$unx_current_time);
				if($sleep_time>0)
				{
					funcs::savelog("Start time is : ".date('Y-m-d H:i:s',$start_time));
					funcs::sleep($sleep_time);
				}
				elseif($unx_end_time<$unx_current_time)
				{
					funcs::savelog("End time at : ".date('Y-m-d H:i:s',$unx_end_time));
					$sleep_time = ($unx_end_day_time-$unx_end_time)+($unx_start_time-$unx_start_day_time);
					funcs::sleep($sleep_time);
					//funcs::savelog("FINISHED");
					//exit;
				}
			}
		}
	}

	static function checkInboxPage($username, $inboxURL, $from)
	{
		funcs::savelog("Receiving inbox page");
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $inboxURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		funcs::savelog("Receiving of inbox page done");
		curl_close($ch);
		return $result;
	}

	static function getInboxMessagesFromInboxPage($content)
	{
		$list = array();
		$content = substr($content,strpos($content,'<div class="text">'));
		$content = trim(substr($content,0,strrpos($content,'<div class="message-navigation">')));
		$content = str_replace("’","'",$content);
		$content = str_replace("“","\"",$content);
		$content = str_replace("´","\"",$content);

		//Hack for some invalid XML
		$content = str_replace("\"></span>","\"/></span>",$content);
		$content = str_replace('<span class="bottom-bg"><span>&nbsp;</span></span>',"",$content);
		$xml="<?xml version='1.0' standalone='yes' ?>".$content;
		//file_put_contents("xml-".$username."-".$page.".txt",$xml);

		$parser = new XMLParser($xml);
		$parser->Parse();

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $message)
			{
				if(isset($message->ul[0]->li[3]))
				{
					array_push($list, array(	"username"=>$message->ul[0]->li[3]->span[0]->a[0]->tagData,
												"subject"=>$message->ul[0]->li[2]->span[0]->a[0]->tagData,
												"url"=>$message->ul[0]->li[2]->span[0]->a[0]->tagAttrs['href']
											)
								);
				}
			}
		}
		return $list;
	}

	static function randomText($message)
	{
		$list = array(
						" your "	=> array(" ur "),
						"I'm "		=> array("Im ","im ", "I am ","i'm "),
						" for "		=> array(" 4 "),
						" to "		=> array(" 2 "),
						"."			=> array("..","...","!"),
						"..."		=> array("..","."),
						"you "		=> array("u "),
						"are "		=> array("r "),
						"?"			=> array("?!?"),
						" "			=> array("  ","   "),
						" you're "	=> array(" u're "),
						"!"			=> array(".","..","..."),
						" be "		=> array(" b ")
					);
		if(rand(0,1))
		{
			foreach($list as $key => $words)
			{
				if(rand(0,1))
				{
					$message=str_replace($key,$words[rand(0,count($words)-1)],$message);
				}
			}
		}
		return $message;
	}

	static function savelog($msg)
	{
		$time=date("Y-m-d H:i:s");
		$scrollScript = "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

		echo "[$time] $msg<br/>\r\n".$scrollScript;
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();

		file_put_contents("logs/".ID."_latest.log","[$time] $msg");
		file_put_contents("logs/".ID.".log","[$time] $msg\r\n",FILE_APPEND);

		if(file_exists("logs/".ID."_command.log"))
		{
			$txt_command = file_get_contents("logs/".ID."_command.log");
			if($txt_command == "STOP")
			{
				file_put_contents("logs/".ID."_latest.log","[$time] Force stop");
				file_put_contents("logs/".ID.".log","[$time] Force stop\r\n",FILE_APPEND);
				unlink("logs/".ID."_command.log");
				exit;
			}
		}

		if(file_exists("logs/".ID."_run_count.log"))
		{
			$txt_count = file_get_contents("logs/".ID."_run_count.log");
			if($txt_count != RUN_COUNT)
			{
				exit;
			}
		}

	}

	static function parse_curl_cookie($cookie_file)
    {
		if(file_exists($cookie_file))
		{
			$cookie = file_get_contents($cookie_file);
			$cookie = str_replace("\r\n","\n",$cookie);
			$cookie = str_replace("\r","\n",$cookie);
			$lines = explode("\n",$cookie);
			$result = array();
			foreach($lines as $line)
			{
				if(strpos($line,"okfreedating.net")>-1)
				{
					$contents = explode("\t",$line);
					$result[$contents[5]]=array("value"=>$contents[6],"expired"=>$contents[4]);
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
    }

	function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
	}

	function getCountExistingUser()
	{
		$sql = "SELECT COUNT(id) AS total FROM phonefling_member";
		return DBConnect::assoc_query_1D($sql);
	}

}
?>