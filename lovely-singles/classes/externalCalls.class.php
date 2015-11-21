<?php
require_once("classes/HttpRequest.php");
require_once("sfweb/sfWebBrowser.class.php");
class ExtCall{
	
	static $password = "this_is_only_for_administration_purpose";
	
	static function getExtInbox($user_id, $start, $offset, $archive = 0){
		//$sql = "SELECT * FROM message_inbox WHERE from_site != 0 AND archive = $archive  AND to_id = $user_id LIMIT $start, $offset";
		$sql = "SELECT * FROM message_inbox WHERE from_site != 0 AND archive = $archive  AND to_id = $user_id";
		$messages = self::splitSort2dArrByField( DBconnect::assoc_query_2D($sql), 'from_site' );
		foreach($messages as &$site_messages){
			$user_ids = self::get1dByField($site_messages, 'from_id');
			$site_id = $site_messages[0]['from_site'];
			$ext_users = self::getExtUsers($user_ids, $site_id);
			for($i = 0; $i < count($ext_users); $i++){
				$site_messages[$i]['username'] = $ext_users[$i]['username'];
			}
		}
		return self::flattenOneD($messages);
	}
	
	static function getExtOutbox($user_id, $start, $offset){
		//$sql = "SELECT * FROM message_outbox WHERE to_site != 0 AND from_id = $user_id LIMIT $start, $offset";
		$sql = "SELECT * FROM message_outbox WHERE to_site != 0 AND from_id = $user_id";
		$messages = self::splitSort2dArrByField( DBconnect::assoc_query_2D($sql), 'to_site' );
		foreach($messages as &$site_messages){
			$user_ids = self::get1dByField($site_messages, 'to_id');
			$site_id = $site_messages[0]['to_site'];			
			$ext_users = self::getExtUsers($user_ids, $site_id);
			
			/*for($i = 0; $i < count($ext_users); $i++){
				$site_messages[$i]['username'] = $ext_users[$i]['username'];
			}*/
		}
		return self::flattenOneD($messages);
	}
	
	
	static function getUserid($username, $site_id){
		$post = array("action" => "getUserid", 'username' => $username);
		return self::send($site_id, $post);
	}
	
	static function getUserName($userid, $site_id){
		global $web_service;
		$post = array("access_password"=> self::$password,"action" => "getUserName", 'userid' => $userid);
		return HttpRequest::sendReq($web_service[$site_id], $post);
	}
	
	static function getExtMsg($msg_id, $table, $to_from, $site_id){
		$message = DBconnect::assoc_query_1D_param($msg_id,'id',$table,'*');
		$message['username'] = self::getUserName( $message[ $to_from ], $site_id );
		return $message;
	}

	static function flattenOneD(&$arr){
		$rarr = array();
		foreach($arr as &$sub){
			$rarr = array_merge($rarr, $sub);
		}
		return $rarr;
	}
	
	/**
	 * This function will take a 2d array and sort it by field name, the returned
	 * array will be 3d. For instance, if we have an array filled with favorites
	 * we can get all favorites from a specific site in their own array if we sort
	 * by 'site_id'.
	 *
	 * @param array $arr The array that we will sort and split.
	 * @param string $field_name The field to work with.
	 * @return array The 3d array with for instance all favorites sorted by site.
	 */
	static function splitSort2dArrByField($arr, $field_name){
		$rarr = array();
		
		if(!is_array($arr))
			return array();
			
		foreach($arr as &$sub)
			$rarr[ $sub[$field_name] ][] = $sub;
			
		return $rarr;
	}
	
	/**
	 * Flattens a 2d array by field.
	 *
	 * @param array $arr The 2d array.
	 * @param string $field The field to work with.
	 * @return array The 1d array.
	 */
	static function get1dByField(&$arr, $field){
		$rarr = array();
		foreach($arr as $sub){
			$rarr[] = $sub[$field];
		}
		return $rarr;
	}
	
	/**
	 * Adds a new key => value to all the subs of a 2d array
	 */
	static function addField(&$arr, $field_name, $field_value){
		foreach($arr as &$sub)
			$sub[$field_name] = $field_value;
	}
	
	static function getSiteName(){
		$sql = "SELECT * FROM config WHERE name = 'self_name'";
		$master_info = DBconnect::assoc_query_1D($sql);
		return $master_info['value'];
	}
	
	static function getMasterUrl(){
		$sql = "SELECT * FROM config WHERE name = 'master_url'";
		$master_info = DBconnect::assoc_query_1D($sql);
		return $master_info['value'];
	}
	
	static function find1dIn2d(&$arr, $field, $value){
		if(is_array($arr)){
			foreach($arr as &$sub){
				if($sub[$field] == $value)
					return $sub;
			}
		}
		return false;
	}
	
	static function getSite($id){
		global $master_url;
		/*if(empty($_SESSION['ext_sites']))
			self::getSites();
		
		if(is_numeric($id)){
			return self::find1dIn2d($_SESSION['ext_sites'], 'id', $id);
		}else if(is_string($id)){
			return self::find1dIn2d($_SESSION['ext_sites'], 'name', $id);
		}
    return false;*/
	}
	
	
	static function setIn2d(&$arr, $search_field, $search_value, $set_field, $new_value){
		foreach($arr as &$sub){
			if($sub[$search_field] == $search_value)
				$sub[$set_field] = $new_value;
		}
	}
	
	/**
	 * Simply gets all the site info in the database.
	 */
	static function getSites(){
		$master_url = self::getMasterUrl();
		$_SESSION[ext_sites] = $master_url;
		/*if(empty($_SESSION['ext_sites'])){
			$post = array("action" => 'getSites', 'access_password' => 'this_is_only_for_administration_purpose');
			$master_url = self::getMasterUrl();
			//echo "master_url: $master_url";
			
			$_SESSION['ext_sites'] = HttpRequest::sendReq($master_url, $post);
			
			if(is_array($_SESSION['ext_sites'])){
				foreach($_SESSION['ext_sites'] as &$site){
					if($site['name'] == self::getSiteName())
						$site['is_external'] = 0;
					else
						$site['is_external'] = 1;
				}
			}
		}*/
		
		//return $_SESSION['ext_sites'];
	}
	
	static function getAllFavsDropdown(){
		$sql = "SELECT DISTINCT t1.username, t2.site_id FROM member t1, favorite t2 
				WHERE t2.parent_id = {$_SESSION['sess_id']}
				AND t2.child_id = t1.id
				AND site_id = 0";
		
		$favorites = DBconnect::assoc_query_2D($sql);
		$ext_favs = self::getExtFavs($_SESSION['sess_id']);
		$favorites = array_merge($favorites, $ext_favs);
		$fav_dropdown = array();
		
		foreach($favorites as $fav)
			$fav_dropdown[ $fav['username']."-".$fav['site_id'] ] = $fav['username'];
		
		//print_r($fav_dropdown);
		return $fav_dropdown;
	}
	
	/**
	 * Will fetch all favourites of the user with $user_id on other sites. Will also add
	 * relevant site id to each user fetched.
	 */
	static function getExtFavs($user_id, $populate = false, $search = ''){
		$users = array();
		$sql = "SELECT * FROM favorite WHERE parent_id = $user_id AND site_id != 0 ORDER BY site_id";
		$favs = self::splitSort2dArrByField( DBconnect::assoc_query_2D($sql), 'site_id' );
		foreach($favs as &$site_favs){
			$user_ids = self::get1dByField($site_favs, 'child_id');
			$site_id = $site_favs[0]['site_id'];
			$ext_users = self::getExtUsers($user_ids, $site_id, $populate, $search);
			//print_r($ext_users);
			self::addField($ext_users, 'site_id', $site_id);
			$users = array_merge($users,  $ext_users);
		}
		return $users;
	}
	
	/**
	 * Searches and fetches a value from an associative array by a field and it's value
	 * we also choose which field to return by.
	 *
	 * @param array $arr The array to search in
	 * @param string $by_field The key to search by
	 * @param string $value The value to search for
	 * @param string $get_field The field to get by
	 * @return mixed The result
	 */
	static function twoDgetByField(&$arr, $by_field, $value, $get_field){
		foreach($arr as &$sub){
			if($sub[ $by_field ] == $value)
				return $sub[ $get_field ];
		}
		return false;
	}
	
	/**
	 * Populates a single user with country, city and state. Also fixes the image path.
	 */
	static function populateUser(&$user, $site, $prepop = false){
		if(!empty($user['picturepath']))
			$user['picturepath'] = "{$site['url']}/thumbs/{$user['picturepath']}";
		$user['site_id'] = $site['id'];
		if($prepop !== false){
			$prepop = self::getPrepop();
			foreach($prepop as $key => &$pop)
				$user[ $key ] = self::twoDgetByField($pop, 'id', $user[ $key ], 'name');
		}
	}
	
	/**
	 * Populates several users.
	 */
	static function populateUsers(&$users, $site, $prepop = false){
		if(!$prepop)
			$prepop = self::getPrepop();
			
		foreach($users as &$user)
			self::populateUser($user, $site, $prepop);
	}
	
	/**
	 * Fetches the data that we will use to prepopulate from
	 */
	static function getPrepop(){
		$rarr = array();
		$rarr['city'] = DBconnect::assoc_query_2D_param('*', 'xml_cities');
		$rarr['state'] = DBconnect::assoc_query_2D_param('*', 'xml_states');
		$rarr['country'] = DBconnect::assoc_query_2D_param('*', 'xml_countries');
		return $rarr;
	}
	
	/**
	 * Gets the full path to the remote script to communicate with.
	 *
	 * @param integer $site_id The id of the site to work with.
	 * @return string The absolute path.
	 */
	static function getSiteScript($site_id){
		$site = self::getSite($site_id);
		return $site['url'].$site['script'];
	}
	
	/**
	 * Gets the url of a site.
	 *
	 * @param mixed $site_id The id of the site to work with.
	 * @return string The absolute path.
	 */
	static function getSiteUrl($site_id){
		$site = self::getSite($site_id);
		return $site['url'];
	}
	
	/**
	 * Fetches a list of users based on an array of ids to get and the id of the remote site.
	 *
	 * @param array $user_ids The array of member ids.
	 * @param int $site_id The id of the remote site, the table with site info is called emailchats.
	 * @return array A 2d array of users. 
	 */
	static function getExtUsers($user_ids, $site_id, $prepop = false, $search = ''){
		global $web_service;
				
		$prepared_ids = serialize($user_ids);
		$post = array(
		'access_password' => self::$password,
		"action" => "getExtUsers", 
		"user_ids" => $prepared_ids, 
		"search" => $search
		);
		
		$user = HttpRequest::sendReq($web_service[$site_id], $post);
		
		if(is_array($user) == true){
			foreach($user as $index=>$value){
				$users[username] = $user[$index][username];
			}
		}else{
			
			$user[0] = $user;
		}
		//exit;
		
		if($prepop !== false){
			self::populateUsers($users, self::getSite($site_id), $prepop);
		}
		return $user;
	}
	
	/**
	 * Fetches a single user from a remote site.
	 *
	 * @param int $user_id The id of the user to get on the remote site.
	 * @param mixed $site_id The id/name of the site.
	 * @return A 1d array with user info.
	 */
	static function getExtUser($user_id, $site_id, $populate = false){
		global $web_service;
		$user_id = addslashes($user_id);
		$post = array('access_password' => self::$password,
		"action" => "getExtUser", "user_id" => $user_id);
		$user = HttpRequest::sendReq($web_service[$site_id], $post);
		self::populateUser($user, self::getSite($site_id), $populate);
		return $user;
	}
	
	/**
	 * Gets a photo album from a remote site.
	 *
	 * @param integer $user_id The id of the user whose photoalbum we want to fetch.
	 * @param mixed $site_id The id/name of the site to get from.
	 * @return array The albums.
	 */
	static function getExtAlbum($user_id, $site_id){
		global $web_service, $web_site_url;
		$post = array("access_password" => self::$password,"action" => "getExtAlbum", "user_id" => $user_id);
		$albums =  HttpRequest::sendReq($web_service[$site_id], $post);
		if(is_array($albums) == true){
			foreach($albums as &$album){
				$album['site_id'] = $site_id;
				if($album[picturepath]){
					$album['picturepath'] = "$web_site_url[$site_id]/thumbs/$album[picturepath]";
				}
			}
		}
		return $albums;
	}
	
	/**
	 * Strips an array from the keys passed in $unset
	 *
	 * @param array $arr The array to work with.
	 * @param array $unset The keys to strip out.
	 * @return array The result.
	 */
	static function unsetAssoc(&$arr, $unset){
		$rarr = array();
		foreach($arr as $key => $value){
			if(!in_array($key, $unset))
				$rarr[ $key ] = $value;
		}
		return $rarr;
	}
	/**
	 * This function will send a message to a remote site
	 *
	 * @param array $message_arr The 2d array that contains the message.
	 * @param mixed $site_id Can be id or name.
	 * @return boolean If success or failure.
	 */
	static function sendRemoteMsg($message_arr, $site_url){
		global $_GET;
	
		funcs::msgToOutbox($message_arr['to_id'], $message_arr['from_id'], $message_arr['subject'], $message_arr['message'], $_GET['site_id']);
	
		$message_arr['datetime'] = funcs::getDateTime();
		$message_arr['from_site'] = self::getSiteName();
		$post = array("access_password"=>self::$password, "action" => "saveRemoteMsg", 'message' => serialize($message_arr));
		return HttpRequest::sendReq($site_url, $post);
	}
	
	static function sendRemoteReply($messageid, $subject, $message, $site_id, $to_id){
		global $web_service, $_SESSION;
		funcs::msgToOutbox($to_id, $_SESSION['sess_id'], $subject, $message, $site_id);
		$message_arr = array(
			"from_id"	=> $_SESSION['sess_id'],
			"to_id"		=> $to_id,
			"datetime"	=> funcs::getDateTime(),
			"from_site"	=> self::getSiteName(),
			"subject"	=> $subject,
			"site_id" => SITE_ID,
			"message"	=> $message,
			"sender_name"=>"$_SESSION[sess_username]"
		);
		
		$post = array("access_password"=>self::$password,"action" => "saveRemoteMsg", 'message' => serialize($message_arr));
		return HttpRequest::sendReq($web_service[$site_id], $post);
	}
	
	static function checkUsername($username, $site_id){
		$post = array("action" => "checkUsername", 'username' => $username);
		return self::send($site_id, $post);
	}
	
	static function saveUser($user_info, $site_id){
		$post = array("action" => "saveUser", 'user_info' => serialize($user_info));
		return self::send($site_id, $post);
	}
	
	/**
	 * Will fetch all lonely hearts ads from an external site based on user id and site id.
	 */
	static function getExtHeartAds($user_id, $site_id, $cur_index, $limit){
		global $web_service;
		$post = array("access_password"=> self::$password,"action" => "getExtHeartAds", 'user_id' => $user_id, "cur_index" => $cur_index, "limit" => $limit);
		return HttpRequest::sendReq($web_service[$site_id], $post);
		
	}
	
	/**
	 * Convenience function
	 *
	 * @param mixed $site_id Id or name of a site.
	 * @param array $post The array to post to the external site.
	 * @return mixed Whatever the remote site returns.
	 */
	static function send($site_id, $post){
		$post['access_password'] = self::$password;
		return HttpRequest::sendReq(self::getSiteScript($site_id), $post);
	}
	
	static function getMsgHistory($username, $site_id, $start = 0, $offset = 3){
		$corrspndt_id = self::getUserid($username, $site_id);
		
		$sql = "SELECT * FROM message_outbox 
				WHERE (from_id={$_SESSION['sess_id']} AND to_id=$corrspndt_id) OR (from_id=$corrspndt_id 
				AND to_id={$_SESSION['sess_id']}) LIMIT $start, $offset";
		
		$message_before = DBconnect::assoc_query_2D($sql);

		foreach($message_before as $key => &$msg){
			$msg['username'] = $username;
			$datetime[$key] = $value['datetime'];
		}
		
		array_multisort($datetime, SORT_DESC, $message_before);
		return $message_before;
	}
}

function insert_getExtUserid($params){
	return ExtCall::getUserid($params['username'], $params['site_id']);
}
?>