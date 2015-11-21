<?php
require_once('classes/top.class.php');

$link = mysql_connect("localhost", "root", "")
    or die("Keine Verbindung m?glich: " . mysql_error());
mysql_select_db("herzoase") or die("Auswahl der Datenbank fehlgeschlagen");


$query = "select count(*) as count from member where fake = 0 and signup_datetime > '2007-10-03'";
$query1 = "select count(*) as count from member where fake = 0 and signup_datetime > '2007-10-03' and isactive = 1";
$query2 = "select count(*) as count from member where fake = 0 and type != 4 and type != 1 and signup_datetime > '2007-10-03'";
$query3 = "select count(*) as count from payment_log where payment_complete = 1 and payday > '2007-10-03' and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query4 = "select count(*) as count from payment_log where payment_complete = 1 and payday > '2007-10-03' and recall = 1 and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query5 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-10-03' and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query6 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-10-03' and recall = 1 and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query7 = "select count(*) as count from payment_log where prolonging = 1 and payday > '2007-10-03' and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query8 = "select count(*) as count from payment_log where cancelled = 1 and payday > '2007-10-03' and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query9 = "select count(*) as count from payment_log where payment_complete = 1 and prolonging = 0 and recall = 0 and new_paid_until > now() and new_paid_until < '2007-12-01' and cancelled = 0 and payday > '2007-10-03' and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";

$result = mysql_query($query);
$result1 = mysql_query($query1);
$result2 = mysql_query($query2);
$result3 = mysql_query($query3);
$result4 = mysql_query($query4);
$result5 = mysql_query($query5);
$result6 = mysql_query($query6);
$result7 = mysql_query($query7);
$result8 = mysql_query($query8);
$result9 = mysql_query($query9);

echo "<b>Herzoase Statistik f&uuml;r Neukunden seit dem 4. Oktober 2007: </b></br></br>";

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
	$gesamtzahl = $row['count'];
	echo($row['count']." Anmeldungen </br>");
}

while ($row = mysql_fetch_array($result1, MYSQL_ASSOC)){
	$prozentual = ($row['count']/($gesamtzahl/100));
	echo $row['count']." heute aktiviert (".round($prozentual,2)."%) </br>";
}

while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)){
	$prozentual = ($row['count']/($gesamtzahl/100));
	echo "Davon ".$row['count']." derzeit bezahlende Nutzer (".round($prozentual,2)."%)</br></br>";
}
	
while ($row = mysql_fetch_array($result3, MYSQL_ASSOC))
	echo $row['count']." Zahlungen insgesamt * / ** </br>";

while ($row = mysql_fetch_array($result4, MYSQL_ASSOC))
	echo $row['count']." Zahlungs Stornos * / ** </br>";

while ($row = mysql_fetch_array($result7, MYSQL_ASSOC))
	echo $row['count']." autom. Mitgliedschafts Verl&auml;ngerungen * / ** </br>";	
	
while ($row = mysql_fetch_array($result8, MYSQL_ASSOC))
	echo $row['count']." gek&uuml;ndigte (Bezahl)Mitgliedschaften </br></br>";

while ($row = mysql_fetch_array($result9, MYSQL_ASSOC))
	echo $row['count']." (bisher) nicht gek&uuml;ndigte Mitgliedschaften die im November auslaufen</br></br>";		
	
while ($row = mysql_fetch_array($result5, MYSQL_ASSOC)){
	echo $row['count']." Euro gesamt * / ** </br>";
	$gesamt = $row['count'];
}

while ($row = mysql_fetch_array($result6, MYSQL_ASSOC)){
	$prozentual = ($row['count']/($gesamt/100));
	echo $row['count']." Euro storniert (".round($prozentual,2)."%) * / ** </br></br>";
	$storno = $row['count'];
}

$verbleibend = $gesamt - $storno;
echo $verbleibend." Euro verbleibend * / ** </br></br>";


echo "<b>Oktober 2007 allein: </b></br></br>";

$query10 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-10-03' and payday < '2007-11-01' and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query11 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-10-03' and payday < '2007-11-01' and recall = 1 and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";

$result10 = mysql_query($query10);
$result11 = mysql_query($query11);

while ($row = mysql_fetch_array($result10, MYSQL_ASSOC)){
	echo $row['count']." Euro gesamt * / ** </br>";
	$gesamt = $row['count'];
}

while ($row = mysql_fetch_array($result11, MYSQL_ASSOC)){
	$prozentual = ($row['count']/($gesamt/100));
	echo $row['count']." Euro storniert (".round($prozentual,2)."%) * / ** </br></br>";
	$storno = $row['count'];
}

$verbleibend = $gesamt - $storno;
echo $verbleibend." Euro verbleibend * / ** </br></br>";


echo "<b>November 2007 allein: </b></br></br>";

$query10 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-10-31' and payday < '2007-12-01' and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query11 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-10-31' and payday < '2007-12-01' and recall = 1 and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";

$result10 = mysql_query($query10);
$result11 = mysql_query($query11);

while ($row = mysql_fetch_array($result10, MYSQL_ASSOC)){
	echo $row['count']." Euro gesamt * / ** </br>";
	$gesamt = $row['count'];
}

while ($row = mysql_fetch_array($result11, MYSQL_ASSOC)){
	$prozentual = ($row['count']/($gesamt/100));
	echo $row['count']." Euro storniert (".round($prozentual,2)."%) * / ** </br></br>";
	$storno = $row['count'];
}

$verbleibend = $gesamt - $storno;
echo $verbleibend." Euro verbleibend * / ** </br></br>";


echo "<b>Dezember 2007 allein: </b></br></br>";

$query10 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-11-31' and payday < '2008-01-01' and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";
$query11 = "select sum(sum_paid) as count from payment_log where payment_complete = 1 and payday > '2007-11-31' and payday < '2008-01-01' and recall = 1 and prolonging = 0 and username in (select username from member where fake = 0 and signup_datetime > '2007-10-03')";

$result10 = mysql_query($query10);
$result11 = mysql_query($query11);

while ($row = mysql_fetch_array($result10, MYSQL_ASSOC)){
	echo $row['count']." Euro gesamt * / ** </br>";
	$gesamt = $row['count'];
}

while ($row = mysql_fetch_array($result11, MYSQL_ASSOC)){
	$prozentual = ($row['count']/($gesamt/100));
	echo $row['count']." Euro storniert (".round($prozentual,2)."%) * / ** </br></br>";
	$storno = $row['count'];
}

$verbleibend = $gesamt - $storno;
echo $verbleibend." Euro verbleibend * / ** </br></br>";



echo "* (ohne Verl&auml;ngerungen v. Altkunden)</br>";
echo "** ohne Zahlungen von Kunden, die sich vor dem 4. Oktober angemeldet, aber danach eine Bezahlmitgliedschaft gew&auml;hlt haben)</br></br></br>";


?>