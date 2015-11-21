<?php 
session_start();
require_once '_include/dbconnect.php';
require_once 'funcs.php';

if (!isset($_GET['action'])) exit;

switch ($_GET['action']) {
	
	case 'vcard':
		$sql = "SELECT info FROM vcard WHERE site_id = " . $_GET['id'];
		$rs = mysql_query($sql);
		$r = mysql_fetch_assoc($rs);
		echo $r['info'];
		break;
		
	case 'vcardh':
		$sql = "SELECT info FROM vcard WHERE site_id = " . $_GET['id'];
		$rs = mysql_query($sql);
		$r = mysql_fetch_assoc($rs);
		if ($r['info'] == null) {
			echo ".";	
		} else {
			echo $r['info'];
		}
		break;
		
	case 'vcardh2':
		$sql = "SELECT info2 FROM vcard WHERE site_id = " . $_GET['id'];
		$rs = mysql_query($sql);
		$r = mysql_fetch_assoc($rs);
		if ($r['info2'] == null) {
			echo ".";
		} else {
			echo $r['info2'];
		}
		break;
		
	case 'savevcard':
		$sql = "SELECT site_id FROM vcard WHERE site_id = " . $_POST['site_id'];
		$info = $_POST['info'];
		$info2 = $_POST['info2'];
		if (!preg_match("/<br>/", $info)) {
			$info = preg_replace("/<div>/", "<br>", $info);
		}
		
		if (!preg_match("/<br>/", $info2)) {
			$info2 = preg_replace("/<div>/", "<br>", $info2);
		}		
		$info = strip_tags($info, '<br>');
		$info2 = strip_tags($info2, '<br>');
		$info = preg_replace("/<br>/", "\r\n", $info);
		$info2 = preg_replace("/<br>/", "\r\n", $info2);
		$rs = mysql_query($sql);
		if (mysql_num_rows($rs) == 0) {
			$sql = "INSERT INTO vcard (site_id, info, info2, created) VALUES (" . $_POST['site_id'] .", '" . mysql_real_escape_string($info) ."', '" . mysql_real_escape_string($info2) ."', NOW()) ";	
		} else {
			$sql = "UPDATE vcard SET info = '" . mysql_real_escape_string($info) . "', "
			     . " info2 = '" . mysql_real_escape_string($info2) ."' "
			     . "WHERE site_id = " . $_POST['site_id'];
		}
		$rs = mysql_query($sql);
		
		$code = 0;
		if (mysql_affected_rows() > 0) {
			$code = 1;
		}
		
		$json = array('code' => $code);
		echo json_encode($json);
		
		break;
		
	case 'city':
		$sql = "SELECT name FROM city WHERE status = 1";
		$rs = mysql_query($sql);
		echo "<select name='profile_city'>";
		while ($row = mysql_fetch_assoc($rs)) {
			$row['name'] = preg_replace("/[^A-Za-z\s]/", "", $row['name']);
			echo "<option value='" . trim($row['name']) ."'>" . trim($row['name']) ."</option>";
		}
		echo "</select>";
		echo '<input type="checkbox" name="ecity" id="ecity" value="1"/>Process for each city';
	
}
