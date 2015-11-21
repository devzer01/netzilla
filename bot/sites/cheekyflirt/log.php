<?php
$strFileName = "textfiles/monalisa69age40to60-page-78.txt";

$data = file_get_contents($strFileName);
//echo $data;
$arr_data = explode(' ',$data);

foreach($arr_data as $result)
{
	if(strstr($result,'class="username">'))
	{
		$new_result = str_replace('class="username">', "", $result);
		echo $new_result."<br>";
	}
}


?>