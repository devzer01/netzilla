<?php
//check permission type//
$permission_lv = array(1, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission


if(!isset($_GET['next'])) SmartyPaginate::reset();
$_GET['username'] = isset($_GET['username'])?trim($_GET['username']):"";
$_GET['order'] = isset($_GET['order'])?trim($_GET['username']):"";
$_GET['type'] = isset($_GET['type'])?trim($_GET['type']):"";

//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=admin_paid&username=".$_GET['username']."&order=".$_GET['order']."&type=".$_GET['type']); //smarty paging set URL

//$type = " WHERE pl.user_id = m.id ";
$type = " WHERE pl.user_id = m.id AND pl.ip != '' AND pl.purchase_finished = 1 ";

if (trim($_GET['username']) != "") {
	$type .= " AND m.username = '" . mysql_real_escape_string($_GET['username']) . "' "; 
	$smarty->assign('username', $_GET['username']);
} 

$order_type = "";
if(in_array($_GET['type'], array('asc','desc')))
	$order_type .= $_GET['type'];
else
	$order_type .= "ASC";

switch($_GET['order'])
{
	case 'username':
		$order = " ORDER BY m.username ".$order_type;
		break;	
	case 'price':
		$order = " ORDER BY pl.price " . $order_type . ", username ASC ";
		break;
	case 'coin_amount':
		$order = " ORDER BY pl.coin_amount ". $order_type . ", m.username ASC ";
		break;
	case 'currency':
		$order = " ORDER BY pl.currency ".$order_type.", m.username ASC";
		break;
	case 'purchase_datetime':
		$order = " ORDER BY pl.purchase_datetime " . $order_type . ", m.username ASC";
		break;
	case 'purchase_finished_date':
		$order = " ORDER BY pl.purchase_finished_date DESC, m.username ASC";
		break;
	case 'payment_method':
		$order = " ORDER BY pl.payment_method ".$order_type.", m.username ASC";
		break;
	case 'payment_type':
		$order = " ORDER BY pl.new_type ".$order_type.", m.username ASC";
		break;
	case 'reference_id':
		$order = " ORDER BY pl.paid_via ".$order_type.", m.username ASC";
		break;
	case '':
	default:
		$order = " ORDER BY pl.purchase_datetime DESC ";
		break;		
}

$num = DBConnect::retrieve_value("SELECT count(*) FROM purchases_log AS pl, member AS m ".$type);

$sql = "SELECT pl.*, m.* FROM purchases_log AS pl, member AS m ".$type.$order." LIMIT ".SmartyPaginate::getCurrentIndex().",".SmartyPaginate::getLimit();
$userrec = DBConnect::assoc_query_2D($sql);

echo "<!-- " . $sql . " -->";

$today = date("Y-m-d");
if(is_array($userrec))
{
	SmartyPaginate::setTotal($num);
}

SmartyPaginate::assign($smarty);

$smarty->assign('userrec', $userrec);
$smarty->display('admin.tpl');
