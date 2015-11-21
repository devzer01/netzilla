<?php
require_once '_include/dbconnect.php';
require_once 'funcs.php';
if(($_SESSION['password']!=ADMIN_PASSWORD) && ($_SESSION['password']!=ADMIN_REPORT_PASSWORD)){ 
	header("location: logout.php");
	exit;
}
if($_POST)
{
	switch($_POST['submit'])
	{
		case 'Update':
			$sql = "UPDATE sites SET remark = '". addslashes($_POST['remark']) ."' ";
			if((isset($_POST['last_checking'])) && ($_POST['last_checking']=="1"))
				$sql .= ", last_checking = '". date('Y-m-d H:i:s') ."' ";

			$sql .= ", male_id = '". addslashes($_POST['male_id']) ."' ";
			$sql .= ", male_user = '". addslashes($_POST['male_user']) ."' ";
			$sql .= ", male_pass = '". addslashes($_POST['male_pass']) ."' ";
			$sql .= ", male_inbox = '". addslashes($_POST['male_inbox']) ."' ";
			$sql .= ", male_last_received = '". addslashes($_POST['male_last_received']) ."' ";
			$sql .= ", female_id = '". addslashes($_POST['female_id']) ."' ";
			$sql .= ", female_user = '". addslashes($_POST['female_user']) ."' ";
			$sql .= ", female_pass = '". addslashes($_POST['female_pass']) ."' ";
			$sql .= ", female_inbox = '". addslashes($_POST['female_inbox']) ."' ";
			$sql .= ", female_last_received = '". addslashes($_POST['female_last_received']) ."' ";

			$sql .= ", var_male = '". addslashes($_POST['var_male']) ."' ";
			$sql .= ", var_female = '". addslashes($_POST['var_female']) ."' ";
			$sql .= ", var_gay = '". addslashes($_POST['var_gay']) ."' ";
			$sql .= ", var_lesbian = '". addslashes($_POST['var_lesbian']) ."' ";
			$sql .= " WHERE id = '". addslashes($_POST['id']) ."'";
			//echo $sql; die();
			mysql_query($sql);
			break;
	}
	//header('location:summary-report.php');
}
elseif(isset($_GET['action']) && ($_GET['action']=='reset' || $_GET['action']=='reset2'))
{
	$id = $_GET['id'];
	$sql = "SELECT name FROM sites WHERE id=".$id;
	$query = mysql_query($sql);

	if($query)
	{
		if($id==51)
		{
			$name = "werKenntWen";
		}
		elseif($id == 91) { // Reif
			$name = 'reif';
		}
		elseif($id == 86) { // in-ist-drin
			$tablename = "inistdrin";
		}
		elseif($id == 95) { // gl-sh
			$tablename = 'glsh';
		}
		else
		{
			$name = mysql_fetch_assoc($query);
			$name = $name['name'];
		}
		
		if($_GET['action']=='reset2')
			$sql = "DELETE FROM ".$name."_sent_messages WHERE sent_datetime <= DATE_SUB(NOW(), INTERVAL 6 DAY)";
		else
			$sql = "TRUNCATE TABLE ".$name."_sent_messages";

		mysql_query($sql);
		
		/*
		 * POK : SAVE LOG To DATABASE
		 */
		$sql = mysql_query("SELECT COUNT(*) as total_rows FROM site_options WHERE site_id = '".$id."' AND site_key = 'LAST_RESET_SENTLOG'");
		while ($row = mysql_fetch_assoc($sql)) {
			if($row['total_rows'] != 0) {
				$query = 'UPDATE site_options SET site_value = "'.time().'" WHERE site_id = "'.$id.'" AND site_key = "LAST_RESET_SENTLOG"';
			} else {
				$query = 'INSERT INTO site_options (site_id, site_key, site_value) VALUES ("'.$id.'","LAST_RESET_SENTLOG","'.time().'")';
			}
			mysql_query($query);
		}
		
	}
	header("location: summary-report.php");
	exit;
}
elseif(isset($_GET['action']) && ($_GET['action']=='resetBeforeLast7Days'))
{
	$id = $_GET['id'];
	$sql = "SELECT name FROM sites WHERE id=".$id;
	$query = mysql_query($sql);

	if($query)
	{
		if($id==51)
		{
			$name = "werKenntWen";
		}
		elseif($id == 91) { // Reif
			$name = 'reif';
		}
		elseif($id == 86) { // in-ist-drin
			$tablename = "inistdrin";
		}
		elseif($id == 95) { // gl-sh
			$tablename = 'glsh';
		}
		else
		{
			$name = mysql_fetch_assoc($query);
			$name = $name['name'];
		}
	
		$sql = "DELETE FROM ".$name."_sent_messages WHERE DATE(sent_datetime) < DATE(NOW())-INTERVAL 7 DAY";
		mysql_query($sql);
	}
	header("location: summary-report.php");
	exit;
}

$siteid = "";
if(isset($_GET['id']))
	$siteid = " AND id = '". addslashes($_GET['id'])."'";

$sql = "SELECT * FROM `sites` WHERE `report`= 'true'".$siteid." ORDER BY `name` ASC";//`id`, `name`, `status`, `remark`, `last_received`, `last_checking`
$query = mysql_query($sql);

if(isset($_GET['id']))
{
	$sites = mysql_fetch_assoc($query);

	$sql_member_male = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_male']."' ";
	$query_member_male = mysql_query($sql_member_male);
	if($query_member_male)
		$member_male = mysql_fetch_assoc($query_member_male);

	$sql_remain_male = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_male']."' and username NOT IN (SELECT to_username FROM ".$sites['name']."_sent_messages) ";
	$query_remain_male = mysql_query($sql_remain_male);
	if($query_remain_male)
		$remain_male = mysql_fetch_assoc($query_remain_male);


	$sql_remain_female = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_female']."' and username NOT IN (SELECT to_username FROM ".$sites['name']."_sent_messages) ";
	$query_remain_female = mysql_query($sql_remain_female);
	if($query_remain_female)
		$remain_female = mysql_fetch_assoc($query_remain_female);

	$sql_member_female = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_female']."' ";
	$query_member_female = mysql_query($sql_member_female);
	if($query_member_female)
		$member_female = mysql_fetch_assoc($query_member_female);


	$sql_remain_gay = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_gay']."' and username NOT IN (SELECT to_username FROM ".$sites['name']."_sent_messages) ";
	$query_remain_gay = mysql_query($sql_remain_gay);
	if($query_remain_gay)
		$remain_gay = mysql_fetch_assoc($query_remain_gay);

	$sql_member_gay = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_gay']."' ";
	$query_member_gay = mysql_query($sql_member_gay);
	if($query_member_gay)
		$member_gay = mysql_fetch_assoc($query_member_gay);


	$sql_remain_lesbian = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_lesbian']."' and username NOT IN (SELECT to_username FROM ".$sites['name']."_sent_messages) ";
	$query_remain_lesbian = mysql_query($sql_remain_lesbian);
	if($query_remain_lesbian)
		$remain_lesbian = mysql_fetch_assoc($query_remain_lesbian);

	$sql_member_lesbian = "SELECT COUNT(id) AS total FROM ".$sites['name']."_member WHERE gender='".$sites['var_lesbian']."' ";
	$query_member_lesbian = mysql_query($sql_member_lesbian);
	if($query_member_lesbian)
		$member_lesbian = mysql_fetch_assoc($query_member_lesbian);

	/*echo $sql_remain_male.";<br/>";
	echo $sql_remain_female.";<br/>";
	echo $sql_remain_gay.";<br/>";
	echo $sql_remain_lesbain.";<br/>";*/
	//return DBConnect::assoc_query_1D($sql);
}
?>
<html>
<head>
<title>BOT - Report <?php if(isset($sites['name'])) echo " - ".$sites['name'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
<script src="sorttable.js"></script>
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
<style>
body.manage {
margin: 30 auto;
width: 980px;
}
table.sortable thead {
    background-color:#eee;
    color:#666666;
    font-weight: bold;
    cursor: pointer;
}
</style>
</head>
<body class="manage">
<?php include('inc.nav.php');?>
<?php if(isset($_GET['id'])){?>
	<form name="form_update" method="post" action="">
		<div class="boxcontainner">
			<span class="label">Site name:</span>
			<span class="field">
				<?php echo $sites['name'];?>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">URL:</span>
			<span class="field">
				<?php echo $sites['url'];?>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Value for Male:</span>
			<span class="field">
				<input type="text" name="var_male" id="var_male" value="<?php echo $sites['var_male'];?>"> 
				Members: <?php echo number_format($member_male['total'],0,'',',');?> Remain: <?php echo number_format($remain_male['total'],0,'',',');?>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Value for Female:</span>
			<span class="field">
				<input type="text" name="var_female" id="var_female" value="<?php echo $sites['var_female'];?>"> 
				Members: <?php echo number_format($member_female['total'],0,'',',');?> Remain: <?php echo number_format($remain_female['total'],0,'',',');?>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Value for Gay:</span>
			<span class="field">
				<input type="text" name="var_gay" id="var_gay" value="<?php echo $sites['var_gay'];?>"> 
				Members: <?php echo number_format($member_gay['total'],0,'',',');?> Remain: <?php echo number_format($remain_gay['total'],0,'',',');?>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Value for Lesbian:</span>
			<span class="field">
				<input type="text" name="var_lesbian" id="var_lesbian" value="<?php echo $sites['var_lesbian'];?>"> 
				Members: <?php echo number_format($member_lesbian['total'],0,'',',');?> Remain: <?php echo number_format($remain_lesbian['total'],0,'',',');?>
			</span> 
		</div>
		<div class="boxcontainner">
		</div>

		<div class="boxcontainner">
			<span class="label">Male Id:</span>
			<span class="field">
				<input type="text" name="male_id" id="male_id" class="box" value="<?php echo $sites['male_id'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Male User:</span>
			<span class="field">
				<input type="text" name="male_user" id="male_user" class="box" value="<?php echo $sites['male_user'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Male Pass:</span>
			<span class="field">
				<input type="text" name="male_pass" id="male_pass" class="box" value="<?php echo $sites['male_pass'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Male Inbox:</span>
			<span class="field">
				<input type="text" name="male_inbox" id="male_inbox" class="longbox" value="<?php echo $sites['male_inbox'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Male Last Received:</span>
			<span class="field">
				<input type="text" name="male_last_received" id="male_last_received" value="<?php echo $sites['male_last_received'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Inbox Bot:</span>
			<span class="boturl">
				<?php echo "localhost/postdata/".$sites['name']."/send-message.php?command=INBOX&sex=Male";?>
			</span> 
		</div>
		<div class="boxcontainner">
		</div>

		<div class="boxcontainner">
			<span class="label">Female Id:</span>
			<span class="field">
				<input type="text" name="female_id" id="female_id" class="box" value="<?php echo $sites['female_id'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Female User:</span>
			<span class="field">
				<input type="text" name="female_user" id="female_user" class="box" value="<?php echo $sites['female_user'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Female Pass:</span>
			<span class="field">
				<input type="text" name="female_pass" id="female_pass" class="box" value="<?php echo $sites['female_pass'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Female Inbox:</span>
			<span class="field">
				<input type="text" name="female_inbox" id="female_inbox" class="longbox" value="<?php echo $sites['female_inbox'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Female Last Received:</span>
			<span class="field">
				<input type="text" name="female_last_received" id="female_last_received" value="<?php echo $sites['female_last_received'];?>">
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">Inbox Bot:</span>
			<span class="boturl">
				<?php echo "localhost/postdata/".$sites['name']."/send-message.php?command=INBOX&sex=Female";?>
			</span> 
		</div>
		<div class="boxcontainner">
		</div>

		<div class="boxcontainner">
			<span class="label">Remark:</span>
			<span class="field">
				<textarea name="remark" style="width:500px"><?php echo $sites['remark'];?></textarea>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">&nbsp;</span>
			<span class="field">
				<label><input type="checkbox" name="last_checking" id="last_checking" class="" value="1"> Check if updating 'Male Last Received' or 'Female Last Received'</label>
			</span> 
		</div>
		<div class="boxcontainner">
			<span class="label">&nbsp;</span>
			<span class="field">
				<input name="id" type="hidden" value="<?php echo $sites['id'];?>"/>
				<input type="submit" name="submit" value="Update" class="button">
			</span>
		</div>
	</form>
<?php }else{?>
	<table class="sortable">
		<thead>
		<tr>
			<th>No.</th>
			<th>Site</th>
			<th width="60">Members</th>
			<th width="60">Sent</th>
			<th width="60">Remain</th>
			<th width="75">Running</th>
			<th width="85">Msg [M]</th>
			<th width="85">Msg [F]</th>
			<th width="110">Last Checking</th>
			<th width="200">Remark</th>
			<th width="180">Last Reset</th>
			<th width="110">Action</th>
		</tr>
		</thead>
		<?php
		$i = 1;
		while($row = mysql_fetch_assoc($query))
		{	
			if($row['name']=="wer-kennt-wen")
				$tablename = "werKenntWen";
			elseif($row['name']=="reif-trifft-jung")
				$tablename = "reif";
			elseif($row['name']=='in-ist-drin')
				$tablename = "inistdrin";
			elseif($row['name']=='gl-sh') {
				$tablename = 'glsh';
			}
			else
				$tablename = $row['name'];

			$sql_member = "SELECT COUNT(id) AS total FROM ".$tablename."_member";
			$query_member = mysql_query($sql_member);
			if($query_member)
				$meber = mysql_fetch_assoc($query_member);
			else
				$meber = array();
			$sql_sent = "SELECT COUNT(DISTINCT  to_username) AS total FROM ".$tablename."_sent_messages";
			$query_sent = mysql_query($sql_sent);
			if($query_sent)
				$sent = mysql_fetch_assoc($query_sent);
			else
				$sent = array();
			/*$sql_test = "SELECT COUNT(*) AS total FROM ".$tablename."_sent_messages WHERE to_username NOT IN (SELECT username FROM ".$tablename."_member)";
			$query_test = mysql_query($sql_test);
			$test = mysql_fetch_assoc($query_test);*/
			$sql_running = "SELECT COUNT(id) AS total, 
								CASE sex
								WHEN 'Male' THEN 'M'
								WHEN 'Female' THEN 'F'
								END AS sender, 
								CASE target
								WHEN 'Male' THEN 'M'
								WHEN 'Female' THEN 'F'
								END AS receiver
								FROM commands WHERE site = '".$row['id']."' AND status = 'true'
								GROUP BY sex";
			$query_running = mysql_query($sql_running);
			$running_status = "";
			if(mysql_num_rows($query_running)>0)
			{
				while($running = mysql_fetch_assoc($query_running))
				{
					$running_status .= $running['sender']." -> ".$running['receiver']." [<strong>".$running['total']."</strong>] ";
				}
			}
			
			// Get Config Last Reset
			$reset_sql = mysql_query('SELECT site_value FROM site_options WHERE site_id = "'.$row['id'].'" AND site_key="LAST_RESET_SENTLOG"');
			$last_reset = 'N/A';
			$tm = '';
			while($r = mysql_fetch_assoc($reset_sql)){
				$tm = $r['site_value'];
				$last_reset = date('D j M Y H:i', $r['site_value']);
			}
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><a href="summary-report.php?id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
			<td align="right"><?php echo number_format($meber['total'],0,'',','); //echo "<br/>".$sql_member;?></td>
			<td align="right"><?php echo number_format($sent['total'],0,'',',');?></td>
			<td align="right"><?php echo number_format(($meber['total']-$sent['total']),0,'',',');?></td>
			<td><?php echo $running_status;?></td>
			<td sorttable_customkey="<?php echo $row['male_last_received'];?>"><?php if($row['male_last_received']!="0000-00-00 00:00:00") echo date("d M H:i", strtotime($row['male_last_received']));?></td>
			<td sorttable_customkey="<?php echo $row['female_last_received'];?>"><?php if($row['female_last_received']!="0000-00-00 00:00:00") echo date("d M H:i", strtotime($row['female_last_received']));?></td>
			<td sorttable_customkey="<?php echo $row['last_checking'];?>"><?php if($row['last_checking']!="0000-00-00 00:00:00") echo date("d M H:i:s", strtotime($row['last_checking']));?></td>
			<td><?php echo $row['remark'];?></td>
			<td align="center">
				<?php echo $last_reset.'<br />(',(($last_reset == 'N/A' ) ? 'NEVER' : funcs::_ago($tm)),')'; ?>
			</td>
			<td><a href="summary-report.php?action=reset&id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure to reset this database?\n\nLast Reset : <?php echo $last_reset; ?>')">Reset</a> | 
				<a href="summary-report.php?action=reset2&id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure to reset this database?\n\nLast Reset : <?php echo $last_reset; ?>')">Reset [!7d]</a> </td>
		</tr>
		<?php $i++; }?>
	</table>
	<!-- <hr>
	<strong>List for checking last received</strong> -->
	<?php
			/*$yesterday = date('Y-m-d H:i:s',strtotime(date('Y-m-d')." 00:00:00") - 86400);
			$today = date('Y-m-d')." 24:00:00";
			$sql_last = "SELECT s.name, c.cdate, c.finished_datetime FROM `commands` c LEFT JOIN sites s ON c.site = s.id WHERE s.status = 'true' AND s.report = 'true' AND ((c.cdate >= '". $yesterday ."' AND c.finished_datetime = '0000-00-00 00:00:00') OR (c.status = 'true' AND c.finished_datetime = '0000-00-00 00:00:00') OR (c.finished_datetime >= '". $yesterday ."' AND c.finished_datetime <= '". $today ."')) GROUP BY s.name";
			//SELECT s.name, c.cdate, c.finished_datetime, time_to_sec(timediff(c.finished_datetime, c.cdate)) / 3600 AS date_diff FROM `commands` c LEFT JOIN sites s ON c.site = s.id WHERE s.status = 'true' AND s.report = 'true' AND (c.status = 'true' AND c.cdate >= '2012-08-07 00:00:00' AND c.finished_datetime = '0000-00-00 00:00:00' AND time_to_sec(timediff(NOW(), c.cdate)) / 3600 > 1) OR (c.finished_datetime >= '2012-08-07 00:00:00' AND c.finished_datetime <= '2012-08-08 24:00:00' AND time_to_sec(timediff(NOW(), c.cdate)) / 3600 > 1) GROUP BY s.name
			//echo $sql_last;
			$query_last = mysql_query($sql_last);
			if(mysql_num_rows($query_last)>0)
			{
				echo "<table>";
				echo "<tr>
						<th>Site Name</th>
						<th>Created Date</th>
						<th>Finished Date</th>
					</tr>";
				while($last = mysql_fetch_assoc($query_last))
				{
					$lastText  = "<tr>";
					$lastText .= "<td>".$last['name']."</td>";
					$lastText .= "<td>".date("d M H:i", strtotime($last['cdate']))."</td>";
					if($last['finished_datetime']=="0000-00-00 00:00:00")
						$lastText .= "<td> - </td>";
					else
						$lastText .= "<td>".date("d M H:i", strtotime($last['finished_datetime']))."</td>";
					$lastText .= "</tr>";
					echo $lastText;
				}
				echo "</table>";
			}*/
	?>
<?php }?>
	<hr>
	<a href="summary-report.php">Back to report page</a> <?php if($_SESSION['password']==ADMIN_PASSWORD){?><a href="index.php" class="back">Back to main page</a><?php }?>
</body>
</html>