<?php
require_once 'funcs.php';

$data = file_get_contents('textfiles/rosesy1990-page-126.txt');
			
$arrIdName = funcs::getIdUsername($data);

foreach ($arrIdName as $val)
{
	//funcs::insertLog($username, $val[1]);
	if(funcs::insertLog('', $val[1]))
	{
		echo $val[1].'<br>'; 
	}
}
?>