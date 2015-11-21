<?php
require_once('funcs.php');

$command = funcs::getCommand(96);
/*echo "<pre>";
print_r($command);
echo "</pre>";*/
function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
}
$command = mb_unserialize($command['command']);
$new_profile = array('username' => 'Noi','password' => 'thtl19', 'status' => 'false');
array_push($command['profiles'], $new_profile);
//array_push($command['profiles'][0], 'status' => 'false');
echo "<pre>";
print_r($command['profiles']);
echo "</pre>";

$max_posible_key = count($command['profiles'])-1;

$current_profile = 0;
for($i=1;$i<=8;$i++)
{
	

	echo $i.". ";//." : current profile ".$command['profiles'][$current_profile]['username']." ";

	/*if($i==1)
	{
		unset($command['profiles'][0]);
		$current_profile++;// = next($command['profiles']);
	}*/
		
	
	if($current_profile<=$max_posible_key)
		$current_profile++;
	else
		$current_profile = 0;

	/*if(!(isset($command['profiles'][$current_profile]['username'])))
	{
			echo "----";
			$exist_profile = array_keys($command['profiles']);
			print_r($exist_profile);
			$current_profile = $exist_profile[0];
			echo "-----";
			echo "case :4 -> ";
	}
	elseif(count($command['profiles'])==1)
	{
			echo "case :1 -> ";
	}
	elseif($current_profile<count($command['profiles'])-1)
	{
		$current_profile++;
		echo "case :2 -> ";
	}
	else
	{
		$current_profile=0;
		echo "case :3 -> ";
	}*/
	/*if((count($command['profiles'])-1)<$current_profile)
		$current_profile = 0;*/	
	

	echo  $max_posible_key." [".$current_profile."] ".$command['profiles'][$current_profile]['username'];
	
	
	/*if(!(isset($command['profiles'][$current_profile]['username'])))
	{
		//$current_profile++;
	}
	else
	{*/
		
	//}

	

	//	echo "deduct";

	///echo " next profile ".$current_profile." ";
	/*echo "[".$current_profile."] ";
	if(isset($command['profiles'][$current_profile]['username']))
		echo $command['profiles'][$current_profile]['username'];
	else
		echo "No user";*/
	echo "<br/>";
	

}

?>