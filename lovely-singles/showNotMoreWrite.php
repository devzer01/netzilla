<?php
	require_once('classes/DBconnect.php');
	require_once('classes/config.php');
	set_time_limit(0);
	define('CACHE_TIME', 5*60);
	$data = array(
					"todate" => "2013-03-01",
					"serverID" => 4,
					);
	
	$cache_file = "templates_c/showNotMoreWrite-cache-".$data['serverID']."-".$data['todate'].".txt";
	if(!file_exists($cache_file) || (filemtime($cache_file) < (time()-CACHE_TIME)))
	{
		$client = new SoapClient(null, array('location' => "http://soap.pluckerz.com/soapserver.php", 'uri' => "urn://kontaktmarkt"));
		$payment = $client->getNotMoreWrite($data );
		file_put_contents($cache_file, serialize($payment));
	}
	else
	{
		$payment=unserialize(file_get_contents($cache_file));
	}

	//print_r($payment);
	$result = array();
	for($i=0; $i<count($payment); $i++)
	{
		$sql = "SELECT count(*) as payments_count, SUM(price) as payments_total FROM purchases_log WHERE purchase_finished=1 AND user_id=".$payment[$i]['profileID'];
		$data = DBConnect::assoc_query_1D($sql);
		$count = $data['payments_count'];
		$sum = $data['payments_total'];
		if($count>0)
		{
			/*$item = array(
							"payments_count" => $count,
							"payments_total" => $sum,
							);*/
			$payment[$i]['payments_count'] = $count;
			$payment[$i]['payments_total'] = $sum;
			array_push($result, $payment[$i]);
		}
	}
	?>
	<table>
	<tr>
		<td>profileID</td>
		<td>Username</td>
		<td>payments count</td>
		<td>payments total</td>
		<td>last sent</td>
	</tr>
	<?php
	foreach($result as $item)
	{
		?>
		<tr>
			<td><?php echo $item['profileID'];?></td>
			<td><?php echo $item['nickname'];?></td>
			<td><?php echo $item['payments_count'];?></td>
			<td><?php echo $item['payments_total'];?></td>
			<td><?php echo $item['last_sent'];?></td>
		</tr>
		<?php
	}
	echo "</table>";
?>