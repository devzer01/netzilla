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

if(($_SESSION['password']!=ADMIN_PASSWORD) && ($_SESSION['password']!=ADMIN_TEST_PASSWORD)){ 
	header("location: logout.php");
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
	funcs::curl_post_async($command_url,$command);
	header("location: .");
	exit;
}
elseif($_GET['action']=="stopall")
{
	$site = isset($_GET['stop_site'])?$_GET['stop_site']:0;
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
			foreach($command_post['profiles'] as $key => $profilename)
			{
				funcs::setInUseStatus($profilename['username'], $command['site'], 'false');
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
				while($mask_url = mysql_fetch_assoc($result))
				{
					echo "<option value='$mask_url[name]' ".$selected.">$mask_url[name]</option>";
					$selected = "";
				}
				echo	"</select><br/>

						<input type='submit' value='Run'/>
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
			$temp = json_decode(runPreset($row['id'], $change_time, $mask_url, $_GET['group']));
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

	if((isset($post['profiles'])) && ($post['profiles'] != ""))
	{
		$arrTmpProfile = array();
		$arrVal = explode(':',$post['profiles']);
		$arrTmpProfile[0] = array('username' => trim($arrVal['0']), 'password' => trim($arrVal['1']));
			
		$arrPost['profiles'] = $arrTmpProfile;
	}

	$arrReceive = array();
	if((isset($post['profilesTest'])) && (count($post['profilesTest'])>0))
	{
		$arrTmpReceive = array();
		$queryProfile = mysql_query("SELECT * FROM profile_test WHERE id='".$post['profilesTest']."'");
		$rs = mysql_fetch_assoc($queryProfile);
		$arrTmpReceive[0]= array('userid' => $rs['userid'], 'username' => $rs['username'], 'password' => $rs['password']);
	}
	$arrPost['receive'] = $arrTmpReceive;
	
	if((isset($post['messages'])) && (count($post['messages'])>0))
	{
		$arrTmpMessage = array();
		foreach($post['messages'] as $key => $val)
		{
			$arrVal = funcs::db_get_message_info($val);
				
			$arrTmpMessage[$key] = array(
					'id' => $arrVal['id'],
					'subject' => $arrVal['subject'],
					'message' => mysql_real_escape_string(str_replace('[URL]',$post['mask_url'],$arrVal['text_message']))
			);
		}

		$arrPost['messages'] = $arrTmpMessage;
		$arrPost['send_test'] = 1;
		$arrPost['version'] = 1;

		unset($arrPost['mask_url']);
	}

	$arrSerialize = $arrPost;
	unset($arrSerialize['machine']);
	unset($arrSerialize['site']);
	unset($arrSerialize['sex']);
	unset($arrSerialize['target']);
	unset($arrSerialize['preset_name']);
	unset($arrSerialize['preset_id']);
	unset($arrSerialize['machine_to']);	

	$txtserialize = serialize($arrSerialize);

	$arr_command = array(
		'server' => $arrPost['machine'],
		'site' => $arrPost['site'],
		'sex' => $arrPost['sex'],
		'target' => $arrPost['target'],
		'send_test' => 1,		
		'command' => $txtserialize,
		'status' => 'true',
		'cdate' => date('Y-m-d H:i:s'),
		'start_time' => $arrPost['start_h'].":".$arrPost['start_m'].":00",
		'end_time' => $arrPost['end_h'].":".$arrPost['end_m'].":00",
		'preset' => $preset_name
	);

	//print_r($arr_command);


	if($id = funcs::insertCommand($arr_command))
	{
		$status = "Command has been created";
	}

	return $status;

//echo "<pre>"; print_r($_POST); echo "</pre>";
}

function runPreset($id, $change_time="", $change_url="", $preset_name="")
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
					$result['profiles'] = funcs::db_get_loginprofile_by_site($result['site'], $result['sex'], "used DESC, id DESC LIMIT 1");
					if(is_array($result['profiles']))
					{
						foreach($result['profiles'] as $key => $profile)
						{
							$result['profiles'][$key] = $profile['username'].":".$profile['password'];
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
		$("#showstatus").load('ajax.php?section=fetchStatusTest');
		var refreshId = setInterval(function() {
			$("#showstatus").load('ajax.php?section=fetchStatusTest');
		}, 10000);
		$.ajaxSetup({ cache: false });		
	}
});

function checkform(doNotShowAlert){
	
	if($('#site').val()!="")
	{		
		if($('#machine').val()!="")
		{
			
			$.ajax({
				type: "POST",
				url: "bot-test.php",
				data: $("#selectpage").serialize(),
				success:(function(result){
					if(result)
					{
						if(!doNotShowAlert)
						{
							alert(result);
						}
					}
				})
			});

			//$('#submit').attr('disabled','disabled');
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
		data: { section: 'getUserProfileTest', id: val, sex: val2},
		success: function(data) {
			$('#resultusers').html(data);
			$('#submit').removeAttr('disabled');
			//alert(data);
		}
	});
}

function getMessageTest(){
	var val = $("select[name=site]").val();	
	var val2 = $("select[name=target]").val();
	var val3 = $("select[name=msg_group]").val();
	if((val!="") && (val3!=""))
	{
		$.ajax({
			type: "POST",
			url: 'ajax.php',
			data: { section: 'getMessage', id: val,target: val2, msg_group: val3},
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

	$('#resultusers').html("");
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
		});
	}
}

function loadPresetGroup(id){
	if(!id)
	{
		var url = "./?action=load_preset_group_popup";
		$("#dialog").load(url).dialog({title: 'Preset group', height: 'auto', width: 400, resizable: true});
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

function getProfileTest(){
	var site = $("#site option:selected").val();
    
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getProfileTest', site: site},
		success: function(data) {
			$('#resultprofiles').html(data);			
		}
	});
}

function getSpec(){
	var site = $("#site option:selected").val();
    
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getSpec', site: site},
		success: function(data) {
			if(data == 0){
				
				var selectValues = { "pm": "PM", "gb": "GB" };
				$('#msg_type').empty();
				$.each(selectValues, function(key, value) {   
				$('#msg_type')
					  .append($('<option>', { value : key })
					  .text(value)); 
				});

			}else if(data == 1){				
				
				var selectValues = { "pm": "PM"};
				$('#msg_type').empty();
				$.each(selectValues, function(key, value) {   
				$('#msg_type')
					  .append($('<option>', { value : key })
					  .text(value)); 
				});	
			
			}else if(data == 3){
				
				var selectValues = { "pm": "PM", "gb": "GB", "pt": "PT" };
				$('#msg_type').empty();
				$.each(selectValues, function(key, value) {   
				$('#msg_type')
					  .append($('<option>', { value : key })
					  .text(value)); 
				});

			}else{

				var selectValues = { "gb": "GB"};
				$('#msg_type').empty();
				$.each(selectValues, function(key, value) {   
				$('#msg_type')
					  .append($('<option>', { value : key })
					  .text(value)); 
				});

			}
		}
	});
}

</script>
</head>
<body>
<div style="width: 815px; margin:10px; float:left">
<?php include('inc.nav.php');?>
		<div id="dialog"></div>
		<form name="selectpage" id="selectpage" action="" method="post" onsubmit="checkform(doNotShowAlert); return false;">
			<input type="hidden" name="preset_name" id="preset_name" value=""/>
			<input type="hidden" name="preset_id" id="preset_id" value=""/>

			<input type="hidden" name="start_h" id="start_h" value="00"/>
			<input type="hidden" name="start_m" id="start_m" value="00"/>
			<input type="hidden" name="end_h" id="end_h" value="00"/>
			<input type="hidden" name="end_m" id="end_m" value="00"/>
			<input type="hidden" name="action" id="action" value="send"/>
		
			<span class="label">Site:</span>
			<span class="field">
				<select name="site" id="site" onchange="getSearchOption(); getProfileTest(); getSpec(); $('select[name=sex]').trigger('change'); $('select[name=target]').trigger('change');">
					<?php
					$arrSites = funcs::db_get_sites_test();
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

			<span class="label"><br/>Receive</span>
			<span class="field"><br/>
				<div id="resultprofiles" style="max-height: 100px"></div>
			</span>			
			<br class="clear">
			
			<span class="label"><br/>Target</span>
			<span class="field"><br/>
				<select name="target" id="target" onchange="getMessageTest();">
					<option value="Male" selected="selected">Male</option>
					<option value="Female">Female</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
			<br class="clear">

			<span class="label">Group:</span>
			<span class="field">
				<select name="msg_group" id="msg_group" onchange="getMessageTest();">
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
				<div id="resultmessage"></div>
			</span>
			<br class="clear">
			</span>

			<span class="label">Mask URL:</span>
			<span class="field">
				<select name="mask_url" id="mask_url">
					<?php
					$result = funcs::get_all_maskurl(true, "id DESC");
					echo "<option value=''>Please Select</option>";
					$selected = 'selected="selected"';
					while($mask_url = mysql_fetch_assoc($result))
					{
						echo "<option value='$mask_url[name]' ".$selected.">$mask_url[name]</option>";
						$selected = "";
					}
					//user list
					?>
				</select>
				<span style="color:red">* For replace [URL] in message.</span>
			</span>
			<br class="clear">
			
			<span class="label">MSG type:</span>
			<span class="field">
				<select id="msg_type" name="msg_type">
					<!--<option selected="selected" value="pm">PM</option>
					<option value="gb">GB</option>-->
				</select>
			</span>
			<br class="clear">

			<hr class="separate">

			<span class="label">&nbsp;</span>
			<span class="field">				
				<input id="submit" type="submit" value="Send" onclick="doNotShowAlert = false;" style="width:150px;">
			</span>
		</form>
</div>
<div id="showstatus" style="margin:15px 15px 15px 15px; float:left; border: dashed 1ps #F00">
</div>
</body>
</html>