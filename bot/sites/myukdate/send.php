<?php
$subject = "hi+there";
$message = "I+have+seen+your+profile+and+I+thought%2C+you+might+be+a+nice+guy!+

Would+love+to+stay+in+contact+with+you%2C+but+this+site+is+not+that+familiar+to+me+and+I+am+constantly+on+the+run+right+now+(holiday+trip+:P+)+

Personally+I+prefer+text+messages%2C+as+I+can+read+them+out+and+about.+I+did+actually+find+another+website+called+Yourbuddy24.com+and+i+can+talk+to+all+of+my+friends+there+when+i+am+away.+Your+profile+looks+okay+and+you+look+nice%2C+maybe+we+should+meet+up+on+there%2C+what+do+you+think?+;)+You+can+find+me+using+the+nickname+search%2C+just+look+for+my+profile+name%2C+i+have+the+same+nick+over+there+:D+

I+would+be+pleased+to+talk+to+you+while+I+am+traveling+around.+I+hope+you+have+a+good+day+and+look+forward+to+hearing+from+you!+X";

$arr_receive = array();

array_push($arr_receive, array('id'=>'1021681711','name'=>'stu27'));
array_push($arr_receive, array('id'=>'1244652436','name'=>'awilliamd'));

foreach ($arr_receive as $result)
{
// 	$receive_id = '1298104093';
// 	$receive_name = 'shybutcute17';
// 	$postUrl = 'http://www.myukdate.com/mailbox/write/-/'.$receive_id.'.html';
// 	$postData = "token=81464ea8&frmTo=$receive_id&to=$receive_name&frmSubject=$subject&frmMessage=$message&sendmsg.x=37&sendmsg.y=14";
	$postUrl = 'http://www.myukdate.com/mailbox/write/-/'.$result['id'].'.html';
	$postData = "token=233680b4&frmTo=$result[id]&to=$result[name]&frmSubject=$subject&frmMessage=$message&sendmsg.x=37&sendmsg.y=14";
	
	$ch = curl_init();
	
	curl_setopt($ch,CURLOPT_URL, $postUrl);
	// curl_setopt($ch, CURLOPT_REFERER, 'http://www.myukdate.com/login.asp');
	curl_setopt($ch, CURLOPT_REFERER, $postUrl);
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
	
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\myukdate\cookie.txt');
	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\myukdate\cookie.txt');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$send = curl_exec($ch);
	
	curl_close($ch);
	
// 	print_r($send);
}

