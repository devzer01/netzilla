<?php

require_once 'lib/dbo.php';

class dbo_member extends dbo {
	
	private $member_id = 0;
	
	function isEmail($email)
	{
		$sql = "SELECT email FROM member WHERE email = :email ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':email' => $email));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function changePassword($username, $old, $new)
	{
		$sql = "UPDATE member SET password = :new WHERE username = :username AND password = :old ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':new' => $new, ':username' => $username, ':old' => $old));
		
		if ($sth->rowCount() === 0) return false;
		return true;
	}
	
	function setFirstLast($username, $first, $last)
	{
		$sql = "UPDATE member SET forname = :first, surname = :last WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':first' => $first, ':last' => $last));
		if ($sth->rowCount() === 0) return false;
		return true;
	}
	
	function setFacebookParams($username, $fb_id, $fb_user, $fb_token)
	{
		$sql = "UPDATE member SET facebook_id = :fb_id, facebook_username = :fb_user, facebook_token = :fb_token WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':fb_id' => $fb_id, ':fb_user' => $fb_user, ':fb_token' => $fb_token));
		if ($sth->rowCount() === 0) return false;
		return true;
	}
	
	function setDescription($username, $description)
	{
		$sql = "UPDATE member SET description = :desc WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':desc' => $description));
		if ($sth->rowCount() === 0) return false;
		return true;
	}
	
	function setGeoGraphy($username, $country, $state, $city)
	{
		$sql = "UPDATE member SET country = :cnt, state = :state, city = :city WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':cnt' => $country, ':state' => $state, ':city' => $city));
		if ($sth->rowCount() === 0) return false;
		return true;
	}
	
	function setPendingProfilePic($username, $picturepath)
	{
		$userid = $this->getId($username);
		
		$sql = "DELETE FROM phototemp WHERE status = 1 AND userid = :userid";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':userid' => $userid));
		
		$sql = "INSERT INTO phototemp (userid, picturepath, datetime, status, site) VALUES (:userid, :picturepath, NOW(), 1, 1) ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':userid' => $userid, ':picturepath' => $picturepath));
	}
	
	function isUsername($username)
	{
		$sql = "SELECT username FROM member WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function addMember($username, $password, $email, $dob, $gender, $country)
	{
		$sql = "INSERT INTO member (username, password, email, birthday, gender, country, zodiac, lookmen, lookwomen, signup_datetime, ref, isactive, type, validation_code, mobile) "
		     . "VALUES (:username, :password, :email, :dob, :gender, :country, :zodiac, :lookmen, :lookwomen, :signup_datetime, :ref, 0, 4, :validation_code, 1) ";
		
		//FIXME: adding some hacks in the db layer to speed up version one development
		$lookmen = 0;
		$lookwomen = 0;
		
		$ref = "";
		
		if (isset($_SESSION['ref'])) {
			$ref = $_SESSION['ref'];
		}
		
		if ($ref == "" && isset($_COOKIE['ref'])) {
			$ref = $_COOKIE['ref'];
		}
		
		if ($gender == 1) $lookwomen = 1;
		else if ($gender == 2) $lookmen = 1;
		
		$zodiac = getZodiac($dob);
		$signup_time = getDateTime();
		
		$validation_code = randomPassword(6); //TODO: add constant for random password length
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(
					':username' => $username, ':password' => $password, ':email' => $email, ':dob' => $dob,
					':gender' => $gender, ':country' => $country, ':zodiac' => $zodiac, ':lookmen' => $lookmen, 
					':lookwomen' => $lookwomen, ':signup_datetime' => $signup_time, ':ref' => $ref,
					':validation_code' => $validation_code
				));
		
		$this->member_id = $this->dbo->lastInsertId();
		return $this->member_id;
	}
	
	function isCode($username, $code)
	{
		$sql = "SELECT validation_code FROM member WHERE username = :username AND validation_code = :code ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':code' => $code));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function getMemberByFacebookId($fb_id)
	{
		$sql = "SELECT username FROM member WHERE facebook_id = :id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $fb_id));
		
		if ($sth->rowCount() === 0) return false;
		
		return $this->getBasicMemberInfo($sth->fetch()['username']);
	}
	
	function getMemberByEmail($email)
	{
		$sql = "SELECT username FROM member WHERE email = :email ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':email' => $email));
		
		if ($sth->rowCount() === 0) return false;
		
		return $this->getBasicMemberInfo($sth->fetch()['username']);
	}
	
	function setMemberIp($username, $ip)
	{
		$sql = "UPDATE member SET ip_address = :ip WHERE username = :username ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':ip' => $ip, ':username' => $username));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function activateMember($username, $password, $code)
	{
		$sql = "UPDATE member SET isactive = 1, isactive_datetime = NOW() WHERE username = :username AND password = :password AND validation_code = :code ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':password' => $password, ':code' => $code));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function setCoin($username, $coin)
	{
		$sql = "UPDATE member SET coin = :coin WHERE username = :username ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':coin' => $coin));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	/**
	 * 
	 * @param unknown $username
	 * @param string $password
	 * @param string $code
	 * @return boolean
	 */
	function isActive($username, $password = null, $code = null)
	{
		if ($password === null && $code === null) {
			$sql = "SELECT isactive FROM member WHERE username = :username ";
			$sth = $this->dbo->prepare($sql);
			$sth->execute(array(':username' => $username));
			
			if ($sth->rowCount() == 0) return false;
			
			return ($sth->fetch()['isactive'] == 1);
		}
		
		$sql = "SELECT isactive FROM member WHERE username = :username AND password = :password AND validation_code = :code ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':password' => $password, ':code' => $code));
			
		if ($sth->rowCount() == 0) return false;
			
		return ($sth->fetch()['isactive'] == 1);
	}
	
	function getFullMemberInfo($username)
	{
		$sql = "SELECT m.username, m.password, m.validation_code, m.city, m.country, m.gender, m.birthday, m.fake, m.state, m.email, "
			 . "m.picturepath, m.description, m.lookmen, m.lookwomen, xc.name_de AS country_name, xs.name_de AS state_name, "
			 . "xci.name_de AS city_name "
			 . "FROM member AS m "
			 . "LEFT JOIN xml_countries AS xc ON xc.id = m.country "
			 . "LEFT JOIN xml_states AS xs ON xs.id = m.state "
			 . "LEFT JOIN xml_cities AS xci ON xci.id = m.city "
			 . "WHERE m.username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch();
	}
	
	function getFavorites($username)
	{
		$id = $this->getId($username);
		$sql = "SELECT username, picturepath FROM member WHERE id IN (SELECT child_id FROM favorite WHERE parent_id = :id) ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id));
		
		if ($sth->rowCount() == 0) return false;
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function isFavorite($username, $favorite)
	{
		$p = $this->getId($username);
		$c = $this->getId($favorite);
		
		$sql = "SELECT * FROM favorite WHERE parent_id = :p AND child_id = :c ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':p' => $p, ':c' => $c));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function addFavorite($username, $favorite)
	{
		$p = $this->getId($username);
		$c = $this->getId($favorite);
		
		$sql = "INSERT INTO favorite SET parent_id = :p, child_id = :c ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':p' => $p, ':c' => $c));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function removeFavorite($username, $favorite)
	{
		$p = $this->getId($username);
		$c = $this->getId($favorite);
		
		$sql = "DELETE FROM favorite WHERE parent_id = :p AND child_id = :c ";

		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':p' => $p, ':c' => $c));
		
		if ($sth->rowCount() == 0) return false;
		
		return true;
	}
	
	function removeFoto($username, $id)
	{
		$uid = $this->getId($username);
	
		$sql = "DELETE FROM fotoalbum WHERE id = :id AND userid = :uid ";
	
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id, ':uid' => $uid));
	
		if ($sth->rowCount() == 0) return false;
	
		return true;
	}
	
	function removeTempFoto($username, $id)
	{
		$uid = $this->getId($username);
	
		$sql = "DELETE FROM phototemp WHERE id = :id AND userid = :uid ";
	
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id, ':uid' => $uid));
	
		if ($sth->rowCount() == 0) return false;
	
		return true;
	}
	
	function isNewUsername($id, $new_username)
	{
		$sql = "SELECT id FROM member WHERE id != :id AND username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id, ':username' => $new_username));
		
		if ($sth->rowCount() === 0) return false;
		
		return true;
	}
	
	function setConfirmUsername($id, $username)
	{
		$sql = "UPDATE member SET username = :username, username_confirmed = 1 WHERE id = :id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id, ':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return true;
	}
	
	function getMemberByUserOrEmail($useremail)
	{
		$sql = "SELECT username FROM member WHERE username = :user OR email = :email ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':user' => $useremail, ':email' => $useremail));
		
		if ($sth->rowCount() === 0) return false;
		
		return $this->getBasicMemberInfo($sth->fetch(PDO::FETCH_ASSOC)['username']);
	}
	
	function getBasicMemberInfo($username)
	{
		$sql = "SELECT username, password, validation_code, city, country, gender, birthday, fake, state, email, picturepath, description, lookmen, lookwomen, isactive, facebook_username, forname, surname FROM member WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch();
	}
	
	function updateBasicMemberInfo($username, $gender, $birthday, $country, $state, $city, $lookmen, $lookwomen, $description)
	{
		$sql = "UPDATE member SET gender = :gender, birthday = :birthday, country = :country, state = :state, city = :city, "
			 . " lookmen = :lookmen, lookwomen = :lookwomen, description = :description "
			 . "WHERE username = :username ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender, ':birthday' => $birthday, ':country' => $country, ':state' => $state,
				':city' => $city, ':lookmen' => $lookmen, ':lookwomen' => $lookwomen, ':description' => $description, 
				':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return true;
	}
	
	function getPendingProfilePicture($username)
	{
		$id = $this->getId($username);
		
		$sql = "SELECT picturepath FROM phototemp WHERE userid = :id AND status = 1 ORDER BY id DESC ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['picturepath'];
	}
	
	function setPendingProfileDesc($username, $description)
	{
		$id = $this->getId($username);
		
		$sql = "DELETE FROM description_temp WHERE userid = :id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id));
		
		$sql = "INSERT INTO description_temp (userid, description, datetime) "
			 . " VALUES (:id, :desc, NOW() ) ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id, ':desc' => $description));
	}
	
	function getPendingProfileDesc($username)
	{
		$id = $this->getId($username);
	
		$sql = "SELECT description FROM description_temp WHERE userid = :id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id));
	
		if ($sth->rowCount() === 0) return false;
	
		return $sth->fetch()['description'];
	}
	
	function getEmail($username)
	{
		$sql = "SELECT email FROM member WHERE username = :username ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['email'];
	}
	
	function getId($username)
	{
		$sql = "SELECT id FROM member WHERE username = :username ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['id'];
	}
	
	function getUsername($id)
	{
		$sql = "SELECT username FROM member WHERE id = :id ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['username'];
	}
	
	function getCoins($username)
	{
		$sql = "SELECT coin FROM member WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['coin'];
	}
	
	function deductCoin($username, $coin)
	{
		$sql = "UPDATE member SET coin = coin - :coin WHERE username = :username ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':coin' => intval($coin), ':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return true;
	}
	
	function addCoinLog($member_id, $send_to, $coin_field, $coin_minus, $coin_remain)
	{
		$sql = "INSERT INTO coin_log(member_id, send_to, coin_field, coin, coin_remain, log_date) "
			 . "VALUES (:member_id, :send_to, :coin_field, :coin_minus, :coin_remain, NOW()) ";
		$sth = $this->dbo->prepare($sql);
		
		$sth->execute(array(
				':member_id' => $member_id, ':send_to' => $send_to, ':coin_field' => $coin_field,
				':coin_minus' => '-' . $coin_minus, ':coin_remain' => $coin_remain
			));
		
		return true;
	}
	
	/**
	 * 
	 * check if the username passed in is a real member or not
	 * 
	 * @param string $username
	 * @return boolean
	 */
	function isFake($username)
	{
		return ($this->getBasicMemberInfo($username)['fake'] == 1);
	}
	
	/**
	 * 
	 * @param string $username
	 */
	function setFirstMessage($username)
	{
		$sql = "UPDATE member SET first_message_sent = 1 WHERE username = :username AND first_message_sent != 1 ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		return true;
	}
	
	function setLastFromTo($from, $to)
	{
		$sql = "UPDATE member SET last_action_to = NOW() WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $to));
		
		$sql = "UPDATE member SET last_action_from = NOW() WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $from));
	}
	
	function getPaymentData($username)
	{
		$sql = "SELECT id, type, payment, payment_received, DATEDIFF(payment, CURDATE()) AS expires FROM member WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch();
	}
	
	function setSignIn($username)
	{
		$sql = "UPDATE member SET signin_datetime = NOW() WHERE username = :username ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return false;
		
		return true;
	}
	
	function loginSite($username, $password)
	{
		$sql = "SELECT *, ((now() - interval 1 hour) > signup_datetime) AS tcheck "
		     . "FROM member "
		     . "WHERE username = :username AND password = :password ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username, ':password' => $password));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch();
	}
	
	function getFakeMemberYoug($gender, $country, $city, $birthday, $rand_year = 5)
	{
		$year = date("Y", strtotime($birthday));
		$from = $year . "-01-01";
		
		$to = rand($year, $year+$rand_year) . "-12-31";
		
		$sql = "SELECT username FROM member WHERE fake=1 AND gender = :gender and picturepath != '' "
		     . "AND country = :country AND city != :city AND birthday BETWEEN :from AND :to ORDER BY last_action_from ASC LIMIT 1";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender, ':country' => $country, ':city' => $city, ':from' => $from, ':to' => $to));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['username'];
	}
	
	function getFakeMemberOld($gender, $country, $city, $birthday, $rand_year = 5)
	{
		$year = date("Y", strtotime($birthday)) - 4;
		$from = $year . "-01-01";
		
		$to = rand($year, $year+$rand_year) . "-12-31";
		
		$sql = "SELECT username FROM member WHERE fake=1 AND gender = :gender and picturepath != '' "
		. "AND country = :country AND city != :city AND birthday BETWEEN :from AND :to ORDER BY last_action_from ASC LIMIT 1";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender, ':country' => $country, ':city' => $city, ':from' => $from, ':to' => $to));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['username'];
	}
	
	function getFakeMemberState($gender, $country, $state, $city)
	{
		$sql = "SELECT username FROM member WHERE fake = 1 and gender = :gender and picturepath != '' AND country = :country AND state = :state AND city != :city ORDER BY last_action_from ASC LIMIT 1";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender, ':country' => $country, ':city' => $city, ':state' => $state));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['username'];
	}
	
	function getFakeMemberCountry($gender, $country)
	{
		$sql = "SELECT username FROM member WHERE fake = 1 and gender = :gender and picturepath != '' AND country = :country ORDER BY last_action_from ASC LIMIT 1";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender, ':country' => $country));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['username'];
	}
	
	function getOnlineMembers()
	{
		$sql = "SELECT id, username, picturepath FROM member m LEFT JOIN member_session s ON m.id=s.member_id WHERE m.isactive=1 AND m.id>3 AND picturepath != '' AND s.last_action_datetime > NOW() - INTERVAL 5 MINUTE ORDER BY RAND() LIMIT 40";
		$sth = $this->dbo->prepare($sql);
		$sth->execute();
		
		if ($sth->rowCount() === 0) return [];
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getPhotoAlbum($username)
	{
		$sql = "SELECT id, picturepath FROM fotoalbum WHERE userid = (SELECT id FROM member WHERE username = :username) ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username));
		
		if ($sth->rowCount() === 0) return [];
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getTempPhotoAlbum($username)
	{
		$sql = "SELECT * FROM phototemp WHERE userid = (SELECT id FROM member WHERE username = :username) AND status = 2 "
             . "ORDER BY datetime ";
    	$sth = $this->dbo->prepare($sql);
    	$sth->execute(array(':username' => $username));
    	
    	if ($sth->rowCount() == 0) return [];
    	
    	return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function advanceSearch($gender, $minbirth, $maxbirth, $country, $state, $city)
	{
		$sql = "SELECT id, username, picturepath FROM member WHERE gender = :g AND birthday BETWEEN :min AND :max AND country = :c AND state = :s AND city = :city AND isactive = 1 ORDER BY if(picturepath = '',1,0), fake, birthday DESC, last_action_to LIMIT 40";
		$params = array(':g' => $gender, ':max' => $minbirth, ':min' => $maxbirth, ':c' => $country, ':s' => $state, ':city' => $city);
		
		if ($country == "" && $state == "" && $city == "") {
			unset($params[':c']);
			unset($params[':city']);
			unset($params[':s']);
			$sql = "SELECT id, username, picturepath FROM member WHERE gender = :g AND birthday BETWEEN :min AND :max AND isactive = 1 ORDER BY if(picturepath = '',1,0), fake, birthday DESC, last_action_to LIMIT 40";
		} else if ($state == "" && $city == "") {
			unset($params[':city']);
			unset($params[':s']);
			$sql = "SELECT id, username, picturepath FROM member WHERE gender = :g AND birthday BETWEEN :min AND :max AND country = :c AND isactive = 1 ORDER BY if(picturepath = '',1,0), fake, birthday DESC, last_action_to LIMIT 40";
		} else if ($city == "") {
			unset($params[':city']);
			$sql = "SELECT id, username, picturepath FROM member WHERE gender = :g AND birthday BETWEEN :min AND :max AND country = :c AND state = :s AND isactive = 1 ORDER BY if(picturepath = '',1,0), fake, birthday DESC, last_action_to LIMIT 40";
		}
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute($params);
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getMembersBySpec($gender, $lookmen, $lookwomen)
	{
		$sql = "SELECT id, username, picturepath FROM member "
			 . "WHERE gender = :gender AND lookmen = :lookmen AND lookwomen = :lookwomen AND isactive = 1 ORDER BY if(picturepath = '',1,0), fake, birthday DESC, last_action_to LIMIT 40";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender, ':lookmen' => $lookmen, ':lookwomen' => $lookwomen));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getFakeMembersByGender($gender)
	{
		$sql = "SELECT id, username, picturepath FROM member WHERE gender = :gender AND isactive = 1 AND fake = 1 AND picturepath != '' AND id > 3 ORDER BY RAND() LIMIT 40";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getMembersByGender($gender)
	{
		$sql = "SELECT username, picturepath FROM member WHERE gender = :gender AND picturepath != '' AND isactive = 1 AND id > 3 ORDER BY RAND() DESC LIMIT 40";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getMembersByUsername($username)
	{
		$sql = "SELECT username, picturepath FROM member WHERE username LIKE :username AND picturepath != '' AND isactive = 1 AND id > 3 LIMIT 40";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':username' => $username . "%"));
	
		if ($sth->rowCount() === 0) return [];
	
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getGifts($username)
	{
		$id = $this->getId($username);
		$sql = "SELECT mg.*, g.*, ms.username AS sender, ms.picturepath, COUNT( * ) AS times FROM `member_gift` AS mg "
		     . "LEFT JOIN `gift` AS g ON g.id = mg.gift_id "
			 . "LEFT JOIN `member` AS ms ON ms.id = mg.sender_id "
			 . "WHERE mg.member_id = :id GROUP BY g.id, mg.sender_id "
			 . "ORDER BY `mg`.`created` DESC,`times` DESC ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $id)); 
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getGiftToUser($sender, $receiver)
	{
		$rec = $this->getId($receiver);
		$send = $this->getId($sender);
		$sql = "SELECT COUNT(*) AS cnt, mg.*, g.*, ms.username AS sender FROM member_gift AS mg "
			 . "LEFT JOIN gift AS g ON g.id = mg.gift_id "
			 . "LEFT JOIN member AS ms ON ms.id = mg.sender_id "
			 . " WHERE mg.member_id = :rec AND mg.sender_id = :send GROUP BY g.id ";

		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':rec' => $rec, ':send' => $send));
		
		if ($sth->rowCount() === 0) return [];
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getSupportName()
	{
		$sql = "SELECT username FROM member WHERE id=2";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':gender' => $gender, ':country' => $country));
		
		if ($sth->rowCount() === 0) return false;
	
		return $sth->fetch()['username'];
	}
}