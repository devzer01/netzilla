<?php 

date_default_timezone_set('Asia/Bangkok');

$start = date("Y-m-d", strtotime("-2 day"));

do {
	
	$finish = $start;
	
	getSignupStats($start);
	
	$start = date("Y-m-d", strtotime($start) + (60 * 60 * 24));
	
} while (strtotime("now") > strtotime($start));



function getSignupStats($start = null) {

	
	flog(sprintf("Start %s", $start));
	
	$pdo = new PDO("mysql:host=192.168.1.203;dbname=bot", "bot", "bot");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "SELECT portal_id, username, topup_date FROM user_topup AS s WHERE topup_date BETWEEN :topup_date_start AND :topup_date_end ";
	$sth = $pdo->prepare($sql);
	
	$sth->execute(array(':topup_date_start' => $start . " 00:00:00", ':topup_date_end' => $start . " 23:59:59"));
	
	$topups = $sth->fetchAll();
	
	$stats = array();
	
	foreach ($topups as $topup) {
		$count = getPreviousTopups($topup['portal_id'], $topup['username'], $topup['topup_date']);
		if (!isset($stats[$topup['portal_id']]['first'])) $stats[$topup['portal_id']]['first'] = 0;
		if (!isset($stats[$topup['portal_id']]['second'])) $stats[$topup['portal_id']]['second'] = 0;
		if (!isset($stats[$topup['portal_id']]['third'])) $stats[$topup['portal_id']]['third'] = 0;
		if (!isset($stats[$topup['portal_id']]['other'])) $stats[$topup['portal_id']]['other'] = 0;
		
		switch ($count) {
			case 0:
				$stats[$topup['portal_id']]['first']++;
				break;
			case 1:
				$stats[$topup['portal_id']]['second']++;
				break;
			case 2:
				$stats[$topup['portal_id']]['third']++;
				break;
			default:
				$stats[$topup['portal_id']]['other']++;
				break;
		}
	}
	
	$sql = "DELETE FROM portal_topup WHERE report_date = :report_date ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':report_date' => $start));
	
	foreach ($stats as $portal_id => $topup) {
	
		$sql = "INSERT INTO portal_topup (portal_id, first, second, third, other, report_date) "
		     . "VALUES (:portal_id, :first, :second, :third, :other, :report_date) ";
		
		$sth = $pdo->prepare($sql);
		$sth->execute(array(':portal_id' => $portal_id, ':first' => $topup['first'], ':second' => $topup['second'], 
				':third' => $topup['third'], ':other' => $topup['other'], ':report_date' => $start));
	}
	
}

function getPreviousTopups($portal_id, $username, $date)
{
	$pdo = new PDO("mysql:host=192.168.1.203;dbname=bot", "bot", "bot");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = "SELECT COUNT(*) AS cnt FROM user_topup AS s WHERE topup_date < :topup_date AND username = :username AND portal_id = :portal_id ";
	$sth = $pdo->prepare($sql);
	
	$sth->execute(array(':topup_date' => $date, ':username' => $username, ':portal_id' => $portal_id));
	
	if ($sth->rowCount() == 0) return 0;
	
	$row = $sth->fetch();
	
	return $row['cnt'];
}

function flog($log) 
{
	$time=date("Y-m-d H:i:s");
	printf("[%s] - %s\n", $time, $log);
}
