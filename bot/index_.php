<?php
date_default_timezone_set("Asia/Bangkok");
require_once 'funcs.php';
if($_SESSION['password']!="Launchpad1"){ 
	header("location: summary-report.php");
	exit;
}
$arrPost = array();
$status = "";
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
		$user_profiles = "";
		foreach($command_post['profiles'] as $key => $profilename)
		{
			//funcs::setInUseStatus($profilename['username'], $command['siteid'], $_GET['in_use']);
			$user_profiles .= $profilename['username'].", ";
		}
		$log_url = "http://".$command['ip']."/postdata/".$command['sitename']."/logs/".$command['id'].".log";//"http://192.168.1.35/postdata/cheekyflirt/logs/16_lasted.log";//
		echo "<pre>";
		echo "User profiles: '". substr($user_profiles, 0, -2)."'<br/><br/>";
		echo funcs::get_data($log_url);
		echo "</pre>";
		echo $command['sitename']."[".$command['id']."]";
		exit;
	}
}
elseif($_GET['action']=="stop")
{
	$sql = "UPDATE commands SET status='stop', finished_datetime = NOW() WHERE id=".$_GET['id'];
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
elseif($_POST)
{
	/*echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	exit;*/
	$alertText = "";
	if($_POST['machine']=="")
		$alertText .= "Please choose machine<br>";/**/

	if($_POST['action']=="send")
	{

		if(!(isset($_POST['profiles'])))
			$alertText .= "Please choose user from user list<br>";

		if(!(isset($_POST['messages'])))
			$alertText .= "Please choose at least 1 message for sending message<br>";

		if($_POST['mask_url']=="")
			$alertText .= "Please enter mask URL";
	}

	if($alertText=="")
	{
		unset($_POST['submit']);
		$arrPost = $_POST; 

		if((isset($_POST['site'])) && ($_POST['site']!=""))
		{
			if($_POST['site']<=9)
			{
				$arr_siteinfo = funcs::db_get_site_info($_POST['site']);

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

		if((isset($_POST['profiles'])) && (count($_POST['profiles'])>0))
		{
			$arrTmpProfile = array();
			foreach($_POST['profiles'] as $key => $val)
			{
				$arrVal = explode(':',$val);
				$arrTmpProfile[$key] = array('username' => trim($arrVal['0']), 'password' => trim($arrVal['1'])); 
				funcs::setInUseStatus($arrVal['0'], $_POST['site'], 'true');
			}
			//print_r($arrTmpProfile); echo "<br>";
			$arrPost['profiles'] = $arrTmpProfile;
		}

		if((isset($_POST['messages'])) && (count($_POST['messages'])>0))
		{
			$arrTmpMessage = array();
			foreach($_POST['messages'] as $key => $val)
			{
				$arrVal = funcs::db_get_message_info($val);
				//echo "<pre>";
				//print_r($arrVal);
				//echo "</pre>";
				$arrTmpMessage[$key] = array(
											'id' => $arrVal['id'],
											'subject' => $arrVal['subject'], 
											'message' => mysql_real_escape_string(str_replace('[URL]',$_POST['mask_url'],$arrVal['text_message']))
											); //addslashes()
			}
			$arrPost['messages'] = $arrTmpMessage;
			unset($arrPost['mask_url']);
		}


		/*echo "<pre>";
		print_r($arrPost);
		echo "</pre>";
		die("Do serialize and insert command below");*/

		$arrSerialize = $arrPost;
		unset($arrSerialize['machine']);
		unset($arrSerialize['site']);
		unset($arrSerialize['sex']);
		unset($arrSerialize['target']);

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
							'end_time' => $arrPost['end_h'].":".$arrPost['end_m'].":00"
							);
		/*echo "<pre>";
		print_r($arr_command);
		echo "</pre>";exit;*/

		/*if(!(isset($arrPost['machine'])))
		{
			$sql = "SELECT server FROM commands WHERE site ='".$arrPost['site']."' AND target ='".$arrPost['target']."'";
			$query = mysql_query($sql);
			while($row = mysql_fetch_assoc($query))
			{
				echo $row['server']."<br/>";
			}
		}
		else
		{*/
			//Insert Command to Database
			if(funcs::insertCommand($arr_command))
			{
				$status = "Command has been created";
			}
		//}
		//die('---');
	}
}//post
?>
<html>
<head>
<title>Bot - Create Command</title>
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<script type="text/javascript">
function listTo(age)
{
	var end = document.getElementById('end');
	
	if(age==60)
	{
		option = '<option value=' + age + '>' + age + '</option>';
		end.innerHTML = option;
	}
	else
	{
		var option;
		for(var i=age; i<=60; i++)
		{
			option = option + '<option value=' + i + '>' + i + '</option>';
		}
		end.innerHTML = option;
		
	}
}

function checkform()
{
	// AUTO ADD BOT COMMAND FOR ALL MACHINES
	if($('#machine').val()==0)
	{
		$("#machine option").each(function (i)
			{
		        if($(this).val() > 0)
				{
					var machineIndex = $(this).val();
					$(this).parent().val(machineIndex);
					if($("input[name='profiles[]']").length >= machineIndex)
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
					else
					{
						$("input[name='profiles[]']").each(function (index)
							{
								$(this).attr('checked', false);
							}
						);
					}

					if($("input[name='profiles[]']:checked").length>0)
					{
						//$.ajax({ type: "POST", url: "index.php", data: $("#selectpage").serialize(), success:(function(result) {});
						$.ajax({ type: "POST", url: "index.php", data: $("#selectpage").serialize(), success:(function(result){}) });

					}
				}
			}
		);
		$('#machine').val(0);
		alert("Auto add bots for every machines is completed");
		return false;
	}
	else
	{
		if($("input[name='profiles[]']:checked").length>0)
		{
			//$.ajax({ type: "POST", url: "index.php", data: $("#selectpage").serialize(), success:(function(result) {});
			$.ajax({ type: "POST", url: "index.php", data: $("#selectpage").serialize(), success:(function(result){}) });

		}
		alert("Add bot for selected machine is completed");
		return false;
		/*if(document.getElementById('subject').value == "")
		{
			alert ('please enter subject');
			document.getElementById('subject').focus();
			return false;
		}

		if(document.getElementById('message').value == "")
		{
			alert ('please enter message.');
			document.getElementById('message').focus();

			return false;
		}
		return true;*/
	}
}

$(document).ready(function() {
	$("#showstatus").load('ajax.php?section=fetchStatus');
	var refreshId = setInterval(function() {
		$("#showstatus").load('ajax.php?section=fetchStatus');
	}, 10000);
	$.ajaxSetup({ cache: false });
});/**/
</script>
<script type="text/javascript">
function getUserProfile(){
	var val = $("select[name=site]").val();
	var val2 = $("select[name=sex]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getUserProfile', id: val, sex: val2},
		success: function(data) {
			$('#resultusers').html(data);
			//alert(data);
		}
	});
}

function getMessage(){
	var val = $("select[name=site]").val();
	var val2 = $("select[name=target]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getMessage', id: val, target: val2},
		success: function(data) {
			$('#resultmessage').html(data);
			//alert(data);
		}
	});
}

function getSearchOption(){
	var filename = '_search_option/' + $("select[name=site] option:selected").text() + '.txt'; //+ 'flirtbox.txt';//
	var default_filename = '_search_option/default.php';
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
			//$('#resultsearchoption').html("<span class='label'>&nbsp;</span> <span style='color:red' class='field'>There is no search option for this site.</span> <br class='clear'>");
		}
	});
}
</script>
</head>
<body>
<!-- <?php if((isset($arrPost)) && (count($arrPost)>0)){?>
<form action='../cheekyflirt/send-message.php' method='post' name='frm'> 
<?php 
	foreach ($arrPost as $key => $val) {
		if(is_array($val))
		{
				foreach($val as $key2 => $val2)
				{
					if(is_array($val2))
					{
							foreach($val2 as $key3 => $val3)
							{
								print('<input type="hidden" name="'.$key.'['.$key2.']'.'['.$key3.']'.'" value="'.$val3.'">'); //echo $key.".".$key2.".".$key3." : ".$val3."<br>";
							}
					}
				}
		}
		else
			print('<input type="hidden" name="'.$key.'" value="'.$val.'">'); //echo $key." : ".$val."<br>";
	} 
?> 
</form> 
<script language="JavaScript"> 
	//document.frm.submit(); 
</script>
<?php }?> -->

<div style="width: 815px; margin:10px; float:left">
<?php include('inc.nav.php');?>
<?php
if($_POST)
{
	if((isset($alertText)) && ($alertText!=""))
		echo "<font color='red'>".$alertText."</font>";
}

//if(!$_POST) {
	?>
	
		<form name="selectpage" id="selectpage" action="" method="post" onsubmit="return checkform();"> <!---->
			<span class="label">Machine:</span>
			<span class="field">
				<select name="machine" id="machine">
					<?php
					$arrMachine = funcs::db_get_machine();
					echo "<option value=''>Please Select</option>";
					echo "<option value='0'>-- ALL --</option>";
					foreach ($arrMachine as $machineData)
					{
						echo "<option value='$machineData[id]'>$machineData[name]</option>";
					}
					//user list
					?>
				</select>
			</span>
			<br class="clear">

			<span class="label">Site:</span>
			<span class="field">
				<select name="site" id="site" onchange="getSearchOption(); $('select[name=sex]').trigger('change');">
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

			<span class="label">Sender:</span>
			<span class="field">
				<select name="sex" id="sex" onchange="getUserProfile();">
					<option value="">Please Select</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
			<br class="clear">

			<span class="label">User List:</span>
			<span class="field">
				<div id="resultusers">	</div>
			</span>
			<br class="clear">

			<span class="label">Target</span>
			<span class="field">
				<select name="target" id="target" onchange="getMessage();">
					<option value="">Please Select</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
			<br class="clear">

			<span class="label">Messages:</span>
			<span class="field">
				<div id="resultmessage">	</div>
				<?php /*
					$messages = funcs::db_get_message();
					if(count($messages)>0)
					{
						foreach($messages as $message)
						{
							echo "<label style='margin-bottom:10px; width:600px; display:block; float:left; padding:2px'><input type='checkbox' name='messages[]' value='".$message['id']."' checked>#".$message['id']."  <strong>".$message['subject']."</strong> :: ".$message['text_message']."</label>";
						}
					}
					else
					{
						echo "<span style='color:red'>There is no messages in database.</span>";
					}*/
				?>
			</span>
			<br class="clear">

			<span class="label">Mask URL:</span>
			<span class="field">
				<select name="mask_url" id="mask_url">
					<?php
					$result = funcs::get_all_maskurl(true);
					//print_r($arrMaskURL);
					echo "<option value=''>Please Select</option>";
					while($mask_url = mysql_fetch_assoc($result))
					{
						echo "<option value='$mask_url[name]'>$mask_url[name]</option>";
					}
					//user list
					?>
				</select>
				<!--<input name="mask_url" value=": your buddy 24 . c o m:" style="width:250px;"/>--> <span style="color:red">* For replace [URL] in message.</span>
			</span>
			<br class="clear">

			<span class="label">MSGs per hour:</span>
			<span class="field">
				<select name="messages_per_hour" id="messages_per_hour">
					<option value="">No Limit</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="4">4</option>
					<option value="8">8</option>
					<option value="15">15</option>
					<option value="30" selected="selected">30</option>
					<option value="60">60</option>
					<option value="120">120</option>
					<option value="360">360</option>
					<option value="720">720</option>
				</select>
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
					<option value="<?php echo $txt;?>"><?php echo $txt;?></option>
					<?php }?>
				</select>
			</span>
			<br class="clear">

			<span class="label">End Time:</span>
			<span class="field">
				<select name="end_h" id="end_h">
					<?php for($i=0;$i<24;$i++){ if($i<10) $txt = "0".$i; else $txt = $i;?>
					<option value="<?php echo $txt;?>"><?php echo $txt;?></option>
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

			<?php /*?><span class="label">Waiting every:</span>
			<span class="field">
				<select name="timer">
					<option value="10">10 sec</option>
					<option value="15">15 sec</option>
					<option value="20">20 sec</option>
					<option value="30">30 sec</option>
					<option value="120">2 min</option>
					<option value="180">3 min</option>
					<option value="240">4 min</option>
					<option value="300">5 min</option>
					<option value="360" selected>6 min</option>
					<option value="600">10 min</option>
					<option value="720">12 min</option>
					<option value="900">15 min</option>
				</select>
			</span>
			<br class="clear">

			<span class="label">Send amount:</span>
			<span class="field">
				<select name="send-amount">
				<?php
				for ($i=10; $i<=1000; $i=$i+10)
				{
					echo "<option value='$i'>$i</option>";
				}
				?>
				</select>
			</span>
			<br class="clear">
			<?php */?>

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
				<input name="submit" type="submit" value="submit" style="width:200px;">
			</span>
		</form>
	
	
	<?php 
	//}else {
		if((isset($status)) && ($status!=""))
			echo "<p style='color:green'>".$status."</p>";
	?>
	<!--<strong><a href="index.php">back to create command</a></strong>-->
	<?php 
	//}
	?>
</div>
<div id="showstatus" style="margin:15px 15px 15px 15px; float:left; border: dashed 1ps #F00">
</div>
</body>
</html>