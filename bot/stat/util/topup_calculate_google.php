<?php 

date_default_timezone_set('Asia/Bangkok');

$start = date("Y-m-d", strtotime("-2 day"));

do {
	
	$finish = $start;
	
	getSignupStats($start, $finish);
	
	$start = date("Y-m-d", strtotime($start) + (60 * 60 * 24));
	
} while (strtotime("now") > strtotime($start));



function getSignupStats($start = null, $finish = null) {

	
	flog(sprintf("Start %s to %s", $start, $finish));
	
	$pdo = new PDO("mysql:host=192.168.1.203;dbname=bot", "bot", "bot");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "SELECT * FROM server AS s WHERE s.provID IN (778,779) ORDER BY s.ID ASC";
	$sth = $pdo->prepare($sql);
	
	$sth->execute();
	
	$servers = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$fromdate = $start;
	$todate = $finish;
	
	if ($start == null) {
		$fromdate = date("Y-m-d", strtotime("-1 day"));
		$todate = date("Y-m-d");
	}
	
	foreach ($servers as $server) {
		
		if ($server['provID'] == 778) continue;
		
		try
		{
			$client = new SoapClient(null, array('location' => $server['profile_url'], 'uri' => "urn://kontaktmarkt"));
			$statisticsResult = $client->getDailyToupUsername(array('fromdate' => $fromdate, 'todate' => $todate));
			
			if (!isset($statisticsResult['google'])) continue;
			
			$users = explode(",", $statisticsResult['google']);
			
			foreach ($users as $user) {
				$sql = "SELECT * FROM google_user_topup WHERE portal_id = :portal_id AND topup_date = :report_date AND username = :username";
				$sth = $pdo->prepare($sql);
				$sth->execute(array(':portal_id' => $server['ID'], ':report_date' => $fromdate, ':username' => $user));
				
				if ($sth->rowCount() == 0) {
					$sql = "INSERT INTO google_user_topup (portal_id, username, topup_date) "
					     . "VALUES (:portal_id, :username, :topup_date) ";
					$sth = $pdo->prepare($sql);
					$sth->execute(array(':portal_id' => $server['ID'], ':topup_date' => $fromdate, ':username' => $user));
				}
			}
		}
		catch (SoapFault $e)
		{
			$statisticsResult = array();
			echo $val['info']." error.<br/>";
		}
	}
}

function flog($log) 
{
	$time=date("Y-m-d H:i:s");
	printf("[%s] - %s\n", $time, $log);
}