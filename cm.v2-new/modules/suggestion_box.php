<?php
//smarty paging//
$permission_lv = array(1, 4, 8, 9);
funcs::checkPermission($smarty, $permission_lv);
SmartyPaginate::connect(); //smarty paging connect
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']."&type=".$_GET['type']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record
		
switch($_GET['do'])
{
	//view suggestion//
	case 'view_suggestion':
		$smarty->assign('data', funcs::getSuggestion($_GET['id']));
		break;
	default:
		$smarty->assign('suggestion_data', funcs::getAllSuggestion());	//get all data suggestion
		
		switch($_GET['type'])
		{	
			case 'outbox':
				//delete message outbox//
				if(isset($_POST['delete_button_x']) && !empty($_POST['delete_button_x']) && (count($_POST['messageid'])>0))
				{
					funcs::deleteMessage_suggestionOutbox($_SESSION['sess_id'], $_POST['messageid']);
					header("Location: ?action=".$_GET['action']."&do=".$_GET['do']."&type=".$_GET['type']);
				}
					
				switch($_GET['do'])
				{
					//view message outbox//
					case 'view_message':
						if(($_GET['from'] != "") && ($_GET['from'] == 'sugges')) 
						{
								if(isset($_GET['id']) && isset($_GET['type']))
								{
									funcs::checkSugges($_GET['id'],$_GET['type']);
								}
						}
						$smarty->assign('message', funcs::getMessage_suggestionOutbox($_SESSION['sess_id'], $_GET['id']));
						break;
					default:
						//check permission type//
// 						$permission_lv = array(1, 2, 8);	//define type permission can open this page.
						$permission_lv = array(1, 4, 8, 9); //jeab edited
						funcs::checkPermission($smarty, $permission_lv);	//check permission
						//get outbox message//
						$mymessage_total = funcs::getNumAllMessage_suggestionOutbox($_SESSION['sess_id']);
						$mymessage = funcs::getAllMessage_suggestionOutbox($_SESSION['sess_id'], SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
						//send data outbox message to template//
						$smarty->assign('mymessage_total',$mymessage_total);
						$smarty->assign('message', $mymessage);
						SmartyPaginate::setTotal($mymessage_total);
						SmartyPaginate::assign($smarty);	
				}				
				break;
			case 'write':
				//check permission type//
// 				$permission_lv = array(1, 2, 8);	//define type permission can open this page.
				$permission_lv = array(1, 4, 8, 9); //jeab edited
				funcs::checkPermission($smarty, $permission_lv);	//check permission
				
				//send message//
				if(isset($_POST['send_button']) && !empty($_POST['send_button']))
				{
					if(funcs::sendFeedback($_SESSION['sess_id'], $_POST['subject'], $_POST['message']))
						header("Location: ?action=suggestion_box&type=complete");
					else
					{
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
						$smarty->assign('save', $_POST);
					}
				}
				break;
			case 'inbox':
			default:
				//delete message//
				if(isset($_POST['delete_button_x']) && !empty($_POST['delete_button_x']) && (count($_POST['messageid'])>0))
				{
					funcs::deleteMessage_suggestionInbox($_SESSION['sess_id'], $_POST['messageid']);
					header("Location: ?action=".$_GET['action']."&do=".$_GET['do']."&type=".$_GET['type']);
				}
				switch($_GET['do'])
				{
					case 'view_message':		
						if(($_GET['from'] != "") && ($_GET['from'] == 'sugges')) 
						{
								if(isset($_GET['id']) && isset($_GET['type']))
								{
									funcs::checkSugges($_GET['id'],$_GET['type']);
								}
						}
						$smarty->assign('message', funcs::getMessage_suggestionInbox($_SESSION['sess_id'], $_GET['id']));
						break;
					case '':
						$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
						funcs::checkPermission($smarty, $permission_lv);	//check permission
						break;
					case 'sugg2':
						//send message//
						if(isset($_POST['send_button']) && !empty($_POST['send_button']))
						{
							//check permission type//
// 							$permission_lv = array(1, 2, 8);	//define type permission can open this page.
							$permission_lv = array(1, 4, 8, 9); //jeab edited
							funcs::checkPermission($smarty, $permission_lv);	//check permission
							if(funcs::sendFeedback($_SESSION['sess_id'], $_POST['subject'], $_POST['message']))
								header("Location: ?action=suggestion_box&do=sugg2&type=complete");
							else
							{
								$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
								$smarty->assign('save', $_POST);
							}
						}
					case 'sugg4':
						$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
						funcs::checkPermission($smarty, $permission_lv);	//check permission
						//get inbox message//
						$mymessage_total = funcs::getNumAllMessage_suggestionInbox($_SESSION['sess_id'], 0);
						$mymessage = funcs::getAllMessage_suggestionInbox($_SESSION['sess_id'], 0, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
						//send data inbox message to template//
						$smarty->assign('message', $mymessage);
						$smarty->assign('mymessage_total', $mymessage_total);
						SmartyPaginate::setTotal($mymessage_total);
						SmartyPaginate::assign($smarty);	
						break;					
					default:
						//check permission type//
// 						$permission_lv = array(1, 2, 8);	//define type permission can open this page.
						$permission_lv = array(1, 4, 8, 9); //jeab edited
						funcs::checkPermission($smarty, $permission_lv);	//check permission						
						//get inbox message//
						$mymessage_total = funcs::getNumAllMessage_suggestionInbox($_SESSION['sess_id'], 0);
						$mymessage = funcs::getAllMessage_suggestionInbox($_SESSION['sess_id'], 0, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
						//send data inbox message to template//
						$smarty->assign('message', $mymessage);
						SmartyPaginate::setTotal($mymessage_total);
						SmartyPaginate::assign($smarty);
				}
		}		
}
//select template file//
$smarty->display('index.tpl');
?>
