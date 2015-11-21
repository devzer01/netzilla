<?php
session_start();
if (!isset($_SESSION['password'])) $_SESSION['password'] = "1234";
require_once '_include/dbconnect.php';
require_once 'funcs.php';
if($_POST)
{
	if((isset($_POST['id'])) && (isset($_POST['vcard'])) && ($_POST['vcard']!=""))
	{

		$sql = "SELECT id FROM vcard WHERE site_id = '" . mysql_real_escape_string(trim($_POST['id'])) . "'";
		
		$rs = mysql_query($sql);
		
		if (mysql_num_rows($rs) == 0) {
			$sql = "INSERT INTO vcard (site_id, info, created) VALUES ('".trim($_POST['id'])."', '".mysql_real_escape_string(trim($_POST['vcard']))."', NOW())";
		 
			if(mysql_query($sql)){
				echo "Vcard Added completed.<br><br>";
			}
		} else {
			$r = mysql_fetch_assoc($rs);
			$sql = "UPDATE vcard SET info = '" . mysql_real_escape_string($_POST['vcard']) . "' WHERE id = " . $r['id'];
			mysql_query($sql);
		}
	}
}

if (isset($_GET['action']) && $_GET['action'] == 'del') {
	
	$sql = "DELETE FROM email WHERE id = " . $_GET['id'];
	mysql_query($sql);
}

$sql = "SELECT id, name FROM sites WHERE status = 'true' ORDER BY name ";

$rs = mysql_query($sql);


?>
<html>
<head>
<title>BOT - Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body class="manage">
	<form name="form_insert" method="post" action="">
		<div class="boxcontainner">
			<span class="label">Site Name:</span>
			<span class="field">
				<select name='id' id='id'>
						<option value='0'>Select Site</option>
					<?php while ($row = mysql_fetch_assoc($rs)) { ?>
						<option value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option>
					<?php } ?>
				</select>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Vcard:</span>
			<span class="field">
				<textarea name='vcard' id='vcard' style="margin: 2px; height: 357px; width: 470px;"></textarea>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">&nbsp;</span>
				<input type="submit" name="submit" value="Save" class="button">
			<span class="field">
			</span>
		</div>
	</form>
	
	<script type='text/javascript'>
		$(function() {
			$("#id").change(function (e) {
				if ($(this).val() != 0) $("#vcard").load("json.php?action=vcard&id=" + $(this).val());
			});
		});
	</script>
	
</body>
</html>
