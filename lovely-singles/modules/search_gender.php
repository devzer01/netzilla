<?php 
	if(isset($_GET['wsex']) && isset($_GET['sex']))
	{
		//smarty paging
		SmartyPaginate::connect();
		SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
		SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show

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
// 		$sql = "SELECT count(*) FROM ".TABLE_MEMBER." m LEFT JOIN xml_countries c WHERE m.".TABLE_MEMBER_GENDER."=".$wsex;
		
// 											'lookmen' => 'lookmen',
// 											'lookwomen' => 'lookwomen',
// 											'lookpairs' => 'lookpairs',
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
// 		echo $sql."<br/><br/>";
// 		$data1_total = DBconnect::get_nbr($sql);
		
// 		$sql = "SELECT m.*,IF(m.picturepath,1,0) as ispic FROM ".TABLE_MEMBER." m LEFT JOIN xml_countries c ON m.country=c.id WHERE m.".TABLE_MEMBER_GENDER."=".$wsex;
// 		switch($_GET['sex'])
// 		{
// 			case 'm':
// 				$sql .= " AND m.".TABLE_MEMBER_LOOKMEN."=1";
// 			break;
// 			case 'w':
// 				$sql .= " AND m.".TABLE_MEMBER_LOOKWOMEN."=1";
// 			break;
// 			case 'p':
// 				$sql .= " AND m.".TABLE_MEMBER_LOOKPAIRS."=1";
// 			break;
// 		}
		$arrCond = array('q_gender' => $wsex, $field => '1', 'q_picture' => '1');
		
// 		$sql .= " AND c.status=1 AND m.isactive=1 ORDER BY ispic DESC, m.fake DESC";
// 		$sql .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
// 		$datas = DBconnect::assoc_query_2D($sql);

		$profileDetails = search::numAllPorfileSameArea2('profile', $arrCond, $start_match, MESSAGE_RECORD_LIMIT);
		
// 		echo "<pre>";
// 		print_r($profileDetails);
// 		echo "</pre>";
		
		$memrec_data = $profileDetails['datas'];
		$memrec_total = $profileDetails['total'];
		
		for($n=0; $n<count($memrec_data); $n++)
		{
// 			$memrec_data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $memrec_data[$n][TABLE_MEMBER_CITY]);
			$memrec_data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $memrec_data[$n][TABLE_MEMBER_CIVIL]);
			$memrec_data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $memrec_data[$n][TABLE_MEMBER_APPEARANCE]);
			$memrec_data[$n][TABLE_MEMBER_HEIGHT] = ($memrec_data[$n][TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $memrec_data[$n][TABLE_MEMBER_HEIGHT]) : "";
		}

		SmartyPaginate::setTotal($memrec_total);
		SmartyPaginate::assign($smarty);

		$smarty->assign('datas', $memrec_data);
		$smarty->assign('data_total', $memrec_total);
		$smarty->assign('year',date('Y'));
		$_SESSION['resultHeader'] = 'Profile Search';		
	} 
	$_SESSION['resulttype'] = 1;
	$smarty->display('index.tpl'); 
?>