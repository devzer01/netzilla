<?php
$postUrl = "http://www.flirthut.com/index.php?handler=search&action=perform&search_type=advanced&keyword=&age_from=18&age_to=30&gender=1&smoking=&drinking=&looking_for=&hair_color=&height=&d_status=&sexuality=0&distance=0&country=225&state=0&city=0&postal_code=&sort=1&search_advanced_0=Search";
//$postUrl = "http://www.flirthut.com/index.php?handler=search&action=perform&search_type=advanced&src_id=&keyword=&age_from=18&age_to=30&gender=1&smoking=&drinking=&looking_for=&hair_color=&height=&d_status=&sexuality=0&distance=0&country=225&state=0&city=0&postal_code=&sort=1&online=0&with_photo=0&err_page=search&err_section=advanced&p=2";


		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		//curl_setopt($ch, CURLOPT_REFERER, $referUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\flirthut\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\flirthut\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$search = curl_exec($ch);
 		//print_r($search);
		curl_close($ch);
	
		$strFileName = "textfiles/1.txt";
		$objFopen = fopen($strFileName, 'w');
		fwrite($objFopen, $search);
		fclose($objFopen);



$data = file_get_contents($strFileName);
$arr_data = explode(' ', $data);
$arr_result = array();
foreach($arr_data as $result)
{
	if(strstr($result, 'href="index.php?handler=member_action&action=bookmark&per_id='))
	{
		$replace1 = str_replace('href="index.php?handler=member_action&action=bookmark&per_id=', '', $result);
		$replace2 = str_replace('">Bookmark</a></li>
				<li><a', '', $replace1);

		array_push($arr_result, array("id"=> $replace2));
	}
}
foreach ($arr_result as $result)
{
	echo $result['id']."<br>";
}