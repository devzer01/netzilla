<?php
//profiler / RemoveBadProfile
error_reporting(E_ALL);
require_once('../../classes/DBconnect.php');
require_once('config.php');

@mysql_connect(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD) or die("Database connection error."); //connect mysql
@mysql_select_db(MYSQL_DATABASE) or die("Error select DB."); //connect database
mysql_query("SET NAMES UTF8");
define("MEMBERS_PER_PAGE", 3);

function check_input($value)
{
	// Stripslashes
	if (get_magic_quotes_gpc())
	{
		$value = stripslashes($value);
	}
	// Quote if not a number
	if (!is_numeric($value) && !is_array($value))
	{
		//$value = mysql_real_escape_string($value) ;
		$value = addslashes($value);
	}
	return $value;
}

if(!isset($_GET['method']))
{
	//$page = 1;
	$page = isset($_GET['page'])?(($_GET['page']>0)?$_GET['page']:1):1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <title>Profile checking</title>
	<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
	<script>
	function removePicture(id)
	{
		$.ajax({
			type: "GET",
			url: "check.php",
			data: { method: "ajax", action: "set", id: id, key: "picturepath", val: ""}
		}).done(function() {
			window.location.reload();
		});
	}

	function approveProfile(id)
	{
		$.ajax({
			type: "GET",
			url: "check.php",
			data: {
				method: "ajax",
				action: "approveProfile",
				id: id,
				username: $("#username_"+id).val(),
				gender: $("#gender_"+id).val(),
				birthday: $("#birthday_"+id).val(),
				country: $("#country_"+id).val(),
				state: $("#state_"+id).val(),
				city: $("#city_"+id).val(),
				description: $("#description_"+id).val(),
				}
		}).done(function(data) {
			if(data == "1")
				window.location.reload();
			else
				alert(data);
		});
	}

	function deleteProfile(id)
	{
		$.ajax({
			type: "GET",
			url: "check.php",
			data: { method: "ajax", action: "deleteProfile", id: id}
		}).done(function() {
			window.location.reload();
		});
	}

	function getStates(profile_id, state_id, city_id)
	{
		$("#state_span_"+profile_id).load("check.php?method=ajax&action=load_state&id="+profile_id+"&selected="+state_id+"&country_id="+$("#country_"+profile_id).val(), function() {
			getCities(profile_id, city_id);
		});
	}

	function getCities(profile_id, city_id)
	{
			$("#city_span_"+profile_id).load("check.php?method=ajax&action=load_city&id="+profile_id+"&selected="+city_id+"&state_id="+$("#state_"+profile_id).val());
	}

	function getAge(id, dateString) 
	{
		var today = new Date();
		var birthDate = new Date(dateString);
		var age = today.getFullYear() - birthDate.getFullYear();
		var m = today.getMonth() - birthDate.getMonth();
		if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
		{
			age--;
		}
		$("#birthday_age_"+id).html(age);
	}
	</script>
</head>
<body>
<?php
$sql = "FROM member WHERE type=4 AND fake=1 AND id>10 AND isactive=0 and checked=0";
$total = DBConnect::retrieve_value("SELECT COUNT(*) ".$sql);
if($total > (($page-1)*MEMBERS_PER_PAGE))
{
}
else
{
	$page = ceil($total / MEMBERS_PER_PAGE);
	echo ' <META HTTP-EQUIV="Refresh" CONTENT="0;URL=check.php?page='.$page.'">';
	exit;
}
$list = DBConnect::assoc_query_2D("SELECT picturepath, id, username, gender, birthday, country, state, city, description ".$sql." LIMIT ".(MEMBERS_PER_PAGE*($page-1)).",".MEMBERS_PER_PAGE);
$countries = DBConnect::assoc_query_2D("SELECT * FROM xml_countries ");
echo "<table border='1' width='100%'>";
$i=0;
foreach($list as $item)
{
	if($i==0)
	{
		echo "<tr>";
		echo "<th>Action</th>";
		foreach($item as $key=>$val)
		{
			switch($key)
			{
				case "id":
					break;
				case "state":
					break;
				case "city":
					break;
				case "country":
					break;
				case "description":
					echo "<th>".$key."</th>";
					echo "<th>Original</th>";
					break;
				default:
					echo "<th>".$key."</th>";
			}
		}
		echo "</tr>";
	}
	echo "<tr>";
	$profile_id = $item['id'];
	$picturepath = $item['picturepath'];
	$country_id = $item['country'];
	$state_id = $item['state'];
	echo "<td align='center'>
	<input type='button' value='Approve' onclick='approveProfile(".$profile_id.")'/><br/>
	<input type='button' value='Delete' onclick='deleteProfile(".$profile_id.")'/>";
	/*if($picturepath != '')
		echo "<br/><input type='button' value='Remove picture' onclick='removePicture(".$profile_id.")'/>";*/
	echo "</td>";
	foreach($item as $key=>$val)
	{
		switch($key)
		{
			case 'id':
				break;
			case "picturepath":
				if($val!="")
				{
					if(file_exists("thumbs/".$val))
						echo "<td><img src='thumbs/".$val."' style='max-width: 250px'/></td>";
					else
						echo "<td>Missing</td>";
				}
				else
					echo "<td></td>";
				break;
			case "description":
				$original = $val;
				$temp = explode("\n", $val);

				foreach($temp as $paragraph)
				{
					if(strlen($paragraph)>10)
					{
						$val = trim($paragraph);
						break;
					}
				}
				echo "<td><textarea autocomplete='off' id='description_".$profile_id."' cols='60' rows='10'>".$val."</textarea></td><td>".nl2br($original)."</td>";
				break;
			case "gender":
				echo "<td><select id='gender_".$profile_id."'><option value='1'";
				if($val == "1")
					echo " selected='selected'";
				echo ">Male</option><option value='2'";
				if($val == "2")
					echo " selected='selected'";
				echo ">Female</option></select></td>";
				break;
			case "country":
				/*echo "<td><select id='country_".$profile_id."' onchange='getStates(".$profile_id.", 0,0)'>";
				foreach($countries as $country)
				{
					echo "<option value='".$country['id']."'";
					if($val == $country['id'])
						echo " selected='selected'";
					echo ">".$country['name_de']."</option>";
				}
				echo "</select></td>";*/
				break;
			case "state":
				/*echo "<td><span id='state_span_".$profile_id."'></span>";
				?>
				<script>
					$( document ).ready(function() {
						getStates(<?php echo $profile_id;?>, <?php echo $state_id;?>, <?php echo $item['city'];?>);
					});
				</script>
				</td>
				<?php*/
				break;
			case "city":
				/*echo "<td><span id='city_span_".$profile_id."'></span></td>";*/
				break;
			case "birthday":
				echo "<td><input type='text' id='".$key."_".$profile_id."' value='".$val."' onkeyup='getAge(".$profile_id.", this.value)'/><br/>AGE: <span id='birthday_age_".$profile_id."'></span>";
				?>
				<script>
					$( document ).ready(function() {
						getAge(<?php echo $profile_id;?>, "<?php echo $val;?>");
					});
				</script>
				<?php
				echo "</td>";
				break;
			default:
				echo "<td><input type='text' id='".$key."_".$profile_id."' value='".$val."'/></td>";
		}
	}
	echo "</tr>";
	$i++;
}
echo "</table><br/>".$total." total<br/>";
if($page > 100)
	echo "<a href='check.php?page=".($page-100)."'>[Previous 100]</a> ";
if($page > 1)
	echo "<a href='check.php?page=".($page-1)."'>[Previous]</a> ";
echo "[".$page."] ";
if($total > ($page*MEMBERS_PER_PAGE))
	echo "<a href='check.php?page=".($page+1)."'>[Next]</a> ";
if($total > (($page+99)*MEMBERS_PER_PAGE))
	echo "<a href='check.php?page=".($page+100)."'>[Next 100]</a> ";
if($page > 1)
echo "<br/>";
?>
</body>
</html>
<?php
}
elseif($_GET['method']=="ajax")
{
	switch($_GET['action'])
	{
		case "set":
			$id = $_GET['id'];
			$key = $_GET['key'];
			$val = $_GET['val'];
			if(DBConnect::retrieve_value("SELECT id FROM member WHERE id=".$id))
			{
				if(DBConnect::execute_q("UPDATE member SET ".$key."='".$val."' WHERE id=".$id))
					echo "1";
				else
					echo "0";
			}
			break;
		case "load_state":
			$id = $_GET['id'];
			$country = $_GET['country_id'];
			$states = DBConnect::assoc_query_2D("SELECT * FROM xml_states WHERE parent=".$country);
			echo "<td><select id='state_".$id."' onchange='getCities(".$id.", 0)'>";
			foreach($states as $state)
			{
				echo "<option value='".$state['id']."'";
				if($state['id'] == $_GET['selected'])
					echo " selected='selected'";
				echo ">".$state['name_de']."</option>";
			}
			echo "</select></td>";
			break;
		case "load_city":
			$id = $_GET['id'];
			$state = $_GET['state_id'];
			$cities = DBConnect::assoc_query_2D("SELECT * FROM xml_cities WHERE parent=".$state);
			echo "<td><select id='city_".$id."'>";
			foreach($cities as $city)
			{
				echo "<option value='".$city['id']."'";
				if($city['id'] == $_GET['selected'])
					echo " selected='selected'";
				echo ">".$city['name_de']."</option>";
			}
			echo "</select></td>";
			break;
		case "approveProfile":
			$id = check_input($_GET['id']);
			$username = check_input($_GET['username']);
			$gender = check_input($_GET['gender']);
			$birthday = check_input($_GET['birthday']);
			//$country = check_input($_GET['country']);
			//$state = check_input($_GET['state']);
			//$city = check_input($_GET['city']);
			$description = check_input($_GET['description']);
			if(!DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."' AND id!=".$id))
			{
				//DBConnect::execute_q("UPDATE member SET username='".$username."', gender='".$gender."', birthday='".$birthday."', country='".$country."', state='".$state."', city='".$city."', description='".$description."', checked=1, isactive=1 WHERE id=".$id);
				DBConnect::execute_q("UPDATE member SET username='".$username."', gender='".$gender."', birthday='".$birthday."', description='".$description."', checked=1, isactive=1 WHERE id=".$id);
				echo "1";
			}
			else
			{
				echo "Duplicate username!";
			}
			break;
		case "deleteProfile":
			$id = check_input($_GET['id']);
			if($profile = DBConnect::assoc_query_1D("SELECT * FROM member WHERE id=".$id))
			{
				if(file_exists("thumbs/".$profile['picturepath']))
				{
					unlink("thumbs/".$profile['picturepath']);
					rmdir("thumbs/".$profile['id']);
				}
				DBConnect::execute_q("DELETE FROM member WHERE id=".$id);
			}
			echo "1";
			break;
	}
}
?>