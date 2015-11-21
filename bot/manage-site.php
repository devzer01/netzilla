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
			$name = $_POST['name'];
			$specify_msg = ($_POST['specify_msg']==1)? 'true' : 'false';
			$sql = "INSERT INTO sites (name, specify_msg, status, report) VALUES ('$name', '$specify_msg', 'true', 'true')";
 
			if(mysql_query($sql)){
				echo "Add site completed.<br><br>";
			}else {
				echo "Couldn't add site please try again later.";
			}
		break;
		case 'Set Status':
			if(is_array($_POST['sites']))
			{
				$ids = join("','",$_POST['sites']);
				$sql = "UPDATE sites SET status = 'false', report = 'false' WHERE id NOT IN('".$ids."')";
				mysql_query($sql);
				$sql = "UPDATE sites SET status = 'true', report = 'true' WHERE id IN('".$ids."')";
				mysql_query($sql);
			}
		break;
		case 'Delete':
			if(is_array($_POST['sites']))
			{
				$ids = join("','",$_POST['sites']);
				$sql = "DELETE FROM sites WHERE id IN('".$ids."')";
				mysql_query($sql);
			}
		break;
	}
}

$result = funcs::get_all_site();
//print_r($sites);
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
?>
<html>
<head>
<title>BOT - Sites</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="styles.css" />
<script LANGUAGE="JavaScript">
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
<?php include('inc.nav.php');?>
<?php if((isset($_GET['action'])) && ($_GET['action']=="add")){?>
	<form name="form_insert" method="post" action="">
		<div class="boxcontainner">
			Site name: <input type="text" name="name" id="name" class="box"> &nbsp; Specify Message? <input type="checkbox" name="specify_msg" value="1"><input type="submit" name="submit" value="Insert" class="button">
		</div>
	</form>
	<hr>

	<form name="form_update" method="post" action="">
		<div class="boxcontainner">
		<?php while($sites = mysql_fetch_assoc($result)){ ?>
			<label class="list"><input name="sites[]" type="checkbox" value="<?php echo $sites['id'];?>" <?php if($sites['status']=='true'){ echo "checked='checked'";}?>/><?php echo $sites['name'];?></label>
		<?php } ?>
		</div>
		<div class="boxcontainner">
			<input type="submit" name="submit" value="Set Status" class="back button">
		</div>
	</form>
	<hr>
<?php }elseif((isset($_GET['action'])) && ($_GET['action']=="delete")){?>	
	<form name="form_delete" method="post" action="">
		<div class="boxcontainner">
		<?php mysql_data_seek($result, 0);?>
		<?php while($sites = mysql_fetch_assoc($result)){ ?>
			<label class="list"><input name="sites[]" type="checkbox" value="<?php echo $sites['id'];?>" /><?php echo $sites['name'];?></label>
		<?php } ?>
		</div>
		<div class="boxcontainner">
			<input type="submit" name="submit" value="Delete" class="back button" onclick="return confirmSubmit();">
		</div>
	</form>
	<hr>
<?php }?>
	<a href="index.php" class="back">Back to main page</a>
</body>
</html>