<?php
require_once '_include/dbconnect.php';
require_once 'funcs.php';
if($_SESSION['password']!=ADMIN_PASSWORD){ 
	header("location: summary-report.php");
	exit;
}
if($_POST)
{
	switch($_POST['submit'])
	{
		case 'Insert':
			if(($_POST['text_message'] != "") && ($_POST['target']!=""))
			{
				$target = implode(",",$_POST['target']);
				if(isset($_POST['site']) && is_array($_POST['site']))
				{
					$count=0;
					foreach($_POST['site'] as $site)
					{
						$sql = "INSERT INTO messages (subject, text_message, site_id, target, status, msg_group) VALUES ('". addslashes($_POST['subject'])."', '". addslashes($_POST['text_message']) ."','".$site."','".$target."', 'true', ".$_POST['msg_group'].")";
						if(mysql_query($sql)){
							$count++;
						}
					}

					if($count>0)
						echo "Add message completed for $count site(s)";
					else
						echo "Couldn't add message, please try again later.";
				}
				else
				{
					$sql = "INSERT INTO messages (subject, text_message, site_id, target, status, msg_group) VALUES ('". addslashes($_POST['subject'])."', '". addslashes($_POST['text_message']) ."','0','".$target."', 'true', ".$_POST['msg_group'].")";
					if(mysql_query($sql))
						echo "Add message completed.";
					else
						echo "Couldn't add message, please try again later.";
				}
			}
			exit;
			break;
		case 'Edit':
			$target = implode(",",$_POST['target']);
			$sql = "UPDATE messages SET subject='". addslashes($_POST['subject'])."', text_message='". addslashes($_POST['text_message'])."', site_id='". addslashes($_POST['site'])."', target='".$target."', msg_group=".$_POST['msg_group']." WHERE id='".$_POST['id']."'";
			mysql_query($sql);
			header("location: manage-message.php?action=add");
			exit;
			break;
		/*case 'Set Status':
			if(is_array($_POST['messages']))
			{
				$ids = join("','",$_POST['messages']);
				$sql = "UPDATE messages SET status = 'false' WHERE id NOT IN('".$ids."') AND site_id=".$_POST['site_id'];
				mysql_query($sql);
				$sql = "UPDATE messages SET status = 'true' WHERE id IN('".$ids."' AND site_id=".$_POST['site_id'];
				mysql_query($sql);
			}
			break;*/
		case 'Delete':
			if(is_array($_POST['messages']))
			{
				$ids = join("','",$_POST['messages']);
				$sql = "DELETE FROM messages WHERE id IN('".$ids."')";
				//echo $sql;
				mysql_query($sql);
			}
			break;
		case "Delete all messages":
			if(isset($_POST['site']) && is_array($_POST['site']))
			{
				$count=0;
				foreach($_POST['site'] as $site)
				{
					$sql = "DELETE FROM messages WHERE site_id=".$site;
					if(mysql_query($sql)){
						$count++;
					}
				}

				if($count>0)
					echo "Delete messages completed for $count site(s)";
				else
					echo "Couldn't delete message, please try again later.";
			}
			break;
		case "Delete all MALES":
			if(isset($_POST['site']) && is_array($_POST['site']))
			{
				$count=0;
				foreach($_POST['site'] as $site)
				{
					$sql = "DELETE FROM messages WHERE site_id=".$site." and target = 'Male'";
					if(mysql_query($sql)){
						$count++;
					}
				}

				if($count>0)
					echo "Delete MALES messages completed for $count site(s)";
				else
					echo "Couldn't delete message, please try again later.";
			}
			break;
		case "Delete all FEMALES":
			if(isset($_POST['site']) && is_array($_POST['site']))
			{
				$count=0;
				foreach($_POST['site'] as $site)
				{
					$sql = "DELETE FROM messages WHERE site_id=".$site." and target = 'Female'";
					if(mysql_query($sql)){
						$count++;
					}
				}

				if($count>0)
					echo "Delete FEMALES messages completed for $count site(s)";
				else
					echo "Couldn't delete message, please try again later.";
			}
			break;
	}
}

if((isset($_GET['action'])) && ($_GET['action']=="edit")){
	$result = funcs::get_message_by_id($_GET['id']);
	//print_r($result);
}
elseif((isset($_GET['action'])) && ($_GET['action']=="delete"))
{
	mysql_query("DELETE FROM messages WHERE id=".$_GET['id']);
	header("location: manage-message.php?action=add");
	exit;
}
else
{
	//$result = funcs::get_all_message();
}
?>
<html>
<head>
<title>BOT - Messages</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<link href="css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
<link href="css/tabber.css" media="screen" rel="stylesheet" type="text/css">
<script src="jquery.multi-select.js" type="text/javascript"></script>
<script src="tabber.js" type="text/javascript"></script>
<script LANGUAGE="JavaScript">
function confirmSubmit()
{
	var agree=confirm("Are you sure you wish to delete?");
	if (agree)
		return true ;
	else
		return false ;
}
function getMessageList(){
	var val = $("select[name=site_id]").val();
	var val2 = $("select[name=target_type]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getMessageList', id: val, sex: val2},
		success: function(data) {
			$('#resultmessage').html(data);
			//alert(data);
		}
	});
}

function deleteMessages()
{
	if(confirm('Are you sure to delete selected messages?'))
	{
		$.ajax({
			type: "POST",
			url: 'manage-message.php',
			data: $("#form_delete").serialize(),
			success: function(data) {
				getMessageList();
			}
		});
	}
	return false;
}

function addMessage()
{
	$.ajax({ type: "POST", url: "manage-message.php", data: $("#form_insert").serialize(), success:(function(result){if(result!='') alert(result)}) });
}
</script>
</head>
<body class="manage">
<?php include('inc.nav.php');?>
<?php if((isset($_GET['action'])) && ($_GET['action']=="add")){?>

	<div class="tabber">
		<div class="tabbertab " title="ADD">
		<form name="form_insert" id="form_insert" method="post" action="" onsubmit="addMessage(); return false;">
			<input type="hidden" name="submit" value="Insert"/>
			<div class="boxcontainner">
				<span class="label">Site name:</span>
				<span class="field">
					<select name="site[]" id="site" multiple="multiple">
						<?php
						$arrSites = funcs::db_get_sites();
						foreach ($arrSites as $siteData)
						{
							echo "<option value='$siteData[id]'>$siteData[name]</option>";
						}
						//user list
						?>
					</select> * Don't need to select if it can be use on other site
					<script type="text/javascript">$('#site').multiSelect({keepOrder: true});$('#your-select').multiSelect('uncheckAll');</script>
				</span> 
			</div>
			<div class="boxcontainner">
				<span class="label">Target</span>
				<span class="field">
					<label><input type="checkbox" name="target[]" value="Male">Male</label>
					<label><input type="checkbox" name="target[]" value="Female">Female</label>
					<label><input type="checkbox" name="target[]" value="Gay">Gay</label>
					<label><input type="checkbox" name="target[]" value="Lesbian">Lesbian</label>
				</span>
			</div>
			<div class="boxcontainner">
				<span class="label">Subject:</span>
				<span class="field">
					<input type="text" name="subject" id="subject" class="box">
				</span>
			</div>
			<div class="boxcontainner">
				<span class="label">Message:</span>
				<span class="field">
					<textarea name="text_message" class="message"></textarea>
				</span>
			</div>
			<div class="boxcontainner">
				<span class="label">Group:</span>
				<span class="field">
					<select name="msg_group" id="msg_group" class="box">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					</select>
				</span>
			</div>
			<div class="boxcontainner">
				<span class="label">&nbsp;</span>
					<input type="submit" value="Insert" class="button">
				<span class="field">
				</span>
			</div>
		</form>
		</div>

		<div class="tabbertab " title="EDIT/DELETE">
		<form name="form_update" id="form_delete" method="post" action="" onsubmit="return deleteMessages()">
			<input type="hidden" name="submit" value="Delete">
			<div class="boxcontainner">
				<span class="label">Site name:</span>
				<span class="field">
					<select name="site_id" id="site_id" onchange="getMessageList();">
						<?php
						$arrSites = funcs::db_get_sites();
						echo "<option value='0'>Please Select</option>";
						foreach ($arrSites as $siteData)
						{
							echo "<option value='$siteData[id]'>$siteData[name]</option>";
						}
						//user list
						?>
					</select>
				</span> 
			</div>
			<div class="boxcontainner">
				<span class="label">Target</span>
				<span class="field">
					<select name="target_type" id="target_type" onchange="getMessageList();">
						<option value="">Please Select</option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
						<option value="Gay">Gay</option>
						<option value="Lesbian">Lesbian</option>
					</select>
				</span>
			</div>
			<div class="boxcontainner">
				<div id="resultmessage"></div>
			</div>
			<div class="boxcontainner">
				<input type="submit" value="Delete" class="back button">
			</div>
		</form>
		</div>

		<div class="tabbertab " title="DELETE BY SITE">
			<div class="boxcontainner">
				<span class="label">&nbsp;</span>
				<span class="field">Delete all messages for these sites.</span>
			</div>
			<br class="clear"/>
			<form name="form_delete_sites" id="form_delete_sites" method="post" action="">
			<div class="boxcontainner">
				<span class="label">Site name:</span>
				<span class="field">
					<select name="site[]" id="site2" multiple="multiple">
						<?php
						$arrSites = funcs::db_get_sites();
						foreach ($arrSites as $siteData)
						{
							echo "<option value='$siteData[id]'>".$siteData[name]." [".($siteData['total_messages']?$siteData['total_messages']:0)."]</option>";
						}
						//user list
						?>
					</select>
					<script type="text/javascript">$('#site2').multiSelect({keepOrder: true});$('#your-select').multiSelect('uncheckAll');</script>
				</span> 
			</div>
			<br class="clear"/><br class="clear"/>
			<div class="boxcontainner">
				<span class="label">&nbsp;</span>
					<input type="submit" name="submit" value="Delete all messages" class="button" style="width: 150px">
					<input type="submit" name="submit" value="Delete all MALES" class="button" style="width: 150px">
					<input type="submit" name="submit" value="Delete all FEMALES" class="button" style="width: 150px">
				<span class="field">
				</span>
			</div>
			</form>
			<br class="clear"/>
		</div>
	</div>
<?php }elseif((isset($_GET['action'])) && ($_GET['action']=="edit")){?>
	<form name="form_insert" method="post" action="">
		<div class="boxcontainner">
			<span class="label">Site name:</span>
			<span class="field">
				<select name="site" id="site">
					<?php
					$arrSites = funcs::db_get_sites();
					echo "<option value='0'>Please Select</option>";
					foreach ($arrSites as $siteData)
					{
						if($result['site_id']==$siteData[id])
							$site_selectd = "selected='selected'";
						else
							$site_selectd ="";
						echo "<option value='$siteData[id]'".$site_selectd.">$siteData[name]</option>";
					}
					//user list
					?>
				</select>
			</span>
		</div>
		<?php $arr_target = explode(',',$result['target']);?>
		<div class="boxcontainner">
			<span class="label">Target</span>
			<span class="field">
				<label><input type="checkbox" name="target[]" value="Male" <?php if(in_array("Male",$arr_target)){ echo 'checked="checked"';}?>>Male</label>
				<label><input type="checkbox" name="target[]" value="Female" <?php if(in_array("Female",$arr_target)){ echo 'checked="checked"';}?>>Female</label>
				<label><input type="checkbox" name="target[]" value="Gay" <?php if(in_array("Gay",$arr_target)){ echo 'checked="checked"';}?>>Gay</label>
				<label><input type="checkbox" name="target[]" value="Lesbian" <?php if(in_array("Lesbian",$arr_target)){ echo 'checked="checked"';}?>>Lesbian</label>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Subject:</span>
			<span class="field">
				<input type="text" name="subject" id="subject" class="box" value="<?php echo $result['subject'];?>">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Message:</span>
			<span class="field">
				<textarea name="text_message" class="message"><?php echo $result['text_message'];?></textarea>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Group:</span>
			<span class="field">
				<select name="msg_group" id="msg_group" class="box">
				<option value="1" <?php if($result['msg_group']==1){ echo 'selected="selected"';}?>>1</option>
				<option value="2" <?php if($result['msg_group']==2){ echo 'selected="selected"';}?>>2</option>
				<option value="3" <?php if($result['msg_group']==3){ echo 'selected="selected"';}?>>3</option>
				<option value="4" <?php if($result['msg_group']==4){ echo 'selected="selected"';}?>>4</option>
				<option value="5" <?php if($result['msg_group']==5){ echo 'selected="selected"';}?>>5</option>
				<option value="6" <?php if($result['msg_group']==6){ echo 'selected="selected"';}?>>6</option>
				</select>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">&nbsp;</span>
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
				<input type="submit" name="submit" value="Edit" class="button">
			<span class="field">
			</span>
		</div>
	</form>
	<hr>
	<a href="manage-message.php?action=add" class="back">Back to main page</a>
<?php }elseif((isset($_GET['action'])) && ($_GET['action']=="delete")){?>
	<form name="form_delete" method="post" action="">
		<div class="boxcontainner">
			<span class="label">Site name:</span>
			<span class="field">
				<select name="site_id" id="site_id" onchange="getMessageList();">
					<?php
					$arrSites = funcs::db_get_sites();
					echo "<option value='0'>Please Select</option>";
					foreach ($arrSites as $siteData)
					{
						echo "<option value='$siteData[id]'>$siteData[name]</option>";
					}
					//user list
					?>
				</select>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Target</span>
			<span class="field">
				<select name="target_type" id="target_type" onchange="getMessageList();">
					<option value="">Please Select</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
		</div>
		<div class="boxcontainner">
			<div id="resultmessage">
			<?php mysql_data_seek($result, 0);?>
			<?php while($messages = mysql_fetch_assoc($result)){ ?>
				<label class="textlist"><input name="messages[]" type="checkbox" value="<?php echo $messages['id'];?>" /><?php echo "#".$messages['id']." <strong>".$messages['subject']."</strong>::". $messages['text_message'];?></label>
			<?php } ?>
			</div>
		</div>
		<div class="boxcontainner">
			<input type="submit" name="submit" value="Delete" class="back button" onclick="return confirmSubmit();">
		</div>
	</form>
	<hr>
	<a href="index.php" class="back">Back to main page</a>
<?php }?>
<script>tabberAutomatic();</script>
</body>
</html>