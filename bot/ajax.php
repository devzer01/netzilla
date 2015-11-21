<?php
require_once 'funcs.php';
switch($_REQUEST['section'])
{
	case 'reassign':
		$site_id = is_numeric($_GET['site_id'])?$_GET['site_id']:0;
		$sex = in_array($_GET['sex'], array("Male", "Female","Gay", "Lesbian"))?$_GET['sex']:"Female";
		$num = is_numeric($_GET['num'])?$_GET['num']:1;

		$sql = "UPDATE user_profiles SET status='true', in_use='false' WHERE site_id=".$site_id." AND sex='".$sex."' AND status='false' ORDER BY created_datetime ASC LIMIT ".$num;
		mysql_query($sql);
		break;
	case 'getUserProfile':
		$userProfiles = funcs::db_get_loginprofile_by_site($_POST['id'],$_POST['sex'], "used DESC, id DESC");
		$returnText = "<span>[total ".count($userProfiles)." profiles]</span> <a href='#' style='float: right' onclick='reassignProfiles(); return false;'>Re-assign 20 profiles</a><br clear='both'/>";
		if(count($userProfiles)>0)
		{
			//$checked = 'checked="checked"';
			$checked = '';
			foreach($userProfiles as $profile)
			{
				$age = $profile['age'];
				if(strpos($profile['username'],"@")!==false)
					$returnText .= "<label class='list'><input type='checkbox' name='profiles[]' value='".$profile['username'].": ".$profile['password']."' ".$checked.">"." ".(($profile['used']=='false')?"[*] ":"").substr(htmlspecialchars($profile['username']),0,strpos(htmlspecialchars($profile['username']),"@"))." [".$age."]</label>";
				else
					$returnText .= "<label class='list'><input type='checkbox' name='profiles[]' value='".$profile['username'].": ".$profile['password']."' ".$checked.">".(($profile['used']=='false')?"[*] ":"").htmlspecialchars($profile['username'])." [".$age."]</label>";
				$checked = '';
			}
			echo $returnText;
		}
		else
		{
			echo "<span style='color:red'>There is no user profile for this site.</span>";
		}
		break;
	case "getMaskURL":
			if(isset($_POST['target']) && ($_POST['target']!=''))
				$result = funcs::get_maskurl_by_target("target='".$_POST['target']."'");
			else
				$result = funcs::get_maskurl_by_target("");
			echo "<option value='' selected='selected'>Please Select</option>";
			while($mask_url = mysql_fetch_assoc($result))
			{
				echo "<option value='$mask_url[name]'>$mask_url[name]</option>";
				$selected = "";
			}
		break;
	case 'getUserProfileTest':
		$userProfiles = funcs::db_get_loginprofile_by_site($_POST['id'],$_POST['sex'], "used DESC, id DESC");
		$returnText = "<a href='#' style='float: right' onclick='reassignProfiles(); return false;'>Re-assign 20 profiles</a><br clear='both'/>";
		if(count($userProfiles)>0)
		{
			$checked = 'checked="checked"';
			foreach($userProfiles as $profile)
			{
				$age = $profile['age'];
				if(strpos($profile['username'],"@")!==false)
					$returnText .= "<label class='list'><input type='radio' name='profiles' value='".$profile['username'].": ".$profile['password']."' ".$checked.">"." ".(($profile['used']=='false')?"[*] ":"").substr(htmlspecialchars($profile['username']),0,strpos(htmlspecialchars($profile['username']),"@"))." [".$age."]</label>";
				else
					$returnText .= "<label class='list'><input type='radio' name='profiles' value='".$profile['username'].": ".$profile['password']."' ".$checked.">".(($profile['used']=='false')?"[*] ":"").htmlspecialchars($profile['username'])." [".$age."]</label>";
				$checked = '';
			}
			echo $returnText;
		}
		else
		{
			echo "<span style='color:red'>There is no user profile for this site.</span>";
		}
		break;
	case 'getUserProfileList':
		$userProfiles = funcs::db_get_allprofile_by_site($_POST['id'],$_POST['sex']);
		$returnText = "";
		if(count($userProfiles)>0)
		{
			foreach($userProfiles as $profile)
			{	if($_POST['action']=='add')
				{
					if($profile['status']=='true')
						$status = "checked='checked'";
					else
						$status = "";
				}
				else
				{
					$status = "";
				}
				$returnText .= "<label class='userlist'><input type='checkbox' name='profiles[]' value='".$profile['id']."' ".$status.">".(($profile['used']=='false')?"[*] ":"")." '".htmlspecialchars($profile['username'])."': '".htmlspecialchars($profile['password'])."'</label>";
			}
			echo $returnText;
		}
		else
		{
			echo "<span style='color:red'>There is no user profile for this site.</span>";
		}
		break;
	case 'getSpec':
		$rs = funcs::db_get_spec_by_site($_POST['site']);
		echo $rs['spec'];

		break;
	case 'getProfileTest':
		$userProfiles = funcs::db_get_profile_test_by_site($_POST['site']);
		$returnText = "";
		if(count($userProfiles)>0)
		{
			$checked = 'checked="checked"';

			foreach($userProfiles as $profile)
			{	
				$returnText .= "<label class='userlist'><input type='radio' name='profilesTest' value='".$profile['id']."' ".$checked.">".(($profile['used']=='false')?"[*] ":"")." '".htmlspecialchars($profile['username'])."': '".htmlspecialchars($profile['password'])."'</label>";
				$checked = '';
			}
			echo $returnText;
		}
		else
		{
			echo "<span style='color:red'>There is no user profile for this site.</span>";
		}
		break;
	case 'getMessage':
		$messages = funcs::db_get_message($_POST['id'], $_POST['target'], $_POST['msg_group']);
		$returnText = "";
			if(count($messages)>0)
			{
				foreach($messages as $message)
				{
					$returnText .= "<label style='margin-bottom:10px; width:600px; display:block; float:left; padding:2px'><input type='checkbox' name='messages[]' value='".$message['id']."' checked=checked>#".$message['id']."  <strong>".$message['subject']."</strong> :: ".$message['text_message']."</label>";
				}
				echo $returnText;
			}
			else
			{
				echo "<span style='color:red'>There is no messages in database.</span>";
			}
		break;
	case 'getMessageTest':
		$messages = funcs::db_get_message_test($_POST['id'], $_POST['target'], $_POST['msg_group']);
		$returnText = "";
			if(count($messages)>0)
			{
				foreach($messages as $message)
				{
					$returnText .= "<label style='margin-bottom:10px; width:600px; display:block; float:left; padding:2px'><input type='checkbox' name='messages[]' value='".$message['id']."' checked=checked>#".$message['id']."  <strong>".$message['subject']."</strong> :: ".$message['text_message']."</label>";
				}
				echo $returnText;
			}
			else
			{
				echo "<span style='color:red'>There is no messages in database.</span>";
			}
		break;
	case 'getMessageList':
		$messages = funcs::db_get_message_list($_POST['id'],$_POST['sex']);
		$returnText = "";
			if(count($messages)>0)
			{
				$returnText .= '<table><tr><th width="50px">ID</th><th>Message</th><th width="50px">Group</th><th width="70px">Action</th></tr>';
				foreach($messages as $message)
				{
					$returnText .= "
					<tr>
						<td><input type='checkbox' name='messages[]' value='".$message['id']."' >#".$message['id']."</td>
						<td><strong>".$message['subject']."</strong> :: ".$message['text_message'].'</td>
						<td align="center">'.$message['msg_group'].'</td>
						<td><a href="manage-message.php?action=edit&id='.$message['id'].'">Edit</a> <a href="manage-message.php?action=delete&id='.$message['id'].'" onclick ="return confirm(\'Are you sure to delete this message?\')">Delete</a></td>
					</tr>';
				}
				$returnText .= "</table>";
				echo $returnText;
			}
			else
			{
				echo "<span style='color:red'>There is no messages in database.</span>";
			}
		break;
	case 'fetchStatus':
		$formurl = 'index.php';
		if(!empty($_GET['returnTo'])) {
			$formurl = $_GET['returnTo'];
		}
		
		$query = funcs::getCommandStatus();
		$content = "<div style='float:right'><input type='checkbox' id='enableRefresh' checked='checked' value='1'> Refresh?</div><div style='float:left'>".mysql_num_rows($query)." total / ".funcs::getRunningCommandCount()." running. [<span style='color: red'>".funcs::getNoResponseCommandCount()."</span>]</div><div style='float:right'>";

		// Ads Market
		$content .= "<form action='".$formurl."' method='get' onsubmit='return confirm(\"Are your sure to STOP ALL RUNNING BOTS on selected contact market?\")' style='float: left'>Market: <input type='hidden' name='action' value='stopmarket'/><select name='stop_target'>";
		$content .=  "<option value=''>-</option>";
		$content .=  "<option value='DE_1'>DE (ALL BOTS)</option>";
		$content .=  "<option value='DE_3'>DE (NOT START ONLY)</option>";
		$content .=  "<option value='DE_2'>DE (RUNNING ONLY)</option>";
		$content .=  "<option value='UK_1'>UK (ALL BOTS)</option>";
		$content .=  "<option value='UK_3'>UK (NOT START ONLY)</option>";
		$content .=  "<option value='UK_2'>UK (RUNNING ONLY)</option>";
		$content .=  "<option value='NICK_1'>NICK (ALL BOTS)</option>";
		$content .=  "<option value='NICK_3'>NICK (NOT START ONLY)</option>";
		$content .=  "<option value='NICK_2'>NICK (RUNNING ONLY)</option>";
		$content .= "</select><input type='submit' value='Stop'/></form>";


		$content .= "<form action='".$formurl."' method='get' onsubmit='return confirm(\"Are your sure to STOP ALL RUNNING BOTS on selected contact market?\")' style='float: left'>|| Site: <input type='hidden' name='action' value='stopall'/><select name='stop_site'>";
		$arrSites = funcs::db_get_sites();
		$content .=  "<option value=''>-</option>";
		$content .=  "<option value='0'>All</option>";
		foreach ($arrSites as $siteData)
		{
			$content .= "<option value='$siteData[id]'>$siteData[name]</option>";
		}
		$content .= "</select><input type='submit' value='Stop'/></form>";

		$content .= "<form action='".$formurl."' method='get' onsubmit='return confirm(\"Are your sure to STOP ALL RUNNING BOTS on selected preset group?\")' style='float: right'>|| Preset group: <input type='hidden' name='action' value='stoppreset'/><select name='preset'><option value=''>-</option>";
		$content .=  "<option value='DE_1'>DE PRESET (ALL)</option>";
		$content .=  "<option value='DE_3'>DE PRESET (WAIT)</option>";
		$content .=  "<option value='DE_2'>DE PRESET (RUNNING)</option>";
		$content .=  "<option value='UK_1'>UK PRESET (ALL)</option>";
		$content .=  "<option value='UK_3'>UK PRESET (WAIT)</option>";
		$content .=  "<option value='UK_2'>UK PRESET (RUNNING)</option>";
		$content .=  "<option value='NICK_1'>NICK PRESET (ALL)</option>";
		$content .=  "<option value='NICK_3'>NICK PRESET (WAIT)</option>";
		$content .=  "<option value='NICK_2'>NICK PRESET (RUNNING)</option>";
		$content .= "<option value=''>-</option>";
		
		$sql = "SELECT DISTINCT(group_name) FROM preset WHERE group_name!=''";
		$result = mysql_query($sql);
		$arrPresets = array();
		$row = array();
		if($result){
			while($row = mysql_fetch_assoc($result))
				array_push($arrPresets, $row);

			if(count($arrPresets))
			{
				foreach ($arrPresets as $siteData)
				{
					$content .= "<option value='$siteData[group_name]'>$siteData[group_name]</option>";
				}
				$content .= "</select><input type='submit' value='Stop'/></form>";
			}
		}

		$content .= "</div><br clear='all'/>
			<table class='status'>
				<tr>
					<th width='40'>No.</th>
					<th width='90'>Server</th>
					<th width='100'>Site</th>
					<th width='40'>Sender</th>
					<th width='40'>Target</th>
					<th width='80'>Action</th>
					<th width='100'>Status</th>
					<th width='300'>Latest</th>
				</tr>
				";
		
				$timeout = 5*60;
				$time_adjustment = 0;
				$prev_site = "";
				$prev_sex = "";
				$class = "w";
				$i = 0;

				while($command = mysql_fetch_assoc($query)){
					if($command['run_count'])
					{
						//$start = microtime();
						$log_url = "http://".$command['ip']."/postdata/".$command['sitename']."/logs/".$command['id']."_latest.log";//"http://192.168.1.35/postdata/cheekyflirt/logs/16_lasted.log";//$command['ip']
						$data = funcs::get_data($log_url);
						//$end = microtime();
						//$latency = microtime_diff ($start, $end);
					}
					else
					{
						$data = "";
						//$latency = 0;
					}
					$data = funcs::get_last_modified($data);/**/
					$action = funcs::mb_unserialize($command['command']);
					$send_test = $action['send_test'];
					
					if($send_test !== 1){
						if($action['action']=="send")
						{
							$action='';
						}
						else
						{
							$action=" [".$action['action']."]";
						}


						$time = $data['time']+$time_adjustment;
						$diff = time()-$time;
						/*if(file_exists($log_url))
							$status = "Yes";
						else
							$status = "No";*/

						if($diff>$timeout)
							$status = "Offline [".$command['start']."]";
						else
							$status = "Running. [$diff s]";

						if(($prev_site != $command['sitename']) || ($prev_sex != $command['sex']))
						{
							if($i%2==0)
								$class = "w";
							else
								$class = "c";
							$prev_site = $command['sitename'];
							$prev_sex = $command['sex'];
							$i++;
						}

						//echo "<!-- ".print_r($command,true)." -->";

						if(!empty($command['end_date']))
							$schedule = '<a href="#" onclick="alert(\'Run on '.$command['start_date'].' to '.$command['end_date'].', '.$command['start_time'].' to '.$command['end_time'].'\')"><img src="schedule-icon.png" height="16" width="16"/></a> ';
						else
							$schedule = "";

			$test_msg = '';
			if($send_test == 1){
				$test_msg = ' - test';
			}

			$max_chars = 70;
			
			$content .= "<tr class='response".$command['response']."'>
						<td class='".$class."' align='right'>".$schedule.$command['id']."</td>
						<td class='".$class."'>".$command['servername']."</td>
						<td class='".$class."'>".$command['sitename'].$action.$test_msg."</td>
						<td class='".$class."'>".$command['sex']."</td>
						<td class='".$class."'>".$command['target']."</td>
						<td class='".$class."'><a href='index.php?action=view_log&id=".$command['id']."' target='_blank'>View</a> &nbsp;|&nbsp; <a href='index.php?action=stop&id=".$command['id']."'>Stop</a>".((strpos($status,"Offline")!==false)?"&nbsp;|&nbsp; <a href='index.php?action=start&id=".$command['id']."'>Start</a>":"")."</td>
						<td class='".$class."'>".$status."</td>
						<td class='".$class."' style='overflow:hidden'>".(strlen($data['message'])>$max_chars?substr($data['message'],0,$max_chars)."...":$data['message'])."</td>
					</tr>
					";/*.$log_url */
					}
				}
		$content .= "</table>";
		echo $content."<br/>Timestamp: ".date('Y-m-d H:i:s');
		break;
	case 'fetchStatusTest':
		$query = funcs::getCommandStatus();
		//$content = "<div style='float:left'>Total ".mysql_num_rows($query)." processes running.</div><div style='float:right'>";
/*
		$content .= "<form action='index.php' method='get' onsubmit='return confirm(\"Are your sure to STOP ALL RUNNING BOTS on selected contact market?\")' style='float: left'>Site: <input type='hidden' name='action' value='stopall'/><select name='stop_site'>";
		$arrSites = funcs::db_get_sites();
		$content .=  "<option value='0'>All</option>";
		foreach ($arrSites as $siteData)
		{
			$content .= "<option value='$siteData[id]'>$siteData[name]</option>";
		}
		$content .= "</select><input type='submit' value='Stop'/></form>";

		$content .= "<form action='index.php' method='get' onsubmit='return confirm(\"Are your sure to STOP ALL RUNNING BOTS on selected preset group?\")' style='float: right'>|| Preset group: <input type='hidden' name='action' value='stoppreset'/><select name='preset'>";
		$sql = "SELECT DISTINCT(group_name) FROM preset WHERE group_name!=''";
		$result = mysql_query($sql);
		$arrPresets = array();
		$row = array();
		
		if($result){
			
			while($row = mysql_fetch_assoc($result)){
				array_push($arrPresets, $row);
			}

			if(count($arrPresets))
			{
				foreach ($arrPresets as $siteData)
				{
					$content .= "<option value='$siteData[group_name]'>$siteData[group_name]</option>";
				}
				$content .= "</select><input type='submit' value='Stop'/></form>";
			}
		}*/

		$content .= "</div><br clear='all'/>
			<table class='status'>
				<tr>
					<th width='40'>No.</th>
					<th width='90'>Server</th>
					<th width='100'>Site</th>
					<th width='40'>Sender</th>
					<th width='40'>Target</th>
					<th width='80'>Action</th>
					<th width='100'>Status</th>
					<th width='300'>Latest</th>
				</tr>
				";
		
				$timeout = 5*60;
				$time_adjustment = 0;
				$prev_site = "";
				$prev_sex = "";
				$class = "w";
				$i = 0;

				while($command = mysql_fetch_assoc($query)){
					$log_url = "http://".$command['ip']."/postdata/".$command['sitename']."/logs/".$command['id']."_latest.log";//"http://192.168.1.35/postdata/cheekyflirt/logs/16_lasted.log";//$command['ip']
					$data = funcs::get_data($log_url);
					$data = funcs::get_last_modified($data);/**/
					$action = funcs::mb_unserialize($command['command']);
					$send_test = $action['send_test'];
					
					if($send_test == 1){
						if($action['action']=="send")
						{
							$action='';
						}
						else
						{
							$action=" [".$action['action']."]";
						}


						$time = $data['time']+$time_adjustment;
						$diff = time()-$time;
						/*if(file_exists($log_url))
							$status = "Yes";
						else
							$status = "No";*/

						if($diff>$timeout)
							$status = "Offline [".$command['start']."]";
						else
							$status = "Running. [$diff s]";

						if(($prev_site != $command['sitename']) || ($prev_sex != $command['sex']))
						{
							if($i%2==0)
								$class = "w";
							else
								$class = "c";
							$prev_site = $command['sitename'];
							$prev_sex = $command['sex'];
							$i++;
						}

						//echo "<!-- ".print_r($command,true)." -->";

						if(!empty($command['end_date']))
							$schedule = '<a href="#" onclick="alert(\'Run on '.$command['start_date'].' to '.$command['end_date'].', '.$command['start_time'].' to '.$command['end_time'].'\')"><img src="schedule-icon.png" height="16" width="16"/></a> ';
						else
							$schedule = "";

			$test_msg = '';
			if($send_test == 1){
				$test_msg = ' - test';
			}

			$max_chars = 70;
			$content .= "
					<tr>
						<td class='".$class."' align='right'>".$schedule.$command['id']."</td>
						<td class='".$class."'>".$command['servername']."</td>
						<td class='".$class."'>".$command['sitename'].$action.$test_msg."</td>
						<td class='".$class."'>".$command['sex']."</td>
						<td class='".$class."'>".$command['target']."</td>
						<td class='".$class."'><a href='bot-test.php?action=view_log&id=".$command['id']."' target='_blank'>View</a> &nbsp;|&nbsp; <a href='bot-test.php?action=stop&id=".$command['id']."'>Stop</a>".((strpos($status,"Offline")!==false)?"&nbsp;|&nbsp; <a href='bot-test.php?action=start&id=".$command['id']."'>Start</a>":"")."</td>
						<td class='".$class."'>".$status."</td>
						<td class='".$class."' style='overflow:hidden'>".(strlen($data['message'])>$max_chars?substr($data['message'],0,$max_chars)."...":$data['message'])."</td>
					</tr>
					";/*.$log_url */
					}
				}
		$content .= "</table>";
		echo $content."<br/>Timestamp: ".date('Y-m-d H:i:s');
		break;
	case 'getLog':
		$query = funcs::get_log_by_site($_POST['id']);
		$total = round(mysql_num_rows($query)/2);
		if($total>0){
			$content = "
				<div style='float:left'>
				<table>
					<tr>
						<th>No.</th>
						<th>Server</th>
						<th>Sender</th>
						<th>Target</th>
						<th>Fihish Date</th>
						<th> </th>
					</tr>
					";
			$i = 0;
			while ($logs = mysql_fetch_assoc($query))
			{
				if($total==$i)
				{
					$content .= "
				</table></div>
				<div style='float:right'>
				<table>
					<tr>
						<th>No.</th>
						<th>Server</th>
						<th>Sender</th>
						<th>Target</th>
						<th>Fihish Date</th>
						<th> </th>
					</tr>";
				}
				$content .= "
					<tr>
						<td>".$logs['id']."</td>
						<td>".$logs['server']."</td>
						<td>".$logs['sex']."</td>
						<td>".$logs['target']."</td>
						<td>".date("d M H:i", strtotime($logs['fdate']))."</td>
						<td><a href='index.php?action=view_log&id=".$logs['id']."' target='_blank'>View Log</a></td>
					</tr>
					"; //&in_use=false
				$i++;
			}
			$content .= "</table></div>";
			echo $content;
		}
		else
			echo "<span style='color:red'>There is no log for this site.</span>";
		break;
}

function microtime_diff($a, $b)
{
	list($a_dec, $a_sec) = explode(" ", $a);
	list($b_dec, $b_sec) = explode(" ", $b);

	return ((float)$b_sec - (float)$a_sec + (float)$b_dec - (float)$a_dec);
}
?>