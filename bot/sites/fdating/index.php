<?php
require("dbconnect.php");

/*$users = DBConnect::assoc_query_2D("SELECT username, COUNT(username) AS ct FROM fdating_member GROUP BY username HAVING ct > 1");

foreach($users as $user)
{
	$list = DBConnect::assoc_query_2D("SELECT id FROM fdating_member WHERE username = '".$user['username']."'");
	$i = 1;
	foreach($list as $item)
	{
		DBConnect::execute_q("UPDATE fdating_member SET username='".$user['username']."-".$i."' WHERE id=".$item['id']);
		$i++;
	}
}
exit;*/

$users = DBConnect::assoc_query_2D("SELECT * FROM fdating_member LIMIT 10");

echo "<table><tr><td></td><th>Name</th><th>Height</th><th>Height (NEW)</th><th>Weight</th><th>Weight (NEW)</th><th>Smoking</th><th>Smoking (NEW)</th><th>Eye Color</th><th>Eye Color (NEW)</th><th>Hair Color</th><th>Hair Color (NEW)</th></tr>";
foreach($users as $user)
{
	echo "<tr>";
	echo "<td><img src='thumbs/".$user['picturepath']."'></td>";
	echo "<td>".$user['username']."</td>";
	echo "<td>".$user['height']."</td>";
	echo "<td>".convertHeight($user['height'])."</td>";
	echo "<td>".$user['weight']."</td>";
	echo "<td>".convertWeight($user['weight'])."</td>";
	echo "<td>".$user['smoking']."</td>";
	echo "<td>".convertSmoking($user['smoking'])."</td>";
	echo "<td>".$user['eyescolor']."</td>";
	echo "<td>".convertEyecolor($user['eyescolor'])."</td>";
	echo "<td>".$user['haircolor']."</td>";
	echo "<td>".convertHaircolor($user['haircolor'])."</td>";
	echo "</tr>";
}
echo "</table>";

function convertHeight($height)
{
	if($height != "")
	{
		if(is_numeric($height))
		{
			return $height;
		}
		else
		{
			$height = substr($height, strpos($height,"(")+1);
			$height = substr($height, 0, strpos($height,")"));
			$height = 100+str_replace(array("1 m "," cm"),"", $height);

			$range=array();
			for($i=48; $i<=84; $i++)
			{
				$cm = round($i*2.54);
				array_push($range, $cm);
			}

			foreach($range as $key => $val)
			{
				if($height < $val)
					return $key;
			}
			return 37;
		}
	}
	else
	{
		return 0;
	}
}

function convertWeight($weight)
{
	if($weight != "")
	{
		if(is_numeric($weight))
		{
			return $weight;
		}
		else
		{
			$weight = substr($weight, strpos($weight,"(")+1);
			$weight = substr($weight, 0, strpos($weight,")"));
			$weight = str_replace(" kg","", $weight);

			$range=array();
			for($i=80; $i<=305; $i=$i+5)
			{
				$kg = round($i*0.45359237);
				array_push($range, $kg);
			}

			foreach($range as $key => $val)
			{
				if($weight < $val)
					return $key;
			}
			return 46;
		}
	}
	else
	{
		return 0;
	}
}

function convertSmoking($smoking)
{
	if($smoking != "")
	{
		if(is_numeric($smoking))
		{
			return $smoking;
		}
		else
		{
			switch($smoking)
			{
				case "No";
					return 0;
					break;
				case "Rarely":
				case "Often":
				case "Very often":
					return 1;
					break;
			}
		}
	}
	else
	{
		return 2;
	}
}

function convertEyecolor($eyecolor)
{
		return 0;
}

function convertHaircolor($haircolor)
{
	if($haircolor != "")
	{
		if(is_numeric($haircolor))
		{
			return $haircolor;
		}
		else
		{
			switch($haircolor)
			{
				case "Black":
					return 1;
					break;
				case "Brown":
					return 2;
					break;
				case "Blonde":
					return 3;
					break;
				case "Red":
					return 4;
					break;
				case "Auburn";
				case "Brunette":
				case "Chestnut":
				case "Golden":
				case "Charcoal":
				case "Silver":
				case "Gray":
				case "Bald":
					return 5;
					break;
			}
		}
	}
	else
	{
		return 0;
	}
}
?>