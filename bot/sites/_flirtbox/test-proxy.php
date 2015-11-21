<?php
require_once 'funcs.php';

if($_POST['ip'])
{
	$data = funcs::checkproxy($_POST['ip']);
	
	if($data!='' and $data != 'Thailand')
	{
		echo '<font color="green">Working</fon>';
	}
	else
	{
		echo '<font color="red">Not Working</fon>';
	}
}else {
	echo '<font color="red">Not Working</fon>';
}