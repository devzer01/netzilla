<?php
date_default_timezone_set("Asia/Bangkok");
require_once 'funcs.php';
if($_GET['action']=="view_log")
{
	$sql = "select c.id as id, c.command, s.name as servername, s.ip as ip, s2.id as siteid, s2.name as sitename from commands c left join servers s on c.server = s.id left join sites s2 on c.site = s2.id where c.id=".$_GET['id'];// c.status='true' and
	$result = mysql_query($sql);
	if(!$result)
	{
		header("location: .");
		exit;
	}
	else
	{
		$command = mysql_fetch_assoc($result);
		$command_post = funcs::mb_unserialize($command['command']);
		$log_url = "http://".$command['ip']."/postdata/".$command['sitename']."/logs/".$command['id'].".log";
		echo "<pre>";
		print_r($command_post);
		echo funcs::get_data($log_url);
		echo "</pre>";
		echo $command['sitename']."[".$command['id']."]";
		exit;
	}
}

if($_SESSION['password']!=ADMIN_PASSWORD){

	if($_SESSION['password']==ADMIN_REPORT_PASSWORD)
		$url = "summary-report.php";
	elseif($_SESSION['password']==ADMIN_TEST_PASSWORD)
		$url = "bot-test.php";

	header("location: $url");

	exit;
}


$arrPost = array();
$status = "";
if($_GET['action']=="stop")
{
	$sql = "UPDATE commands SET status='stop', finished_datetime = NOW() WHERE id=".$_GET['id'];
	$result = mysql_query($sql);

	$sql = "UPDATE schedule SET status='false' WHERE id=".$_GET['id'];
	$result = mysql_query($sql);

	$sql = "SELECT command, site FROM commands WHERE id=".$_GET['id'];
	$result = mysql_query($sql);
	$command = mysql_fetch_assoc($result);
	$command_post = funcs::mb_unserialize($command['command']);
	$user_profiles = "";
	foreach($command_post['profiles'] as $key => $profilename)
	{
		funcs::setInUseStatus($profilename['username'], $command['site'], 'false');
	}
	header("location: .");
	exit;
}
elseif($_GET['action']=="start")
{
	$command = mysql_query("SELECT c.*,si.name as site_name, se.ip FROM commands c LEFT JOIN sites si ON c.site=si.id LEFT JOIN servers se ON c.server=se.id WHERE c.id=".$_GET['id']);
	$command = mysql_fetch_assoc($command);
	$command_url = "http://".$command['ip']."/postdata/".$command['site_name']."/send-message.php";

	mysql_query("UPDATE commands SET run_count=run_count+1 WHERE id=".$_GET['id']);
	funcs::curl_post_async($command_url,$command);
	header("location: .");
	exit;
}
elseif($_GET['action']=="stopall")
{
	if(isset($_GET['stop_site']))
	{
		if($_GET['stop_site']!="")
			$site = $_GET['stop_site'];
	}
	else
	{
		$site = 0;
	}

	if($_GET['stop_site']!="")
	{
		$query = funcs::getCommandStatus();
		while($command = mysql_fetch_assoc($query))
		{
			if(($site==0) || ($command['site']==$site))
			{
				$id = $command['id'];
				$sql = "UPDATE commands SET status='stop', finished_datetime = NOW() WHERE id=".$id;
				$result = mysql_query($sql);

				$sql = "UPDATE schedule SET status='false' WHERE id=".$id;
				$result = mysql_query($sql);

				$sql = "SELECT command, site FROM commands WHERE id=".$id;
				$result = mysql_query($sql);
				$command = mysql_fetch_assoc($result);
				$command_post = funcs::mb_unserialize($command['command']);
				$user_profiles = "";
				if(isset($command_post['profiles']) && is_array($command_post['profiles']) && count($command_post['profiles']))
				{
					foreach($command_post['profiles'] as $key => $profilename)
					{
						funcs::setInUseStatus($profilename['username'], $command['site'], 'false');
					}
				}
			}
		}
	}
	header("location: .");
	exit;
}
elseif($_GET['action']=="stoppreset")
{
	$preset = isset($_GET['preset'])?$_GET['preset']:"";
	if($preset != "")
	{
		$sql = "SELECT * FROM commands WHERE preset='".$preset."'";
		$result = mysql_query($sql);
		$arrCommands = array();
		$row = array();
		if($result)
		{
			while($row = mysql_fetch_assoc($result))
				array_push($arrCommands, $row);

			if(count($arrCommands))
			{
				foreach($arrCommands as $command)
				{
					$id = $command['id'];
					$sql = "UPDATE commands SET status='stop', finished_datetime = NOW() WHERE id=".$id;
					$result = mysql_query($sql);

					$sql = "UPDATE schedule SET status='false' WHERE id=".$id;
					$result = mysql_query($sql);

					$command_post = funcs::mb_unserialize($command['command']);
					$user_profiles = "";
					if(isset($command_post['profiles']) && is_array($command_post['profiles']) && count($command_post['profiles']))
					{
						foreach($command_post['profiles'] as $key => $profilename)
						{
							funcs::setInUseStatus($profilename['username'], $command['site'], 'false');
						}
					}
				}
			}
		}
	}
	header("location: .");
	exit;
}

elseif($_GET['action']=="preset_popup")
{
	?>
	Name: <input type="text" name="name" id="name"/>
	<input type="button" value="Save" onclick="savePreset($('#name').val());"/>
	<?php
	exit;
}
elseif($_GET['action']=="save_preset")
{
	$name = $_POST['preset_name'];
	$id = $_POST['preset_id'];
	if($name!="")
	{
		$unset_arr = array('profiles', 'preset_name', 'preset_id');
		foreach($unset_arr as $item)
		{
			unset($_POST[$item]);
		}
		mysql_query("INSERT INTO preset (name, site_id, status, setting) VALUES ('".$name."', '".$_POST['site']."', '1', '".serialize($_POST)."')");
		echo "FINISH";
	}
	elseif($id!="")
	{
		$unset_arr = array('profiles', 'preset_name', 'preset_id');
		foreach($unset_arr as $item)
		{
			unset($_POST[$item]);
		}
		mysql_query("UPDATE preset SET site_id='".$_POST['site']."', status='1', setting='".serialize($_POST)."' WHERE id='".$id."'");
		echo "FINISH";
	}
	exit;
}
elseif($_GET['action']=="load_preset")
{
	$sql = "SELECT * FROM preset WHERE id = ".$_POST['id'];
	$result = mysql_query($sql);
	if($result)
	{
		$result = mysql_fetch_assoc($result);
		$result = unserialize($result['setting']);
		$result = array_slice($result, $_POST['index'], 1);
		if($result)
			echo json_encode($result);
	}
	exit;
}
elseif($_GET['action']=="load_preset_popup")
{
	$sql = "SELECT p.id, p.name, s.name as site_name, p.group_name FROM preset p LEFT JOIN sites s ON p.site_id=s.id ORDER BY group_name ASC, site_name ASC, name ASC";
	$result = mysql_query($sql);
	$arr = array();
	$row = array();
	if($result){
		while($row = mysql_fetch_assoc($result))
			array_push($arr, $row);

		if(count($arr))
		{
			echo "<table><tr><th><input type='checkbox' id='allPresets'/></th><th width='30'>ID</th><th width='130'>Name</th><th>Group</th><th width='100'>Site</th><th>Action</th></tr>";
			foreach($arr as $item)
			{
				echo "<tr>";
				echo "<td><input type='checkbox' name='presetID[]' value='".$item['id']."'/></td>";
				echo "<td align='center'>".$item['id']."</td>";
				echo "<td>".$item['name']."</td>";
				echo "<td>".$item['group_name']."</td>";
				echo "<td>".$item['site_name']."</td>";
				echo "<td>
						<a href='#' onclick='loadPreset(\"".$item['id']."\", 0)'>Load</a> ||
						<a href='#' onclick='runPreset(\"".$item['id']."\")'>Run</a> ||
						<!-- <a href='#' onclick='loadAndRunPreset(\"".$item['id']."\", 0)'>Load & Run</a> || -->
						<a href='#' onclick='savePresetID(\"".$item['id']."\", 0)'>Save</a> ||
						<a href='#' onclick='deletePreset(\"".$item['id']."\", 0)'>Delete</a>
					  </td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "&nbsp;&nbsp;↑ <a href='#' onclick='loadAndRunPresets(1)'>Load and run</a>";
			?>
			<script>
				jQuery('#allPresets:checkbox').change(function(){
				if($(this).attr('checked'))
					$('*[name=\"presetID[]\"]').attr('checked','checked');
				else
					$('*[name=\"presetID[]\"]').removeAttr('checked');});
			</script>
			<?php
		}
		else
		{
			echo "No preset. <script>setTimeout('closePopup()',2000);</script>";
		}
	}
	exit;
}
elseif($_GET['action']=="load_preset_group_popup")
{
	$sql = "SELECT DISTINCT(group_name) FROM preset WHERE group_name!=''";
	$result = mysql_query($sql);
	$arr = array();
	$row = array();
	if($result){
		while($row = mysql_fetch_assoc($result))
			array_push($arr, $row);

		if(count($arr))
		{
			echo "<table><tr><th width='140'>Group name</th><th>Action</th></tr>";
			foreach($arr as $item)
			{
				echo "<tr>";
				echo "<td>".$item['group_name']."</td>";
				echo "<td>
						<!-- <a href='#' onclick='runPresetGroup(\"".$item['group_name']."\")'>RUN</a> -->
						<form onsubmit='runPresetGroup(jQuery(this).serialize()); return false;'>
							<input type='checkbox' name='start_on_time' checked='checked' value='1'/> Start on time?<br/>
							<input type='checkbox' name='change_time' value='1'/> change time?<br/>
							<input type='hidden' name='group' value='".$item['group_name']."'/>
							start time: <select name='start_h'>";
				for($i=0;$i<24;$i++){ if($i<10) $txt = "0".$i; else $txt = $i;?>
					<option value="<?php echo $txt;?>"><?php echo $txt;?></option>
				<?php }
				echo		"</select>
							to: <select name='end_h'>";
				for($i=0;$i<24;$i++){ if($i<10) $txt = "0".$i; else $txt = $i;?>
					<option value="<?php echo $txt;?>"><?php echo $txt;?></option>
				<?php }
				echo	"</select><br/>
						New URL? <select name='mask_url'>";
				$result = funcs::get_all_maskurl(true, "id DESC");
				echo "<option value=''>Not change</option>";
				$i = 1;
				while($mask_url = mysql_fetch_assoc($result))
				{
					echo "<option value='$mask_url[name]' ".(($i == 1) ? 'selected="selected"' : '').">$mask_url[name]</option>";
					$i++;
					// echo "<option value='$mask_url[name]' ".$selected.">$mask_url[name]</option>";
					// $selected = "";
				}
				echo	"</select><br/>"

						.'Proxy Type:<br />
						<select name="select_proxy_type" id="select_proxy_type">
							<option value="99">Not Change Proxy Setting</option>
							<option value="1" selected="selected">Tor Proxy</option>
							<option value="2">Random Proxy</option>
							<option value="3">No Proxy</option>
						</select><br />'

						."<input type='submit' value='Run'/>
						</form>
					  </td>";
				echo "</tr>";
			}
			echo "</table>";
			?>
			<script>
				jQuery('#allPresets:checkbox').change(function(){
				if($(this).attr('checked'))
					$('*[name=\"presetID[]\"]').attr('checked','checked');
				else
					$('*[name=\"presetID[]\"]').removeAttr('checked');});
			</script>
			<?php
		}
		else
		{
			echo "No preset group. <script>setTimeout('closePopup()',2000);</script>";
		}
	}
	exit;
}

elseif($_GET['action']=="delete_preset")
{
	$sql = "DELETE FROM preset WHERE id=".$_POST['id'];
	$result = mysql_query($sql);
	echo "FINISH";
	exit;
}
elseif($_GET['action']=="runPreset")
{
	echo runPreset($_GET['id']);
	exit;
}
elseif($_GET['action']=="runPresetGroup")
{
	if(isset($_GET['change_time']) && ($_GET['change_time']=="1"))
	{
		$change_time = array("start_h" => $_GET['start_h'], "end_h" => $_GET['end_h']);
	}
	else
	{
		$change_time = "";
	}

	if(!isset($_GET['start_on_time']))
	{
		$start_on_time = 0;
	}
	else
	{
		$start_on_time = 1;
	}

	if(isset($_GET['mask_url']) && ($_GET['mask_url']!=""))
	{
		$mask_url = $_GET['mask_url'];
	}
	else
	{
		$mask_url = "";
	}

	$message = "";
	$result = mysql_query("SELECT id FROM preset WHERE group_name='".$_GET['group']."'");
	if($result)
	{
		while($row = mysql_fetch_assoc($result))
		{
			$temp = json_decode(runPreset($row['id'], $change_time, $mask_url, $_GET['group'], array(
				'proxy_type' => (empty($_GET['select_proxy_type'])) ? 99 : $_GET['select_proxy_type'], 'start_on_time'=>$start_on_time)));
			if($message != "")
			{
				$message->message .= $temp->message;
			}
			else
			{
				$message = $temp;
			}
		}
	}
	$return = json_encode($message);
	echo $return;
	exit;
}
elseif(isset($_POST) && is_array($_POST) && count($_POST))
{
	echo runBot($_POST);
	exit;
}//post

function runBot($post, $preset_name="")
{
	$alertText = "";
	if($post['machine']=="")
		$alertText .= "Please choose machine\r\n";

	//Disabled by Phai on 2013-10-31, no profile assigned to command, get it when bot start
	/*if($post['action']!="check")
	{
		if(!(isset($post['profiles'])))
			$alertText .= "Please choose user from user list\n\r";
	}*/

	if($post['action']!="check")
	{
		if(!(isset($post['messages'])))
			$alertText .= "Please choose at least 1 message for sending message\n\r";

		if($post['mask_url']=="")
			$alertText .= "Please enter mask URL\n\r";

		if($post['schedule']==1)
		{
			if(($post['start_h']=="00") && ($post['start_m']=="00") && ($post['end_h']=="00") && ($post['end_m']=="00"))
				$alertText .= "Please select start and end time if you want to make this bot run on schedule.\n\r";
		}

		// Check flirt-fever's fever.
		if(($post['site']==46) && ($post['version']==2))
		{
			if(!(isset($post['fevers'])))
				$alertText .= "Please choose at least 1 fever.\n\r";
		}
	}

	if($alertText=="")
	{
		unset($post['submit']);
		$arrPost = $post;

		if((isset($post['site'])) && ($post['site']!=""))
		{
			if($post['site']<=9)
			{
				$arr_siteinfo = funcs::db_get_site_info($post['site']);

				if($arr_siteinfo['search_country']=="0")
					unset($arrPost['country']);

				if($arr_siteinfo['search_area']=="0")
					unset($arrPost['area']);

				if($arr_siteinfo['search_gender']=="0")
					unset($arrPost['gender']);

				if($arr_siteinfo['search_sex']=="0")
					unset($arrPost['sexual']);

				if($arr_siteinfo['search_age']=="0")
				{
					unset($arrPost['start']);
					unset($arrPost['end']);
				}
			}
		}

		if((isset($post['profiles'])) && (count($post['profiles'])>0))
		{
			$arrTmpProfile = array();
			foreach($post['profiles'] as $key => $val)
			{
				$arrVal = explode(':',$val);
				$arrTmpProfile[$key] = array('username' => trim($arrVal['0']), 'password' => trim($arrVal['1']));
				funcs::setInUseStatus($arrVal['0'], $post['site'], 'true');
			}
			//print_r($arrTmpProfile); echo "<br>";
			$arrPost['profiles'] = $arrTmpProfile;
		}

		if((isset($post['messages'])) && (count($post['messages'])>0))
		{
			$arrTmpMessage = array();
			foreach($post['messages'] as $key => $val)
			{
				$arrVal = funcs::db_get_message_info($val);
				//echo "<pre>";
				//print_r($arrVal);
				//echo "</pre>";
				$arrTmpMessage[$key] = array(
											'id' => $arrVal['id'],
											'subject' => $arrVal['subject'],
											'message' => mysql_real_escape_string(str_replace('[URL]',$post['mask_url'],$arrVal['text_message']))
											); //addslashes()
			}
			$arrPost['messages'] = $arrTmpMessage;
			//unset($arrPost['mask_url']);
		}

		$arrSerialize = $arrPost;
		unset($arrSerialize['machine']);
		unset($arrSerialize['site']);
		unset($arrSerialize['sex']);
		//unset($arrSerialize['target']);
		unset($arrSerialize['preset_name']);
		unset($arrSerialize['preset_id']);
		unset($arrSerialize['machine_to']);

		$txtserialize = serialize($arrSerialize);
		$arr_command = array(
							'server' => $arrPost['machine'],
							'site' => $arrPost['site'],
							'sex' => $arrPost['sex'],
							'target' => $arrPost['target'],
							'command' => $txtserialize,
							'status' => 'true',
							'cdate' => date('Y-m-d H:i:s'),
							'start_time' => $arrPost['start_h'].":".$arrPost['start_m'].":00",
							'end_time' => $arrPost['end_h'].":".$arrPost['end_m'].":00",
							'preset' => $preset_name
							);
		if(isset($post['start_on_time']))
		{
			$arr_command['start_on_time'] = $post['start_on_time'];
		}

		//Insert Command to Database
		if($id = funcs::insertCommand($arr_command))
		{
			if(($arrPost['schedule']=="1") && ($post['action']=="send"))
			{
				$start_time = $arrPost['start_h'].":".$arrPost['start_m'].":00";
				$end_time = $arrPost['end_h'].":".$arrPost['end_m'].":00";

				if(strtotime($start_time)>strtotime($end_time))
				{
					if((time()<strtotime($start_time)) && (time()<strtotime($end_time)))
					{
						$start_date = date('Y-m-d',time()+60*60*24);
					}
					else
					{
						$start_date = date('Y-m-d');
					}
				}
				else
				{
					$start_date = date('Y-m-d');
				}

				$end_date = date('Y-m-d', strtotime($start_date." 00:00:00")+((60*60*24)*($arrPost['runningDays']-1)));

				mysql_query("INSERT INTO schedule_log (command_id, start_datetime) VALUES (".$id.", NOW())");
				mysql_query("INSERT INTO schedule (id, start_date, start_time, end_date, end_time, status) VALUES (".$id.", '".$start_date."', '".$start_time."', '".$end_date."', '".$end_time."', 'true')");
			}
			$status = "Command has been created";
		}
		return $status;
	}
	else
		return $alertText;
}

function runPreset($id, $change_time="", $change_url="", $preset_name="", $options = array())
{
	$return = array("result"=>0, "message"=>"");
	$return_message = "";
	$sql = "SELECT * FROM preset WHERE id = ".$id;
	$result = mysql_query($sql);
	if($result)
	{
		$result = mysql_fetch_assoc($result);
		$result = unserialize($result['setting']);
		if($result)
		{
			if(!empty($options)) {
				foreach($options as $key => $value){
					if($key == 'proxy_type') {
						if($value != '99') {
							$result[$key] = $value;
						}
					} else {
						$result[$key] = $value;
					}
				}
			}

			$servers = range($result['machine'], $result['machine_to']);
			$machine_from = $result['machine'];
			$machine_to = $result['machine_to'];
			$totalMachines = $machine_to - $machine_from + 1;
			$age1 = $result['age_from'];
			$age2 = $result['age_to'];
			$totalAges = $age2 - $age1 + 1;

			if(is_array($change_time) && isset($change_time['start_h']))
				$result['start_h'] = $change_time['start_h'];
			if(is_array($change_time) && isset($change_time['end_h']))
				$result['end_h'] = $change_time['end_h'];

			if(!empty($change_url))
			{
				$result['mask_url'] = " ".$change_url." ";
				$target_result = mysql_query("SELECT target FROM mask_url WHERE name = '".$change_url."'");
				$result['target_cm'] = current(mysql_fetch_row($target_result));
			}

			unset($result['machine_to']);
			unset($result['plz1']);
			unset($result['plz2']);
			unset($result['plz3']);
			unset($result['messages']);

			if(isset($result['eafem']) && ($result['eafem']=="1"))
			{
				if($totalMachines-$totalAges!=0)
				{
					$return_message = "Please select age range matches total machines to run.";
				}
			}

			if($return_message == "")
			{
				$result['messages'] = funcs::db_get_message($result['site'], $result['target'], $result['msg_group']);
				if(is_array($result['messages']))
				{
					foreach($result['messages'] as $key => $message)
					{
						$result['messages'][$key] = $message['id'];
					}
				}

				$machine_index = 1;
				$min = 0;
				$hour = 0;
				foreach($servers as $server)
				{
					$result['machine'] = $server;
					if(isset($result['eafem']) && ($result['eafem']=="1"))
					{
						if($machine_index == 1)
						{
							$result['age_to'] = $age1;
						}
						else
						{
							$result['age_from']++;
							$result['age_to']++;
						}
					}

					//Disabled by Phai on 2013-10-31, no profile assigned to command, get it when bot start
					/*$result['profiles'] = funcs::db_get_loginprofile_by_site($result['site'], $result['sex'], "used DESC, id DESC LIMIT 1");
					if(is_array($result['profiles']))
					{
						foreach($result['profiles'] as $key => $profile)
						{
							$result['profiles'][$key] = $profile['username'].":".$profile['password'];
						}
					}*/
					
					
					/**
					 * Random Start
					 * 
					 * @By : Pok
					 */
					if($machine_index == 1) {
						if(empty($result['start_m'])) {
							$min = 10;
						} else {
							$min = (int) $result['start_m'];
						}
						if(empty($result['start_h'])) {
							$hour = 1;
						} else {
							$hour = (int) $result['start_h'];
						}
					} else {
						$rand = rand(1,5);
						$min = ($min + $rand);
						if($min > 59) {
							$result['start_m'] = sprintf("%02d",($min % 60));
							$start_hour = ($hour + (floor($min/60)));
							$result['start_h'] = sprintf("%02d",$start_hour);
						} else {
							$result['start_m'] = sprintf("%02d",$min);
						}
					}
					
					$return_message .= "Machine ".$server." => ".runBot($result, $preset_name)."\r\n";
					$machine_index++;
				}
			}
			$return['result'] = 1;
			$return['message'] = $return_message;
		}
	}
	return json_encode($return);
}
?>
<html>
<head>
<title>Bot - Create Command</title>
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<script language="javascript" type="text/javascript" src="_include/jquery-ui-1.9.2.custom.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript">
var presetIDs = "";
var currentPresetIDIndexToRun = 0;
var doNotShowAlert = false;

$(document).ready(function() {
	if($("#showstatus").length>0)
	{
		$("#showstatus").load('ajax.php?section=fetchStatus');
		var refreshId = setInterval(function() {
			//alert($('#enableRefresh').is(':checked'));
			if($('#enableRefresh').is(':checked'))
			{
				$("#showstatus").load('ajax.php?section=fetchStatus');
			}
		}, 10000);
		$.ajaxSetup({ cache: false });
	}
});

function zeroPad(n, p, c) {
  	var pad_char = typeof c !== 'undefined' ? c : '0';
	var pad = new Array(1 + p).join(pad_char);
    return (pad + n).slice(-pad.length)
}

function checkform(doNotShowAlert){
	if($('#site').val()!="")
	{
		checkMachineOrder();
		// AUTO ADD BOT COMMAND FOR ALL MACHINES
		if($('#machine').val()!="")
		{
			var machine_from = parseInt($('#machine').val());
			var machine_to = parseInt($('#machine_to').val());
			var totalMachines = machine_to - machine_from + 1;
			var age1 = parseInt($('#age_from').val());
			var age2 = parseInt($('#age_to').val());
			var totalAges = age2 - age1 + 1;
			min = 0;
			hour = 0;

			if($('#eafem').attr('checked'))
			{
				if(totalMachines-totalAges!=0)
				{
					alert("Please select age range matches total machines to run.");
					return false;
				}
			}
			var machineIndex = 1;
			for(i=machine_from; i<=machine_to; i++)
			{
				if(machine_from != machine_to)
				{
					$('#machine').val(i);
					$('#machine_to').val(i);
					//Disabled by Phai on 2013-10-31, no profile assigned to command, get it when bot start
					/*if($("input[name='profiles[]']").length >= machineIndex)
					{
						$("input[name='profiles[]']").each(function (index)
							{
								if(index==(machineIndex-1))
									$(this).attr('checked', true);
								else
									$(this).attr('checked', false);
							}
						);
					}
					else*/
					{
						$("input[name='profiles[]']").each(function (index)
							{
								$(this).attr('checked', false);
							}
						);
					}
				}

				//Disabled by Phai on 2013-10-31, no profile assigned to command, get it when bot start
				//if($("input[name='profiles[]']:checked").length>0)
				{
					if($('#eafem').attr('checked'))
					{
						if(machineIndex==1)
						{
							$('#age_to').val(age1)
						}
						else
						{
							$('#age_from').val(parseInt($('#age_from').val())+1)
							$('#age_to').val(parseInt($('#age_to').val())+1)
						}
					}

					if(machineIndex==1)
					{
						min = parseInt($("#start_m").val());
						hour = parseInt($("#start_h").val());
						$.ajax({
									type: "POST",
									url: "index.php",
									data: $("#selectpage").serialize(),
									success:(function(result){
										if(result)
										{
											if(!doNotShowAlert)
											{
												alert(result);
											}
										}
										if(result=='Command has been created')
										{
											if(totalMachines==1)
											{
												if(machine_from < parseInt($('#machine option:last-child').val()))
												{
													$('#machine').val(machine_from+1);
													$('#machine_to').val(machine_to+1);
												}
												else
												{
													$('#machine').val(1);
													$('#machine_to').val(1);
												}
											}
											else if(totalMachines>1)
											{
												if((machine_from+totalMachines) < parseInt($('#machine option:last-child').val()))
												{
													$('#machine').val(machine_from+totalMachines);

													if((machine_to+totalMachines) < parseInt($('#machine option:last-child').val()))
														$('#machine_to').val(machine_to+totalMachines);
													else
														$('#machine_to').val(parseInt($('#machine option:last-child').val()));
												}
												else
												{
													$('#machine').val(1);
													$('#machine_to').val(totalMachines);
												}
											}

											if($('#eafem').attr('checked'))
											{
												$('#age_from').val(age1);
												$('#age_to').val(age2);
											}
											getUserProfile();
										}
										else
										{
											$('#submit').removeAttr('disabled');
										}
									})
								});
						$('#submit').attr('disabled','disabled');
					}
					else
					{
						random = Math.floor((Math.random()*5)+1);
						min = parseInt(min+random);
						if(min > 59) {
							start_m = min%60; 
							console.log("Start Min (2) : "+min);
							$("#start_m").val(zeroPad(start_m,2));
							start_h = (hour + Math.floor(min/60));
							console.log("Start Hour : "+start_h);
							$("#start_h").val(zeroPad(start_h,2));
						} else {
							console.log("Start Min (1) : "+min);
							$("#start_m").val(zeroPad(min,2));
						}
						
						$.ajax({
							type: "POST",
							url: "index.php",
							data: $("#selectpage").serialize(),
							success:(function(result){
							})
						});
					}
					if(machineIndex==totalMachines)
					{
						if(((presetIDs.length)-1) >= currentPresetIDIndexToRun)
						{
							loadAndRunPreset(presetIDs[currentPresetIDIndexToRun]);
							currentPresetIDIndexToRun++;
						}
						else
						{
							presetIDs = new Array();
							currentPresetIDIndexToRun = 0;
						}
					}
				}
				machineIndex++;
			}

		}
	}
	return false;
}

function getUserProfile(){
	var val = $("select[name=site]").val();
	var val2 = $("select[name=sex]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getUserProfile', id: val, sex: val2},
		success: function(data) {
			$('#resultusers').html(data);
			$('#submit').removeAttr('disabled');
			//alert(data);
		}
	});
}

function getMaskURL(){
	$('#mask_url').find('option').remove();
	var val = $("select[name=target_cm]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getMaskURL', target: val},
		success: function(data) {
			$('#mask_url').find('option').remove().end().append(data);
		}
	});
}

function getMessage(){
	var val = $("select[name=site]").val();
	var val2 = $("select[name=target]").val();
	var val3 = $("select[name=msg_group]").val();
	if((val!="") && (val2!="") && (val3!=""))
	{
		$.ajax({
			type: "POST",
			url: 'ajax.php',
			data: { section: 'getMessage', id: val, target: val2, msg_group: val3},
			success: function(data) {
				$('#resultmessage').html(data);
				//alert(data);
			}
		});
	}
	else if(val=="")
	{
		$('#resultmessage').html("<span style='color:red'>Please select Site</span>");
	}
	else if(val2=="")
	{
		$('#resultmessage').html("<span style='color:red'>Please select Target</span>");
	}
}

function getSearchOption(){
	var filename = '_search_option/' + $("select[name=site] option:selected").text() + '.txt'; //+ 'flirtbox.txt';//
	var default_filename = '_search_option/default.txt';

	$('#logout_after_sent').prop('checked', false);
	$('#repeat_profile').prop('checked', false);

	$.ajax({
		type: 'HEAD',
		url: filename,
		success: function() {
			$.get(filename, function(data) {
				$('#resultsearchoption').html(data);
			});
		},
		error: function() {
			$.get(default_filename, function(data) {
				$('#resultsearchoption').html(data);
			});
		}
	});
}

function checkMachineOrder(){
	var val1 = parseInt($('#machine_from').val());
	var val2 = parseInt($('#machine_to').val());

	if(val2 < val1)
	{
		$('#machine_to').val(val1);
		$('#machine_from').val(val2);
	}
}

function reassignProfiles(){
	var num = 20;
	var site_id = $("select[name=site] option:selected").val();
	var sex = $("select[name=sex] option:selected").val();

	$('#resultusers').htm("");
	$.get("ajax.php?section=reassign&site_id="+site_id+"&sex="+sex+"&num="+num, function(data) {
		getSearchOption();
		$('select[name=sex]').trigger('change');
		$('select[name=target]').trigger('change');
	});
}

function savePreset(name){
	if(!name)
	{
		var url = "./?action=preset_popup";
		$("#dialog").load(url).dialog({title: 'Save preset', height: 90, width: 315, resizable: false});
	}
	else
	{
		$('#preset_name').val(name);
		$('#preset_id').val("");
		var obj = $("#selectpage").serializeArray();
		$.post('?action=save_preset', $.param(obj), function(data){if(data=="FINISH") closePopup();});
	}
}

function savePresetID(id){
	if(id)
	{
		$('#preset_name').val("");
		$('#preset_id').val(id);
		var obj = $("#selectpage").serializeArray();
		$.post('?action=save_preset', $.param(obj), function(data){alert(data);});
	}
}

function closePopup(){
	$("#dialog").dialog('close');
}

function loadAndRunPresets(fromBeginning){
	if(fromBeginning == 1)
	{
		var i = 0;
		presetIDs = new Array();
		currentPresetIDIndexToRun = 0;
		$("*[name='presetID[]']").each(function(){
			if($(this).attr('checked'))
			{
				id = $(this).val();
				presetIDs[i] = id;
				i++;
			}
		});
	}

	if((presetIDs.length-1) > currentPresetIDIndexToRun)
	{
		doNotShowAlert = true;
		loadAndRunPreset(presetIDs[currentPresetIDIndexToRun]);
		currentPresetIDIndexToRun++;
	}
}

function runPreset(id){
	$.get('index.php?action=runPreset&id='+id, function(data){if(data.result) alert(data.message);}, 'json');
}

function runPresetGroup(data){
	$.get('index.php?action=runPresetGroup&'+data, function(data){if(data.result) alert(data.message);}, 'json');
}

function sleep(ms)
{
	var dt = new Date();
	dt.setTime(dt.getTime() + ms);
	while (new Date().getTime() < dt.getTime());
}

function loadAndRunPreset(id){
	loadPreset(id, 0, 1);
}

function loadPreset(id, index, submit){
	if(!id)
	{
		var url = "./?action=load_preset_popup";
		$("#dialog").load(url).dialog({title: 'Load preset', height: 400, width: 650, resizable: true});
	}
	else
	{
		$.post('?action=load_preset', {id: id, index: index}, function(data){
			if(data)
			{
				var obj = jQuery.parseJSON(data);
				for (var i in obj)
				{
					if(typeof(obj[i])==="object")
					{
						//console.log("- "+i+" is object");
						for (var j in obj[i])
						{
							if($("*[name='"+i+"["+j+"]']").is(':checkbox'))
							{
								$("*[name='"+i+"["+j+"]']").prop('checked', 'checked');
								$("*[name='"+i+"["+j+"]']").trigger('change');
							}
							else if($("*[name='"+i+"[]']").is(':checkbox'))
							{

								$("*[name='"+i+"[]']").each(function(){
// 									if($(this).val()==obj[i][j])
// 									{
// 										$(this).attr('checked', 'checked');
// 									}
// 									else
// 									{
// 										$(this).removeAttr('checked');
// 									}
									$("*[name^="+i+"][value="+obj[i][j]+"]").attr("checked",true);
									$(this).trigger('change');
								});
							}
							else
							{
								$("*[name='"+i+"["+j+"]']").val(obj[i][j]);
								$("*[name='"+i+"["+j+"]']").trigger('change');
							}
						}
					}
					else
					{
						//console.log("- "+i+" not object");
						if($("*[name="+i+"]").is(':checkbox'))
						{
							$("*[name="+i+"]").prop('checked', 'checked');
							$("*[name="+i+"]").trigger('change');
						}
						else if($("*[name="+i+"]").is(':radio'))
						{
							$("*[name="+i+"][value="+obj[i]+"]").prop('checked', 'checked');
							$("*[name="+i+"]").trigger('change');
						}
						else
						{
							$("*[name="+i+"]").val(obj[i]);
							$("*[name="+i+"]").trigger('change');
						}
					}
					sleep(50);
				}
				loadPreset(id, index+1, submit);
			}
			else
			{
				if(submit==1)
				{
					$('#submit').submit();
				}
			}
		});
	}
}

function loadPresetGroup(id){
	if(!id)
	{
		var url = "./?action=load_preset_group_popup";
		$("#dialog").load(url).dialog({title: 'Preset group', height: '800', width: 400, resizable: true});
	}
	else
	{
		alert('TEST');
		/*$.post('?action=load_preset', {id: id, index: index}, function(data){
			if(data)
			{
				var obj = jQuery.parseJSON(data);
				for (var i in obj)
				{
					if(typeof(obj[i])==="object")
					{
						for (var j in obj[i])
						{
							if($("*[name='"+i+"["+j+"]']").is(':checkbox'))
							{
								$("*[name='"+i+"["+j+"]']").attr('checked', 'checked');
								$("*[name='"+i+"["+j+"]']").trigger('change');
							}
							else if($("*[name='"+i+"[]']").is(':checkbox'))
							{
								$("*[name='"+i+"[]']").each(function(){
									if($(this).val()==obj[i][j])
									{
										$(this).attr('checked', 'checked');
									}
									else
									{
										$(this).removeAttr('checked');
									}
									$(this).trigger('change');
								});
							}
							else
							{
								$("*[name='"+i+"["+j+"]']").val(obj[i][j]);
								$("*[name='"+i+"["+j+"]']").trigger('change');
							}
						}
					}
					else
					{
						if($("*[name="+i+"]").is(':checkbox'))
						{
							$("*[name="+i+"]").attr('checked', 'checked');
							$("*[name="+i+"]").trigger('change');
						}
						else
						{
							$("*[name="+i+"]").val(obj[i]);
							$("*[name="+i+"]").trigger('change');
						}
					}
					sleep(50);
				}
				loadPreset(id, index+1, submit);
			}
			else
			{
				if(submit==1)
				{
					$('#submit').submit();
				}
			}
		});*/
	}
}


function sleep(milliseconds) {
   var start = new Date().getTime();
   for (var i = 0; i < 1e7; i++) {
	if ((new Date().getTime() - start) > milliseconds){
	  break;
	}
   }
 }

function deletePreset(id){
	if(confirm('Are you sure to delete?'))
		$.post('?action=delete_preset', {id: id}, function(data){if(data=="FINISH") loadPreset();});
}

function getMsgArea(){
	var site = $("select[name=site]").val();
	var login_by = $("select[name=login_by]").val();

	// Do not set if site id > 84
	if(site == 35 || site == 59 || site == 68 || site == 60 || site == 67 || site == 56 || site == 5 || site > 84){
		$("#messages_area").show();

		if(site == 68 || site == 60 || site == 67 || site == 56 || site == 5 || site > 84){
			if(login_by == 1){
				$("#repeat_area").show();
			}else{
				$("#repeat_area").hide();
			}
		}else{
			$("#repeat_area").hide();
		}

	}else{
		$("#messages_area").hide();
		$("#repeat_area").hide();
	}

}

function getRepeatProfile(){
	var site = $("select[name=site]").val();

	if(site == 68 || site == 60 || site == 67 || site == 56 || site == 5 || site > 84){
		$("#repeat_area").show();
	}else{
		$("#repeat_area").hide();
	}
}

function getRepeatProfile(){
	var login_by = $("select[name=login_by]").val();
	var site = $("select[name=site]").val();

	if(site == 68 || site == 60 || site == 67 || site == 56 || site == 5 || site > 84){
			if(login_by == 1){
				$("#repeat_area").show();
			}else{
				$("#repeat_area").hide();
			}
		}else{
			$("#repeat_area").hide();
		}
}

</script>
</head>
<body>
<div style="width: 815px; margin:10px; float:left">
<?php include('inc.nav.php');?>
		<div id="dialog"></div>
		<form name="selectpage" id="selectpage" action="#" method="post" onsubmit="checkform(doNotShowAlert); return false;"> <!---->
			<input type="hidden" name="preset_name" id="preset_name" value=""/>
			<input type="hidden" name="preset_id" id="preset_id" value=""/>
			<span class="label">Site:</span>
			<span class="field">
				<select name="site" id="site" onchange="$('#target_cm').val('').trigger('change');getSearchOption(); $('select[name=sex]').trigger('change'); $('select[name=target]').trigger('change');getMsgArea();">
					<?php
					$arrSites = funcs::db_get_sites();
					echo "<option value=''>Please Select</option>";
					foreach ($arrSites as $siteData)
					{
						echo "<option value='$siteData[id]'>$siteData[name]</option>";
					}
					//user list
					?>
				</select>
			</span>
			<br class="clear">

			<span class="label">Machine:</span>
			<span class="field">
				<?php
				$arrMachine = funcs::db_get_machine();
				?>
				<select name="machine" id="machine">
					<?php
					foreach ($arrMachine as $machineData)
					{
						echo "<option value='$machineData[id]'>$machineData[name]</option>";
					}
					?>
				</select>
				To
				<select name="machine_to" id="machine_to">
					<?php
					foreach ($arrMachine as $machineData)
					{
						echo "<option value='$machineData[id]'>$machineData[name]</option>";
					}
					?>
				</select>
			</span>
			<br class="clear">

			<span class="label">Sender:</span>
			<span class="field">
				<select name="sex" id="sex" onchange="getUserProfile();">
					<option value="">Please Select</option>
					<option value="Male">Male</option>
					<option value="Female" selected="selected">Female</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
			<br class="clear">

			<span class="label"><br/>User List:</span>
			<span class="field">
				<br/>
				<div id="resultusers" style="max-height: 100px">	</div>
			</span>
			<br class="clear">

			<span class="label"><br/>Target</span>
			<span class="field"><br/>
				<select name="target" id="target" onchange="getMessage();">
					<!-- <option value="">Please Select</option> -->
					<option value="Male" selected="selected">Male</option>
					<option value="Female">Female</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
			<br class="clear">

			<span class="label">Message Group:</span>
			<span class="field">
				<select name="msg_group" id="msg_group" onchange="getMessage();">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				</select>
				<a href="#" onclick="$('#messageListSpan').toggle(); return false">Show / hide</a> ||
				<a href="#" onclick="$('#resultmessage').find(':checkbox').attr('checked', true); return false">Select all</a> ||
				<a href="#" onclick="$('#resultmessage').find(':checkbox').attr('checked', false); return false">Select none</a>
			</span>
			<br class="clear">

			<span id="messageListSpan" style="display: none">
			<span class="label">Messages:</span>
			<span class="field">
				<div id="resultmessage">	</div>
			</span>
			<br class="clear">
			</span>

			<span class="label">Profile Type:</span>
			<span class="field">
				<select name="profile_type" id="profile_type" onchange="">
					<option value='1'>Very young</option>
					<option value='2'>Young</option>
					<option value='3'>Old</option>
					<option value='4'>Gay</option>
					<option value='5'>Lesbian</option>
				</select>
			</span>
			<br class="clear">

			<span class="label">Target CM:</span>
			<span class="field">
				<select name="target_cm" id="target_cm" onchange="getMaskURL();">
					<?php
					$result = funcs::get_all_target_cm(true);
					echo "<option value='' selected='selected'>== ALL ==</option>";
					while($target_cm = mysql_fetch_assoc($result))
					{
						echo "<option value='$target_cm[target]'>$target_cm[target]</option>";
					}
					?>
				</select>
			</span>
			<br class="clear">

			<span class="label">Mask URL:</span>
			<span class="field">
				<select name="mask_url" id="mask_url">
					<?php
					/*$result = funcs::get_all_maskurl(true, "id DESC");
					echo "<option value=''>Please Select</option>";
					$selected = 'selected="selected"';
					while($mask_url = mysql_fetch_assoc($result))
					{
						echo "<option value='$mask_url[name]' ".$selected.">$mask_url[name]</option>";
						$selected = "";
					}*/
					?>
				</select>
				<script>getMaskURL();</script>
				<span style="color:red">* For replace [URL] in message.</span>
			</span>
			<br class="clear">

			<span class="label">MSGs per hour:</span>
				<span class="field">
					<select name="messages_per_hour" id="messages_per_hour">
						<option value="">No Limit</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="4">4</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="10">10</option>
						<option value="12">12</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
						<option value="28">28</option>
						<option value="30" selected="selected">30</option>
						<option value="45">45</option>
						<option value="60">60</option>
						<option value="120">120</option>
						<option value="360">360</option>
						<option value="720">720</option>
					</select>
					<span id="messages_area" style="display:none">
						&nbsp;&nbsp;and&nbsp;&nbsp;
						<input type="checkbox" id="logout_after_sent" name="logout_after_sent" value="Y">Logout&nbsp;&nbsp;and login after
						<select name="messages_logout" id="messages_logout">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="4">4</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="10">10</option>
						<option value="12">12</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
						<option value="28">28</option>
						<option value="30" selected="selected">30</option>
						<option value="45">45</option>
						<option value="60">60</option>
						<option value="120">120</option>
						<option value="360">360</option>
						<option value="720">720</option>
					</select>
					&nbsp;wait&nbsp;
					<select name="wait_for_login" id="wait_for_login">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="4">4</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="10">10</option>
						<option value="12">12</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
						<option value="28">28</option>
						<option value="30" selected="selected">30</option>
						<option value="45">45</option>
						<option value="60">60</option>
						<option value="120">120</option>
						<option value="360">360</option>
						<option value="720">720</option>
					</select>&nbsp;&nbsp;minute and Login with&nbsp;&nbsp;
					<select name="login_by" id="login_by" onchange="getRepeatProfile();">
						<option value="1">new profile</option>
						<option value="2">current profile</option>
					</select>
				</span>
			</span>
			<br class="clear">

			<span id="repeat_area" style="display:none">
				<span class="label">Brand new,last login 24 hr.?</span>
				<span class="field">
					<input type="checkbox" id="repeat_profile" name="repeat_profile" value="Y">
				</span>
				<br class="clear">
			</span>

			<span class="label">Schedule?</span>
			<span class="field">
				<input type="checkbox" name="schedule" value="1"/>
				Yes, run it for
				<select name="runningDays">
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
				</select>
				days.
			</span>
			<br class="clear">

			<span class="label">Start Time:</span>
			<span class="field">
				<select name="start_h" id="start_h">
					<?php for($i=0;$i<24;$i++){ if($i<10) $txt = "0".$i; else $txt = $i;?>
					<option value="<?php echo $txt;?>"><?php echo $txt;?></option>
					<?php }?>
				</select>
				:
				<select name="start_m" id="start_m">
					<?php for($i=0;$i<60;$i++){ if($i<10) $txt = "0".$i; else $txt = $i;?>
					<option value="<?php echo $txt;?>" <?php if($txt=='01')echo 'selected="selected"';?>><?php echo $txt;?></option>
					<?php }?>
				</select>
				To
				<select name="end_h" id="end_h">
					<?php for($i=0;$i<24;$i++){ if($i<10) $txt = "0".$i; else $txt = $i;?>
					<option value="<?php echo $txt;?>" <?php if($txt=='12')echo 'selected="selected"';?>><?php echo $txt;?></option>
					<?php }?>
				</select>
				:
				<select name="end_m" id="end_m">
					<?php for($i=0;$i<60;$i++){ if($i<10) $txt = "0".$i; else $txt = $i;?>
					<option value="<?php echo $txt;?>"><?php echo $txt;?></option>
					<?php }?>
				</select>
			</span>
			<br class="clear">

			<hr class="separate">
			<!-- Search Option Here -->

			<span class="label"><strong>Search option::</strong></span>
			<span class="field">
			</span>
			<br class="clear">

			<div id="resultsearchoption"></div>
			<br class="clear">

			<hr class="separate">

			<span class="label">&nbsp;</span>
			<span class="field">
				<input name="save" id="save" type="button" value="Save preset" style="width:150px;" onclick="savePreset(); return false;">
				<input name="save" id="save" type="button" value="Load preset" style="width:150px;" onclick="loadPreset(); return false;">
				<input name="save" id="save" type="button" value="Preset group" style="width:150px;" onclick="loadPresetGroup(); return false;">
				<input id="submit" type="submit" value="Submit" onclick="doNotShowAlert = false;" style="width:150px;">
			</span>
		</form>
</div>
<div id="showstatus" style="margin:15px 15px 15px 15px; float:left; border: dashed 1ps #F00">
</div>
</body>
</html>