<?php
require_once('classes/top.class.php');

	//test randomStartProfile()
	//$to_id = funcs::randomStartProfile(1981);
	//print_r($to_id); 

	/*test simpleSearch()
	$arr = array("fake" => "0", "start" => "0",  "offset" => "10", "gender" => "1");
	$result = Search::simpleSearch($arr);
	print_r($result);*/

	/*
$link2 = mysql_connect("87.106.14.201", "MasterChat", "OPksh39374hnIujsklwi23nahd")
    or die("Keine Verbindung m�glich: " . mysql_error());
mysql_select_db("email_app") or die("Auswahl der Datenbank fehlgeschlagen");

$query2 = "select nickname from senderProfiles";
$result2 = mysql_query($query2);
mysql_close($link2);


$link = mysql_connect("localhost", "root", "")
    or die("Keine Verbindung m?glich: " . mysql_error());
mysql_select_db("partnerboerse24") or die("Auswahl der Datenbank fehlgeschlagen");

$query = "select id, username, mobileno from member where signup_datetime > '2007-10-25' and isactive = 1 and fake = 0";
$result = mysql_query($query);
//mysql_close($link);

$ii = 0;
$i = 0;

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {	
		
	while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {

		if (utf8_decode($row2['nickname']) == $row['username'])
		{
			$vorhanden = 1;
			break;
		}						
	}
	mysql_data_seek($result2, 0);
	
	if($vorhanden == 1){
		$i++;
	}
	else {
		$userid = $row['id'];
		if ($userid != '1005239'){
			$to_id = funcs::randomStartProfile($userid);
			echo "$userid"." : ".$row['username']." : "."$row[mobileno]</br>"; 
			//funcs::sendMessage($row['id'],$to_id,'Erstanmeldung','Erstanmeldung',3);		
		}
		$ii++;
	}
	$vorhanden = 0;
}

echo "Vorhanden: ".$i."</br>";
echo "Fehlen: ".$ii;

*/
	
/*	
	//Erstanmeldungs nachrichten generieren
	$to_id = funcs::randomStartProfile($row['id']);
	funcs::sendMessage($row['id'],$to_id,'Erstanmeldung','Erstanmeldung',3); 
	$i++;
	echo $i.' : '.$row['id'].' '.$row['username'].'</br>';

	$to_id = funcs::randomStartProfile(1000686);
	echo "funcs::sendMessage(1004577,$to_id,'Erstanmeldung','Erstanmeldung',3)"; 
	funcs::sendMessage(1000686,$to_id,'Erstanmeldung','Erstanmeldung',3); 
*/

	//TECHNIKMAIL nachrichten generieren
/*	$userid = 2;
	funcs::sendMessage($userid,'herbie59','Online Zahlung - Paypal','Hallo herbie59! <br><br> Leider gibts es noch ein kleines Kommunikationsproblem mit Paypal, aber wir arbeiten dran! <br><br> Deine Mitgliedschaft wurde nun hochgestuft und als kleiner Ausgleich auf den 11.08. verlängert. Vielen Danke für deine Geduld <br> <br> Deine Marianne von der Technik',2); 	
	echo 'herbie59';
	funcs::sendMessage($userid,'geilerswen','Online Zahlung - ELV','Hallo geilerswen! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'geilerswen';
	funcs::sendMessage($userid,'Johannsen008','Online Zahlung - ELV','Hallo Johannsen008! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'Johannsen008';	
	funcs::sendMessage($userid,'ncy1983','Online Zahlung - ELV','Hallo ncy1983! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'ncy1983';	
	funcs::sendMessage($userid,'jinopino','Online Zahlung - ELV','Hallo jinopino! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'jinopino';		
	funcs::sendMessage($userid,'Sonnenboy25','Online Zahlung - ELV','Hallo Sonnenboy25! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'Sonnenboy25';	
	
	funcs::sendMessage($userid,'cellidaseggi','Online Zahlung - ELV','Hallo cellidaseggi! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'cellidaseggi';
	funcs::sendMessage($userid,'zummsel008','Online Zahlung - ELV','Hallo zummsel008! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'zummsel008';	
	funcs::sendMessage($userid,'baer313','Online Zahlung - ELV','Hallo baer313! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'baer313';	
	funcs::sendMessage($userid,'thor27','Online Zahlung - ELV','Hallo thor27! <br><br> Leider hatten wir ein kleines Problem mit dem Elektronischen Lastschriftverfahren. Dieses ist nun gelöst und du kannst es einfach nochmal probieren. <br><br> Ich würde mich freuen, dich nun demnächst als Mitglied bei Partnerboerse24 begrüssen zu dürfen. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'thor27';		
	funcs::sendMessage(2,'BlackDeathDragon','Gold Abo','Guten Morgen, liebes Mitglied. <br><br> Vielen Dank f�r den Hinweis. Tatsächlich hatten wir wohl ein kleines Problem, welches nun aber behoben ist. <br> <br> Deine Marianne von der Technik',2); 	
	echo 'BlackDeathDragon';	*/	
	
	//$arr = funcs::checkFor1DayGod(45);
	
/*	if (funcs::sendMessage(45, 'Kontaktanzeige', 'TEST_ANZEIGENHEADER', 'TEST_ANZEIGENTEXT', 5))
	{
		echo "TEST ERFOLGREICH";
	}
	else echo "SCHEISS"; */
	//print_r($arr);
	
	/*$result = Search::getPaymentStatistic('2007-07-01 00:00:00', '2007-08-09 00:00:00');
	print_r($result);*/
	
	/*$sql = "select id from member where isactive=1 and signup_datetime>'2007-08-23' and signup_datetime < '2007-08-24 12:40:00' and fake = 0";
	$res=mysql_query($sql) or print "fuck";
	
	while($row=mysql_fetch_row($res)){
	
	
	if (funcs::sendMessage($row[0], funcs::randomStartProfile($row[0]), 'Erstanmeldung Nachfüllung', 'Erstanmeldung Nachfüllung', 3))
	{
		echo "TEST ERFOLGREICH $row[0] <br>";
	}
	else echo "SCHEISS $row[0] <br>"; 
	}
*/

	
	//	-----------------------------------------------------------------------------------------------------------------------------------------
	
	
/*
	//	*** Willkommens Mail ***
		$sql = "UPDATE member SET isactive=0 AND validation_code='".funcs::randomPassword(6)."' WHERE id=1011595";
	DBConnect::execute($sql);
	//		   	           getMessageEmail_membership(&$smarty, $username)	   
	$mail_message = funcs::getMessageEmail_membership($smarty,'ph0enix');
	$mail_from = 'anmeldung@herzoase.com';
	$mail_subject = 'test.. anmeldung';
		if (funcs::sendMail('leap84@freenet.de', $mail_subject, $mail_message, $mail_from))
	{
		print "neuanmeldung Versandt.. ";
	

	
	//	*** Admin Mail / Mail an Petra ***
	$test[1] = 'ph0enix';
	//		   send_memberExtend_admin(&$smarty,$email,$subject,$array)
	if (funcs::send_memberExtend_admin(&$smarty,'dk@mintnet.de','test..',$test))
	{
		print "memberExtend_admin Versandt.. ";
	}

	

	// *** Mitgliedschaftsverlängerung (Überweisung) ***
	//		   send_memberExtend_customer(&$smarty,$username,$email,$type,$extend,$pay)
	if (funcs::send_memberExtend_customer(&$smarty,'ph0enix','dk@mintnet.de',3,'01-03-2009','30,00'))
	{
		print "memberExtend_customer Versandt.. ";
	}

	

	//	*** Kontakt - Nachrichten Mail ***
	//		   emailAfterEmail($userid,$from,$subj,$str)
	if (funcs::emailAfterEmail('1011595','1011595','test..','nachricht..'))
	{
		print "emailAfterEmail Versandt.. ";
	}	
	
	

	// *** Mitgliedschaftsverlängerung (ELV) ***
	//		   send_memberExtend_customer_paid_via_4(&$smarty,$username,$email,$type,$extend,$pay)
	if (funcs::send_memberExtend_customer_paid_via_4(&$smarty,'ph0enix','dk@mintnet.de',3,'01-03-2009','30,00'))
	{
		print "memberExtend_customer_paid_via_4 Versandt.. ";
	}

	

	// *** Mitgliedschaftsverlängerung (3 TagesAbbo .. per Überweisung) ***
	//		   send_memberExtend_customer_3_days(&$smarty,$username,$email,$type,$extend,$pay)
	if (funcs::send_memberExtend_customer_3_days(&$smarty,'ph0enix','dk@mintnet.de',3,'01-03-2009','30,00'))
	{
		print "memberExtend_customer_3_days Versandt.. ";
	}
*/
	
	
	// *** Mahnungs-Email an den Admin ***
	$test[1] = 'ph0enix';
	//		   send_memberCancel_admin(&$smarty,$email,$subject,$array)
	if (funcs::send_memberCancel_admin(&$smarty,'dk@mintnet.de','test.. memberCancel_admin',$test))
	{
		print "memberCancel_admin Versandt.. ";
	}

	
	
	// *** Mahnungs-Email an den User ***
	//		   sendMemberCancelEmail($id, $log_id, &$smarty)
	if (funcs::sendMemberCancelEmail(1011595, 5087, &$smarty))
	{
		print "MemberCancelEmail Versandt.. ";
	}

	
	
	// *** Mahnungs-Email an den User ***
	//		   sendMemberCancelEmail30Days($id, $log_id, &$smarty)
	//		   ..$id.. & ..$log_id..  sind NICHT EGAL beim testen --> siehe Hauke ;-)
	if (funcs::sendMemberCancelEmail30Days(1011595, 5087, &$smarty))
	{
		print "MemberCancelEmail30Days Versandt.. ";
	}

	

	
	//$result = funcs::getNewest(0,1,'');
	//$result = funcs::getOfDayNew(2,1);
	//print_r($result);
	
	//$result = Search::getELVStatistic('2007-07-01 00:00:00', '2007-10-09 00:00:00');
	//print_r($result);	
	
?>