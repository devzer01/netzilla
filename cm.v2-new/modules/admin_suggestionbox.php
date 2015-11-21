<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

//smarty paging//
SmartyPaginate::connect(); //smarty paging connect
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

switch($_GET['do'])	
{
	case 'delete':
		//delete suggestion//
		if(funcs::admin_deleteSuggestionBox($_POST['suggestion_box_id']))
			header("Location: ?action=admin_suggestionbox");
		break;
	case 'edit':
		//edit suggestion//
		if(isset($_POST['submit_button']) && !empty($_POST['submit_button'])) {
			if(funcs::admin_updateSuggestion($_POST['subject'], $_POST['message'], $_GET['id'])) {
			header("Location: ?action=admin_suggestionbox");
		}
		}
		$smarty->assign('data', funcs::getSuggestion($_GET['id']));
		break;
	case 'view':
		//send suggestion data to template//
		$smarty->assign('data', funcs::getSuggestion($_GET['id']));
		break;
	case 'write':
		//add new suggestion//
		if(isset($_POST['submit_button']) && !empty($_POST['submit_button']))
			if(funcs::admin_addSuggestion($_POST['subject'], $_POST['message']))
				header("Location: ?action=".$_GET['action']);
		break;
	default:
		//send all suggestion data to template//
		$suggestion_box = funcs::getAllSuggestion();
		$count = count($suggestion_box);
		$smarty->assign('suggestion_box', $suggestion_box);
}
if($count > 0)
	SmartyPaginate::setTotal($count);	//define data total
SmartyPaginate::assign($smarty);	//send data about smarty paging to template

//select template file//
$smarty->display('admin.tpl');
?>