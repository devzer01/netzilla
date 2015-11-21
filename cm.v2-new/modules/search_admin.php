<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']."&order=".$_GET['order']."&type=".$_GET['type']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

$proc = $_GET['proc'];
$user = $_GET['user'];
$page = $_GET['page'];
if($proc=='del'){ 
	$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_ISACTIVE." = 0 
			WHERE ".TABLE_MEMBER_USERNAME." = '$user'  AND ".TABLE_MEMBER_STATUS." != 1"; 
	DBconnect::execute($sql);
	header("Location: ?action=admin_manageuser&order=".$_GET['order']."&type=".$_GET['type']."&next=".$_GET['next']);
	exit;
}

$total = 5000;

$arrPost = $_POST ;
		$search_type =  $arrPost['q_forsearch'];
		$gender =  $arrPost['q_gender'];
		$country = $arrPost ['country'];
		$state = $arrPost['state'];
		$city = $arrPost['city'];
		$_SESSION["arrPost"] = array();
		$con = "";
		
		if($gender){
		$con .= ($con=="")? " where " : " and ";
		$con .= "(t1.gender like '%{$gender}%')";}
		if($country != 0){
		$con .= ($con=="")? " where " : " and ";
		$con .= "(t1.country = $country)";}
		if($state!= 0){
		$con .= ($con=="")? " where " : " and ";
		$con .= "(t1.state = $state)";}
		if($city  != 0){
		$con .= ($con=="")? " where " : " and ";
		$con .= "(t1.city = $city)";}
	
		switch($_GET['order'])
		{
			case 'city':
			$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." AS username, t4.name as ".TABLE_MEMBER_CITY.", t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY.",t1.".TABLE_MEMBER_STATUS." AS status FROM ".TABLE_MEMBER." as t1 left outer join xml_countries as t2 on t1.country=t2.id left outer join xml_states as t3 on t1.state=t3.id left outer join xml_cities as t4 on t1.city=t4.id $con ORDER BY t4.name";
				switch($_GET['type'])
				{
				case 'desc':
				$sql .= " DESC";
				break;
				default:
				$sql .= " ASC";
				}
			$sql .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
			$data = DBconnect::assoc_query_2D($sql);
			break;
			case 'state':
			$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." AS username, t4.name as ".TABLE_MEMBER_CITY.", t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY.",t1.".TABLE_MEMBER_STATUS." AS status FROM ".TABLE_MEMBER." as t1 left outer join xml_countries as t2 on t1.country=t2.id left outer join xml_states as t3 on t1.state=t3.id left outer join xml_cities as t4 on t1.city=t4.id $con ORDER BY t3.name";
				switch($_GET['type'])
				{
				case 'desc':
				$sql .= " DESC";
				break;
				default:
				$sql .= " ASC";
				}
			$sql .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
			$data = DBconnect::assoc_query_2D($sql);
			break;
			case 'country':
			$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." AS username, t4.name as ".TABLE_MEMBER_CITY.", t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY.",t1.".TABLE_MEMBER_STATUS." AS status FROM ".TABLE_MEMBER." as t1 left outer join xml_countries as t2 on t1.country=t2.id left outer join xml_states as t3 on t1.state=t3.id left outer join xml_cities as t4 on t1.city=t4.id $con ORDER BY t2.name";
		
				switch($_GET['type'])
				{
				case 'desc':
				$sql .= " DESC";
				break;
				default:
				$sql .= " ASC";
				}
			$sql .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
			$data = DBconnect::assoc_query_2D($sql);
			break;
			default:
			$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." AS username, t4.name as ".TABLE_MEMBER_CITY.", t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY.",t1.".TABLE_MEMBER_STATUS." AS status FROM ".TABLE_MEMBER." as t1 left outer join xml_countries as t2 on t1.country=t2.id left outer join xml_states as t3 on t1.state=t3.id left outer join xml_cities as t4 on t1.city=t4.id $con ORDER BY ".TABLE_MEMBER_USERNAME;

				switch($_GET['type'])
				{
				case 'desc':
				$sql .= " DESC";
				break;
				default:
				$sql .= " ASC";
				}

			$countdata = count(DBconnect::assoc_query_2D($sql));
			$sql .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
			$data = DBconnect::assoc_query_2D($sql);		
			$rec = SEARCH_RESULTS_PER_PAGE-count($data);
			if($rec != 0)
			{
			$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ISACTIVE."=1 AND ".TABLE_MEMBER_PICTURE." !=''";
			$total_pic = DBconnect::get_nbr($sql);

			$sql = "SELECT ".TABLE_MEMBER_USERNAME." AS username, ".TABLE_MEMBER_CITY.", ".TABLE_MEMBER_STATE.", ".TABLE_MEMBER_COUNTRY.",".TABLE_MEMBER_STATUS." AS status FROM ".TABLE_MEMBER. " WHERE ".TABLE_MEMBER_ISACTIVE." = 1 AND t1.".TABLE_MEMBER_COUNTRY."=t2.id AND t1.".TABLE_MEMBER_STATE."=t3.id AND t1.".TABLE_MEMBER_CITY."=t4.id AND ".TABLE_MEMBER_PICTURE." ='' ORDER BY ".TABLE_MEMBER_USERNAME;

			switch($_GET['type'])
			{
				case 'desc':
					$sql .= " DESC";
					break;
				default:
					$sql .= " ASC";
			}
			$begin = SmartyPaginate::getCurrentIndex()-$total_pic;
			if($begin < 0)
				$begin = 0;
			$sql .= " LIMIT ".$begin.", ".$rec;
			$data1 = DBconnect::assoc_query_2D($sql);

			for($n=0; $data1[$n]; $n++)
				array_push($data, $data1[$n]);
		}

		}
		$_SESSION['result_memrec'] = $memrec;
		$_SESSION['resultHeader'] = " Profiles Search"; 
		$_SESSION['resulttype'] = 1;		 

SmartyPaginate::setTotal($countdata);
SmartyPaginate::assign($smarty);

//send data to template//
$smarty->assign('tr',$tr); 
$smarty->assign('userrec',$data); 
$smarty->assign('page_number',$page_number);
//select template file//
$smarty->display('admin.tpl'); 
?> 




