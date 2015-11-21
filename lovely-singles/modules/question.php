<?php
	
//check permission type//
$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

//send message//
if(isset($_POST['act']) && $_POST['act'] == 'sendquestion'){
	/////if(funcs::sendMessage($_SESSION['sess_id'], 'Marianne_TeamHerzoase', $_POST['subject'], $_POST['message'], 2))
	if(funcs::sendQuestionMessage($_SESSION['sess_id'], $_POST['subject'], $_POST['message'], 2,$smarty))
		header("Location: ?action=mymessage&type=complete_question");
		//$smarty->assign('text', funcs::getText($_SESSION['lang'], '$complete')); //send complete
	else
	{
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
		$smarty->assign('save', $_POST);
	}
}
//select template file//
$smarty->display('index.tpl');
?>
