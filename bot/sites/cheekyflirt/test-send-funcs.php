<?php
require_once 'funcs.php';

$subject = "hi+there";
$message = "I+have+seen+your+profile+and+I+thought%2C+you+might+be+a+nice+guy!+

Would+love+to+stay+in+contact+with+you%2C+but+this+site+is+not+that+familiar+to+me+and+I+am+constantly+on+the+run+right+now+(holiday+trip+:P+)+

Personally+I+prefer+text+messages%2C+as+I+can+read+them+out+and+about.+I+did+actually+find+another+website+called+Yourbuddy24.com+and+i+can+talk+to+all+of+my+friends+there+when+i+am+away.+Your+profile+looks+okay+and+you+look+nice%2C+maybe+we+should+meet+up+on+there%2C+what+do+you+think?+;)+You+can+find+me+using+the+nickname+search%2C+just+look+for+my+profile+name%2C+i+have+the+same+nick+over+there+:D+

I+would+be+pleased+to+talk+to+you+while+I+am+traveling+around.+I+hope+you+have+a+good+day+and+look+forward+to+hearing+from+you!+X";

$file_name = "textfiles/21cindyage18to30-page-9.txt";

$data = file_get_contents($file_name);

$arrRecipient = funcs::file_get_data($data);
// $i=1;
foreach($arrRecipient as $val)
{
	$checkname = funcs::dbCheckSentUser($val['name']);
	echo 'check name'.$checkname['name'];
// 	if($checkname['name'] != $val['name'])
// 	{
			
// 		if($i==1)
// 		{
// 			funcs::insertLogPage($username, $end_page);
// 		}
			
// 		funcs::send_message($val['token'], $val['id'], $val['name'], $subject, $message);
// 		funcs::insertLog($username, $val['name']);

		// 					echo "message sent to " . $val['name'] . "<br>";
// 		sleep($timer);
// 	}
// 	$i++;

	
	
	echo "<pre>";
	echo trim($val['token'])."<br>";;
	echo $val['id']."<br>";
	echo $val['name']."<br>";
	echo "</pre>";
}
