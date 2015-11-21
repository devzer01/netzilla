<?php
session_start();
$_SESSION['password'] = "1234";
require_once '_include/dbconnect.php';
require_once 'funcs.php';
if($_POST)
{
	$_POST['username'] = trim($_POST['username']);
	$_POST['password'] = trim($_POST['password']);
	switch($_POST['submit'])
	{
		case 'Insert':
			if((isset($_POST['username'])) && (isset($_POST['password'])) && ($_POST['username']!=""))
			{
				$sql = "INSERT INTO user_profiles (userid, username, password, status, usergroup, site_id, sex, created_datetime, lastused, in_use) VALUES ('".trim($_POST['userid'])."', '".trim($_POST['username'])."', '".trim($_POST['password'])."', 'true', '1', '".$_POST['site']."', '".$_POST['sex']."', '". date('Y-m-d H:i:s') ."', '0000-00-00 00:00:00', 'false')";
	 
				if(mysql_query($sql)){
					echo "Add user completed.<br><br>";
				}else {
					$sql = "UPDATE user_profiles SET password = '".trim($_POST['password'])."', status = 'true', in_use ='false' WHERE username = '".trim($_POST['username'])."'";
					if(mysql_query($sql))
					{
						echo "Update user completed";
					}else{
						echo "Couldn't add user please try again later.";
					}
				}
			}
		break;
		case 'Set Status':
			if(is_array($_POST['profiles']))
			{
				$ids = join("','",$_POST['profiles']);
				$sql = "UPDATE user_profiles SET status = 'false' WHERE site_id ='".$_POST['site_id']."' AND sex='".$_POST['sex_type']."' AND id NOT IN('".$ids."')";
				//echo $sql."<br/>";
				mysql_query($sql);
				$sql = "UPDATE user_profiles SET status = 'true' WHERE site_id ='".$_POST['site_id']."' AND sex='".$_POST['sex_type']."' AND id IN('".$ids."')";
				//echo $sql."<br/>";
				mysql_query($sql);
			}
		break;
		case 'Delete':
			if(is_array($_POST['profiles']))
			{
				$ids = join("','",$_POST['profiles']);
				$sql = "DELETE FROM user_profiles WHERE site_id ='".$_POST['site_id']."' AND id IN('".$ids."')";
				//echo $sql."<br/>";
				mysql_query($sql);
			}
		break;
	}
}

/*$result = funcs::get_all_site();
//print_r($sites);
echo "<pre>";
print_r($_POST);
echo "</pre>";*/
?>
<html>
<head>
<title>BOT - Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<script LANGUAGE="JavaScript">
function getUserProfileList(getaction){
	var val = $("select[name=site_id]").val();
	var val2 = $("select[name=sex_type]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getUserProfileList', id: val, sex: val2, action: getaction},
		success: function(data) {
			$('#resultusers').html(data);
			//alert(data);
		}
	});
}
function confirmSubmit()
{
	var agree=confirm("Are you sure you wish to delete?");
	if (agree)
		return true ;
	else
		return false ;
}
</script>
</head>
<body class="manage">
	<form name="form_insert" method="post" action="" onsubmit="if($('#site').val()==''){alert('Please select site name.'); return false;} else{return true;}">
		<div class="boxcontainner">
			<span class="label">Site name:</span>
			<span class="field">
				<select name="site" id="site">
					<?php
					$arrSites = funcs::db_get_sites();
					echo "<option value=''>Please Select</option>";
					foreach ($arrSites as $siteData)
					{
						if(($_POST['site']!="") && ($_POST['site']==$siteData[id]))
							$site_selected = "selected='selected'";
						else
							$site_selected = "";

						echo "<option value='$siteData[id]' ".$site_selected.">$siteData[name]</option>";
					}
					//user list
					?>
				</select>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Sex:</span>
			<span class="field">
				<select name="sex" id="sex">
					<option value="Male" <?php if($_POST['sex']=="Male"){ echo 'selected="selected"';}?>>Male</option>
					<option value="Female" <?php if($_POST['sex']=="Female"){ echo 'selected="selected"';}?>>Female</option>
					<option value="Gay" <?php if($_POST['sex']=="Gay"){ echo 'selected="selected"';}?>>Gay</option>
					<option value="Lesbian" <?php if($_POST['sex']=="Lesbian"){ echo 'selected="selected"';}?>>Lesbian</option>
				</select>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Userid:</span>
			<span class="field">
				<input type="text" name="userid" id="userid" class="box">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Username:</span>
			<span class="field">
				<input type="text" name="username" id="username" class="box">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Password:</span>
			<span class="field">
				<input type="text" name="password" id="password" class="box">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">&nbsp;</span>
				<input type="submit" name="submit" value="Insert" class="button">
			<span class="field">
			</span>
		</div>
	</form>
	<hr/>
	<form name="form_delete" method="post" action="">
		<div class="boxcontainner">
			<span class="label">Site name:</span>
			<span class="field">
				<select name="site_id" id="site_id" onchange="getUserProfileList('<?php echo $_GET['action'];?>');">
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
		</div>
		<div class="boxcontainner">
			<span class="label">Sex:</span>
			<span class="field">
				<select name="sex_type" id="sex_type" onchange="getUserProfileList('<?php echo $_GET['action'];?>');">
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
		</div>
		<div id="resultusers" class="boxcontainner"></div>
		<div class="boxcontainner">
			<input type="submit" name="submit" value="Delete" class="back button" onclick="return confirmSubmit();">
		</div>
	</form>
</body>
</html>