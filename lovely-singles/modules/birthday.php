<?php
$today = funcs::getDate();	//get date today
$proc = $_GET['proc'];
$username = $_REQUEST['username'];

//send card//
if($proc=='sending')
{
	$tr = 1;
	if($username!='')
	{
		$_SESSION['uname'] = $username;
		header("Location: ?action=birthday&proc=sending");
		exit;
	}
	 $username = $_SESSION['uname']; 
}
else
	$tr = 2;

//get member birthday is today//
$sql1 = "SELECT * FROM ".TABLE_MEMBER
		." WHERE DAYOFMONTH(".TABLE_MEMBER_BIRTHDAY.") = DAYOFMONTH('$today') 
		AND MONTH(".TABLE_MEMBER_BIRTHDAY.") = MONTH('$today') ORDER BY picturepath DESC";
$memrec = DBconnect::assoc_query_2D($sql1);

for($n = 0; $memrec[$n]; $n++){
	$memrec[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $memrec[$n][TABLE_MEMBER_CITY]);
	$memrec[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $memrec[$n][TABLE_MEMBER_CIVIL]);
	$memrec[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $memrec[$n][TABLE_MEMBER_APPEARANCE]);
	$memrec[$n][TABLE_MEMBER_HEIGHT] = funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $memrec[$n][TABLE_MEMBER_HEIGHT]);
	$memrec[$n]['picturepath'] = trim($memrec[$n]['picturepath']);
}

//get all card//
$sql2 = "SELECT ".TABLE_CARD_ID." AS cardid ,"
			.TABLE_CARD_CARDTMP." AS cardtmp ,"
			.TABLE_CARD_CARDPATH." AS cardpath ,"
			.TABLE_CARD_CARDTMP." AS cardtmp  ,"
			.TABLE_CARD_CARDSHOW." AS cardshow   FROM ".TABLE_CARD
			." WHERE ".TABLE_CARD_CARDSHOW." = '1'"
			." ORDER  BY " .TABLE_CARD_ID." ASC";
$rec = DBconnect::assoc_query_2D($sql2); 

//show card in template//
foreach($rec as $key => $val){
	$cardrec[$key] = ' <table width="150" height="100"  border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">';  
	$cardrec[$key] .= '  <tr><td width="200" bgcolor="#FFFFFF">';
	$cardrec[$key] .= '  <div align="center" style=" cursor:pointer" onclick="window.location.href=\'?action=writecard&uname='.$username.'&card='.$val['cardid'].'\'">'; 
	$cardrec[$key] .= ' <img src="'. $val['cardtmp'].'">';
	$cardrec[$key] .= '  </div></td></tr></table>'; 
}

//paging function//
$_SESSION['result_memrec'] = $memrec;
$_SESSION['resultHeader'] = " BIRTHDAY"; 
$_SESSION['resulttype'] = 1;

$recordperpage = 20;	//fix limit record to show this page
$page = $_GET['page'];
$arrTmp = $_SESSION['result_memrec'];
$count = count($arrTmp);	//get total record
$datas = array_slice($arrTmp, $page*$recordperpage, $recordperpage);	//select data to show
$total_records = $count;	//total record
$pages = ceil($count / $recordperpage);	//get last page
$page_number = "";
if($pages > 0)
{
	$page_number .= '['; 
	for($i=1; $i<=$pages; $i++)
	{ 
		if($_REQUEST[page]==($i-1))
		{
			$page_number.= ' <a href="?action=birthday&page=' . ($i-1). '"><font color="grey"><b>' . $i . '</b></font></a> '; 
		}else{
			$page_number.= ' <a href="?action=birthday&page=' . ($i-1) . '">' . $i . '</a> '; 
		}
	}
	$page_number.= ']';
}
if($_REQUEST[page]>0)
	$page_number = ' <a href="?action=birthday&page=' . ($_REQUEST[page]-1).'"> '. funcs::getText($_SESSION['lang'], '$back_button') .'</a> '.$page_number;

if($pages > 0 &&$_REQUEST[page]<$pages-1)
	$page_number .= ' <a href="?action=birthday&page=' . ($_REQUEST[page]+1).'"> '. funcs::getText($_SESSION['lang'], '$next_button') .'</a> ';
//send data to template//
$smarty->assign('tr',$tr); 
$smarty->assign('datas',$datas);
$smarty->assign('countRow',count($datas));
$smarty->assign('cardrec', $cardrec); 
$smarty->assign('year',date('Y'));
$smarty->assign('page_number',$page_number);
//select template file//
$smarty->display('index.tpl');
?>
