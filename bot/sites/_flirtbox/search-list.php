<?php 
require_once 'func.php';


$url = "http://www.flirtbox.co.uk/quickSearch.php?resultpage=1&gender=2&targettedGender=1&age_group=0&country=70&location=&username=";
$content =  funcs::get_url_contents($url);

//write content to file
$strFileName = "name.txt";
$objFopen = fopen($strFileName, 'w');
fwrite($objFopen, $content);
fclose($objFopen);

//get content from file
$str = file_get_contents('name.txt');

preg_match_all('/.*<div class="username"><a href="\/(.*)">.*<\/a><\/div>.*/',$str, $matches);


/* foreach ($matches[1] as $val)
{
	echo "value : $val <br>";
} */




?>
