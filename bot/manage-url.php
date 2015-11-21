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
			$sql = "INSERT INTO mask_url (name) VALUES ('$name')";
 
			if(mysql_query($sql)){
				echo "Add mask URL completed.<br><br>";
			}else {
				echo "Couldn't add mask URL please try again later.";
			}
		break;
		case 'Set Status':
			if(is_array($_POST['mask_url']))
			{
				$ids = join("','",$_POST['mask_url']);
				$sql = "UPDATE mask_url SET status = 'false' WHERE id NOT IN('".$ids."')";
				mysql_query($sql);
				$sql = "UPDATE mask_url SET status = 'true' WHERE id IN('".$ids."')";
				mysql_query($sql);
			}
		break;
		case 'Delete':
			if(is_array($_POST['mask_url']))
			{
				$ids = join("','",$_POST['mask_url']);
				$sql = "DELETE FROM mask_url WHERE id IN('".$ids."')";
				mysql_query($sql);
			}
		case 'Save':
			if(is_array($_POST['mask_url']))
			{
				foreach($_POST['mask_url'] as $id=>$val)
				{
					$status = (isset($val['status']) && ($val['status']=='true'))?"true":"false";
					if($id>0)
					{
						$sql = "UPDATE mask_url SET status = '".$status ."', name='".$val['name']."', target='".$val['target']."' WHERE id=".$id;
					}
					else
					{
						if(!empty($val['name']) && !empty($val['target']))
						{
							$sql = "INSERT INTO mask_url (name, target, status) VALUES ('".$val['name']."', '".$val['target']."', '".$status."')";
						}
					}
					mysql_query($sql);
				}
			}
			header("location: ".$_SERVER['HTTP_REFERER']);
			exit;
		break;
	}
}

$result = funcs::get_all_maskurl();
?>
<html>
<head>
<title>BOT - Mask URL</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="styles.css" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<script LANGUAGE="JavaScript">
var new_index = -1;
function confirmSubmit()
{
	var agree=confirm("Are you sure you wish to delete?");
	if (agree)
		return true ;
	else
		return false ;
}

function addNewMaskURL()
{
	row = $('#mask_url_table tr:last');
	row.clone().insertAfter(row).find("input").each(function() {
		if(this.type == 'text')
		{
			this.value = "";
		}

		if(endsWith(this.name, "[name]"))
		{
			this.name = "mask_url["+new_index+"][name]";
		}
		else if(endsWith(this.name, "[target]"))
		{
			this.name = "mask_url["+new_index+"][target]";
		}
		else if(endsWith(this.name, "[status]"))
		{
			this.name = "mask_url["+new_index+"][status]";
		}
	});
	column = $('#mask_url_table tr:last td:last');
	column.html("");
	new_index--;
}

function endsWith(str, suffix) {
    return str.slice(-suffix.length) == suffix;
}
</script>
</head>
<body class="manage">
<?php include('inc.nav.php');?>

<form name="form_update" method="post" action="">
	<div class="boxcontainner">
	<table id="mask_url_table">
	<tr>
		<th>Enabled?</th>
		<th>URL</th>
		<th>Target</th>
		<th>Test</th>
	</tr>
	<?php while($mask_url = mysql_fetch_assoc($result)){ ?>
		<tr>
			<td align="center"><input name="mask_url[<?php echo $mask_url['id'];?>][status]" type="checkbox" value="true" <?php if($mask_url['status']=='true'){ echo "checked='checked'";}?>/></td>
			<td><input type="text" name="mask_url[<?php echo $mask_url['id'];?>][name]" value="<?php echo $mask_url['name'];?>"/></td>
			<td><input type="text" name="mask_url[<?php echo $mask_url['id'];?>][target]" value="<?php echo $mask_url['target'];?>"/></td>
			<td><?php $url="http://".strtolower(str_replace("..",".",
					str_replace(
						array("http://", "www ", " bunkt "," punkt ", " pkt "," pnkt "," dot", " c o m", " com", " net"," n e t", " N E T"," NET"," "),
						array("", "www.",".", ".", ".", ".", ".", ".com", ".com", ".net",".net",".net",".net",""),
						$mask_url['name'])
					)); echo "<a href='$url' target='_blank'>$url</a>";?></td>
		</tr>
	<?php } ?>
	</table>
	</div>
	<div class="boxcontainner">
		<input type="button" value="Add" class="button" onclick="addNewMaskURL();">
		<input type="submit" name="submit" value="Save" class="button">
	</div>
</form>

<a href="index.php" class="back">Back to main page</a>
</body>
</html>