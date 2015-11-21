<?php
require_once '_include/dbconnect.php';
require_once 'funcs.php';
if($_POST)
{
	switch($_POST['submit'])
	{
		case 'Insert':
			$sql = "INSERT INTO user_profiles (userid, username, password, status, usergroup, site_id, created_datetime) VALUES ('".$_POST['userid']."', '".$_POST['username']."', '".$_POST['password']."', 'true', '1', '".$_POST['site']."', '". date('Y-m-d H:i:s') ."')";
 
			if(mysql_query($sql)){
				echo "Add user completed.<br><br>";
			}else {
				echo "Couldn't add user please try again later.";
			}
		break;
		case 'Set Status':
			if(is_array($_POST['profiles']))
			{
				$ids = join("','",$_POST['profiles']);
				$sql = "UPDATE user_profiles SET status = 'false' WHERE site_id ='".$_POST['site_id']."' AND id NOT IN('".$ids."')";
				//echo $sql."<br/>";
				mysql_query($sql);
				$sql = "UPDATE user_profiles SET status = 'true' WHERE site_id ='".$_POST['site_id']."' AND id IN('".$ids."')";
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
<title>BOT - Logs</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<script LANGUAGE="JavaScript">
function getLog(){
	var val = $("select[name=site_id]").val();
	$.ajax({
		type: "POST",
		url: 'ajax.php',
		data: { section: 'getLog', id: val},
		success: function(data) {
			$('#ajaxresult').html(data);
			//alert(data);
		}
	});
}
</script>
</head>
<body class="manage">
<?php include('inc.nav.php');?>
	<form name="form_update" method="post" action="">
		<div class="boxcontainner">
			<span class="label">Site name:</span>
			<span class="field">
				<select name="site_id" id="site_id" onchange="getLog();">
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
		<div id="ajaxresult" class="boxcontainner"></div>
	</form>
	<hr>
	<a href="index.php" class="back">Back to main page</a>
</body>
</html>