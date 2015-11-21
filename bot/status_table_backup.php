<div style="margin:15px 15px 15px 15px; float:left; border: dashed 1ps #F00">
	<?php
	$query = funcs::getCommandStatus();

	//print_r($query);
	?>
	<table>
	<tr>
		<th>No...</th>
		<th>Server</th>
		<th>Site</th>
		<th>Status</th>
		<th>Details</th>
		<th>Action</th>
	</tr>
	<?php 
	$timeout = 5*60;
	$time_adjustment = 0;

	while($command = mysql_fetch_assoc($query)){
		$log_url = "http://".$command['ip']."/postdata/".$command['sitename']."/logs/".$command['id']."_latest.log";
		$data = funcs::get_data($log_url);
		$data = funcs::get_last_modified($data);

		$time = $data['time']+$time_adjustment;
		$diff = time()-$time;
		if($diff>$timeout)
			$status = "Offline";
		else
			$status = "Running. [$diff s]";
	?>
	<tr>
		<td><?php echo $command['id'];?></td>
		<td><?php echo $command['servername'];?></td>
		<td><?php echo $command['sitename'];?></td>
		<td><?php echo $status;?></td>
		<td><?php echo $data['message'];?></td>
		<td><a href="index.php?action=view_log&id=<?php echo $command['id'];?>">View log</a></td>
	</tr>
	<?php }?>
	</table>
</div>