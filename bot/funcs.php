<?php
error_reporting(0);
session_start();
define("ADMIN_PASSWORD", "KuhMaler$88");
define("ADMIN_REPORT_PASSWORD", "Flensburg1");
define("ADMIN_LOGS_PASSWORD", "LogFilesChecking");
define("ADMIN_TEST_PASSWORD", "BotTestProcess");

//unset($_SESSION['password']);
if(!(isset($_SESSION['password']))) //Launchpad1 : Flensburg1
{	if($_POST['password'])
	{
		$_SESSION['password']=$_POST['password'];
		//echo $_SESSION['password'];

		if($_POST['password']==ADMIN_PASSWORD)
			$url = " .";//echo "All";
		elseif($_POST['password']==ADMIN_REPORT_PASSWORD)
			$url = "summary-report.php";//echo "Only Report";
		elseif($_POST['password']==ADMIN_TEST_PASSWORD)
			$url = "bot-test.php";//echo "Only Report";
		header("location: $url");
		exit;
	}
	?>
	<form action="login.php" method="post">
	Password: <input type="password" name="password"/> <input type="submit" value="Login"/>
	</form>
	<?php
	exit;
}
require_once '_include/dbconnect.php';

$sql = "SELECT * FROM settings";
$result = mysql_query($sql);
$settings = array();
$row = array();
if($result){
	while($row = mysql_fetch_assoc($result))
		array_push($settings, $row);
}

if(is_array($settings))
{
	foreach($settings as $setting)
	{
		define($setting['setting_name'], $setting['setting_value']);
	}
}

class funcs
{
	static function memberlogin($username, $password)
	{
		if(!file_exists("cookie.txt"))
		{
			$cookie = fopen('cookie.txt', "w");
			fclose($cookie);
		}else {
			$cookie = fopen('cookie.txt', "w");
			fwrite($cookie, '1');
			fclose($cookie);
		}
		
		$postUrl = 'http://www.cheekyflirt.com/login.php';
		//$postData = "username=pinkpooh, password=123456,Submit2=login";
		$postData = "username=$username&password=$password&Submit2=login";
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.cheekyflirt.com/index.php');
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$loginData = curl_exec($ch);
		
		curl_close($ch);
		return $loginData;
// 		print_r($loginData);
	}
	
	function get_contents($start, $end, $page){
		if ($page>=1)
		{
			$postUrl = "http://www.cheekyflirt.com/users.php?gender=M&sexuality=&fromAge=18&toAge=30&country=5&country_area=&online=&images=&videoProfile=&signedUp=&miles=&username=&page=$page";
			$referUrl = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=$start&age_end=$end&frmlocation=0&frmDays=0&coreg_zone=search_quick";
			$referUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
		}
		
		else 
		{
			$postUrl = "http://www.cheekyflirt.com/users.php?gender=M&sexuality=&fromAge=18&toAge=30&country=5&country_area=&online=&images=&videoProfile=&signedUp=&miles=&username=";
			$postUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
			$referUrl = "http://www.myukdate.com/searchf.asp";
		}
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		//curl_setopt($ch, CURLOPT_REFERER, $referUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$search = curl_exec($ch);
// 		echo $case.'<br>';
// 		print_r($search);
		curl_close($ch);
	
		return $search;	
	}
	
	function file_get_data($data)
	{
		$arrStr = explode(' ', $data);
		$newArr = array();
		foreach ($arrStr as $key=>$val)
		{
			if(strstr($val, 'href="profile-home.php?userid='))
			{	
				$replace1 = str_replace('href="profile-home.php?userid=', '', $val);
				$replace2 = (int)str_replace('"', '', $replace1);
				if($replace2 != '')
				{
					array_push($newArr, array('recipient'=>$replace2));
				}
			}		
		}
		//$result = array_unique($newArr);

		return $newArr;
	}
	
	function send_message($receive_id, $subject, $message)
	{
		
		$postUrl = 'http://www.cheekyflirt.com/account-sendMessage.php';
		$postData = "action=_VERIFY&msgto=$receive_id&subject=$subject&rte1=$message";
		
		$ch = curl_init();
	
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		// curl_setopt($ch, CURLOPT_REFERER, 'http://www.myukdate.com/login.asp');
		curl_setopt($ch, CURLOPT_REFERER, $postUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$send = curl_exec($ch);
		
		curl_close($ch);
	}
	
	function insertLog($username, $receiver)
	{
		$sql = "insert into cheekyflirt (id, sender, name, send_date) values ('', '$username', '$receiver', now())";
		mysql_query($sql);
		
	}
	
	function insertLogPage($username, $page)
	{
		$sql = "insert into cheekyflirt_page (id, name, page) values ('', '$username', '$page')";
		mysql_query($sql);
				
	}
	
	static function replaceWord($text)
	{
		$search = array(' ', ',', "'");
		$replace = array('+', '%2C', '%27');
		
		return str_replace($search, $replace, $text);
	}

	function db_get_machine()
	{
		$sql = "select id, name from servers WHERE enabled='true'";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "name"=>$result['name']));
		}
		return $arr;
	}
	
	function db_get_sites()
	{
		$sql = "select s.id, s.name, m.total_messages from sites s left join (select site_id, count(*) as total_messages FROM messages group by site_id) m on m.site_id=s.id where s.status = 'true' and s.report = 'true' order by s.name asc";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr,$result);
		}
		
		return $arr;
	}

	function db_get_sites_test()
	{
		$sql = "select s.id, s.name, m.total_messages from sites s left join (select site_id, count(*) as total_messages FROM messages group by site_id) m on m.site_id=s.id where s.status = 'true' and s.report = 'true' and site_test='Y' order by s.name asc";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr,$result);
		}
		
		return $arr;
	}

	function db_get_site_info($siteid)
	{
		$sql = "select search_country, search_area, search_gender, search_sex, search_age, specify_msg from sites where id = '".$siteid."'";
		$query = mysql_query($sql);
		$result = mysql_fetch_assoc($query);

		return $result;
	}

	function db_get_loginprofile_by_site($site_id, $sex, $order="id DESC")
	{
		$sql = "select id, username, password, lastsent, used, DATEDIFF( NOW( ) , created_datetime ) AS age from user_profiles where status = 'true' and in_use = 'false' and site_id = '".$site_id."' and sex = '".$sex."' order by ".$order;
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "username"=>$result['username'], "password"=>$result['password'], "used"=>$result['used'], "age" => $result['age']));
		}

		return $arr;
	}

	function db_get_allprofile_by_site($site_id, $sex)
	{
		$sql = "select id, username, password, status, used from user_profiles where site_id = '".$site_id."' and sex = '".$sex."' and status !='banded' ORDER BY id";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "username"=>$result['username'], "password"=>$result['password'], "status"=>$result['status'], "used"=>$result['used']));
		}

		return $arr;
	}

	function db_get_profile_test_by_site($site_id)
	{
		$sql = "select id, username, password, status, used from profile_test where site_id = '".$site_id."' and status !='banded'";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "username"=>$result['username'], "password"=>$result['password'], "status"=>$result['status'], "used"=>$result['used']));
		}

		return $arr;
	}

	function db_get_loginprofile()
	{
		$sql = "select * from user_profiles where status = 'true'";
		$query = mysql_query($sql);
		
		$arr= array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "username"=>$result['username'], "password"=>$result['password']));
		}
		
		return $arr;
	}
	
	static function db_get_last_page()
	{
		$sql = "select max(page) as lastpage from cheekyflirt_page";
		$query = mysql_query($sql);
		
		return mysql_fetch_assoc($query);
	}
	
	static function dbCheckSentUser($user)
	{
		$sql = "select name from cheekyflirt where name = '$user'";
		$query = mysql_query($sql);
	
		$result = mysql_fetch_assoc($query);
	
		return $result;
	}
	
	static function db_get_spec_by_site($site){
		$sql = "select * from sites where id='".$site."'";//.$ext_sql
		$query = mysql_query($sql);		
		$result = mysql_fetch_assoc($query);
	
		return $result;
	}

	static function db_get_message($siteid = null, $target, $msg_group=1)
	{
		$ext_sql = " and (site_id='0' or site_id='$siteid')";// and site_id='0'
		if($siteid!=null)
		{
			$siteinfo = funcs::db_get_site_info($siteid);
			if($siteinfo['specify_msg']=="true")
				$ext_sql = " and site_id='$siteid'";
		}
		$sql = "select * from messages where status = 'true' and FIND_IN_SET('".$target."',target)>0 ".$ext_sql." AND msg_group=".$msg_group." ORDER BY id ASC";//.$ext_sql
		$query = mysql_query($sql);
		$arr = array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "subject" => $result['subject'], "text_message"=>$result['text_message']));
		}

		return $arr;
	}

	static function db_get_message_test($siteid = null, $msg_group=1)
	{
		$ext_sql = " and (site_id='0' or site_id='$siteid')";// and site_id='0'
		if($siteid!=null)
		{
			$siteinfo = funcs::db_get_site_info($siteid);
			if($siteinfo['specify_msg']=="true")
				$ext_sql = " and site_id='$siteid'";
		}
		$sql = "select * from messages where status = 'true' ".$ext_sql." AND msg_group=".$msg_group." ORDER BY id ASC";//.$ext_sql
		
		$query = mysql_query($sql);
		$arr = array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "subject" => $result['subject'], "text_message"=>$result['text_message']));
		}

		return $arr;
	}

	static function db_get_message_list($siteid, $target)
	{
		$siteinfo = funcs::db_get_site_info($siteid);
		if($siteinfo['specify_msg']=="true")
			$ext_sql = " and site_id='$siteid'";
		else
			$ext_sql = " and (site_id='0' or site_id='$siteid')";

		$sql = "select * from messages where target='".$target."' ".$ext_sql." ORDER BY id ASC";
		$query = mysql_query($sql);
		$arr = array();
		while ($result = mysql_fetch_assoc($query))
		{
			array_push($arr, array("id"=>$result['id'], "subject" => $result['subject'], "text_message"=>$result['text_message'], "status"=>$result['status'], "msg_group"=>$result['msg_group']));
		}

		return $arr;
	}

	static function db_get_message_info($messageid)
	{
		$sql = "select id, subject, text_message from messages where id = '".$messageid."'";
		$query = mysql_query($sql);
		$result = mysql_fetch_assoc($query);

		return $result;
	}

	static function db_count_profile()
	{
		$sql = "select id  from user_profiles where status = 'true'";
		$query = mysql_query($sql);
		
		$num = mysql_num_rows($query);

		return $num;
	}

	static function insertCommand($arr_command)
	{
		$start_on_time = isset($arr_command['start_on_time'])?$arr_command['start_on_time']:1;

		$sql = "insert into commands (server, site, sex, target, command, status, cdate, start_on_time, start_time, end_time, preset) values ('".$arr_command['server']."','".$arr_command['site']."','".$arr_command['sex']."','".$arr_command['target']."','".$arr_command['command']."','".$arr_command['status']."','".$arr_command['cdate']."','".$start_on_time."','".$arr_command['start_time']."','".$arr_command['end_time']."','".$arr_command['preset']."')";
		//echo "<br>".$sql."<br>"; exit;
		if(mysql_query($sql))
			return mysql_insert_id();
		else
			return false;
	}

	static function getCommand($id)
	{
		$sql = "select * from commands where id = '".$id."'";
		$query = mysql_query($sql);
		$result = mysql_fetch_assoc($query);
		return $result;
	}

	static function getCommandStatus()
	{
		$sql = "select c.start_time as start, c.site, c.server, c.id as id, c.sex, c.target, c.command as command, c.response, s.name as servername, s.ip as ip, s2.name as sitename, sc.start_date, sc.end_date, sc.start_time, sc.end_time, c.run_count, s.enabled_stop_all from commands c left join servers s on c.server = s.id left join sites s2 on c.site = s2.id left join schedule sc on c.id=sc.id where c.status='true' order by sitename asc, c.sex";
		$query = mysql_query($sql);
		//$result = mysql_fetch_assoc($query);
		return $query;
	}

	static function getRunningCommandCount()
	{
		$sql = "select count(*) from commands c left join sites s2 on c.site = s2.id where c.run_count>0 AND c.status='true'";
		$query = mysql_query($sql);
		//$result = mysql_fetch_assoc($query);
		return current(mysql_fetch_row($query));
	}

	static function getNoResponseCommandCount()
	{
		$sql = "select count(*) from commands c left join sites s2 on c.site = s2.id where c.run_count>0 AND c.status='true' AND response=3";
		$query = mysql_query($sql);
		//$result = mysql_fetch_assoc($query);
		return current(mysql_fetch_row($query));
	}

	function get_data($url,$post=array())
	{
		$repeat = 2;
		$ch = curl_init();
		$timeout = 2;
		$httpCode = 0;
		curl_setopt($ch,CURLOPT_FRESH_CONNECT,true);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		do
		{
			$data = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($httpCode == 403)
			{
				if($repeat>0)
				{
					$repeat--;
					sleep(5);
				}
				else
				{
					logging("Fail after $repeat times retries.");
					$httpCode=0;
				}
			}
		}while($httpCode==403);

		curl_close($ch);

		$output = "";
		$data = htmlspecialchars($data);

		// Add color for each line of log
		$red_count=0;
		$green_count=0;
		$send_completed_count=0;
		$sign_guestbook_completed_count = 0;
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
			if(strpos($line,"failed")>-1)
			{
				$line = "<font color='red'><b>$line</b></font>";
				$red_count++;
			}
			elseif(strpos($line,"Random new profile")>-1)
				$line = "<font color='blue'><b>$line</b></font>";
			elseif(strpos($line,"Sent message to")>-1)
			{
				$line = "<font color='green'><b>$line</b></font>";
				$green_count++;
			}
			elseif(strpos($line,"completed")>-1)
			{
				$line = "<font color='green'><b>$line</b></font>";
				$green_count++;
			}
			elseif(strpos($line,"Search for")>-1)
				$line = "<font size='4'><b>$line</b></font>";

			if(strpos($line, "Sending message completed.")!==false)
				$send_completed_count++;
			elseif(strpos($line, "Signing guestbook completed.")!==false)
				$sign_guestbook_completed_count++;

			$output .= $line."\r\n";
		}
		$output = "\n<strong>SEND MESSAGE COMPLETED: ".$send_completed_count."\n<strong>SIGN GUESTBOOK COMPLETED: ".$sign_guestbook_completed_count."\n<strong>RED LINE: ".$red_count."\nGREEN LINE: ".$green_count."</strong>\n\n".$output;
		return $output;
	}

	function get_last_modified($data)
	{
		$temp = explode("\n",$data);
		$last = "";
		foreach($temp as $line)
		{
			$line = strip_tags($line);
			if((strpos($line,"[")==0) && (strpos($line,"]")>0))
			{
				$last=substr($line,1,strpos($line,"]")-1);
				$dt = new DateTime($last);
				$last=((int)$dt->getTimestamp());

				$msg=substr($line,strpos($line,"]")+1);
			}
		}
		return array('time'=>$last, 'message'=>trim($msg));
	}

	/********************************************************/
	function get_all_site()
	{
		$sql = "SELECT id, name, status FROM sites ORDER BY name ASC";
		$query = mysql_query($sql);
		//$result = mysql_fetch_assoc($query);
		return $query;
	}

	function get_all_message()
	{
		$sql = "SELECT id, subject, text_message, status FROM messages ORDER BY id ASC";
		$query = mysql_query($sql);
		//$result = mysql_fetch_assoc($query);
		return $query;
	}

	function get_message_by_id($id)
	{
		$sql = "SELECT id, subject, text_message, site_id, target, status, msg_group FROM messages WHERE id = '".$id."' LIMIT 0, 1";
		$query = mysql_query($sql);
		$result = mysql_fetch_assoc($query);
		return $result;
	}

	function get_all_maskurl($cond = null, $order="id ASC")
	{
		if($cond!=null)
			$ext = "WHERE status = 'true'";
		else
			$ext = "";
		$sql = "SELECT * FROM mask_url $ext ORDER BY ".$order;
		$query = mysql_query($sql);
		return $query;
	}

	function get_maskurl_by_target($cond = null, $order="id DESC")
	{
		if($cond==null)
			$ext = "";
		else
			$ext = "WHERE status = 'true' AND ".$cond;
		$sql = "SELECT * FROM mask_url $ext ORDER BY ".$order;
		$query = mysql_query($sql);
		return $query;
	}

	function get_all_target_cm($cond = null, $order="target ASC")
	{
		if($cond!=null)
			$ext = "WHERE status = 'true' AND target!=''";
		else
			$ext = "";
		$sql = "SELECT target FROM mask_url $ext GROUP BY target ORDER BY ".$order;
		$query = mysql_query($sql);
		return $query;
	}

	function get_log_by_site($site_id)
	{
		$sql = "SELECT c.id, s.name AS server, c.sex, c.target, c.cdate, c.finished_datetime AS fdate FROM commands c LEFT JOIN servers s ON c.server = s.id WHERE c.status != 'true' and c.site = '".$site_id."' ORDER BY fdate DESC LIMIT 0, 200";
		$query = mysql_query($sql);
		return $query;
	}

	function setInUseStatus($username, $siteid, $status)
	{
		$set = "SET in_use = '".$status."'";
		if($status=="true"){
			$set = $set.", used='true'";
		}

		$sql = "UPDATE user_profiles ".$set." WHERE username = '".$username."' AND site_id = '".$siteid."'";		
		mysql_query($sql);
	}

	/********************************************************/

	function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
	}

	function curl_post_async($url, $params)
	{
		foreach ($params as $key => &$val) {
		  if (is_array($val)) $val = implode(',', $val);
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$parts=parse_url($url);

		$fp = fsockopen($parts['host'],
			isset($parts['port'])?$parts['port']:80,
			$errno, $errstr, 30);

		$out = "POST ".$parts['path']."?".$parts['query']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		if (isset($post_string)) $out.= $post_string;

		fwrite($fp, $out);
		fclose($fp);
	}
	
	/**
	 * Pok Added
	 */
	public static final function _ago($tm,$rcs = 0) {
	   $cur_tm = time(); $dif = $cur_tm-$tm;
	   $pds = array('second','minute','hour','day','week','month','year','decade');
	   $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
	   for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
	
	   $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ago",$no,$pds[$v]);
	   // if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
	   return $x;
	}
}
?>