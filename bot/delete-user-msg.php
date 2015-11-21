<?php
require_once '_include/dbconnect.php';
require_once 'funcs.php';
if($_SESSION['password']!=ADMIN_PASSWORD){ 
	header("location: summary-report.php");
	exit;
}

if($_POST)
{
	$sql = "select * from sites where id='".$_POST["site"]."'";
	$query = mysql_query($sql);
	$rs = mysql_fetch_assoc($query);

	if($_POST['site']==91)
		$rs['name'] = 'reif';

	$sql_del_1 = "delete from ".str_replace("-", "", $rs["name"])."_reservation where username='".$_POST["username"]."'";
	mysql_query($sql_del_1);

	$sql_del_2 = "delete from ".str_replace("-", "", $rs["name"])."_sent_messages where to_username='".$_POST["username"]."'";
	mysql_query($sql_del_2);
}

?>
<html>
<head>
<title>BOT - Sites</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<script LANGUAGE="JavaScript">

function confirmSubmit()
{
	return confirm("Are you sure you wish to delete?");
}

</script>
</head>
<body class="manage">
<?php include('inc.nav.php');?>

<form name="form_delete" method="post" action="" onsubmit="if($('#site').val()==''){alert('Please select site name.'); return false;} else{return true;}">
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
		<span class="label">Username:</span>
		<span class="field">
			<input type="text" name="username" id="username" class="box">
		</span>
	</div>

	<div class="boxcontainner">
		<span class="label">&nbsp;</span>
			<input type="submit" name="submit" value="Delete" class="button" onclick="return confirmSubmit();">
		<span class="field">
		</span>
	</div>
</form>

</body>
</html>