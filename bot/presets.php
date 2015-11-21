<?php
require_once '_include/dbconnect.php';
require_once 'funcs.php';

if(isset($_GET['action']) && ($_GET['action']=="delete_group"))
{
	if(isset($_GET['id']) && is_numeric($_GET['id']))
	{
		mysql_query("UPDATE preset SET group_name='' WHERE id=".$_GET['id']);
	}
	header("location: presets.php");
	exit;
}
elseif(isset($_POST['submit_form']) && ($_POST['submit_form']=="1"))
{
	if(isset($_POST['presetID']) && is_array($_POST['presetID']))
	{
		switch($_POST['action'])
		{
			case "Save group":
				if($_POST['group_name'] != '')
				{
					foreach($_POST['presetID'] as $id)
					{
						mysql_query("UPDATE preset SET group_name='".$_POST['group_name']."' WHERE id=".$id);
					}
				}
				break;
			case "Delete group":
				foreach($_POST['presetID'] as $id)
				{
					mysql_query("UPDATE preset SET group_name='' WHERE id=".$id);
				}
				break;
		}
	}
	header("location: presets.php");
	exit;
}
?>
<html>
<head>
<title>BOT - Presets</title>
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
<?php
	$sql = "SELECT p.id, p.name, s.name as site_name, p.group_name FROM preset p LEFT JOIN sites s ON p.site_id=s.id ORDER BY site_name ASC, name ASC";
	$result = mysql_query($sql);
	$arr = array();
	$row = array();
	if($result){
		while($row = mysql_fetch_assoc($result))
			array_push($arr, $row);

		if(count($arr))
		{
			?>
			<form action='' method='post'>
			<input type="hidden" name="submit_form" value="1"/>
			<table><tr><th><input type='checkbox' id='allPresets'/></th><th width='30'>ID</th><th width='130'>Name</th><th width='100'>Site</th><th>Group</th><th>Action</th></tr>
			<?php
			foreach($arr as $item)
			{
				echo "<tr>";
				echo "<td><input type='checkbox' name='presetID[]' value='".$item['id']."'/></td>";
				echo "<td align='center'>".$item['id']."</td>";
				echo "<td>".$item['name']."</td>";
				echo "<td>".$item['site_name']."</td>";
				echo "<td>".$item['group_name']."</td>";
				echo "<td><a href='?action=delete_group&id=".$item['id']."'>Delete group</a></td>";
				echo "</tr>";
			}
			?>
			</table>
			<br/>
			<input type="text" name="group_name"/> <input type="submit" name="action" value="Save group"> <input type="submit" name="action" value="Delete group">
			</form>
			<script>
				jQuery('#allPresets:checkbox').change(function(){
				if($(this).attr('checked'))
					$('*[name=\"presetID[]\"]').attr('checked','checked');
				else
					$('*[name=\"presetID[]\"]').removeAttr('checked');});
			</script>
			<?php
		}
		else
		{
			echo "No preset. <script>setTimeout('closePopup()',2000);</script>";
		}
	}
?>
</body>
</html>