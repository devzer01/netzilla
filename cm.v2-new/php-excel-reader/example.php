<?php
if (!isset($_SESSION)) 
  	session_start();

error_reporting(E_ALL ^ E_NOTICE);

function DateFormatDB($date)
{
	$newDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$date);
	return $newDate;
}

function DateFormatDisplay($date)
{
	$newDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3/$2/$1",$date);
	return $newDate;
}
?>
<html>
<head>
<style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:12px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>
</head>

<body>
<?php
if(isset($_POST['import']) && $_POST['import']=="excel")
{
	/*echo "<br/><pre>";
	print_r($_SESSION['schedules']);
	echo "</pre>";*/
	foreach($_SESSION['schedules'] as $agentId => $schedules)
	{
		foreach($schedules as $sch_date => $shifts)
		{
			$shift1_start = "";
			$shift1_end = "";
			$shift2_start = "";
			$shift2_end = "";
			$shift3_start = "";
			$shift3_end = "";
			if(!(empty($shifts[0])))
			{
				$shift1_start = $shifts[0]['start'];
				$shift1_end = $shifts[0]['end'];
			}

			if(!(empty($shifts[1])))
			{
				$shift2_start = $shifts[1]['start'];
				$shift2_end = $shifts[1]['end'];
			}

			if(!(empty($shifts[2])))
			{
				$shift3_start = $shifts[2]['start'];
				$shift3_end = $shifts[2]['end'];
			}

			$sql = "INSERT INTO `sms_app`.`anims_schedule` (`animID`, `date`, `shift1_start`, `shift1_end`, `shift2_start`, `shift2_end`, `shift3_start`, `shift3_end`, `update`) VALUES ('$agentId', '$sch_date', '$shift1_start', '$shift1_end', '$shift2_start', '$shift2_end', '$shift3_start', '$shift3_end', NOW())";

			echo $sql.";<br/>";
		}
		echo "<br/>--------------------------<br/>";
	}
}
else if(isset($_FILES["file"]["name"]))
{
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	$target = "../xls/";
	if ($extension=="xls")
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		}
		else
		{
			/*echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			echo "Type: " . $_FILES["file"]["type"] . "<br>";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";*/
			$filename = $_FILES["file"]["name"];

			/*if (file_exists($target . $filename))
			{
				echo $filename . " already exists. ";
			}
			else
			{*/
				move_uploaded_file($_FILES["file"]["tmp_name"], $target . $filename);
				//echo "Stored in: " . $target . $filename;

				//////////////////////////////////////////////
				// Start get data from xls store to SESSION //
				//////////////////////////////////////////////
				require_once 'excel_reader2.php';
				//$filename = "June-logs-temp.xls";//"example.xls";
				$reader = new Spreadsheet_Excel_Reader();
				$reader->setUTFEncoder('iconv');
				$reader->setOutputEncoding('UTF-8');
				$reader->read($target.$filename);
				//$reader = new Spreadsheet_Excel_Reader("example.xls");
				//echo $reader->dump(true,true); 

				$_SESSION['schedules'] = array();
				foreach($reader->sheets as $k=>$data)
				{
					if($reader->boundsheets[$k]['name']=="Schedules")//get data only on sheet name is 'Schedules'
					{
						/*
						//get the last date of the month
						$firstrow = $data['cells'][1];
						//remove first cell of the first row : data is 'Agent ID'
						if($firstrow[1]=="Agent ID")
							unset($firstrow[1]);

						$max_col = max($firstrow)+1;
						*/
						echo '<form action="example.php" method="post">
								<input name="import" type="hidden" value="excel"/>
								<input name="submit" type="submit" value="Import Excel"/>
							</form>';
						echo "<table width='100%' border='1'>";
						
						//get data start from col 2 to col 32, maximum date on the month is 31
						$min_col = 2;
						$max_col = 32;

						foreach($data['cells'] as $line => $row)
						{

							/*if($line>=2)
							{*/
								$agent_id = $row[1];
								echo "<tr>";
								echo "<td align='center' nowrap>".$agent_id."</td>";
								for($col=$min_col;$col<=$max_col;$col++)//foreach($row as $col => $cell)//
								{
									$cell = $row[$col];
									$day = $data['cells'][1][$col];
									if($day!="")
									{
										if($line==1)
										{
											echo "<td align='center'>".date("D",strtotime($day))."<br/>".$day."</td>";
										}
										else
										{
											if((strpos($cell,"-")!==false) && trim($cell)!="")
											{
												echo "<td align='center' nowrap>";
												$arr_shift = explode(";",$cell);
												//echo $day." : $cell<br/>";
												$cur_date = DateFormatDB($day);
												foreach($arr_shift as $key => $shift)
												{
													if($key>0)
														echo "<hr/>";

													$arr_time = explode("-",$shift);
													$start = $cur_date." ".trim($arr_time[0]).":00";
													$end = $cur_date." ".trim($arr_time[1]).":00";
													if(strtotime($end)<=strtotime($start))
													{
														$cur_date = date("Y-m-d",strtotime($cur_date)+86400);
														$end = $cur_date." ".$arr_time[1].":00";
													}

													echo "s : ".$start."<br/>e : ".$end."<br/>";

													$_SESSION['schedules'][$agent_id][DateFormatDB($day)][$key] = array("start"=>$start,"end"=>$end);
												}/**/
												//echo "<br/>--------------------------<br/>";
												//echo "<td>Agent Id : ".$agent_id."</td>";
												echo "</td>";
											}
											else
											{
												echo "<td align='center'>&nbsp;</td>";
											}
										}
									}
								}
								//echo "<br/>--------------------------------------------<br/>";
								echo "</tr>";
							/*}*/
						}
						echo "</table>";
					}
				}

				//////////////////////////////////////////////
				//  End get data from xls store to SESSION  //
				//////////////////////////////////////////////
			//}
		}
	}
	else
	{
		echo "Invalid file";
	}
}
else
{
	unset($_SESSION['schedules']);
?>
	<p>Please upload .xls file only</p>
	<form action="example.php" method="post" enctype="multipart/form-data">
		<label for="file">Filename:</label>
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Submit">
	</form>
<?php 
}
?>
</body>
</html>
