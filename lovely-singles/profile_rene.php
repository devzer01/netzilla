<?php
// SERVER

$server[1]['IP'] = "87.106.2.201";
$server[1]['User'] = "manu";
$server[1]['PW'] = "orkan2007";

$server[2]['IP'] = "87.106.20.91";
$server[2]['User'] = "progger";
$server[2]['PW'] = "adgjl";

$server[3]['IP'] = "80.237.202.104";
$server[3]['User'] = "progger";
$server[3]['PW'] = "adgjl";

$server[4]['IP'] = "localhost";
$server[4]['User'] = "root";
$server[4]['PW'] = "";

$server[5]['IP'] = "80.237.202.233";
$server[5]['User'] = "haust";
$server[5]['PW'] = "dungeon33";

$server[6]['IP'] = "87.106.84.176";
$server[6]['User'] = "User4All";
$server[6]['PW'] = "\$brain\$";

$server[7]['IP'] = "80.237.206.27";
$server[7]['User'] = "supervisor";
$server[7]['PW'] = "trsd6\$ls_f";

$server[8]['IP'] = "80.237.202.37";
$server[8]['User'] = "supervisor";
$server[8]['PW'] = "trsd6\$ls_f";

$server[9]['IP'] = "87.106.9.172";
$server[9]['User'] = "supervisor";
$server[9]['PW'] = "trsd6\$ls_f";

$server[10]['IP'] = "80.237.158.75";
$server[10]['User'] = "SQL4all";
$server[10]['PW'] = "jgdze%%wea058!!LSnHS";

// KOMA
$km_count =0;
$koma[$km_count++] = array('db' => 'angeloflove' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'funundflirt' ,'server'=> $server[5]);
$koma[$km_count++] = array('db' => 'kuesschen' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'herzblatt' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'datefinder' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'loveunion' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'einsameengel' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'sonachat' ,'server'=> $server[8]);
$koma[$km_count++] = array('db' => 'datingherz' ,'server'=> $server[9]);
$koma[$km_count++] = array('db' => 'flirt-jet' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'singleflirtchat' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'engelflirt' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'schnellundungezwungen' ,'server'=> $server[1]);
$koma[$km_count++] = array('db' => 'sehnsuechte' ,'server'=> $server[1]);
$koma[$km_count++] = array('db' => 'easychat24' ,'server'=> $server[9]);
$koma[$km_count++] = array('db' => 'einsamebengel' ,'server'=> $server[2]);
$koma[$km_count++] = array('db' => 'zartejungs' ,'server'=> $server[1]);
$koma[$km_count++] = array('db' => 'heterosucks' ,'server'=> $server[5]);
$koma[$km_count++] = array('db' => 'queerfolk' ,'server'=> $server[5]);
$koma[$km_count++] = array('db' => 'amorsdate' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'lustforchat' ,'server'=> $server[9]);
$koma[$km_count++] = array('db' => 'feenhimmel' ,'server'=> $server[4]);
$koma[$km_count++] = array('db' => 'gochatten' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'liebesfeuerwerk' ,'server'=> $server[10]);
$koma[$km_count++] = array('db' => 'datingsternchen' ,'server'=> $server[6]);
$koma[$km_count++] = array('db' => 'liebeimspiel' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'liebeundemotionen' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'brodelndebegierde' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'chatinsherz' ,'server'=> $server[3]);
$koma[$km_count++] = array('db' => 'flirtinsherz' ,'server'=> $server[6]);








$all = 0;
for($j=0; $j<= $km_count; $j++){
	echo "<h1>".$koma[$j]['db']."</h1>";
	$link = @mysql_connect($koma[$j]['server']['IP'], $koma[$j]['server']['User'], $koma[$j]['server']['PW']);
	@mysql_select_db($koma[$j]['db']);
	
	
	$query = "SELECT * FROM user WHERE handynr = ''";
	$result = mysql_query($query);
	mysql_close($link);
	
	
	$link = mysql_connect("localhost", "root", "")
		or die("Keine Verbindung m?glich: " . mysql_error());
	mysql_select_db("herzoase") or die("Auswahl der Datenbank fehlgeschlagen");
	
	$i=0;
	$temp_name = "_preflirt";
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
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
		}*/
		
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
			
	/*  		if($row['plz'] != '')
			{
				$plz = substr($row['plz'],0,2).'XXX';
			}
			else 
			{
				$plz = '';
			}*/
	
			$plz ='';
			
			$country = rand(2,3);
			if ($country == 2){
				$state = 20;
				$city = rand(112,121);	
			}
			else{
				$country = 22;
				$state = 21;
				$city = rand(122,131);							
			}
	
			//$query2 = "INSERT INTO member (username,password,email,gender,birthday,country,state,city,fake,area,height,weight,eyescolor,haircolor,type,lookmen,lookwomen,description,picturepath,mobileno,ursprung) 
			//Values('".utf8_encode($row['nick'])."','test721225user','".$zufall_mail."',".(($row['sex']=='m')?1:2).",'".$geb."','".$country."','".$state."','".$city."',1,'".$plz."','".$row['groesse']."','".$row['gewicht']."',".$eyes.",".$hair.",".rand(2, 4).",".(($row['searchSex']=='Mann'||$row['searchSex']=='Egal')?1:0).",".(($row['searchSex']=='Frau'||$row['searchSex']=='Egal')?1:0).",'".utf8_encode($row['flirttext'])."','".$picture."','".$zufall."','".$row['KoMa']."');";
			
			//echo $query2."<br>";
			//print_r($row)."<br>" ;
			//$result2 = mysql_query($query2);
			$i++;
		
	}
	mysql_close($link);
	
	echo "<br><br>fertig! neue Profile $i<br>";
}
?>
