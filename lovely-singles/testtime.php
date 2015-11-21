<?php
/*$month=date(”m”); 
$day=date(”d”); 
$year=date(”Y”); 
$mk_data = mktime(0, 0, 0, $month, $day, $year); 
echo date(”Y-d-M”, $mk_data);*/
 $mdate = date('Y-m-d', strtotime('+ 1 Year'));
 echo  $mdate ;
?>
