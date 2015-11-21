<?php
$page = 2;
// 	$postUrl = "http://www.myukdate.com/profile.get.php?profileme=search&type=uk&country=GBR&gender[2]=2&age_start=18&age_end=30&frmlocation=0&frmDays=0&coreg_zone=search_quick";
// 	$postUrl .= "&coreg_phrases[1171]=Search+for+cute+local+ladies+at+BeNaughtyInLondon.com!";

if ($page==1)
{
	
	$postUrl = "http://www.myukdate.com/profile.get.php?pg=1";
	$referUrl = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=18&age_end=30&frmlocation=0&frmDays=0&coreg_zone=search_quick";
	$referUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
}
elseif($page>=2)
{
	$ref_page = $page-1;
	$postUrl = "http://www.myukdate.com/profile.get.php?pg=$page";
	$referUrl = "http://www.myukdate.com/profile.get.php?pg=$ref_page";
	
	$postUrl1 = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=18&age_end=30&frmlocation=0&frmDays=0&coreg_zone=search_quick";
	$postUrl1 .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
	$referUrl1 = "http://www.myukdate.com/searchf.asp";
	$ch1 = curl_init();
	curl_setopt($ch1,CURLOPT_URL, $postUrl1);
	curl_setopt($ch1, CURLOPT_REFERER, $referUrl1);
	curl_setopt($ch1,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_HEADER, 1);
	curl_setopt($ch1, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\myukdate\cookie.txt');
	curl_setopt ($ch1, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\myukdate\cookie.txt');
	curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, TRUE);
	$search = curl_exec($ch1);
	curl_close($ch1);
}
else 
{
	$postUrl = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=18&age_end=30&frmlocation=0&frmDays=0&coreg_zone=search_quick";
	$postUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";
	$referUrl = "http://www.myukdate.com/searchf.asp";	
}

		
$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, $postUrl);
curl_setopt($ch, CURLOPT_REFERER, $referUrl);
curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

// curl_setopt($ch,CURLOPT_POST, 1);
// curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);

curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\myukdate\cookie.txt');
curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\myukdate\cookie.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
$search = curl_exec($ch);

curl_close($ch);
print_r($search);exit;
$filename = "textfiles/test.txt";

$objFopen = fopen($filename, 'w');
fwrite($objFopen, $search);
fclose($objFopen);

// 			get content from file
$data = file_get_contents($filename);


$arrStr = explode(' ', $data);




$newArr = array();

foreach ($arrStr as $key=>$val)
{
	if (!isset($token)){
		if (strstr($val, '&token=')) {
	
			$new_val = str_replace('"', '', $val);
			$find_token = explode('&token=', $new_val);
			if (isset($find_token[1])) {
				$token = $find_token[1];
			}
		
		}
	}
	
	
	if(strstr($val, 'href="/review/profile/'))
	{
		
		if(strstr($val, '<img'))
		{
			$val = '';
		}
		
		
		$replace1 = str_replace('href="/review/profile/', '', $val);
		$replace2 = explode('.html">', $replace1);
		
		array_push($newArr, array('recipient'=>$replace2));
		
	}
	
}

$arrResutl = array();
foreach ($newArr as $val)
{
	foreach ($val as $val2) {
		if(isset($val2[1])) {
			
			$id = $val2[0];
			$name = $val2[1];
			
			array_push($arrResutl, array('id'=>$id, 'name'=>$name, 'token'=>$token));
		}
	}
}

		

echo "<pre>";
print_r($arrResutl);
echo "</pre>";