<?php


$link = mysql_connect("localhost", "root", "")
    or die("Keine Verbindung m?glich: " . mysql_error());
mysql_select_db("herzoase") or die("Auswahl der Datenbank fehlgeschlagen");


$query = "SELECT * FROM member where ursprung='TD'";
$result = mysql_query($query);

$i=0;
$i2=1;
$temp_name = "_preflirt";
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

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
		
		$query2 = "UPDATE member SET country='".$country."',state='".$state."', city='".$city."', area ='".$plz."' where username ='".$row['username']."'"; 

		echo $query2."<br>";			
		//print_r($row)."<br>" ;
		$result2 = mysql_query($query2);
		$i++;	
}
mysql_close($link);

echo "<br><br>fertig! neue Profile $i<br>";
?>

