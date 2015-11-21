<?php
date_default_timezone_set("Asia/Bangkok");
require_once 'funcs.php';
$arrPost = array();
$status = "";
if($_GET['action']=="view_log")
{
	$sql = "select c.id as id, s.name as servername, s.ip as ip, s2.name as sitename from commands c left join servers s on c.server = s.id left join sites s2 on c.site = s2.id where c.status='true' and c.id=".$_GET['id'];
	$result = mysql_query($sql);
	if(!$result)
	{
		header("location: .");
		exit;
	}
	else
	{
		$command = mysql_fetch_assoc($result);
		$log_url = "http://".$command['ip']."/postdata/".$command['sitename']."/logs/".$command['id'].".log";//"http://192.168.1.35/postdata/cheekyflirt/logs/16_lasted.log";//
		echo "<pre>".funcs::get_data($log_url)."</pre>";
		exit;
	}
}
elseif($_GET['action']=="stop")
{
	$sql = "UPDATE commands SET status='stop' WHERE id=".$_GET['id'];
	$result = mysql_query($sql);

	header("location: .");
	exit;
}
elseif($_POST)
{
	$alertText = "";
	if($_POST['machine']=="")
		$alertText .= "Please choose machine<br>";

	if(!(isset($_POST['profiles'])))
		$alertText .= "Please choose user from user list<br>";

	if(!(isset($_POST['messages'])))
		$alertText .= "Please choose at least 1 message for sending message<br>";

	if($_POST['mask_url']=="")
		$alertText .= "Please enter mask URL";

	if($alertText=="")
	{
		unset($_POST['submit']);
		$arrPost = $_POST; 

		if((isset($_POST['site'])) && ($_POST['site']!=""))
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

		if((isset($_POST['profiles'])) && (count($_POST['profiles'])>0))
		{
			$arrTmpProfile = array();
			foreach($_POST['profiles'] as $key => $val)
			{
				$arrVal = explode(':',$val);
				$arrTmpProfile[$key] = array('username' => $arrVal['0'], 'password' => $arrVal['1']); 
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
											'message' => mysql_real_escape_string(str_replace('[URL]','&ldquo;'.$_POST['mask_url'].'&rdquo;',$arrVal['text_message']))
											); //addslashes()
			}
			$arrPost['messages'] = $arrTmpMessage;
			unset($arrPost['mask_url']);
		}


		//echo "<pre>";
		//print_r($arrPost);
		//echo "</pre>";
		//echo "Do serialize and insert command below";

		$arrSerialize = $arrPost;
		unset($arrSerialize['machine']);
		unset($arrSerialize['site']);

		$txtserialize = serialize($arrSerialize);
		$arr_command = array(
							'server' => $arrPost['machine'],
							'site' => $arrPost['site'],
							'command' => $txtserialize,
							'status' => 'true',
							'cdate' => date('Y-m-d H:i:s')
							);
		//echo "<pre>";
		//print_r($arr_command);
		//echo "</pre>";

		//Insert Command to Database
		if(funcs::insertCommand($arr_command))
		{
			$status = "Command has been created";
		}
	}
}//post

/*
$command = funcs::getCommand(4);
echo "<pre>";
print_r($command);
echo "</pre>";

print_r(unserialize($command['command']));*/
?>
<html>
<head>
<title>Bot - Create Command</title>
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<style type="text/css">
body{ font-family: serif; font-size: 14px; line-height: 18px;}
span.label{ float:left; width:200px; text-align:right; margin-right:10px;}
span.field{ float:left; width:600px; text-align:left;}
.clear{ clear:both; margin-bottom:10px;}
.separate{
	border:dashed #00CCFF; 
	border-width:1px 0 0 0; 
	height:0;
	line-height:0px;
	font-size:0;
	margin:0 0 5px 0;
	padding:0;
}

table {background-color:black; padding:0px;}
td,th {background-color:white; padding:4px; font-family: serif; font-size: 14px; line-height: 18px;}
</style>
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
	}*/
	return true;
}

$(document).ready(function() {
	$("#showstatus").load('ajax.php?section=fetchStatus');
	var refreshId = setInterval(function() {
		$("#showstatus").load('ajax.php?section=fetchStatus');
	}, 10000);/**/
	$.ajaxSetup({ cache: false });
});/**/
</script>
<script type="text/javascript">
function getUserProfile(){
	var val = $("select[name=site]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getUserProfile', id: val},
		success: function(data) {
			$('#resultusers').html(data);
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
<?php if((isset($arrPost)) && (count($arrPost)>0)){?>
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
<?php }?>

<div style="width: 815px; margin:15px; float:left">
<?php
if($_POST)
{
	if((isset($alertText)) && ($alertText!=""))
		echo "<font color='red'>".$alertText."</font>";
}

if(!$_POST) {
	?>
	
		<form name="selectpage" id="selectpage" action="" method="post" onsubmit="return checkform();">
			<span class="label">Machine:</span>
			<span class="field">
				<select name="machine" id="machine">
					<?php
					$arrMachine = funcs::db_get_machine();
					echo "<option value=''>Please Select</option>";
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
				<select name="site" id="site" onchange="getUserProfile(); getSearchOption();">
					<?php
					$arrSites = funcs::db_get_sites();
					echo "<option value=''>Please Select</option>";
					foreach ($arrSites as $siteData)
					{
						echo "<option value='$siteData[id]'>$siteData[name]</option>";
					}
					//user list
					?>
				</select> <a href="add-site.php">Add site</a> || <a href="delete-site.php">delete site.</a>
			</span>
			<br class="clear">

			<span class="label">User List:</span>
			<span class="field">
				<div id="resultusers" style="margin: 5px auto; width: 800px;">	</div>
			</span>
			<br class="clear">

			<span class="label">Messages:</span>
			<span class="field">
				<?php
					$messages = funcs::db_get_message();
					if(count($messages)>0)
					{
						foreach($messages as $message)
						{
							echo "<label style='margin-bottom:10px; width:600px; display:block; float:left; padding:2px'><input type='checkbox' name='messages[]' value='".$message['id']."'>#".$message['id']."  <strong>".$message['subject']."</strong> :: ".$message['text_message']."</label>";
						}
					}
					else
					{
						echo "<span style='color:red'>There is no messages in database.</span>";
					}
				?>
			</span>
			<br class="clear">

			<span class="label">Mask URL:</span>
			<span class="field">
				<input name="mask_url" value="" style="width:250px;"/> <span style="color:red">* For replace [URL] in message.</span>
			</span>
			<br class="clear">

			<span class="label">Waiting every:</span>
			<span class="field">
				<select name="timer">
					<option value="120">2 min</option>
					<option value="180">3 min</option>
					<option value="240">4 min</option>
					<option value="300">5 min</option>
					<option value="600">10 min</option>
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
	}else {
		if((isset($status)) && ($status!=""))
			echo "<p style='color:green'>".$status."</p>";
	?>
	<strong><a href="index_new.php">back to create command</a></strong>
	<?php 
	}
	?>
</div>
<div id="showstatus" style="margin:15px 15px 15px 15px; float:left; border: dashed 1ps #F00">
</div>
</body>
</html>