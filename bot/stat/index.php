<?php

session_start();
require_once('config.php');
require_once 'vendor/autoload.php';
require_once('db.php');
require_once('smarty3/Smarty.class.php');

date_default_timezone_set('Asia/Bangkok');

$app = new \Slim\Slim();

$smarty = new Smarty();
$smarty->setTemplateDir('templates/');
$smarty->setCompileDir('templates_c/');
$smarty->setConfigDir('configs/');
$smarty->setCacheDir('cache/');

$app->get('/ping', function () use ($app, $smarty) {

	echo "pong";

});

$app->get("/daily", function () use ($smarty) {
	
	$day_start = date("Y-m-d", strtotime("-30 day"));
	$day_finish = date("Y-m-d");
	
	$hour_start = date("Y-m-d", strtotime("-7 day"));
	
	$markets = array('a' => 'All', 'g' => 'German', 'e' => 'English');
	$smarty->assign('markets', $markets);
	$smarty->assign('day_start', $day_start);
	$smarty->assign('day_finish', $day_finish);
	$smarty->assign('hour_start', $hour_start);
	
	$smarty->display('daily.tpl');
});

$app->get("/profilereport", function () use ($app, $smarty) {
	
	$db = getDbHandler();
	
	$sql = "SELECT id, name, profile_req FROM sites WHERE status = 'true' ORDER BY name ";
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$sites = $sth->fetchAll();
	
	$site_report = array();
	
	foreach ($sites as $site) {
		$site_report[$site['id']] = $site;
	}
	
	
	$start = date("Y-m-d", strtotime("-7 day"));
	
	$profiles = array();
	$days = array();
	
	$required = array();
	$actual = array();
	
	do {
	
		$dstart = $start . " 00:00:00";
		$dfinish = $start . " 23:59:59";
	
		$sql = "SELECT site_id, COUNT(*) AS cnt, DATE(created_datetime) AS cdate FROM user_profiles WHERE created_datetime BETWEEN  '" . $dstart . "' AND '" . $dfinish . "' GROUP BY site_id, DATE(created_datetime) ";
		$sth = $db->prepare($sql);
		$sth->execute();
	
		$rows = $sth->fetchAll();
		
		$total_required = 0;
		
		foreach($site_report as $site_id => $site) {
			$site_report[$site_id][$start] = 0;
			$total_required += $site['profile_req'];
		}
		
		$required[$start] = $total_required;
		
		$days[] = array('cdate' => $start);
		
		$total_actual = 0;
		
		foreach ($rows as $row) {
			$site_report[$row['site_id']][$row['cdate']] = $row['cnt'];
			$total_actual += $row['cnt'];
		}
		
		$actual[$start] = $total_actual;
	
		$start = date("Y-m-d", strtotime($start) + (60 * 60 * 24));
	
	} while (strtotime($start) < strtotime("now"));
	
	$smarty->assign('site_report', $site_report);
	$smarty->assign('days', $days);
	$smarty->assign('required', $required);
	$smarty->assign('actual', $actual);
	
	$smarty->display('profilereport.tpl');
	
});

$app->get("/p7profile/:id", function ($id) use ($app, $smarty) {
	$db = getDbHandler();
	
	$start = date("Y-m-d", strtotime("-7 day"));
	
	$profiles = array();
	
	do {
		
		$dstart = $start . " 00:00:00";
		$dfinish = $start . " 23:59:59";
		
		$sql = "SELECT COUNT(*) AS cnt, DATE(created_datetime) AS cdate FROM user_profiles WHERE site_id = " . $id . " AND created_datetime BETWEEN  '" . $dstart . "' AND '" . $dfinish . "' GROUP BY DATE(created_datetime) ";
		$sth = $db->prepare($sql);
		$sth->execute();
		
		if ($sth->rowCount() == 0) {
			$profiles[] = array('cnt' => 0, 'cdate' => $start);			
		} else {
			$profiles[] = $sth->fetch();
		}
		
		$start = date("Y-m-d", strtotime($start) + (60 * 60 * 24));
		
	} while (strtotime($start) < strtotime("now"));
	
	$profiles = array_reverse($profiles);
	
	$smarty->assign('profiles', $profiles);
	
	
	$sql = "SELECT name, profile_req FROM sites WHERE id = :id ";
	$sth = $db->prepare($sql);
	$sth->execute(array(':id' => $id));
	
	$site = $sth->fetch();
	
	$smarty->assign('site', $site);
	
	$smarty->display('profile.tpl');
	
});

$app->get("/json/stats(/:start(/:finish))", function ($start = null, $finish = null) use ($app, $smarty) {
	
	if ($start == null) {
		$start = date("Y-m-d", strtotime("-7 day"));
		$finish = date("Y-m-d", strtotime("now"));
	}
	
	$pdo = getDbHandler();
	$sql = "SELECT SUM(ref) AS signup, SUM(ref_payment) AS payment, SUM(ref_payment_attempt) AS payment_attempt, SUM(ref_payment_count) AS paycount, SUM(ref_payment_attempt_count) AS payattemptcount "
	     . "FROM signup "
	     . "WHERE report_date BETWEEN :start AND :finish ";
	
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start . " 00:00:00", ':finish' => $finish . " 23:59:59"));
	
	$smarty->assign('start', $start);
	$smarty->assign('finish', $finish);
	
	$rows = $sth->fetch();
	
	$smarty->assign('rows', $rows);
	
	$smarty->display('google.tpl');
	
});

$app->get("/topup/summary(/:start(/:finish))", function ($start = null, $finish = null) use ($app, $smarty) {
	if ($start == null) {
		$start = date("Y-m-d", strtotime("-30 day"));
		$finish = date("Y-m-d", strtotime("now"));
	}

	$pdo = getDbHandler();
	
	$sql = "SELECT SUM(first) AS first, SUM(second) AS second, SUM(third) AS third, SUM(other) AS other "
	. "FROM portal_topup "
	. "WHERE report_date BETWEEN :start AND :finish ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));

	$topup = $sth->fetch();

	$smarty->assign('rows', $topup);

	$smarty->display('topup.tpl');
});


$app->get("/google/summary(/:start(/:finish))", function ($start = null, $finish = null) use ($app, $smarty) {
	if ($start == null) {
		$start = date("Y-m-d", strtotime("-7 day"));
		$finish = date("Y-m-d", strtotime("now"));
	}
	
	$pdo = getDbHandler();
	$sql = "SELECT SUM(ref) AS signup, SUM(ref_payment) AS payment, SUM(ref_payment_attempt) AS payment_attempt, SUM(ref_payment_count) AS paycount, SUM(ref_payment_attempt_count) AS payattemptcount "
	. "FROM signup "
	. "WHERE report_date BETWEEN :start AND :finish ";
	
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start . " 00:00:00", ':finish' => $finish . " 23:59:59"));
	
	$smarty->assign('start', $start);
	$smarty->assign('finish', $finish);
	
	$rows = $sth->fetch();
	
	$sql = "SELECT SUM(first) AS first, SUM(second) AS second, SUM(third) AS third, SUM(other) AS other "
	     . "FROM google_portal_topup "
	     . "WHERE report_date BETWEEN :start AND :finish ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$topup = $sth->fetch();
	
	$rows = array_merge($rows, $topup);
	$smarty->assign('rows', $rows);
	
	$smarty->display('google.tpl');
});

$app->get("/json/topup(/:start(/:finish))", function ($start = null, $finish = null) use ($app, $smarty) {
	if ($start == null) {
		$start = date("Y-m-d", strtotime("-30 day"));
		$finish = date("Y-m-d", strtotime("now"));
	}

	$pdo = getDbHandler();
	$sql = "SELECT SUM(first) AS first, SUM(second) AS second, SUM(third) AS third, SUM(other) AS other, report_date "
	. "FROM portal_topup "
	. "WHERE report_date BETWEEN :start AND :finish GROUP BY report_date";

	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));

	$smarty->assign('start', $start);
	$smarty->assign('finish', $finish);

	$rows = $sth->fetchAll();

	$data = array();
	foreach ($rows as $row) {		
		$data['first'][] = array(intval(strtotime($row['report_date']) * 1000), intval($row['first']));
		$data['second'][] = array(intval(strtotime($row['report_date']) * 1000), intval($row['second']));
		$data['third'][] = array(intval(strtotime($row['report_date']) * 1000), intval($row['third']));
		$data['other'][] = array(intval(strtotime($row['report_date']) * 1000), intval($row['other']));
	}
	
	$app->contentType("application/json");
	echo json_encode($data);
});

$app->get("/redirect(/:start(/:finish))", function ($start = null, $finish = null) use ($app, $smarty) {
	
	$db = getDbHandler();
	
	if ($start == null) {
		$start = date("Y-m-d", strtotime("-1 day"));
		$finish = date("Y-m-d", strtotime("now"));
	}
	
	$sql = "SELECT COUNT(*) as cnt, host, DATE(CONVERT_TZ(created_date, '-05:00', '+01:00')) AS date FROM log WHERE CONVERT_TZ(created_date, '-05:00', '+01:00') BETWEEN :start_time AND :finish_time AND cookie = 0 AND browser NOT LIKE '%bot%' GROUP BY host, DATE(CONVERT_TZ(created_date, '-05:00', '+01:00')) ";
	
	$sth = $db->prepare($sql);
	
	$sth->execute(array(':start_time' => $start . " 00:00:00", ':finish_time' => $finish . " 23:59:59"));
	
	$rows = $sth->fetchAll();
	
	$smarty->assign('rows', $rows);
	$smarty->assign('day_start', $start);
	$smarty->assign('day_finish', $finish);
	
	$sql = "SELECT COUNT(*) as cnt FROM log WHERE CONVERT_TZ(created_date, '-05:00', '+01:00') BETWEEN :start_time AND :finish_time AND cookie = 0 AND browser NOT LIKE '%bot%' ";
	$sth = $db->prepare($sql);
	$sth->execute(array(':start_time' => $start . " 00:00:00", ':finish_time' => $finish . " 23:59:59"));
	
	$row = $sth->fetch();
	
	$smarty->assign('total', $row['cnt']);
	
	$smarty->display('redirect.tpl');
	
});

$app->get("/redirectsignup(/:start(/:finish))", function ($start = null, $finish = null) use ($app, $smarty) {

	$db = getDbHandler();

	if ($start == null) {
		$start = date("Y-m-d", strtotime("-1 day"));
		$finish = date("Y-m-d", strtotime("now"));
	}

	$sql = "SELECT count AS cnt, host, report_date, mobile FROM signup_redirect WHERE report_date BETWEEN :start_time AND :finish_time AND TRIM(host) != '' AND host NOT IN ('g1', 'g2') GROUP BY report_date, host, mobile ";

	$sth = $db->prepare($sql);

	$sth->execute(array(':start_time' => $start, ':finish_time' => $finish));

	$rows = $sth->fetchAll();

	$smarty->assign('rows', $rows);
	$smarty->assign('day_start', $start);
	$smarty->assign('day_finish', $finish);

	$smarty->display('redirectsignup.tpl');

});

$app->get("/report", function () use ($app, $smarty) {
	
	$db = getDbHandler();
	
	$sql = "SELECT s.id, s.name, s.members, s.profile_req, SUM(IF(up.status='true',1,0)) as active, SUM(IF(up.status='false',1,0)) as inactive, s.last_checking FROM sites AS s LEFT JOIN user_profiles AS up ON up.site_id = s.id WHERE  s.status = 'true' GROUP BY s.name ORDER BY s.name ";
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$sites = array();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	foreach ($rows as $row) {
		$row['total_sent'] = getTotalSent($row['id'], $row['name']);
		$row['total_sent_today'] = getTotalSentDate($row['id'], $row['name'], date("Y-m-d"), date("Y-m-d"));
		$row['total_sent_yesterday'] = getTotalSentDate($row['id'], $row['name'], date("Y-m-d", strtotime("-1 day")), date("Y-m-d", strtotime("-1 day")));
		$row['total_sent_twodays'] = getTotalSentDate($row['id'], $row['name'], date("Y-m-d", strtotime("-2 day")), date("Y-m-d", strtotime("-1 day")));
		$row['total_sent_7d'] = getTotalSentDate($row['id'], $row['name'], date("Y-m-d", strtotime("-7 day")), date("Y-m-d", strtotime("-1 day")));
		$row['avg_24'] = getAvgSentPerProfile($row['id'], $row['name'], date("Y-m-d", strtotime("-24 hour")));
		$row['avg_48'] = getAvgSentPerProfile($row['id'], $row['name'], date("Y-m-d", strtotime("-48 hour")));
		$row['avg_7d'] = getAvgSentPerProfile($row['id'], $row['name'], date("Y-m-d", strtotime("-7 day")));
		$row['running'] = getRunning($row['id'], $row['name']);
		$row['last_reset'] = getLastReset($row['id'], $row['name']);
		$row['profile_create_today'] = getProfileCreated($row['id'], $row['name']);
		$row['profile_create_yesterday'] = getProfileCreatedYesterday($row['id'], $row['name']);
		$sites[] = $row;
	}
	
	$smarty->assign('sites', $sites);
	$smarty->display('report.tpl');
	
});

$app->get("/reset/:id(/:short)", function ($id, $short = false) use ($app) {
	
	$db = getDbHandler();
	
	$sql = "SELECT name FROM sites WHERE id=".$id;
	
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$row = $sth->fetch();
	
	$name = $row['name'];
	
	switch ($id) {
		case 51:
			$name = 'werKenntWen';
			break;
		case 91:
			$name = 'reif';
			break;
		case 86:
			$name = 'inistdrin';
			break;
	}
	
	$sql = "DELETE FROM ".$name."_sent_messages WHERE sent_datetime <= DATE_SUB(NOW(), INTERVAL 6 DAY)";	
	
	if (!$short) {
		$sql = "TRUNCATE TABLE ".$name."_sent_messages";
	}
	
	
	$sth = $db->prepare($sql);
	$sth->execute();
	
			
	$sql = "SELECT id FROM site_options WHERE site_id = '".$id."' AND site_key = 'LAST_RESET_SENTLOG'";
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$query = "";
	if ($sth->rowCount() == 0) {
		$query = 'INSERT INTO site_options (site_id, site_key, site_value) VALUES ("'.$id.'","LAST_RESET_SENTLOG","'.time().'")';
	} else {
		$query = 'UPDATE site_options SET site_value = "'.time().'" WHERE site_id = "'.$id.'" AND site_key = "LAST_RESET_SENTLOG"';
	}
	
	$sth = $db->prepare($query);
	$sth->execute();

	$app->redirect("/bot/stat/report");
	
});

$app->get("/download/excel(/:start(/:finish))", function ($start = null, $finish = null) use ($app) {
	
	require_once('Classes/PHPExcel.php');
	
	require_once 'Classes/PHPExcel/Writer/Excel2007.php';
	
	$objPHPExcel = new PHPExcel();
	
	$objPHPExcel->getProperties()->setCreator("Netzilla");
	$objPHPExcel->getProperties()->setLastModifiedBy("Netzilla");
	$objPHPExcel->getProperties()->setTitle("P30 Bot Stats");
	$objPHPExcel->getProperties()->setSubject("P30 Bot Stats");
	$objPHPExcel->getProperties()->setDescription("Past 30 days bot statistics");
	
	$pdo = getDbHandler();
	
	if ($start == null) {
		$start = date("Y-m-d", strtotime("-30 day"));
		$finish = date("Y-m-d");
	}
	
	$sql = "SELECT name, site_id, count, report_date FROM sent_message_date LEFT OUTER JOIN sites AS s ON s.id = site_id WHERE report_date BETWEEN :start AND :finish ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$grid = array();
	
	foreach ($rows as $row) {
		$grid[$row['name']][$row['report_date']] = $row['count'];
	}
	
	$date_header = array();
	
	foreach($grid as $site_id => &$dates) {
		
		$date_index = $start;
		
		do {
			
			if (!isset($dates[$date_index])) {
				$dates[$date_index] = 0;
			}
			
			if (!isset($date_header[$date_index])) {
				$date_header[$date_index] = $date_index;
			}
			
			$date_index = date("Y-m-d", strtotime($date_index) + (60 * 60 * 24));
			
		} while (strtotime($date_index) <= strtotime("now"));
		ksort($dates);
	}

	$rows = array();
	
	$rows[] = array_merge(array('Site'), array_values($date_header));
	
	foreach ($grid as $site_id => $dates) {
		$rows[] = array_merge(array($site_id), array_values($dates));
	}
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->fromArray($rows, null, 'A1', true);
	
	//signup counts
	$sql = "SELECT name, site_id, count, report_date FROM signup_site_date LEFT OUTER JOIN sites AS s ON s.id = site_id WHERE report_date BETWEEN :start AND :finish ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$grid2 = array();
	
	foreach ($rows as $row) {
		$grid2[$row['name']][$row['report_date']] = $row['count'];
	}
	
	$date_header = array();
	
	foreach($grid2 as $site_id => &$dates) {
	
		$date_index = $start;
	
		do {
				
			if (!isset($dates[$date_index])) {
				$dates[$date_index] = 0;
			}
				
			if (!isset($date_header[$date_index])) {
				$date_header[$date_index] = $date_index;
			}
				
			$date_index = date("Y-m-d", strtotime($date_index) + (60 * 60 * 24));
				
		} while (strtotime($date_index) <= strtotime("now"));
		ksort($dates);
	}
	
	$rows2 = array();
	
	$rows2[] = array_merge(array('Site'), array_values($date_header));
	
	foreach ($grid2 as $site_id => $dates) {
		$rows2[] = array_merge(array($site_id), array_values($dates));
	}
	
	
	$objPHPExcel->createSheet(1);
	$objPHPExcel->setActiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->fromArray($rows2, null, 'A1', true);
	

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
	$app->contentType('application/vnd.ms-excel');
	$app->response()->header('Content-Disposition', 'attachment; filename="stats.xls"');
	
	$objWriter->save('php://output');	
});

$app->get("/json/daily(/:start(/:finish(/:market)))", function ($start = null, $finish = null, $market = 'g') use ($app) {
	
	if ($start == null) {
		$start = date("Y-m-d", strtotime("-30 day"));
		$finish = date("Y-m-d");
	}
	
	$mSQL = "";
	
	if ($market != null && $market != 'a') {
		if ($market == 'g') $mSQL = " AND portal_id NOT IN (3) ";
		if ($market == 'e') $mSQL = " AND portal_id IN (3) ";
	}
	
	$pdo = getDbHandler();
	$sql = "SELECT SUM(count) AS cnt, report_date FROM sent_message_date WHERE report_date BETWEEN :start AND :finish GROUP BY report_date ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$data = array();
	$messages = array();
	foreach ($rows as $row) {
		$messages[] = array(intval(strtotime($row['report_date']) * 1000), intval($row['cnt']));
	}
	
	$data['messages'] = $messages;
	
	$sql = "SELECT SUM(member_active) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish " . $mSQL . " GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$activations = array();
	foreach ($rows as $row) {
		$activations[] = array(intval(strtotime($row['report_date']) * 1000), intval($row['cnt']));
	}
	
	$data['activations'] = $activations;
	
	$sql = "SELECT SUM(ref) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish " . $mSQL . " GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$refs = array();
	foreach ($rows as $row) {
		$refs[] = array(intval(strtotime($row['report_date']) * 1000), intval($row['cnt']));
	}
	
	$data['refs'] = $refs;
	
	$sql = "SELECT SUM(ref_payment) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish " . $mSQL . " GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$ref_pays = array();
	foreach ($rows as $row) {
		$ref_pays[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}
	
	$data['ref_pays'] = $ref_pays;
	
	$sql = "SELECT SUM(ref_payment_attempt) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish " . $mSQL . " GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$ref_pay_attempt = array();
	foreach ($rows as $row) {
		$ref_pay_attempt[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}
	
	$data['ref_pay_attempt'] = $ref_pay_attempt;
	
	$sql = "SELECT SUM(mobile_active) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish " . $mSQL . " GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$mobile_active = array();
	foreach ($rows as $row) {
		$mobile_active[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}
	
	$data['mobile_active'] = $mobile_active;
	
	$sql = "SELECT SUM(mobile_count) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish " . $mSQL . " GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$mobile_signup = array();
	foreach ($rows as $row) {
		$mobile_signup[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}
	
	$data['mobile_signup'] = $mobile_signup;
	
	$app->contentType("application/json");
	echo json_encode($data);
});

$app->get("/json/google(/:start(/:finish))", function ($start = null, $finish = null) use ($app) {

	if ($start == null) {
		$start = date("Y-m-d", strtotime("-30 day"));
		$finish = date("Y-m-d");
	}

	$pdo = getDbHandler();

	$sql = "SELECT SUM(ref) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	$refs = array();
	foreach ($rows as $row) {
		$refs[] = array(intval(strtotime($row['report_date']) * 1000), intval($row['cnt']));
	}

	$data['refs'] = $refs;

	$sql = "SELECT SUM(ref_payment) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	$ref_pays = array();
	foreach ($rows as $row) {
		$ref_pays[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}

	$data['ref_pays'] = $ref_pays;

	$sql = "SELECT SUM(ref_payment_attempt) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	$ref_pay_attempt = array();
	foreach ($rows as $row) {
		$ref_pay_attempt[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}

	$data['ref_pay_attempt'] = $ref_pay_attempt;
	
	$sql = "SELECT SUM(ref_payment_count) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$ref_pay_count = array();
	foreach ($rows as $row) {
		$ref_pay_count[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}
	
	$data['ref_pay_count'] = $ref_pay_count;
	
	$sql = "SELECT SUM(ref_payment_attempt_count) AS cnt, report_date FROM signup WHERE report_date BETWEEN :start AND :finish GROUP BY report_date  ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));
	
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$ref_pay_attempt_count = array();
	foreach ($rows as $row) {
		$ref_pay_attempt_count[] = array(intval(strtotime($row['report_date']) * 1000), floatval($row['cnt']));
	}
	
	$data['ref_pay_attempt_count'] = $ref_pay_attempt_count;

	$app->contentType("application/json");
	echo json_encode($data);
});

$app->get("/json/hourly(/:start(/:finish))", function ($start = null, $finish = null) use ($app) {

	if ($start == null) {
		$start = date("Y-m-d 00:00:00", strtotime("-7 day"));
		$finish = date("Y-m-d 23:59:59");
	}
	
	$pdo = getDbHandler();
	$sql = "SELECT SUM(count) AS cnt, report_date, hour FROM sent_message_hour WHERE report_date BETWEEN :start AND :finish GROUP BY report_date, hour ";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':start' => $start, ':finish' => $finish));

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	$data = array();

	foreach ($rows as $row) {
		$data[] = array(intval(strtotime($row['report_date'] . " " . $row['hour']. ":00:00") * 1000), intval($row['cnt']));
	}
	$app->contentType("application/json");
	echo json_encode($data);
});

$app->get("/", function () {
	$dbh = getDbHandler();
	
	$sql = "SELECT host, port FROM proxy ORDER BY RAND() LIMIT 1";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	
	echo json_encode($result);
});
$app->run();

function getTotalSent($id, $name)
{
	$db = getDbHandler();
	
	switch ($id) {
		case 51:
			$name = 'werKenntWen';
			break;
		case 91:
			$name = 'reif';
			break;
		case 86:
			$name = 'inistdrin';
			break;
		case 95:
			$name = 'glsh';
			break;
	}
	
	$sql = "SELECT COUNT(*) AS cnt FROM " . $name . "_sent_messages";
	
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	
	return $row['cnt'];
}

function getTotalSentDate($id, $name, $date_from, $date_to)
{
	$db = getDbHandler();

	switch ($id) {
		case 51:
			$name = 'werKenntWen';
			break;
		case 91:
			$name = 'reif';
			break;
		case 86:
			$name = 'inistdrin';
			break;
		case 95:
			$name = 'glsh';
			break;
	}

	$sql = "SELECT COUNT(*) AS cnt FROM " . $name . "_sent_messages WHERE CONVERT_TZ(sent_datetime, '+07:00', '+01:00') BETWEEN '" . $date_from . " 00:00:00' AND '" . $date_to . " 23:59:59' ";

	$sth = $db->prepare($sql);
	$sth->execute();

	$row = $sth->fetch(PDO::FETCH_ASSOC);

	return $row['cnt'];
}

function getRunning($id, $name)
{
	$db = getDbHandler();
	
	$sql = "SELECT COUNT(id) AS total,
	CASE sex
	WHEN 'Male' THEN 'M'
	WHEN 'Female' THEN 'F'
	END AS sender,
	CASE target
	WHEN 'Male' THEN 'M'
	WHEN 'Female' THEN 'F'
	END AS receiver
	FROM commands WHERE site = '".$id."' AND status = 'true'
	GROUP BY sex";
	
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$rows = $sth->fetchAll();
	
	$str = "";
	
	foreach($rows as $row) {
		$str .= $row['sender']." -> ".$row['receiver']." [<strong>".$row['total']."</strong>] ";
	}
	
	return $str;
}

function getProfileCreated($id, $name)
{
	$db = getDbHandler();
	
	$sql = "SELECT COUNT(*) AS cnt FROM user_profiles WHERE site_id = " . $id . " AND created_datetime >= '" . date("Y-m-d 00:00:00") . "' ";
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$row = $sth->fetch();
	
	return $row['cnt'];
}

function getProfileCreatedYesterday($id, $name)
{
	$db = getDbHandler();

	$sql = "SELECT COUNT(*) AS cnt FROM user_profiles WHERE site_id = " . $id . " AND created_datetime >= '" . date("Y-m-d 00:00:00", strtotime("-1 day")) . "' AND created_datetime <= '" . date("Y-m-d 23:59:59", strtotime("-1 day")) . "'";
	$sth = $db->prepare($sql);
	$sth->execute();

	$row = $sth->fetch();

	return $row['cnt'];
}

function getAvgSentPerProfile($id, $name, $from_date)
{
	$db = getDbHandler();

	switch ($id) {
		case 51:
			$name = 'werKenntWen';
			break;
		case 91:
			$name = 'reif';
			break;
		case 86:
			$name = 'inistdrin';
			break;
		case 95:
			$name = 'glsh';
			break;
	}
	
	$sql = "SELECT COUNT(*) AS cnt FROM " . $name . "_sent_messages WHERE sent_datetime >= :from_date GROUP BY from_username";

	$sth = $db->prepare($sql);
	$sth->execute(array(':from_date' => $from_date));

	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	$data = array();
	
	foreach ($rows as $row) {
		$data[] = $row['cnt'];
	}
	
	if (count($data) > 0) { 
	
		$avg = (int) (array_sum($data) / count($data));
		$max = max($data);
		$min = min($data);
		
		return $avg . " / " . $max . " / " . $min;
	}
	
	return "";
}

function getLastReset($id, $name)
{
	$db = getDbHandler();
	$sql = 'SELECT site_value FROM site_options WHERE site_id = "'.$id.'" AND site_key="LAST_RESET_SENTLOG"';
	
	$sth = $db->prepare($sql);
	$sth->execute();
	
	$last_reset = 'N/A';
	
	if ($sth->rowCount() != 0) {
		$row = $sth->fetch();
		$last_reset = date('D j M Y H:i', $row['site_value']);
	}
	
	return $last_reset;
}