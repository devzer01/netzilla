<?php
	# This page is used for sorting datas
	# Create by Parkin R.
	# Jun 19, 2007
	require_once('classes/top.class.php');
	//$sql = "ALTER TABLE `member` ADD `count` INT NOT NULL DEFAULT '0'";
	//$query = mysql_query($sql);
	$sql = "SELECT `city`, `id`,`gender`,`picturepath` FROM `member` WHERE isactive != 0 ORDER BY `city`, `flag` DESC, `birthday` DESC";
	$query = mysql_query($sql);
	$num = mysql_num_rows($query);
	while($rec=mysql_fetch_array($query)){
		$City = $rec['city'];
		$Gender = $rec['gender']; 
		$PP = $rec['picturepath'];
		$Pic = 1; 
		if(trim($PP)!=''){ $Pic = 0; }
		$Datas[$City][$Gender][$Pic][] = $rec['id'];
	}
	$sql = "UPDATE `member` SET `count` = '0' WHERE isactive = 0"; 
	$query = mysql_query($sql); 
	//print_r($Datas);
	foreach($Datas as $KeyCity => $ValCity){
		foreach($ValCity as $KeyGender => $ValGender){
			foreach($ValGender as $KeyPic => $ValPic){ 
				foreach($ValPic as $KeyDatas => $ValDatas){ 
					$Number = $Number+1;
					$sql = "UPDATE `member` SET `count` = '$Number' WHERE `id` = $ValDatas AND isactive = 1"; 
					$query = mysql_query($sql);
				} 
			} 
			$Number = 0;
		} 
	} 
?>