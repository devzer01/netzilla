<?php

$result = array();
switch($_GET['new'])
{
	case "f":
	default:
		$temp = DBConnect::assoc_query_2D("SELECT id, RIGHT(picturepath,1) as pic FROM ".TABLE_MEMBER." WHERE gender = 2 AND isactive = 1 ORDER BY signup_datetime DESC LIMIT 10");

		if(is_array($temp) && (count($temp) > 0))
		{
			foreach($temp as $member)
			{
				array_push($result, funcs::getAdvanceProfile($member['id'],1));
			}
		}
		$smarty->assign("frauenResult", $result);
		$smarty->assign("countNewProfile", count($result));
		break;
	case "m":
		$temp = DBConnect::assoc_query_2D("SELECT id, RIGHT(picturepath,1) as pic FROM ".TABLE_MEMBER." WHERE gender = 1 AND isactive = 1 ORDER BY signup_datetime DESC LIMIT 10");

		if(is_array($temp) && (count($temp) > 0))
		{
			foreach($temp as $member)
			{
				array_push($result, funcs::getAdvanceProfile($member['id'],1));
			}
		}
		$smarty->assign("mannResult", $result);
		$smarty->assign("countNewProfile", count($result));
		break;
	case "p":
		$temp = DBConnect::assoc_query_2D("SELECT id, RIGHT(picturepath,1) as pic FROM ".TABLE_MEMBER." WHERE gender = 3 AND isactive = 1 ORDER BY signup_datetime DESC LIMIT 10");

		if(is_array($temp) && (count($temp) > 0))
		{
			foreach($temp as $member)
			{
				array_push($result, funcs::getAdvanceProfile($member['id'],1));
			}
		}
		$smarty->assign("pairsResult", $result);
		$smarty->assign("countNewProfile", count($result));
		break;
}

$smarty->assign('year', date('Y'));
$smarty->display('index.tpl');
?>