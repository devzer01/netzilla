<?php
/*$link = mysql_connect("87.106.2.201", "manu", "orkan2007")
    or die("Keine Verbindung m?glich: " . mysql_error());
mysql_select_db("singlescout18") or die("Auswahl der Datenbank fehlgeschlagen");*/

//$link = mysql_connect("80.237.155.45", "hauke", "dragon33")
    //or die("Keine Verbindung m?glich: " . mysql_error());
//mysql_select_db("highlove") or die("Auswahl der Datenbank fehlgeschlagen");

$link = mysql_connect("87.106.9.172","hauke","montecassino")
    or die("Keine Verbindung m?glich: " . mysql_error());
mysql_select_db("howerbedaten") or die("Auswahl der Datenbank fehlgeschlagen");

/*$link = mysql_connect("localhost", "root", "")
    or die("Keine Verbindung m?glich: " . mysql_error());
mysql_select_db("defaultprofile") or die("Auswahl der Datenbank fehlgeschlagen");
*/

$query = "SELECT * FROM tbl_customer where exported = 0 limit 5000";
$result = mysql_query($query);
$i = 0;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$sqlupdate = "UPDATE tbl_customer SET exported = 2 WHERE email = '".$row['email']."'";
	$result2 = mysql_query($sqlupdate);
	echo $sqlupdate;
	$i++;
}
echo $i." Kunden exportiert"; 
/*
$query = "SELECT * FROM tbl_customer where exported = 0 Limit 15000";
$result = mysql_query($query);
$i = 0;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$sqlupdate = "UPDATE tbl_customer SET exported = 2 WHERE email = '".$row['email']."'";
	$result2 = mysql_query($sqlupdate);
	//echo $sqlupdate;
	$i++;
}
echo $i." Kunden exportiert <br /> <br />"; */

$query = "SELECT * FROM tbl_customer where exported = 2";
$result = mysql_query($query);

mysql_close($link);


$link = mysql_connect("localhost", "root", "")
    or die("Keine Verbindung m?glich: " . mysql_error());
mysql_select_db("herzoase") or die("Auswahl der Datenbank fehlgeschlagen");

$i=0;
$i2=1;
//$temp_name = "_preflirt";
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
	flush();
	ob_flush();
	echo "Datensatz: $i <br />";
	
	/*	$query_test = "SELECT * FROM member WHERE username='".$row['nick']."';";
	//echo $query_test."<BR>";
	$result_test = mysql_query($query_test);
	if (mysql_num_rows($result_test) > 0){
			//$query_del = "Delete FROM user WHERE nick='".$row['nick']."'";
			//$result_del = mysql_query($query_del);
			$newname = $row['nick'].rand(1,5);
			$query_update = "UPDATE member set username ='".$newname."' WHERE username='".$row['nick']."';";
			echo $query_update;
	}

	if ($row['pic'] != 'nopic' && $row['pic'] != 'nopic.jpg'){
		$picpath = "";
		if(file_exists('/srv/www/htdocs/defaultprofile/cms_content/images'.$temp_name.'/'.$row['sex'].'/'.$row['pic']) && !file_exists('/srv/www/htdocs/defaultprofile/cms_content/new/images'.$temp_name.'/'.$row['pic'])){
			$picpath = 'images'.$temp_name.'/'.$row['pic'];
			$check = copy('/srv/www/htdocs/defaultprofile/cms_content/images'.$temp_name.'/'.$row['sex'].'/'.$row['pic'],               '/srv/www/htdocs/defaultprofile/cms_content/new/images'.$temp_name.'/'.$row['pic']);

		}elseif(file_exists('/srv/www/htdocs/defaultprofile/cms_content/images'.$temp_name.'/'.$row['sex'].'/'.$row['pic'].'.jpg') && !file_exists('/srv/www/htdocs/defaultprofile/cms_content/new/images'.$temp_name.'/'.$row['pic'])){
			$picpath = 'images'.$temp_name.'/'.$row['pic'].'.jpg';
			$check = copy('/srv/www/htdocs/defaultprofile/cms_content/images'.$temp_name.'/'.$row['sex'].'/'.$row['pic'].'.jpg',               '/srv/www/htdocs/defaultprofile/cms_content/new/images'.$temp_name.'/'.$row['pic'].'.jpg');

		}elseif(file_exists('/srv/www/htdocs/defaultprofile/cms_content/nPics'.$temp_name.'/'.$row['pic']) && !file_exists('/srv/www/htdocs/defaultprofile/cms_content/new/images'.$temp_name.'/'.$row['pic'])){
			$picpath = 'images'.$temp_name.'/'.$row['pic'];
		$check = copy('/srv/www/htdocs/defaultprofile/cms_content/nPics'.$temp_name.'/'.$row['pic'],               '/srv/www/htdocs/defaultprofile/cms_content/new/images'.$temp_name.'/'.$row['pic']);
		}




		 if ($picpath != "" && $check){
			$query2 ="INSERT INTO `user` (`nick`, `age`, `passwd`, `email`, `handynr`, `sex`, `wsex`, `plz`, `pic`, `flirttext`, `type`, `klick`, 	`used`, `created`, `last_login`, `sort`, `freigabe`, `email_exportiert`, `ort`, `land`, `status`, `geb`, `stern`, `augen`, `haare`, `groesse`, `gewicht`, `bh`, `tatoo`, `piercing`, `behaarung`, `raucher`, `hobbies`, `beruf`, `essen`, `anschreiben`, `sonstiges`, `name`, Koma) 
		VALUES ('".$row['nick']."', ".$row['age'].", '".$row['passwd']."', '".$row['email']."', '".$row['handynr']."', '".$row['sex']."', '".$row['wsex']."', '".$row['plz']."', '".$picpath."', '".$row['flirttext']."', '".$row['type']."', ".$row['klick'].", ".$row['used'].", '".$row['created']."', '".$row['last_login']."', ".$row['sort'].", ".$row['freigabe'].", '".$row['email_exportiert']."', '".$row['ort']."', '".$row['land']."', '".$row['status']."', '".$row['geb']."', '".$row['stern']."', '".$row['augen']."', '".$row['haare']."', '".$row['groesse']."', '".$row['gewicht']."', '".$row['bh']."', '".$row['tatoo']."', '".$row['piercing']."', '".$row['behaarung']."', '".$row['raucher']."', '".$row['hobbies']."', '".$row['beruf']."', '".$row['essen']."', '".$row['anschreiben']."', '".$row['sonstiges']."', '".$row['name']."','preflirt')";
			//echo $query2."<br>";
			$result2 = mysql_query($query2);
			$i++;
		}
	}
	
		srand(microtime()*1000000);
  		$zufall = (rand(1,100) * rand(2,10000) + rand(1000,1000000)).(rand(1,100) * rand(2,10000) + rand(1000,1000000));
  		$zufall_mail = $zufall."@test.de";
		if ($row['geb']){
	  		$str1 = substr($row['geb'], 0, 2);
	  		$str2 = substr($row['geb'], 3, 2);
	  		$str3 = substr($row['geb'], 6, 4);
	  		$geb = $str3.'-'.$str2.'-'.$str1;			
		}
		//else $geb = '';
		else {
	  		$str1 = rand(1,28);
	  		$str2 = rand(1,12);
	  		$str3 = 2007 - $row['age'];
	  		$geb = $str3.'-'.$str2.'-'.$str1;			
		}
				
  		$picture = $row['sex'].'/'.$row['pic'].'.jpg';
  		
  		if ($row['augen']){
	  		switch ($row['augen']) {
			case 'blau':
			   $eyes= 2;
			   break;
			case 'braun':
			   $eyes= 1;
			   break;
			case 'grün':
			   $eyes= 3;
			   break;
			default:
			   $eyes= 4;
			   break;			   
			}  			
  		}
  		else $eyes = 0;
  		
   		if ($row['haare']){
	  		switch ($row['haare']) {
			case 'schwarz':
			   $hair= 1;
			   break;
			case 'braun':
			   $hair= 2;
			   break;
			case 'blond':
			   $hair= 3;
			   break;
			case 'rot':
			   $hair= 4;
			   break;
			default:
			   $hair= 5;
			   break;			   
			}  			
  		}
  		else  $hair = 0;
  		
  		if($row['plz'] != '')
  		{
  			$plz = substr($row['plz'],0,2).'XXX';
  		}
  		else 
  		{
  			$plz = '';
  		}

/*		$plz ='';
		
		$country = rand(2,3);
		if ($country == 2){
			$state = 20;
			$city = rand(112,121);	
		}
		else{
			$country = 22;
			$state = 21;
			$city = rand(122,131);							
		}*/
		$sql_double = "SELECT username FROM member WHERE email = '".$row['email']."'";
		$result_double = mysql_query($sql_double); 
		$result_double_row = mysql_fetch_row($result_double);
		if (!$result_double_row[0]){
			//echo "Efolgreich: ".$row['email']."<br>";
			$querryplz = substr($row['plz'],0,2).'xxx';
			if ($querryplz == '10xxx' || $querryplz == '12xxx') $querryplz = '13xxx';
			if ($querryplz == '20xxx') $querryplz = '22xxx';
			if ($querryplz == '81xxx') $querryplz = '80xxx';			
			$cityquerry = "SELECT id FROM xml_cities WHERE plz = '".$querryplz."'";
			$cityresult = mysql_query($cityquerry);
			$cityresultrow = mysql_fetch_row($cityresult);
			//echo "Stadt: ".$cityresultrow[0]."<br>";
			if ($cityresultrow[0] < 1) $cityresult = 0;
			$query2 = "INSERT INTO member (validation_code,username,password,forname,surname,email,city,area,isactive,fake,advertise_regist) 
			Values('za46e3','".$row['email']."','za46e3','".$row['vorname']."','".$row['nachname']."','".$row['email']."','".$cityresultrow[0]."','".$row['plz']."',0,0,1);";
			
			//echo utf8_encode($query2)."<br>";			
		}
		else {
			echo "UPDATE tbl_customer SET exported = 1 WHERE email = '".$row['email']."';"."<br>";
			$i2++;				
		}

		//print_r($row)."<br>" ;
		$result2 = mysql_query(utf8_encode($query2));
		$i++;	
}
mysql_close($link);

echo "<br><br>fertig! $i neue Profile <br>";
?>

