<?php

	include('modules/checkprofile.php');
	
	$total_score = $sub_score1 + $sub_score2 + $sub_score3;
	if($total_score < 100)
	{
		$smarty->assign('sub_score1', $sub_score1);
		$smarty->assign('sub_score2', $sub_score2);
		$smarty->assign('sub_score3', $sub_score3);
	}
	else
	{
		header('location: .');
		exit;
	}

	$smarty->display('index.tpl');
?>