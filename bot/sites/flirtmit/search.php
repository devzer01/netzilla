<?php
$age_start = 18;
$age_end = 50;
$page = 3;
if($page=='')
{
	$url = "http://www.flirtmit.ch/alpha/mitglieder/suche.php";
	$refer_url = "http://www.flirtmit.ch/alpha/start/start_ein.php";
	$post_data = "search[altervon]=$age_start&search[alterbis]=$age_end&search[geschlecht]=1&search[LandID]=-1&search[BLandID]=&Hi=1&suchen=1&regio=suchen+%C2%BB";
}
else
{
	$new_page = ($page-1) * 10;
	$url = "http://www.flirtmit.ch/alpha/mitglieder/suche.php?suchen=true&start=$new_page&search[altervon]=$age_start&search[alterbis]=$age_end&search[geschlecht]=1&search[LandID]=-1&search[BLandID]=&";
	$refer_url = "http://www.flirtmit.ch/alpha/mitglieder/suche.php";
}

		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $refer_url);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		if($page=='')
		{
			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
		}
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\flirtmit\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$search = curl_exec($ch);
 		//print_r($search);
		curl_close($ch);
//exit;
		$arr_data = explode(' ',$search);
		$arr_result = array();
		foreach($arr_data as $result)
		{
			if(strstr($result, 'href="/alpha/mitglieder/profil.php?RID='))
			{
				if(!strstr($result,'span'))
				{
					$replace = str_replace('href="/alpha/mitglieder/profil.php?RID=','',$result);
					$arr_replace = explode('&',$replace);
					//echo $arr_replace[0]."<br>";
					array_push($arr_result, array('id'=>$arr_replace[0]));
				}
				//
				
			}
		}
	
print_r($arr_result);