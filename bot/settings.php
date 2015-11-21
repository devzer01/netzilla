<?php
date_default_timezone_set("Asia/Bangkok");
require_once 'funcs.php';
if($_SESSION['password']!=ADMIN_PASSWORD){
	
	if($_SESSION['password']==ADMIN_REPORT_PASSWORD)
		$url = "summary-report.php";
	elseif($_SESSION['password']==ADMIN_TEST_PASSWORD)
		$url = "bot-test.php";
		
	header("location: $url");

	exit;
}

if(isset($_POST['submit']) && ($_POST['submit'] == "Save"))
{
	if(is_array($_POST['settings']))
	{
		foreach($_POST['settings'] as $setting_name => $setting_value)
		{
			mysql_query("UPDATE settings SET setting_value='".$setting_value."' WHERE setting_name='".$setting_name."'");
		}
	}
	
	/**
	 * Boot on Day
	 */
	if(!empty($_POST['reboot'])) {
		$day = serialize($_POST['reboot']);
		mysql_query("UPDATE settings SET setting_value='".$day."' WHERE setting_name='REBOOT_DAYS'");
	}

	header("location: settings.php");
	exit;
}

/**
 * Boot on Day
 */
	$days = unserialize(REBOOT_DAYS);

?>
<html>
<head>
<title>Bot - Setting</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<script language="javascript" type="text/javascript" src="_include/jquery-ui-1.9.2.custom.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.9.2.custom.css" />
</head>
<body>
<body class="manage">
<?php include('inc.nav.php');?>
<input type="button" value="Restart Tor" onclick="if(confirm('This will takes some minutes to completed, are you sure to restart Tor?')) window.location='tools/restart_tor.php';"/><br/>
<form name="form_settings" method="post" action="">
<table>
	<tr>
		<th>Name</th><th>Value</th>
	</tr>
	<tr>
		<td>Stop all running bots at</td>
		<td>
			<input type="text" name="settings[STOP_RUNNING_TIME]" value="<?php echo STOP_RUNNING_TIME;?>"/> (eg. 23:00)
		</td>
	</tr>
	<tr>
		<td>Reboot VMs at Stop all bots</td>
		<td>
			<select name="settings[REBOOT_ENABLED]">
				<option value="0" <?php echo ((REBOOT_ENABLED == 0 ) ? 'selected="selected"' : ''); ?>>Disabled</option>
				<option value="1" <?php echo ((REBOOT_ENABLED == 1 ) ? 'selected="selected"' : ''); ?>>Enabled</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Reboot on Day</td>
		<td>
			<input type="checkbox" name="reboot[]" value="1" <?php echo ((in_array(1, $days)) ? 'checked="checked"' : ''); ?> /> Mon<br />
			<input type="checkbox" name="reboot[]" value="2" <?php echo ((in_array(2, $days)) ? 'checked="checked"' : ''); ?> /> Tue<br />
			<input type="checkbox" name="reboot[]" value="3" <?php echo ((in_array(3, $days)) ? 'checked="checked"' : ''); ?> /> Wed<br />
			<input type="checkbox" name="reboot[]" value="4" <?php echo ((in_array(4, $days)) ? 'checked="checked"' : ''); ?> /> Thu<br />
			<input type="checkbox" name="reboot[]" value="5" <?php echo ((in_array(5, $days)) ? 'checked="checked"' : ''); ?> /> Fri<br />
			<input type="checkbox" name="reboot[]" value="6" <?php echo ((in_array(6, $days)) ? 'checked="checked"' : ''); ?> /> Sat<br />
			<input type="checkbox" name="reboot[]" value="7" <?php echo ((in_array(7, $days)) ? 'checked="checked"' : ''); ?> /> Sun
		</td>
	</tr>
</table>
<input type="submit" name="submit" value="Save" />
</form>
</body>
</html>