<?php
require_once('classes/top.class.php');
$profile_per_hour = 4;

class autoregister
{
	static function getNextMember()
	{
		$sql = "SELECT * FROM member_test_profiles WHERE moved = 'false' ORDER BY id ASC LIMIT 0,1";
		return DBConnect::assoc_query_1D($sql);
	}
}
$member=autoregister::getNextMember();
/*while($member=autoregister::getNextMember())
{*/
	echo "<pre>";
	print_r($member);
	echo "</pre>";
//}
?>