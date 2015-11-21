<?php
require_once '_include/dbconnect.php';
require_once 'funcs.php';
$sql = "SELECT * FROM `sites` WHERE `report`= 'true' ORDER BY `name` ASC";//`id`, `name`, `status`, `remark`, `last_received`, `last_checking`
$query = mysql_query($sql);
?>
<html>
<head>
<title>BOT - Check Banded Users<?php if(isset($sites['name'])) echo " - ".$sites['name'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body class="manage">
	<strong>Check Banded Users</strong>
	<table>
		<tr>
			<th>No.</th>
			<th>Site</th>
			<th>Remark</th>
		</tr>
		<?php $i = 1; while($row = mysql_fetch_assoc($query)){?>
		<tr>
			<td><?php echo $i;?></td>
			<td><a href="<?php echo "http://localhost/postdata/".$row['name']."/send-message.php?command=BAND";?>" target="_blank"><?php echo $row['name'];?></a></td>
			<td><?php echo $row['remark'];?></td>
		</tr>
		<?php $i++; }?>
	</table>
</body>
</html>