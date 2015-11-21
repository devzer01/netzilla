<?php
/*SmartyPaginate::connect();
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show


/**
 * SEARCH BY NICK NAME
 **/

if((isset($_POST['q_nickname'])) && ($_POST['q_nickname']!=''))
{
	if((isset($_POST['q_type'])) && ($_POST['q_type']!=''))
		$smarty->assign('q_type', $_POST['q_type']);

	$arr_byNickName = search::searchByNickName($_POST['q_nickname']);
	/*echo "<pre>";
	print_r($arr_byNickName);
	echo "</pre>";*/

	$arr_byNickNameSimilar = search::searchByNickNameSimilar($_POST['q_nickname']);
	/*echo "<pre>";
	print_r($arr_byNickNameSimilar);
	echo "</pre>";*/

	$arr_byNickNameAdditional = search::searchByNickNameAdditional($_POST['q_nickname']);
	/*echo "<pre>";
	print_r($arr_byNickNameAdditional);
	echo "</pre>";*/

	$arr_results = array_merge($arr_byNickName, $arr_byNickNameSimilar);
	$arr_results = array_merge($arr_results, $arr_byNickNameAdditional);

	/*echo "<pre>";
	print_r($arr_results);
	echo "</pre>";*/
}
else
{
	echo funcs::getText($_SESSION['lang'], '$search_new1');
}
?>
<?php

/*for($n = 0; $memrec_data[$n]; $n++)
{
	$memrec_data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $memrec_data[$n][TABLE_MEMBER_CITY]);
	$memrec_data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $memrec_data[$n][TABLE_MEMBER_APPEARANCE]);
	$memrec_data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $memrec_data[$n][TABLE_MEMBER_CIVIL]);
}

SmartyPaginate::setTotal($memrec_total);
SmartyPaginate::assign($smarty);

//check search result
$smarty->assign('noresult',$noResult);
//get member by search//
echo "<pre>";
print_r($memrec_data);
echo "</pre>";
$smarty->assign('extended', $extended);
$smarty->assign('minProfiles', PROFILE_SEARCH_MIN);
$smarty->assign('maxProfiles', PROFILE_SEARCH_MAX);
$smarty->assign('data_total', $memrec_total);*/
$smarty->assign('datas',$arr_results);
$smarty->assign('year',date('Y'));
$smarty->display('index.tpl');
?>