<?php

   $date = "2008-3-14";

    $sqldate = strftime("%Y-%m-%d", strtotime($date));
    $day = strftime("%d", strtotime($date));
    $weekday = strftime("%u", strtotime($date));
    
    $month = date("m", strtotime($date));
    $year = date("Y", strtotime($date));
    
    $weekdate_to = $year."-".$month."-".($day + 7 - $weekday);
    $weekdate_from = $year."-".$month."-".($day - $weekday + 1);
    
    print "Datum: ".$date."<br>";
    print "Tag: ".$day."<br>";
    print "Wochentag: ".$weekday."<br>";
    print "Woche von: ".$weekdate_from."<br>";
    print "Woche bis: ".$weekdate_to."<br>";
    
    $centerdb = mysql_connect("localhost", "root", "") or die("Keine Verbindung möglich: " . mysql_error());
   
   mysql_select_db("emailchat_center") or die("Auswahl der Datenbank fehlgeschlagen");
   
   $sql = "select * from payfactors where site = 'flirtfeuerwehr' and datum_beginn in (select MAX(datum_beginn) from payfactors where site = 'flirtfeuerwehr' and datum_beginn <= '".$date."')";

   $sitefactors = mysql_query($sql);
   
   $array = mysql_fetch_assoc($sitefactors);
   
   mysql_close($centerdb);

print "Alles geklappt!<br>";
   print_r($array);
    
?>