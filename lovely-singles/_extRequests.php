<?php
require_once('classes/top.class.php');
$access_password = "this_is_only_for_administration_purpose";

if($_POST['access_password'] == $access_password)
{
	switch($_POST['action'])
	{
		case 'get_payment':
			$sql = "SELECT t1.*, t2.id as member_id, t2.mobileno as member_mobileno, t2.email FROM payment_log t1, member t2 WHERE t1.username = t2.username";
			if(isset($_POST['id']) && ($_POST['id'] != ''))
			{
				$sql .= " AND t1.ID=".$_POST['id'];
			}
			elseif(isset($_POST['username']) && ($_POST['username'] != ''))
			{
				$sql .= " AND t1.real_name like '%".$_POST['username']."%'";
			}
			elseif(isset($_POST['nickname']) && ($_POST['nickname'] != ''))
			{
				$sql .= " AND t1.username like '%".$_POST['nickname']."%'";
			}			

			if(is_array($_POST['criteria']))
			{
				foreach($_POST['criteria'] as $key => $val)
				{
					if ($key == 'reminder')
						$sql .= " AND (t1.".$val;
					else 
						$sql .= " AND t1.".$key." = '".$val."'";
				}
			}
			
			/*$myFile = "testSQLFile.txt";
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $sql);
			fclose($fh);*/

			$sql .= " AND sum_paid != 0 ORDER BY t1.payday DESC";			
			
			$list = DBConnect::assoc_query_2D($sql);
			//$list['sql']=$sql;
			echo serialize($list);
			break;
		case 'set_finish':
			$old_paid_until = DBConnect::retrieve_value("SELECT DATE(old_paid_until) FROM ".TABLE_PAY_LOG." WHERE ID='".$_POST['id']."'");
			$interval = DBConnect::retrieve_value("SELECT DATEDIFF(old_paid_until,CURDATE()) FROM payment_log WHERE ID='".$_POST['id']."'");
			if($old_paid_until == "0000-00-00")
			{
				$interval = DBConnect::retrieve_value("SELECT DATEDIFF(payday,new_paid_until) FROM payment_log WHERE ID='".$_POST['id']."'");
				DBConnect::execute("UPDATE ".TABLE_PAY_LOG." SET payment_complete='1', payday=NOW(), new_paid_until=NOW() + INTERVAL ".$interval." DAY WHERE ID='".$_POST['id']."'");
			}
			elseif($interval >= 0)
			{
				DBConnect::execute("UPDATE ".TABLE_PAY_LOG." SET payment_complete='1', payday=NOW() WHERE ID='".$_POST['id']."'");
			}
			else
			{
				$interval = DBConnect::retrieve_value("SELECT DATEDIFF(new_paid_until,old_paid_until) FROM payment_log WHERE ID='".$_POST['id']."'");
				DBConnect::execute("UPDATE ".TABLE_PAY_LOG." SET payment_complete='1', payday=NOW(), new_paid_until=NOW() + INTERVAL ".$interval." DAY WHERE ID='".$_POST['id']."'");
			}
			$info = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_PAY_LOG." WHERE ID='".$_POST['id']."'");
			DBConnect::execute("UPDATE ".TABLE_MEMBER." SET payment_received=NOW(), type='".$info['new_type']."', payment='".$info['new_paid_until']."' WHERE username='".$info['username']."'");
			break;
		case 'set_cancel':
			$sqlusername = "SELECT username FROM ".TABLE_PAY_LOG." WHERE ID='".$_POST['id']."'";
			$username = DBConnect::retrieve_value($sqlusername);
			$info = DBConnect::assoc_query_1D("SELECT * FROM member WHERE username='{$username}'");
			$duration_type = funcs::checkForMembershipDurationType($info['payment_received'], $info['payment']);
			if($duration_type == 2)
			{
				DBConnect::execute("UPDATE ".TABLE_PAY_LOG." SET reminder_date=(new_paid_until - INTERVAL 6 DAY) WHERE ID='".$_POST['id']."'");
			}
			else
			{
				DBConnect::execute("UPDATE ".TABLE_PAY_LOG." SET recall='1' WHERE ID='".$_POST['id']."'");
				DBConnect::execute("UPDATE ".TABLE_MEMBER." SET type='4', payment_received = '', payment = '', in_storno = 1, flag = 1 WHERE username='".$username."'");
				$info = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_PAY_LOG." WHERE ID='".$_POST['id']."'");
				$sql = "INSERT INTO kto_blacklist SET username ='".$username."', blz = ".$info['bank_blz'].", kto = ".$info['bank_account'].", blacklist_date ='".funcs::getDate()."'";			
				DBConnect::execute($sql);
				echo serialize(1);
			}
			break;
		case 'reminder':
			$sql = "UPDATE ".TABLE_PAY_LOG." SET payment_complete = 1, recall='0', reminder_costs='".$_POST['cost']."', reminder_date='0000-00-00' WHERE ID='".$_POST['id']."'";
			DBConnect::execute($sql);
			$entry = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_PAY_LOG." WHERE ID='".$_POST['id']."'");
			$sql = "UPDATE ".TABLE_MEMBER." SET type='".$entry['new_type']."', payment_received = '".$entry['payday']."', payment = 'new_paid_until', in_storno = 0, flag = 0 WHERE username='".$entry['username']."'";
			DBConnect::execute($sql);
			break;
		case 'get_member_profile':
			$sql = "SELECT * FROM member WHERE id=".$_POST['id'];

			$list = DBConnect::assoc_query_1D($sql);
			echo serialize($list);
			break;
		case 'get_profile':
			$list = array();
			$select = "SELECT * FROM member WHERE";
			if(isset($_POST['option']) && ($_POST['option'] != ''))
				$sql = " {$_POST['option']}";
			else
				$sql = " fake='1' AND flag='1'";

			if(isset($_POST['id']) && ($_POST['id'] != ''))
				$sql .= " AND id=".$_POST['id'];
			elseif(isset($_POST['username']) && ($_POST['username'] != ''))
				$sql .= " AND username like '%".$_POST['username']."%'";
			$sql .= " ORDER BY id ASC";

			$list['list'] = DBConnect::assoc_query_2D($select.$sql." LIMIT {$_POST['start']}, {$_POST['num']}");
			$list['num'] = DBConnect::retrieve_value("SELECT COUNT(*) FROM member WHERE".$sql);
			if($list['num'] == 1)
			{
				$list['album'] = funcs::getAllFotoAlbum($_POST['id']);
				$list['lonely_ads'] = funcs::getAllLonely_Heart($_POST['id'], '', '');
				if(is_array($list['lonely_ads']))
				{
					foreach($list['lonely_ads'] as &$lonely_ads)
					{
						$lonely_ads['target_id'] = $lonely_ads['target'];
						$lonely_ads['category_id'] = $lonely_ads['category'];
						$lonely_ads['target'] = funcs::getAnswerChoice($_SESSION['lang'],'', '$targetGroup', $lonely_ads['target']);
						$lonely_ads['category'] = funcs::getAnswerChoice($_SESSION['lang'],'', '$category', $lonely_ads['category']);
					}
				}
				$list['targetGroup'] = funcs::getChoice($_SESSION['lang'],'','$targetGroup');
				$list['category'] = funcs::getChoice($_SESSION['lang'],'','$category');
			}
			echo serialize($list);
			break;
		case 'get_next_profile':
			$list = array();
			$select = "SELECT id";
			$sql = " FROM member WHERE fake='1' AND flag='1'";
			if(isset($_POST['username']) && ($_POST['username'] != ''))
			{
				$sql .= " AND username like '%".$_POST['username']."%'";
			}
			$sql .= " AND id > ".$_POST['id']." ORDER BY id ASC";

			$list = DBConnect::retrieve_value($select.$sql." LIMIT 1");
			echo serialize($list);
			break;
		case 'upload_image':
			funcs2::removePic_profile($_POST['id']);

			if(!is_dir("thumbs/".$_POST['id']))
				mkdir("thumbs/".$_POST['id'], 0777);
			if(!is_dir("thumbs/thumbs/".$_POST['id']))
				mkdir("thumbs/thumbs/".$_POST['id'], 0777);
			copy($_POST['src_path'].$_POST['pic1'], $_POST['pic1']);
			copy($_POST['src_path'].$_POST['pic2'], $_POST['pic2']);
			break;
		case 'upload_album_image':
			$userid = $_POST['id'];
			$file = $_POST['filename'];
			$data = funcs::getAllFotoAlbum($userid);
			if(count($data) < 4)
			{
				$file_name = time().'_'.$file['name'];
				$path = $userid.'/foto/';
				//echo UPLOAD_DIR.$path;
				if(!is_dir(UPLOAD_DIR.$path))
					mkdir(UPLOAD_DIR.$userid);
					mkdir(UPLOAD_DIR.$path);

				$path = $path;
				if(copy($_POST['src_path'].$_POST['pic'], UPLOAD_DIR.$path.$file_name))
				{
					$sql = "INSERT INTO ".TABLE_FOTOALBUM."
							SET ".TABLE_FOTOALBUM_USERID."=".$userid.",
							".TABLE_FOTOALBUM_PICTUREPATH."='".$path.$file_name."',
							".TABLE_FOTOALBUM_DATETIME."='".funcs::getDatetime()."'";

					return DBconnect::execute_q($sql);
				}
			}
			break;
		case 'save_profile':
			if(isset($_POST['save']['picturepath']) && ($_POST['save']['picturepath'] == ""))
				funcs2::removePic_profile($_POST['id']);

			if(($_POST['save']['headline'] != '') && ($_POST['save']['text'] != ''))
			{
				$ad['target'] = $_POST['save']['target'];
				$ad['category'] = $_POST['save']['category'];
				$ad['headline'] = $_POST['save']['headline'];
				$ad['text'] = $_POST['save']['text'];
				$ad['admin'] = 1;
				$ad['userid'] = $_POST['id'];
				$ad['datetime'] = date("Y-m-d H:i:s", time());
				funcs::addLonelyHeart($ad);
			}
			foreach($_POST['save']['delete_album'] as $id)
			{
				funcs::deleteFotoAlbum($id, $_POST['id']);
			}
			if(count($_POST['save']['lonely_heart_id']) > 0)
				funcs::deleteLonely_Heart($_POST['id'], $_POST['save']['lonely_heart_id']);
			if(count($_POST['save']['ads']) > 0)
			{
				foreach($_POST['save']['ads'] as $id => $data)
				{
					$sql = "UPDATE lonely_heart_ads SET target='".$data['target']."', category='".$data['category']."', headline='".$data['headline']."', text='".$data['text']."' WHERE userid=".$_POST['id']." AND id=".$id;
					DBconnect::execute_q($sql);
				}
			}
			funcs::updateProfile($_POST['id'], $_POST['save']);
			break;
		case 'getChoices':
			$temp = array(
				'gender' => funcs::getChoice($_SESSION['lang'],'','$gender'),
				'date' => funcs::getRangeAge(1,31),
				'month' => funcs::getChoice($_SESSION['lang'],'','$month'),
				'year' => funcs::getYear(90, 18),
				'appearance' => funcs::getChoice($_SESSION['lang'],'$nocomment','$appearance'),
				'eyescolor' => funcs::getChoice($_SESSION['lang'],'$nocomment','$eyes_color'),
				'haircolor' => funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_color'),
				'hairlength' => funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_length'),
				'beard' => funcs::getChoice($_SESSION['lang'],'$nocomment','$beard'),
				'zodiac' => funcs::getChoice($_SESSION['lang'],'$nocomment','$zodiac'),
				'status' => funcs::getChoice($_SESSION['lang'],'$nocomment','$status'),
				'sexuality' => funcs::getChoice($_SESSION['lang'],'$nocomment','$sexuality'),
				'age' => funcs::getRangeAge(18, 99),
				'yesno' => funcs::getChoice($_SESSION['lang'],'','$yesno')
			);

			echo serialize($temp);
			break;
		case 'search':
			if($_POST['criteria']['search_type'] == "2")
				echo serialize(search::getUsersList($_POST['criteria']));
			else
				echo serialize(search::getUsersAd($_POST['criteria']));
			break;
		case 'getExtUsers':
			$user_ids = unserialize( stripslashes($_POST['user_ids']) );
			$users = array();
			foreach($user_ids as $id){
				$sql = "SELECT * FROM member WHERE id = $id AND username LIKE '%{$_POST['search']}%'";
				$user = DBconnect::assoc_query_1D($sql);
				if($user != 0){
					$album = funcs::getAllFotoAlbum($user['id']);
					$user['have_album'] = empty($album) ? 0 : 1;
					unset($user['password']);
			    unset($user['email']);
					$users[] = $user;
				}
			}
			echo serialize($users);
			break;
		case 'getExtUser':
			$user = DBconnect::assoc_query_1D_param($_POST['user_id'],'username','member','*');
			$album = funcs::getAllFotoAlbum($user['id']);
			$user['have_album'] = empty($album) ? 0 : 1;
			unset($user['password']);
			unset($user['email']);
			echo serialize($user);
			break;
		case 'saveRemoteMsg':
			$message = unserialize( stripslashes($_POST['message']) );
			echo serialize(DBconnect::assoc_insert_1D($message, 'message_inbox'));
			break;
		case 'getExtHeartAds':
      		$userid = funcs::getUserid($_POST['user_id']);
      		$ads = funcs::getAllLonely_Heart($userid, $_POST['cur_index'], $_POST['limit']);
      		$total = funcs::getNumLonelyHeart($userid);
      		$return = array("ads" => $ads, "total" => $total);
			echo serialize( $return );
			break;
		case 'getExtAlbum':
      		$userid = funcs::getUserid($_POST['user_id']);
			$sql = "SELECT * FROM fotoalbum WHERE userid = $userid";
			echo serialize( DBconnect::assoc_query_2D($sql) );
			break;
		case 'getUserid':
      		$userid = funcs::getUserid($_POST['username']);
			echo serialize( $userid );
			break;
		case 'getUserName':
	      	$sql = "SELECT username FROM member WHERE id = {$_POST['userid']}";
	      	$username = DBconnect::retrieve_value($sql);
			echo serialize( $username );
			break;
		case 'checkUsername':
			echo serialize( funcs::isUsername( $_POST['username'] ) );
			break;
		case 'saveUser':
		      $user_info = unserialize( stripslashes($_POST['user_info']) );
		      $colnames = array_flip(DBconnect::get_col_names('member'));
		      $member_post = array_intersect_key($user_info, $colnames);
		      echo serialize( DBconnect::assoc_insert_1D($member_post, 'member') );
			break;
		case 'getSites':
		      $sites = DBconnect::assoc_query_2D_param('*', 'emailchats');
		      echo serialize( "getsites" );
		      break;
		case 'getPhotoTemp':
			  $photodata = funcs::getPhotoTemp($_POST[id]);
			  echo serialize( $photodata );
			  break;
		case 'getPhotoTempStatus':
			$phototypedata = funcs::getPhotoTempStatus($_POST[userid],$_POST[status]);
			  echo serialize( $phototypedata );
			  break;
		case 'getPhotoProfileTempOfEmailChatID':
			$photodata = funcs::getPhotoProfileTempOfEmailChatID($_POST[id]);
			  echo serialize( $photodata );
			  break;
		case 'getPhotoAlbumTempOfEmailChatID':
			$photodata = funcs::getPhotoAlbumTempOfEmailChatID($_POST[id]);
			  echo serialize( $photodata );
			  break;		
		case 'approvePhoto':
			funcs::approvePhoto($_POST[id],$_POST[ch_photo]);
			break;
		case 'deninePhoto':
			funcs::deninePhoto($_POST[id],$_POST[ch_photo]);
			break;
		case 'getPayList':
		    $list = funcs::getPayList($_POST[anfang], $_POST[ende], $_POST[jahr]);
		    echo serialize($list);
		    break;
		case 'getPayData':
		    $list = funcs::getPayData($_POST[date], $_POST[type]);
		    echo serialize($list);
		    break;
		case 'getUserStat':
		    $list = funcs::getUserStat($_POST[date], $_POST[type]);
		    echo serialize($list);
		    break;
		default:
			break;
	}
}
else
{
	echo "Error, you don't have permission to access this service.";
}
?>