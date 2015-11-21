<?php
	if($_GET['cond']==1){
		$arrPost = $_SESSION["arrPost"] ;
		$search_type = $arrPost['q_forsearch'];
		$gender = $arrPost['q_gender'];
		$country = $arrPost['country'];
		$state = $arrPost['state'];
		$city = $arrPost['city'];
		$_SESSION["arrPost"] = array();
	}
		$mindate = $mny.date("-m-d");
		$maxdate = $mxy.date("-m-d");
		if($gender){ $con = " WHERE gender = '$gender' "; }
		if($country != 0){ $con .= " AND country = '$country' "; }
		if($state!= 0){ $con .= " AND   state  = '$state' "; }
		if($city  != 0){ $con .= " AND   city  = '$city' "; } 
	if($search_type == 1){   
		$con .=" AND ".TABLE_MEMBER.".".TABLE_MEMBER_ID. " = ".TABLE_LONELYHEART.".".TABLE_LONELYHEART_USERID;
		$sql = "SELECT * FROM ".TABLE_MEMBER." ,".TABLE_LONELYHEART." $con ORDER BY ".TABLE_MEMBER.".".TABLE_MEMBER_ID;
		$memrec = DBconnect::assoc_query_2D($sql);
		$_SESSION['result_memrec'] = $memrec; 
		$_SESSION['resultHeader'] = " Lonely Heart Search ";
		$_SESSION['resulttype'] = 2;
	}else if($search_type == 2){  
		if($gender){ $con .= " WHERE gender = '$gender' "; }
		if($country != 0){ $con .= " AND country = '$country' "; }
		if($state!= 0){ $con .= " AND   state  = '$state' "; }
		if($city  != 0){ $con .= " AND   city  = '$city' "; } 
		$sql = "SELECT * FROM ".TABLE_MEMBER." $con ORDER BY ".TABLE_MEMBER_ID;
		$memrec = DBconnect::assoc_query_2D($sql);
		$_SESSION['result_memrec'] = $memrec;
		$_SESSION['resultHeader'] = " Profiles Search"; 
		$_SESSION['resulttype'] = 1;
	}   
		$recordperpage = 20;
		$page = $_GET['page'];
		$arrTmp = $_SESSION['result_memrec'];
		$count = count($arrTmp);
		$datas = array_slice($arrTmp, $page*$recordperpage, $recordperpage);
		for($n = 0; $datas[$n]; $n++)
		{
			$datas[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $datas[$n][TABLE_MEMBER_CITY]);
			$datas[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $datas[$n][TABLE_MEMBER_APPEARANCE]);
			$datas[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $datas[$n][TABLE_MEMBER_CIVIL]);
		}
		$total_records = $count ; 
		$pages = ceil($count / $recordperpage);
		$page_number = "";
		if($pages > 0){
			$page_number .= '['; 
			for($i=1; $i<=$pages; $i++){ 
				if($_REQUEST[page]==($i-1)){
					$page_number.= ' <a href="?action=search&page=' . ($i-1). '"><font color="red"><b>' . $i . '</b></font></a> '; 
				}else{
					$page_number.= ' <a href="?action=search&page=' . ($i-1) . '">' . $i . '</a> '; 
				}
			}
			$page_number.= ']';
		}
		if($_REQUEST[page]>0){
			$page_number = ' <a href="?action=search&page=' . ($_REQUEST[page]-1).'"> prev</a> '.$page_number;
		}
		if($pages > 0 &&$_REQUEST[page]<$pages-1){
			$page_number .= ' <a href="?action=search&page=' . ($_REQUEST[page]+1).'"> next</a> ';
		}  
	//get member by search//
	//$funcs::searchMember($_GET['q_search_for'], $_GET['q_search_gender'], 0, 0, 0, 0); 
	$smarty->assign('datas',$datas);
	$smarty->assign('year',date('Y'));
	$smarty->assign('page_number',$page_number);
	$smarty->display('index.tpl'); 
?>
