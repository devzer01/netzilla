<?php
$postUrl = "http://www.cheekyflirt.com/users.php?gender=M&sexuality=&fromAge=18&toAge=30&country=5&country_area=&online=&images=&videoProfile=&signedUp=&miles=&username=&page=2";
			$referUrl = "http://www.myukdate.com/saved_search.php?profileme=search&type=uk&country=GBR&gender[1]=1&age_start=18&age_end=30&frmlocation=0&frmDays=0&coreg_zone=search_quick";
			$referUrl .= "&coreg_phrases[952]=Pick+out+the+best-looking+guys+who+have+personals+on+Flirt.com!";

			$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL, $postUrl);
		//curl_setopt($ch, CURLOPT_REFERER, $referUrl);
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'c:\wamp\www\postdata\cheekyflirt\cookie.txt');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$search = curl_exec($ch);
// 		echo $case.'<br>';
// 		print_r($search);
		curl_close($ch);
	
		print_r($search);	