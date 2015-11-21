<?php
include'classes/top.class.php';
$today=getdate();
$days=date("Y-m-d H:i:s", (time() - (3 * 24 * 60 * 60)));
$sql="select * from member where (signin_datetime < '".$days."') and (sendmail_datetime < '".$days."')";
//$sql ="select * from member where email like 'zerocoolz%'";
$arr_record=DBconnect::assoc_query_2D($sql);
foreach ($arr_record as $key => $value)
{
	$recipients = $value['email'];
	# -=-=-=- MIME BOUNDARY
	$mime_boundary = "----herzoase.com----".md5(time());
	# -=-=-=- MAIL HEADERS

	$to = $recipients;
	$subject = "We're miss you on herzoase.com";

	$header="From: no-reply@herzoase.com\n";
	$header.= "Reply-To: no-reply@herzoase.com\n";
	$header.= "MIME-Version: 1.0\n";
	$header.= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
	$header.= "--$mime_boundary\n";
	$header.= "Content-Type: text/html; charset=UTF-8\n";
	$header.= "Content-Transfer-Encoding: 8bit\n\n";
	$sql="select * from member where ((".TABLE_MEMBER_STATUS." != 1 and isactive='1' )and(";
	$i=0;
	if ($value['lookpairs']==1){
		$sql.="(lookpairs = '1')";
		$i=$i+1;
	}
	if($value['lookmen']==1){
		if($i > 0)
		$sql.="or";
		$sql.=" (gender='1' and ";
		if($value['gender']==1)
			$sql.="lookmen='1')";
		if($value['gender']==2)
			$sql.="lookwomen='1')";	
		$i=$i+1;
	}
	if($value['lookwomen']==1){
		if($i > 0)
		$sql.="or";
		$sql.=" (gender='2' and ";
		if($value['gender']==1)
			$sql.="lookmen='1')";
		if($value['gender']==2)
			$sql.="lookwomen='1')";	
		$i=$i+1;
	}
	if(($value['gender']==1) && ($i == 0)){
		$sql.=" (gender='2' and ";
		if($value['gender']==1)
			$sql.="lookmen='1')";
		if($value['gender']==2)
			$sql.="lookwomen='1')";	
		$i=$i+1;
	}
	elseif(($value['gender']==2) && ($i == 0)){
		$sql.=" (gender='1' and ";
		if($value['gender']==1)
			$sql.="lookmen='1')";
		if($value['gender']==2)
			$sql.="lookwomen='1')";	
		$i=$i+1;
	}
	if($i==0)
	$sql.="1";
	$sql.=") and (picturepath <> '')) order by rand() limit 4";
	$arr_record2=DBconnect::profile($sql);
	foreach ($arr_record2 as $key2 => &$value2)
	{
		$value2[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $value2[TABLE_MEMBER_CITY]);
		$value2[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $value2[TABLE_MEMBER_CIVIL]); 
		$value2[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $value2[TABLE_MEMBER_APPEARANCE]);
		$temp = split('-',$value2['birthday']);
		$value2['age'] = date(Y)-$temp[0];
	}
	$smarty->assign("members", $arr_record2);

	//$sql = "update member set sendmail_datetime = '".date('Y-m-d H:i:s')."' where id ='".$value['id']."'";
	//mysql_query($sql);

	$message = $smarty->fetch("remind_message.tpl");
	if(funcs::sendMail($to,$subject,$message,'no-reply@herzoase.com'))
	{
		echo "Completed send to => ".$value['email']."<br>";
	}
	else
	{
		echo "Error send to => ".$value['email']."<br>";
	}
	ob_flush() ;
	flush();
}

echo "<br>Total: ".count($arr_record)." members.";
?>