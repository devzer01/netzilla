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
$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

if(is_array($_GET) == true){
	foreach($_GET as $index=>$value){
		$index = "$index";
		$$index = $value;
	}
}
if(is_array($_POST) == true){
	foreach($_POST as $index=>$value){
		$index = "$index";
		$$index = $value;
		$_SESSION[formPost][$index] = $value;
	}
}

if($nPage == false){
	$nPage = 1;
}

$start = ($nPage * MESSAGE_RECORD_LIMIT ) - MESSAGE_RECORD_LIMIT;

//if(isset($_POST['submit'])){
$today = funcs::getDate();
$arrPost = $_SESSION[formPost];

if(is_array($arrPost) == true){
	foreach($arrPost as $index=>$value){
		$index = "$index";
		$$index = $value;
	}
}


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

//$cond = "";
if($min_age != ""){
	$mny = date('Y')-$min_age;
	$min_age = $mny.date("-m-d");
}

if($max_age != ""){
	$mxy = date('Y')-$max_age;
	$max_age = $mxy.date("-m-d");
}

$sql = "SELECT * \n";
$sql .= "FROM member \n";
$sql .= "WHERE 1 \n";
if($username)
	$sql .= "AND username LIKE  '%$username%'\n";
if($email)
	$sql .= "AND email LIKE  '%$email%'\n";
if($gender)
	$sql .= "AND gender LIKE  '%$gender%'\n";
if($country)
	$sql .= "AND country = '$country'\n";
if($state)
	$sql .= "AND state = '$state'\n";
if($city)
	$sql .= "AND city = '$city'\n";
if($appearance)
	$sql .= "AND appearance = '$appearance'\n";
if($eyescolor)
	$sql .= "AND eyescolor = '$eyescolor'\n";
if($haircolor)
	$sql .= "AND haircolor = '$haircolor'\n";
if($beard)
	$sql .= "AND beard = '$beard'\n";
if($zodiac)
	$sql .= "AND zodiac = '$zodiac'\n";
if($civilstatus)
	$sql .= "AND civilstatus = '$civilstatus'\n";
if($sexuality)
	$sql .= "AND sexuality = '$sexuality'\n";
if($tattos)
	$sql .= "AND tattos = '$tattos'\n";
if($smoking)
	$sql .= "AND smoking = '$smoking'\n";
if($glasses)
	$sql .= "AND glasses = '$glasses'\n";
if($handicapped)
	$sql .= "AND handicapped = '$handicapped'\n";
if($piercings)
	$sql .= "AND piercings = '$piercings'\n";
if($lookmen)
	$sql .= "AND lookmen = '$lookmen'\n";
if($lookwomen)
	$sql .= "AND lookwomen = '$lookwomen'\n";
if($lookpairs)
	$sql .= "AND lookpairs = '$lookpairs'\n";
if($min_age)
	$sql .= "AND birthday <= '$min_age'\n";
if($max_age)
	$sql .= "AND birthday >= '$max_age'\n";
if($relationship)
	$sql .= "AND relationship = '$relationship'\n";
if($onenightstand)
	$sql .= "AND onenightstand = '$onenightstand'\n";
if($affair)
	$sql .= "AND affair = '$affair'\n";
if($friendship)
	$sql .= "AND friendship = '$friendship' \n";
if($cybersex)
	$sql .= "AND cybersex = '$cybersex'\n";
if($picture_swapping)
	$sql .= "AND picture_swapping =	'$picture_swapping'\n";
if($live_dating)
	$sql .= "AND live_dating = '$live_dating'\n";
if($role_playing)
	$sql .= "AND role_playing = '$role_playing'\n";
if($s_m)
	$sql .= "AND s_m = '$s_m'\n";
if($partner_exchange)
	$sql .= "AND partner_exchange = '$partner_exchange'\n";
if($voyeurism)
	$sql .= "AND voyeurism = '$voyeurism'\n";
$sql .= "ORDER BY picturepath DESC \n";
$sql .= "LIMIT $start, ".MESSAGE_RECORD_LIMIT." ";
$result = mysql_query($sql)or die("<pre>$sql</pre>".mysql_error());
$n = 0;
$member = array();
while($rs = mysql_fetch_array($result)){
	foreach($rs as $index=>$value){
		if(gettype($index) == string)
		$member[$n][$index] = $value;
	}
	$n++;
}

$sql = "SELECT COUNT(id) AS num \n";
$sql .= "FROM member \n";
$sql .= "WHERE 1 \n";
if($username)
	$sql .= "AND username LIKE  '%$username%'\n";
if($email)
	$sql .= "AND email LIKE  '%$email%'\n";
if($gender)
	$sql .= "AND gender LIKE  '%$gender%'\n";
if($country)
	$sql .= "AND country = '$country'\n";
if($state)
	$sql .= "AND state = '$state'\n";
if($city)
	$sql .= "AND city = '$city'\n";
if($appearance)
	$sql .= "AND appearance = '$appearance'\n";
if($eyescolor)
	$sql .= "AND eyescolor = '$eyescolor'\n";
if($haircolor)
	$sql .= "AND haircolor = '$haircolor'\n";
if($beard)
	$sql .= "AND beard = '$beard'\n";
if($zodiac)
	$sql .= "AND zodiac = '$zodiac'\n";
if($civilstatus)
	$sql .= "AND civilstatus = '$civilstatus'\n";
if($sexuality)
	$sql .= "AND sexuality = '$sexuality'\n";
if($tattos)
	$sql .= "AND tattos = '$tattos'\n";
if($smoking)
	$sql .= "AND smoking = '$smoking'\n";
if($glasses)
	$sql .= "AND glasses = '$glasses'\n";
if($handicapped)
	$sql .= "AND handicapped = '$handicapped'\n";
if($piercings)
	$sql .= "AND piercings = '$piercings'\n";
if($lookmen)
	$sql .= "AND lookmen = '$lookmen'\n";
if($lookwomen)
	$sql .= "AND lookwomen = '$lookwomen'\n";
if($lookpairs)
	$sql .= "AND lookpairs = '$lookpairs'\n";
if($min_age)
	$sql .= "AND birthday <= '$miTABLE_MEMBER_IDn_age'\n";
if($max_age)
	$sql .= "AND birthday >= '$max_age'\n";
if($relationship)
	$sql .= "AND relationship = '$relationship'\n";
if($onenightstand)
	$sql .= "AND onenightstand = '$onenightstand'\n";
if($affair)
	$sql .= "AND affair = '$affair'\n";
if($friendship)
	$sql .= "AND friendship = '$friendship' \n";
if($cybersex)
	$sql .= "AND cybersex = '$cybersex'\n";
if($picture_swapping)
	$sql .= "AND picture_swapping =	'$picture_swapping'\n";
if($live_dating)
	$sql .= "AND live_dating = '$live_dating'\n";
if($role_playing)
	$sql .= "AND role_playing = '$role_playing'\n";
if($s_m)
	$sql .= "AND s_m = '$s_m'\n";
if($partner_exchange)
	$sql .= "AND partner_exchange = '$partner_exchange'\n";
if($voyeurism)
	$sql .= "AND voyeurism = '$voyeurism'\n";
$result = mysql_query($sql)or die("<pre>$sql</pre>".mysql_error());
$rs = mysql_fetch_array($result);
$num_member = $rs[num];
$pages = ceil($num_member / MESSAGE_RECORD_LIMIT);

// list member in same my profle area
if($start+MESSAGE_RECORD_LIMIT > $num_member ){
	if(count($member) > 0){
		$limit_member_by_location = MESSAGE_RECORD_LIMIT - count($member);
	}else{
		$limit_member_by_location = MESSAGE_RECORD_LIMIT;
	}
	$start = $pages + (($nPage * MESSAGE_RECORD_LIMIT) - MESSAGE_RECORD_LIMIT);
	$temp = Search::geProfileSameArea($_SESSION['sess_id'], $gender, '', $start, $limit_member_by_location, $city);
    $profileDetailsSamArea = $temp[0];
	$profileDetailsSamArea[0]['advanced_result'] = "yes";
	$member = mergeArrays($member, $profileDetailsSamArea);//array_merge($member,$temp[0]);
}

if(is_array($member) == true){
	foreach($member as $index=>$value){
		$member[$index][city] = funcs::getAnswerCity($_SESSION['lang'], $member[$index][city]);
		$member[$index][appearance] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $member[$index][appearance]);
		$member[$index][civilstatus] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $member[$index][civilstatus]);
	}
}

// cal. member pages list
$sql = "SELECT COUNT(id) AS num \n";
$sql .= "FROM member \n";
$sql .= "WHERE isactive = '1' \n";
$result = mysql_query($sql)or die("<pre>$sql</pre>".mysql_error());
$rs = mysql_fetch_array($result);
$pages = ceil($rs[num]/MESSAGE_RECORD_LIMIT);



$show_advsearch_pages = "\n";
$page_limit = 9;
$p = 1;
$row = ceil($pages/$page_limit);
$current_cell = ceil(($nPage/$page_limit));
$r = $current_cell;
$p = (($current_cell * $page_limit) - $page_limit) + 1;
while($r < $current_cell+1){
	if($nPage-1 > 0){
		$show_advsearch_pages .= "<a href=\"index.php?action=show_advsearch&nPage=".($nPage-1)."\">";
		$show_advsearch_pages .= " &lt;&lt; ";
		$show_advsearch_pages .= "</a>\n";
	}else{
		$show_advsearch_pages .= " &lt;&lt; ";
	}

	for($i=1; $i<=$page_limit; $i++){
		if($p == $nPage){
			$show_advsearch_pages .= "$p \n";
		}else{
			$show_advsearch_pages .= "<a href=\"index.php?action=show_advsearch&nPage=$p\">";
			$show_advsearch_pages .= "$p";
			$show_advsearch_pages .= "</a>\n";
		}
		$p++;
	}
	
	if($nPage+1 < $pages){
		$show_advsearch_pages .= "<a href=\"index.php?action=show_advsearch&nPage=".($nPage+1)."\">";
		$show_advsearch_pages .= " &gt;&gt; ";
		$show_advsearch_pages .= "</a>\n";
	}else{
		$show_advsearch_pages .= " &gt;&gt; ";
	}
	$r++;
}

$_SESSION['resulttype'] = 3;
$_SESSION['resultHeader'] = "Advance Search"; 
//$member = array_shift($member);
//echo"<pre>";
//print_r($member);
$smarty->assign('show_advsearch_pages',$show_advsearch_pages);
$smarty->assign('datas',$member);
$smarty->assign('year',date('Y'));
$smarty->display('index.tpl'); 
	
?>
