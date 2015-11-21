<?php
function mergeArrays(&$arr1, &$arr2){
	$rarr = array();
	
	foreach($arr1 as &$sub1)
		$rarr[] = $sub1;
		
	foreach($arr2 as &$sub2)
		$rarr[] = $sub2;
		
	return $rarr;
}
//check permission type//
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission


//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

if(!empty($_POST['submit']) && isset($_POST['submit'])){
	$today = funcs::getDate();
	$arrPost = $_POST ;
	
	//print_r($arrPost);
	
	$username = $arrPost['q_nickname'];
	$email = $arrPost['q_email'];
	$gender = $arrPost['gender'];
	if(($arrPost[year] != "") && ($arrPost[month] != "") && ($arrPost[day] != "")){
		$birthday = " $arrPost[year]-$arrPost[month]-$arrPost[date]";
	}else{
		$birthday = "";
	}
	$country = $arrPost['country'];
	$state = $arrPost['state'];
	$city = $arrPost['city'];
	$area = $arrPost['area'];
	$height = $arrPost['height'];
	$weight = $arrPost['weight'];
	$appearance = $arrPost['appearance']; 
	$eyescolor = $arrPost["eyescolor"] ;
	$haircolor = $arrPost['haircolor'];
	$beard = $arrPost['beard'];
	$zodiac = $arrPost['zodiac'];
	$civilstatus = $arrPost['civilstatus'];
	$sexuality = $arrPost['sexuality'];
	$tattos = $arrPost['tattos'];
	$smoking = $arrPost['smoking'];
	$glasses = $arrPost['glasses']; 
	$handicapped = $arrPost["handicapped"] ;
	$piercings = $arrPost['piercings'];
	$lookmen = $arrPost['lookmen'];
	$lookwomen = $arrPost['lookwomen'];
	$lookpairs = $arrPost['lookpairs'];
	$min_age = $arrPost['minage'];
	$max_age = $arrPost['maxage'];
	$relationship = $arrPost['relationship'];
	$onenightstand = $arrPost['onenightstand'];
	$affair = $arrPost['affair'];
	$friendship = $arrPost['friendship'];	 
	$cybersex = $arrPost['cybersex']; 
	$picture_swapping = $arrPost["picture_swapping"] ;
	$live_dating = $arrPost['live_dating'];
	$role_playing = $arrPost['role_playing'];
	$s_m = $arrPost['s_m'];
	$partner_exchange = $arrPost['partner_exchange'];
	$voyeurism = $arrPost['voyeurism'];
	$_SESSION["arrPost"] = array();
	
	$cond = "";
	if($min_age != ""){
		$mny = date('Y')-$min_age;
		$min_age = $mny.date("-m-d");
	}
	
	if($max_age != ""){
		$mxy = date('Y')-$max_age;
		$max_age = $mxy.date("-m-d");
	}
	
	if($username != ""){
		$cond .= ($cond=="")? " where " : " and ";
		$cond .= "(m.username like '%{$username}%')";}
	if($email != ""){
		$cond .= ($cond=="")? " where " : " and ";
		$cond .= "(m.email like '%{$email}%')";}
	if($gender != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.gender like '%{$gender}%')";}
	if($country != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.country = '{$country}')";}
	if($state != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.state = '{$state}')";}
	if($city != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.city = '{$city}')";}
	if($appearance != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.appearance = '{$appearance}')";}
	if($eyescolor != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.eyescolor = '{$eyescolor}')";}
	if($haircolor != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.haircolor = '{$haircolor}')";}
	if($beard != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.beard = '{$beard}')";}
	if($zodiac != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.zodiac = '{$zodiac}')";}
	if($civilstatus != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.civilstatus = '{$civilstatus}')";}
	if($sexuality != '0'){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.sexuality = '{$sexuality}')";}
	if($tattos != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.tattos = '{$tattos}')";}
	if($smoking != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.smoking = '{$smoking}')";}
	if($glasses != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.glasses = '{$glasses}')";}
	if($handicapped != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.handicapped = '{$handicapped}')";}
	if($piercings != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.piercings = '{$piercings}')";}
	if($lookmen != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.lookmen = '$lookmen)";}
	if($lookwomen != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.lookwomen = '{$lookwomen}')";}
	if($lookpairs != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.lookpairs = '{$lookpairs}')";}
	if($min_age){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.birthday <= '$miTABLE_MEMBER_IDn_age')";}
	if($max_age){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.birthday >= '$max_age')";}
	if($relationship != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.relationship = '{$relationship}')";}
	if($onenightstand != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.onenightstand = '{$onenightstand}')";}
	if($affair != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.affair = '{$affair}')";}
	if($friendship != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.friendship = '{$friendship}')";}
	if($cybersex != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.cybersex = '{$cybersex}')";}
	if($picture_swapping != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.picture_swapping =	'{$picture_swapping}')";}
	if($live_dating != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.live_dating = '{$live_dating}')";}
	if($role_playing != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.role_playing = '{$role_playing}')";}
	if($s_m != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.s_m = '{$s_m}')";}
	if($partner_exchange != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.partner_exchange = '{$partner_exchange}')";}
	if($voyeurism != ""){
		$cond .= ($cond=="")? " where " : " and ";	
		$cond .= "(m.voyeurism = '{$voyeurism}')";}

	if($cond == ""){
		$cond .= " WHERE DAYOFMONTH(".TABLE_MEMBER_BIRTHDAY.") = DAYOFMONTH('$today')  ";
		$cond .= " AND MONTH(".TABLE_MEMBER_BIRTHDAY.") = MONTH('$today')  ";
	}

	$sql = "select m.* from ".TABLE_MEMBER." as m $cond order by picturepath desc";
	
	//echo $sql;
	
	$memrec = DBconnect::assoc_query_2D($sql);

	if(count($memrec) > 0 && is_array($memrec)){
        $idTmp = array();
        for($i=0; $i < count($memrec); $i++){
            $idTmp[] = $memrec[$i]['id'];
        }         
    }
        
	$temp = Search::geProfileSameArea($_SESSION['sess_id'], $gender, '', 0, 200, $city);
    $profileDetailsSamArea = $temp[0];
    
    if(!empty($idTmp) && is_array($idTmp)){
	    for($i=0; $i < count($profileDetailsSamArea); $i++){
	    	if(in_array($profileDetailsSamArea[$i]['id'], $idTmp)){
	    		unset($profileDetailsSamArea[$i]);
	    	}
	    }
    }
    
    //print_r($memrec);
    
    if(is_array($memrec) && count($memrec) > 0)
	{
    	if(is_array($profileDetailsSamArea)){
    		$profileDetailsSamArea[0]['advanced_result'] = "yes";
    		$memrec = mergeArrays($memrec, $profileDetailsSamArea);
    	}
	}
    else
    	$memrec = $profileDetailsSamArea;
    
    //print_r($memrec);
    
	$_SESSION['result_search'] = $memrec;
	$_SESSION['resultHeader'] = "Advance Search"; 
	$_SESSION['resulttype'] = 3;
}

	$recordperpage = SEARCH_RESULTS_PER_PAGE;
	//$page = isset($_GET['next']) ? ($_GET['next']/ $recordperpage)-1 : 0;
	$_GET['next'] = isset($_GET['next']) ? $_GET['next']-1 : 0;
	$arrTmp = $_SESSION['result_search'];
	$count = count($arrTmp);
	//$datas = array_slice($arrTmp, $page*$recordperpage, $recordperpage);
	$datas = array_slice($arrTmp, $_GET['next'], $recordperpage);
	
	foreach($datas as &$data){
		$data[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[TABLE_MEMBER_CITY]);
		$data[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[TABLE_MEMBER_APPEARANCE]);
		$data[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $data[TABLE_MEMBER_CIVIL]);
	}
	
	$total_records = $count ; 
	$pages = ceil($count / $recordperpage);
	$page_number = "";
	if($pages > 0){
		$page_number .= '['; 
		for($i=1; $i<=$pages; $i++)
		{ 
			if($_REQUEST[page]==($i-1))
			{
				$page_number.= ' <a href="?action=show_advsearch&page=' . ($i-1). '"><font color="red"><b>' . $i . '</b></font></a> '; 
			}else{
				$page_number.= ' <a href="?action=show_advsearch&page=' . ($i-1) . '">' . $i . '</a> '; 
			}
		}
		$page_number.= ']';
	}
	if($_REQUEST[page]>0){
		$page_number = ' <a href="?action=show_advsearch&page=' . ($_REQUEST[page]-1).'"> prev</a> '.$page_number;
	}
	if($pages > 0 &&$_REQUEST[page]<$pages-1){
		$page_number .= ' <a href="?action=show_advsearch&page=' . ($_REQUEST[page]+1).'"> next</a> ';
	}

	SmartyPaginate::setTotal($total_records);
	SmartyPaginate::assign($smarty);

	$smarty->assign('datas',$datas);
	$smarty->assign('year',date('Y'));
	$smarty->assign('page_number',$page_number);
	$smarty->display('index.tpl'); 
?>
