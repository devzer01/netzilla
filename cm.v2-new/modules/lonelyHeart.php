<?php	
//check permission type//
if($_GET['action'] == 'lonely_heart_ads_view')
	$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
elseif($_GET['action'] == 'lonely_heart_ads')
	$permission_lv = array(1, 4, 8, 9);		//define type permission can open this page.	
funcs::checkPermission($smarty, $permission_lv);	//check permission
//get userid for get lonelyheart//
if($_GET['username'] != '')
{
	$userid = funcs::getUserid($_GET['username']);
	$lonelyProfile = funcs::getloneyByUsername($_GET['username']);
    if(is_array($lonelyProfile))
	{
		$lonelyProfile['city'] = funcs::getAnswerCity($_SESSION['lang'], $lonelyProfile['city']);
		$lonelyProfile['appearance'] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $lonelyProfile['appearance']);
		$lonelyProfile['civilstatus'] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $lonelyProfile['civilstatus']);
		$lonelyProfile['height'] = ($lonelyProfile['height']>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $lonelyProfile['height']) : "";
		$lonelyProfile['weight'] = ($lonelyProfile['weight']>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$weight', $lonelyProfile['weight']) : "";

		$smarty->assign('thisyear',date('Y'));
		$smarty->assign('lonelyProfile', $lonelyProfile);
	}
}
else
	$userid = $_SESSION['sess_id'];

if($userid)
{
	//smarty paging//
	SmartyPaginate::connect(); //smarty paging connect
	SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
	SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
	SmartyPaginate::setUrl("?action=".$_GET['action']); //smarty paging set URL

	switch($_GET['do'])
	{
		//delete lonely heart ads//
		case 'delete':
			print_r($_POST);
			if(count($_POST['lonely_heart_id']) > 0)
				funcs::deleteLonely_Heart($_SESSION['sess_id'], $_POST['lonely_heart_id']);
			header("Location: ?action=lonely_heart_ads");
			break;
		//edit lonely heart ads//
		case 'search':
			//Get Lonely Heart Datas & assign to smarty
			$smarty->assign('year',date('Y'));
			
			  $mData = Search::GetNewLonelyHeart("M",3);
			  for($n = 0; $mData[$n]; $n++)
			  {	
					$mData[$n][civilstatus] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $mData[$n][civilstatus]);
					$mData[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $mData[$n][TABLE_MEMBER_CITY]);   
					$mData[$n][height] = ($mData[$n][height]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $mData[$n][height]) : "";
					$mData[$n][weight] = ($mData[$n][weight]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$weight', $mData[$n][weight]) : "";
			  }
			  $smarty->assign("MLonelyHeart", $mData);
			  
			  $fData = Search::GetNewLonelyHeart("F",3);
			  for($n = 0; $fData[$n]; $n++)
			  {	
					$fData[$n][civilstatus] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $fData[$n][civilstatus]);
					$fData[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $fData[$n][TABLE_MEMBER_CITY]);
					$fData[$n][height] = ($fData[$n][height]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $fData[$n][height]) : "";
					$fData[$n][weight] = ($fData[$n][weight]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$weight', $fData[$n][weight]) : "";
			  }
			  $smarty->assign("FLonelyHeart", $fData);
			//-----------------------------------------
			//send choice to template//
			  $smarty->assign('thumbpath', UPLOAD_DIR);
			break;
		case 'edit':
			if(isset($_POST['submit_hidden']) && !empty($_POST['submit_hidden']))
			{
				funcs::editLonelyHeart($_SESSION['sess_id'], $_GET['lonelyid'], $_POST);
				header("Location: ?action=lonely_heart_ads");
			}
			$lonelyheart = funcs::getLonelyHeart($_SESSION['sess_id'], $_GET['lonelyid']);
			$smarty->assign('lonelyheart', $lonelyheart);
			break;
		//add new lonely heart ads//
		case 'write':
			if(isset($_POST['submit_hidden']) && !empty($_POST['submit_hidden']))
			{
				$save = $_POST;
				if(($_SESSION['sess_admin'] == 1) && ($_GET['username'] != ''))
				{
					$save['admin'] = 1;
					$save[TABLE_LONELYHEART_USERID] = funcs::getUserid($_GET['username']);
				}
				else
					$save[TABLE_LONELYHEART_USERID] = $_SESSION['sess_id'];
				$save[TABLE_LONELYHEART_DATETIME] = funcs::getDateTime();
				funcs::addLonelyHeart($save);
				if(($_GET['from'] == 'admin') && ($_SESSION['sess_admin'] == 1))
					header("Location: ?action=lonely_heart_ads_view&username=".$_GET['username']);
				else
					header("Location: ?action=lonely_heart_ads");
			}
			break;
		//view lonely heart ads//
		case 'view':
			$lonelyheart = funcs::getLonelyHeart($userid, $_GET['lonelyid']);
			$lonelyheart[TABLE_LONELYHEART_TARGET] = funcs::getAnswerChoice($_SESSION['lang'],'', '$targetGroup', $lonelyheart[TABLE_LONELYHEART_TARGET]);
			$lonelyheart[TABLE_LONELYHEART_CATEGORY] = funcs::getAnswerChoice($_SESSION['lang'],'', '$category', $lonelyheart[TABLE_LONELYHEART_CATEGORY]);
			$smarty->assign('lonelyheart', $lonelyheart);
			$smarty->display('lonelyHeart_view.tpl');
			exit();
			break;
		case 'view_profile':
			funcs::checkPermission($smarty, $permission_lv);	//check permission
			$userid = funcs::getUserid($_GET['username']);
			//get profile//
			$profile = funcs::getProfile($userid);
			
			$smarty->assign('thumbpath', UPLOAD_DIR);
			//get answer//
			$profile[TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'],'', '$gender', $profile[TABLE_MEMBER_GENDER]);
			$profile[TABLE_MEMBER_COUNTRY] = funcs::getAnswerCountry($_SESSION['lang'], $profile[TABLE_MEMBER_COUNTRY]);
			$profile[TABLE_MEMBER_STATE] = funcs::getAnswerCountry($_SESSION['lang'], $profile[TABLE_MEMBER_STATE]);
			$profile[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $profile[TABLE_MEMBER_CITY]);
			$profile[TABLE_MEMBER_HEIGHT] = ($profile[TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $profile[TABLE_MEMBER_HEIGHT]) : "";
			$profile[TABLE_MEMBER_WEIGHT] = ($profile[TABLE_MEMBER_WEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$weight', $profile[TABLE_MEMBER_WEIGHT]) : "";
			$profile[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $profile[TABLE_MEMBER_APPEARANCE]);
			$profile[TABLE_MEMBER_EYE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$eyes_color', $profile[TABLE_MEMBER_EYE]);
			$profile[TABLE_MEMBER_HAIRCOLOR] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$hair_color', $profile[TABLE_MEMBER_HAIRCOLOR]);

			$profile[TABLE_MEMBER_HAIRLENGTH] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$hair_length', $profile[TABLE_MEMBER_HAIRLENGTH]);
			$profile[TABLE_MEMBER_BEARD] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$beard', $profile[TABLE_MEMBER_BEARD]);
			$profile[TABLE_MEMBER_ZODIAC] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$zodiac', $profile[TABLE_MEMBER_ZODIAC]);
			$profile[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $profile[TABLE_MEMBER_CIVIL]);
			$profile[TABLE_MEMBER_SEXUALITY] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$sexuality', $profile[TABLE_MEMBER_SEXUALITY]);
			$profile[TABLE_MEMBER_TATTOS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_TATTOS]);
			$profile[TABLE_MEMBER_SMOKING] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_SMOKING]);
			$profile[TABLE_MEMBER_GLASSES] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_GLASSES]);
			$profile[TABLE_MEMBER_HANDICAPPED] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_HANDICAPPED]);
			$profile[TABLE_MEMBER_PIERCINGS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_PIERCINGS]);
			$profile[TABLE_MEMBER_LOOKMEN] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LOOKMEN]);
			$profile[TABLE_MEMBER_LOOKWOMEN] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LOOKWOMEN]);
			$profile[TABLE_MEMBER_LOOKPAIRS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LOOKPAIRS]);
			$profile[TABLE_MEMBER_RELATIONSHIP] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_RELATIONSHIP]);
			$profile[TABLE_MEMBER_ONENIGHTSTAND] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_ONENIGHTSTAND]);
			$profile[TABLE_MEMBER_AFFAIR] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_AFFAIR]);
			$profile[TABLE_MEMBER_FRIENDSHIP] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_FRIENDSHIP]);
			$profile[TABLE_MEMBER_CYBERSEX] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_CYBERSEX]);
			$profile[TABLE_MEMBER_PICTURE_SWAP] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_PICTURE_SWAP]);
			$profile[TABLE_MEMBER_LIVE_DATING] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LIVE_DATING]);
			$profile[TABLE_MEMBER_ROLE_PLAYING] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_ROLE_PLAYING]);
			$profile[TABLE_MEMBER_S_M] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_S_M]);
			$profile[TABLE_MEMBER_PARTNER_EX] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_PARTNER_EX]);
			$profile[TABLE_MEMBER_VOYEURISM] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_VOYEURISM]);
			//$profile[TABLE_MEMBER_TATTOS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_TATTOS]);
			//$lonelyheart[TABLE_LONELYHEART_TARGET] = funcs::getAnswerChoice($_SESSION['lang'],'', '$targetGroup', $lonelyheart[TABLE_LONELYHEART_TARGET]);
			//$lonelyheart[TABLE_LONELYHEART_CATEGORY] = funcs::getAnswerChoice($_SESSION['lang'],'', '$category', $lonelyheart[TABLE_LONELYHEART_CATEGORY]);

			$smarty->assign('profile', $profile);
			//$smarty->assign('lonelyheart', $lonelyheart);
			$smarty->display('lonelyHeart_viewprofile.tpl');
			exit();
			break;
		case 'veiw_lonely' :
		
			$lonely_heart_data = funcs::getAllLonely_Heart($userid, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
			//get total lonely heart ads by user//
			$lonely_heart_total = funcs::getNumLonelyHeart($userid);
			
			for($n=0; $lonely_heart_data[$n]; $n++)
			{
				$lonely_heart_data[$n][TABLE_LONELYHEART_TARGET] = funcs::getAnswerChoice($_SESSION['lang'],'', '$targetGroup', $lonely_heart_data[$n][TABLE_LONELYHEART_TARGET]);
				$lonely_heart_data[$n][TABLE_LONELYHEART_CATEGORY] = funcs::getAnswerChoice($_SESSION['lang'],'', '$category', $lonely_heart_data[$n][TABLE_LONELYHEART_CATEGORY]);
				$lonely_heart_data[$n][TABLE_MEMBER_HEIGHT] = ($lonely_heart_data[$n][TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $lonely_heart_data[$n][TABLE_MEMBER_HEIGHT]) : "";
				$lonely_heart_data[$n][TABLE_MEMBER_WEIGHT] = ($lonely_heart_data[$n][TABLE_MEMBER_WEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$weight', $lonely_heart_data[$n][TABLE_MEMBER_WEIGHT]) : "";
			}
			
			//send data to template//
			$smarty->assign('lonely_heart', $lonely_heart_data);
			SmartyPaginate::setTotal($lonely_heart_total);
			SmartyPaginate::assign($smarty);
			$smarty->display('lonelyHeart_popup.tpl');
			exit();
			break;
		default:
			if(isset($_POST['submit_hidden']) && !empty($_POST['submit_hidden']))
			{
				foreach($_POST as $key => $val)
				{
					$save[$key] = mysql_real_escape_string($val);
				}
				//$save = $_POST;
				if(($_SESSION['sess_admin'] == 1) && ($_GET['username'] != ''))
				{
					$save['admin'] = 1;
					$save[TABLE_LONELYHEART_USERID] = funcs::getUserid($_GET['username']);
				}
				else
					$save[TABLE_LONELYHEART_USERID] = $_SESSION['sess_id'];
				$save[TABLE_LONELYHEART_DATETIME] = funcs::getDateTime();
				funcs::addLonelyHeart($save);
				if(($_GET['action'] == 'lonely_heart_ads_view') && ($_SESSION['sess_admin'] == 1))
					header("Location: ?action=lonely_heart_ads_view&username=".$_GET['username']);
				else
					header("Location: ?action=lonely_heart_ads");
			}
			if(!isset($_GET['next']))
				SmartyPaginate::setCurrentItem(1); //go to first record
				
			//get lonely heart ads data//
			$lonely_heart_data = funcs::getAllLonely_Heart($userid, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
			//get total lonely heart ads by user//
			$lonely_heart_total = funcs::getNumLonelyHeart($userid);
			
			for($n=0; $lonely_heart_data[$n]; $n++)
			{
				$lonely_heart_data[$n][TABLE_LONELYHEART_TARGET] = funcs::getAnswerChoice($_SESSION['lang'],'', '$targetGroup', $lonely_heart_data[$n][TABLE_LONELYHEART_TARGET]);
				$lonely_heart_data[$n][TABLE_LONELYHEART_CATEGORY] = funcs::getAnswerChoice($_SESSION['lang'],'', '$category', $lonely_heart_data[$n][TABLE_LONELYHEART_CATEGORY]);
				$lonely_heart_data[$n][TABLE_MEMBER_HEIGHT] = ($lonely_heart_data[$n][TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $lonely_heart_data[$n][TABLE_MEMBER_HEIGHT]) : "";
				$lonely_heart_data[$n][TABLE_MEMBER_WEIGHT] = ($lonely_heart_data[$n][TABLE_MEMBER_WEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$weight', $lonely_heart_data[$n][TABLE_MEMBER_WEIGHT]) : "";
			}
			
			//send data to template//
			$smarty->assign('lonely_heart', $lonely_heart_data);
			SmartyPaginate::setTotal($lonely_heart_total);
			SmartyPaginate::assign($smarty);
			
	}	

	//send choice to template//
	$smarty->assign('targetGroup', funcs::getChoice($_SESSION['lang'],'','$targetGroup'));
	$smarty->assign('category', funcs::getChoice($_SESSION['lang'],'','$category'));	
	//select template file//
	$smarty->display('index.tpl');
}
else
{
	header("location: .");
}
?>