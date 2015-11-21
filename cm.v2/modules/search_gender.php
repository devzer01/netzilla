<?php 
	if(isset($_GET['wsex']) && isset($_GET['sex']))
	{
		//smarty paging
		SmartyPaginate::connect();
		SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
		SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show

		SmartyPaginate::setUrl("?action=".$_GET['action']."&wsex=".$_GET['wsex']."&sex=".$_GET['sex']); //smarty paging set URL
		
		if((!isset($_GET['next'])) || ($_GET['next']==1)){
			SmartyPaginate::setCurrentItem(1); //go to first record
			$next = 0;
			$start_match = 0;
		}else{
			$next = $_GET['next'];
			$start_match = $next - 1;
		}

		switch($_GET['wsex'])
		{
			case 'm':
				$wsex = 1;
			break;
			case 'w':
				$wsex = 2;
			break;
			case 'p':
				$wsex = 3;
			break;
		}

		switch($_GET['sex'])
		{
			case 'm':
				$sql .= " AND m.".TABLE_MEMBER_LOOKMEN."=1";
				$field = TABLE_MEMBER_LOOKMEN;
			break;
			case 'w':
				$sql .= " AND m.".TABLE_MEMBER_LOOKWOMEN."=1";
				$field = TABLE_MEMBER_LOOKWOMEN;
			break;
			case 'p':
				$sql .= " AND m.".TABLE_MEMBER_LOOKPAIRS."=1";
				$field = TABLE_MEMBER_LOOKWOMEN;
			break;
		}
		$arrCond = array('q_gender' => $wsex, $field => '1', 'q_picture' => '1');

		$profileDetails = search::numAllPorfileSameArea2('profile', $arrCond, $start_match, SEARCH_RESULTS_PER_PAGE);
		
		$memrec_data = $profileDetails['datas'];
		$memrec_total = $profileDetails['total'];
		
		for($n=0; $n<count($memrec_data); $n++)
		{
			$memrec_data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $memrec_data[$n][TABLE_MEMBER_CIVIL]);
			$memrec_data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $memrec_data[$n][TABLE_MEMBER_APPEARANCE]);
			$memrec_data[$n][TABLE_MEMBER_HEIGHT] = ($memrec_data[$n][TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $memrec_data[$n][TABLE_MEMBER_HEIGHT]) : "";
		}

		SmartyPaginate::setTotal($memrec_total);
		SmartyPaginate::assign($smarty);

		$smarty->assign('result', $memrec_data);
		$smarty->assign('data_total', $memrec_total);
		$smarty->assign('year',date('Y'));
		$_SESSION['resultHeader'] = 'Profile Search';		
	} 
	$_SESSION['resulttype'] = 1;
	$smarty->display('index.tpl'); 
?>