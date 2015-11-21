<?php
//check permission type//
$permission_lv = array(1, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission


/**
 * Functions
 */
class Message {
	
	private $start = 0;
	private $per_page = 50;
	
	public function __construct() {
		$this->start = isset($_GET['iDisplayStart'])?trim($_GET['iDisplayStart']):$this->start;
		$this->per_page = isset($_GET['iDisplayLength'])?trim($_GET['iDisplayLength']):$this->per_page;
	}
	
	private function getSearch() {
		$kw = trim($_GET['sSearch']);
		if(empty($kw)) {
			return '';
		} else {
			return " AND ( T0.message LIKE '%".$kw."%' OR T1.username LIKE '%".$kw."%' OR T2.username LIKE '%".$kw."%' )";
		}
	}
	
	private function getOrder() {
		$col = trim($_GET['iSortCol_0']);
		switch($col) {
			case '1':
				$order_by = 'reciever';
				break;
			case '3';
				$order_by = 'T0.message';
				break;
			case '0':
				$order_by = 'sender';
				break;
			default:
				$order_by = 'T0.datetime';
				break;
		}
		
		return 'ORDER BY '.$order_by.' '.(($_GET['sSortDir_0'] == 'asc') ? 'asc' : 'desc');
	}
	
	public function countMessages() {
		$sql = "SELECT COUNT(*) as total_rows FROM message_outbox T0
			LEFT JOIN member T1 ON T0.to_id = T1.id
			LEFT JOIN member T2 ON T0.from_id = T2.id
			WHERE T1.fake = 0 AND T2.fake = 0 and T1.id>1 and T2.id>1 and T1.isactive=1 and T2.isactive=1 and T1.username!='' and T2.username!='' ".$this->getSearch();
		$sql = mysql_query($sql);
		$fetch = mysql_fetch_assoc($sql);
		return $fetch['total_rows'];
	}
	public function listMessage() {
		$sql = "SELECT T0.message, T0.datetime, T1.username as reciever, T2.username as sender FROM message_outbox T0
			LEFT JOIN member T1 ON T0.to_id = T1.id
			LEFT JOIN member T2 ON T0.from_id = T2.id
			WHERE T1.fake = 0 AND T2.fake = 0 and T1.id>1 and T2.id>1 and T1.isactive=1 and T2.isactive=1 and T1.username!='' and T2.username!='' 
			".$this->getSearch()."
			".$this->getOrder()."
			LIMIT ".$this->start.",".$this->per_page."
			
		";
		$sql = mysql_query($sql);
		$ret = array();
		while($fetch = mysql_fetch_assoc($sql)){
			$ret[] = $fetch;		
		}
		return $ret;
	}
}


/**
 * Display
 */
$message = new Message();
$get = isset($_GET['get'])?trim($_GET['get']):"";
switch($get) {
	case 'message':	
		header('Content-type: text/json');
		header('Content-type: application/json');
		$data = array();
		$msg = $message->listMessage();
		$i = 0;
		foreach($msg as $m) { 
			$data[] = array(
				$m['sender'],
				$m['reciever'],
				$m['datetime'],
				$m['message'],
				'<a href="?action=admin_view_messages&username='.$m['sender'].'&username2='.$m['reciever'].'" class="admin-link" onclick="window.open(\'?action=admin_view_messages&display=part&username='.$m['sender'].'&username2='.$m['reciever'].'\',null, \'height=600,width=800,status=yes,toolbar=no,menubar=no,location=no\'); return false;">View</a>'
			);
			$i++;
		}
		echo(json_encode(array(
				"iTotalRecords" => $i,
				"iTotalDisplayRecords" => $message->countMessages(),
				'aaData' => $data
		)));
		break;
	default:
		$smarty->display('admin.tpl');
		break;
}