<?php

include_once('DBconnect.php');
class Search{

  static function getExtra($next, $limit){
  	//$total_limit = $next + $limit;
  	$sql = "SELECT * FROM member ORDER BY picturepath DESC LIMIT $next, $limit";
  	return DBconnect::assoc_query_2D($sql);
  }
  
  
  //Einfache Suche:
  static function geProfile($username, $gender, $image, $country, $state, $city, $minage, $maxage, $next, $limit)
  {
      $sql1  = "select m.* from member as m where (m.isactive='1') and m.type != '1' and m.type != '9'";
      $sql2  = ($username!="")? " and (m.username = '$username')" : "";
      $sql3  = ($gender!="")? " and (m.gender='$gender')" : "";
      $sql3 .= ($image>0)? " and (m.picturepath!='')" : "";
      $sql3 .= ($country!="" && $country>0)? " and (country='$country')" : "";
      $sql3 .= ($state!="" && $state>0)? " and (state='$state')" : "";
      $sql3 .= ($city!="" && $city>0)? " and (city='$city')" : "";

      /*$mny = date('Y')-$minage;
		  $mxy = date('Y')-$maxage;
		  $mindate = $mny.date("-m-d");
		  $maxdate = $mxy.date("-m-d");
		
      $sql3 .= " and (DATEDIFF(DATE(NOW()), m.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
		  $sql3 .= " and DATEDIFF(DATE(NOW()), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";*/
		
		$sql3 .= " and ((YEAR(NOW()) - YEAR(m.birthday)) >= '$minage')";
		$sql3 .= " and ((YEAR(NOW()) - YEAR(m.birthday)) <= '$maxage')";
		  
		  //$totalLimit = $next + $limit;
		  $sql3 .= " order by fake, last_action_to limit $next,$limit";
//		  $sql3 .= " order by m.id asc limit $next,$totalLimit";
		  $sql = $sql1.$sql2.$sql3;
		  //echo $sql."<br><br>";
		  $query = @mysql_query($sql);
		  
		  if(@mysql_num_rows($query)>0){	
  		    while($rs=@mysql_fetch_array($query)){
  		        $arrTmp[] = $rs;
  		    }    		
  		}else{
  		    $sql2  = ($username!="")? " and (m.username like '%$username%')" : "";
  		    $sql = $sql1.$sql2.$sql3;
  		    $query = @mysql_query($sql);
  		    if(@mysql_num_rows($query)>0){
  		      while($rs=@mysql_fetch_array($query)){
  		          $arrTmp[] = $rs;
  		      } 
  		  }
  		}  		
  		return $arrTmp;
  }
  
  static function isFoundProfile($username, $gender, $image, $country, $state, $city, $minage, $maxage){
      $sql  = "select m.* from member as m where (m.isactive='1')";
      $sql .= ($username!="")? " and (m.username = '".trim($username)."')" : "";
      $sql .= ($gender!="")? " and (m.gender='$gender')" : "";
      $sql .= ($image>0)? " and (m.picturepath!='')" : " and (m.picturepath='')";
      $sql .= ($country!="" && $country>0)? " and (country='$country')" : "";
      $sql .= ($state!="" && $state>0)? " and (state='$state')" : "";
      $sql .= ($city!="" && $city>0)? " and (city='$city')" : "";
      
      $mny = date('Y')-$minage;
		  $mxy = date('Y')-$maxage;
		  $mindate = $mny.date("-m-d");
		  $maxdate = $mxy.date("-m-d");
		
      $sql .= " and (DATEDIFF(NOW(), m.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
		  $sql .= " and DATEDIFF(NOW(), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";  
		
		  $query = @mysql_query($sql);
		  if(@mysql_num_rows($query)>0){
		      return true;
		  }else{
		      return false;
		  }
  }
  
  static function countProfile($username, $gender, $image, $country, $state, $city, $minage, $maxage){
  
      $sql1  = "select m.* from member as m where (m.isactive='1')";
      $sql2  = ($username!="")? " and (m.username = '$username')" : "";
      $sql3  = ($gender!="")? " and (m.gender='$gender')" : "";
      $sql3 .= ($image>0)? " and (m.picturepath!='')" : " and (m.picturepath='')";
      $sql3 .= ($country!="" && $country>0)? " and (country='$country')" : "";
      $sql3 .= ($state!="" && $state>0)? " and (state='$state')" : "";
      $sql3 .= ($city!="" && $city>0)? " and (city='$city')" : "";
      
      $mny = date('Y')-$minage;
		  $mxy = date('Y')-$maxage;
		  $mindate = $mny.date("-m-d");
		  $maxdate = $mxy.date("-m-d");
		
      $sql3 .= " and (DATEDIFF(NOW(), m.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
		  $sql3 .= " and DATEDIFF(NOW(), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
		  $sql3 .= "";
		  
		  $num = 0;
		  $sql = $sql1.$sql2.$sql3;
		  //echo $sql."<br><br>";
		  $query = @mysql_query($sql);		  
		  if(@mysql_num_rows($query)){
		      $num = @mysql_num_rows($query);
		  }else{
		      $sql2  = ($username!="")? " and (m.username like '%$username%')" : "";
		      $sql = $sql1.$sql2.$sql3;
		      $query = @mysql_query($sql);
		      $num = @mysql_num_rows($query);
		  }
		  
		  return $num;
  }
  
  //Detail Suche:
  static function geProfileSameArea($userid, $gender, $idTmp, $next, $limit, $city){
  	
  	  $sql_member = "select * from member where id='$userid'";
      $query_member = @mysql_query($sql_member);
      $rs_member = @mysql_fetch_array($query_member);
      
      $sgl_city = "select * from xml_cities where id='$city'";
      $query_city = @mysql_query($sgl_city);
      $rs_city = @mysql_fetch_array($query_city);
      $area_code = substr($rs_city['plz'], 0, 2);
      $first_nbr = substr($rs_city['plz'], 0, 1);      
           
      
/*      $sql_city = "SELECT * FROM xml_cities WHERE id = '{$rs_member['city']}'";
      $query_city = @mysql_query($sql_city);
      $rs_city = @mysql_fetch_array($query_city);
      $area_code = substr($rs_city['plz'], 0, 2);
      $first_nbr = substr($rs_city['plz'], 0, 1);
*/      
      $sql = array();
      $sql[0] = "select m.* from member as m where (m.isactive='1') and m.type != '1' and m.type != '9'";
      $sql[1] = " AND m.city IN(SELECT id FROM xml_cities WHERE plz LIKE '$area_code%')";
      $sql[2] = ($gender != "")? " and (m.gender='$gender')" : "";
      $sql[3] = ($idTmp!="")? " and (m.id not in ($idTmp))" : "";
      //$totalLimit = $next + $limit;
      $sql[4] = " order by m.picturepath desc, m.fake, m.last_action_to limit $next, $limit";
     
      $count_arr = array($sql[0], $sql[1], $sql[2], $sql[3]);
      $sql_str = implode('',$sql);
      //echo $sql_str;
      //echo "<br>".$city;     
      
	  $sql_count = implode('', $count_arr);
	  $query = @mysql_query($sql_str);
	  $query_count = @mysql_query($sql_count); 
	  if(count($query)>0){
	      while($rs=@mysql_fetch_array($query))
	          $arrTmp[] = $rs;
	      //echo @mysql_num_rows($query_count)." ";
	      return array($arrTmp, @mysql_num_rows($query_count));
	  }else{
	  	  $sql[1] = " AND m.city IN(SELECT id FROM xml_cities WHERE plz LIKE '{$first_nbr}%')";
	  	  $count_arr = array($sql[0], $sql[1], $sql[2], $sql[3]);
	  	  $sql_count = implode('', $count_arr);
	  	  $sql_str = implode('',$sql);
	  	  $query = @mysql_query($sql_str);
	  	  $query_count = @mysql_query($sql_count);
		  if(count($query)>0){
		      while($rs=@mysql_fetch_array($query))
		          $arrTmp[] = $rs;
		      return array($arrTmp, @mysql_num_rows($query_count));
		  }else
			  return array(array(), 0);
	  }
  
  }
  
  static function getLonelyHeartAds($username, $gender, $image, $country, $state, $city, $minage, $maxage, $next, $limit, $self_gender){
  
  	  /*
  	   * self_gender:
  	   * 1 = man
  	   * 2 = woman
  	   */
      $sql1  = "select lha.userid,m.username,m.picturepath from member as m ";
      $sql1 .= "inner join lonely_heart_ads as lha on m.id=lha.userid where (m.isactive='1')";
      $sql2  = ($username!="")? " and (m.username = '$username')" : "";
      $sql3  = ($gender!="")? " and (m.gender='$gender')" : "";
      $sql3 .= ($image>0)? " and (m.picturepath!='')" : " and (m.picturepath='')";
      $sql3 .= ($self_gender == 1) ? " and (m.lookmen = '1')" : " and (m.lookwomen = '1')";
      $sql3 .= ($country!="" && $country>0)? " and (country='$country')" : "";
      $sql3 .= ($state!="" && $state>0)? " and (state='$state')" : "";
      $sql3 .= ($city!="" && $city>0)? " and (city='$city')" : "";
      
      $mny = date('Y')-$minage;
	  $mxy = date('Y')-$maxage;
	  $mindate = $mny.date("-m-d");
	  $maxdate = $mxy.date("-m-d");
		
      $sql3 .= " and (DATEDIFF(NOW(), m.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
	  $sql3 .= " and DATEDIFF(NOW(), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
      $sql3 .= " group by m.id"; 
      
      $total_Limit = $next+$limit;
      $sql3 .= " order by lha.id limit $next,$total_Limit";
    
      $sql = $sql1.$sql2.$sql3;
      //echo $sql;
      $query = @mysql_query($sql);
		  if(@mysql_num_rows($query)>0){
		      for($i=0;$rs=@mysql_fetch_array($query);$i++){
		          $sql_lha = "select * from lonely_heart_ads where userid='".$rs[userid]."' order by datetime desc";
		          $query_lha = @mysql_query($sql_lha);
		          
		          if(count($query_lha)>0){
		              $rs_lha = @mysql_fetch_array($query_lha);
		              $arrTmp[$i][username] = $rs[username];	
                  	  $arrTmp[$i][picturepath] = $rs[picturepath];	              
		              $arrTmp[$i][id] = $rs_lha[id];
		              $arrTmp[$i][userid] = $rs_lha[userid];
		              $arrTmp[$i][target] = $rs_lha[target];
		              $arrTmp[$i][category] = $rs_lha[category];		              
		              $arrTmp[$i][headline] = $rs_lha[headline];
		              $arrTmp[$i][text] = $rs_lha[text];
		              $arrTmp[$i][admin] = $rs_lha[admin];
		              $arrTmp[$i][datetime] = $rs_lha[datetime];
		          }	
      
		      }	               
		  }else{
		      $sql2  = ($username!="")? " and (m.username like '%$username%')" : "";
		      $sql = $sql1.$sql2.$sql3;
		      $query = @mysql_query($sql);
		      
		      if(@mysql_num_rows($query)>0){
  		      for($i=0;$rs=@mysql_fetch_array($query);$i++){
  		          $sql_lha = "select * from lonely_heart_ads where userid='".$rs[userid]."' order by datetime desc";
  		          $query_lha = @mysql_query($sql_lha);
  		          
  		          if(count($query_lha)>0){
  		              $rs_lha = @mysql_fetch_array($query_lha);
  		              $arrTmp[$i][username] = $rs[username];	
                      $arrTmp[$i][picturepath] = $rs[picturepath];	              
  		              $arrTmp[$i][id] = $rs_lha[id];
  		              $arrTmp[$i][userid] = $rs_lha[userid];
  		              $arrTmp[$i][target] = $rs_lha[target];
  		              $arrTmp[$i][category] = $rs_lha[category];		              
  		              $arrTmp[$i][headline] = $rs_lha[headline];
  		              $arrTmp[$i][text] = $rs_lha[text];
  		              $arrTmp[$i][admin] = $rs_lha[admin];
  		              $arrTmp[$i][datetime] = $rs_lha[datetime];
  		          }	
        
  		      }	
          }
		  }
      		  
		  return $arrTmp;  
  }  

  static function isFoundLonelyHeartAds($username, $gender, $image, $country, $state, $city, $minage, $maxage){
      $sql  = "select lha.userid,m.username,m.picturepath from member as m ";
      $sql .= "inner join lonely_heart_ads as lha on m.id=lha.userid where (m.isactive='1')";
      $sql .= ($username!="")? " and (m.username = '$username')" : "";
      $sql .= ($gender!="")? " and (m.gender='$gender')" : "";
      $sql .= ($image>0)? " and (m.picturepath!='')" : " and (m.picturepath='')";
      $sql .= ($country!="" && $country>0)? " and (country='$country')" : "";
      $sql .= ($state!="" && $state>0)? " and (state='$state')" : "";
      $sql .= ($city!="" && $city>0)? " and (city='$city')" : "";
      
      $mny = date('Y')-$minage;
	  $mxy = date('Y')-$maxage;
	  $mindate = $mny.date("-m-d");
	  $maxdate = $mxy.date("-m-d");
		
      $sql .= " and (DATEDIFF(NOW(), m.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
      $sql .= " and DATEDIFF(NOW(), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
      $sql .= " group by m.id";
      
      $query = @mysql_query($sql);
      if(@mysql_num_rows($query)>0){
          return true;
      }else{
          return false;
      }
  }

  static function countLonelyHeartAds($username, $gender, $image, $country, $state, $city, $minage, $maxage){
      
      $sql1  = "select lha.userid,m.username,m.picturepath from member as m ";
      $sql1 .= "inner join lonely_heart_ads as lha on m.id=lha.userid where (m.isactive='1')";
      $sql2  = ($username!="")? " and (m.username = '$username')" : "";
      $sql3  = ($gender!="")? " and (m.gender='$gender')" : "";
      $sql3 .= ($image>0)? " and (m.picturepath!='')" : " and (m.picturepath='')";
      $sql3 .= ($country!="" && $country>0)? " and (country='$country')" : "";
      $sql3 .= ($state!="" && $state>0)? " and (state='$state')" : "";
      $sql3 .= ($city!="" && $city>0)? " and (city='$city')" : "";
      
      $mny = date('Y')-$minage;
		  $mxy = date('Y')-$maxage;
		  $mindate = $mny.date("-m-d");
		  $maxdate = $mxy.date("-m-d");
		
      $sql3 .= " and (DATEDIFF(NOW(), m.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
		  $sql3 .= " and DATEDIFF(NOW(), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
      $sql3 .= " group by m.id"; 
      
      $sql = $sql1.$sql2.$sql3;
      $query = @mysql_query($sql);
      
      if(@mysql_num_rows($query)){
          $num = @mysql_num_rows($query);
      }else{
          $sql2 = ($username!="")? " and (m.username like '%$username%')" : "";
          $sql = $sql1.$sql2.$sql3;
          
          $query = @mysql_query($sql);
          $num = @mysql_num_rows($query);
      }
      
      return $num;
  }
  
  static function getLonelyHeartAdsSameArea($userid, $gender, $idTmp, $next, $limit, $city, $self_gender){
      
      $sql_member = "select * from member where id='$userid'";
      $query_member = @mysql_query($sql_member);
      $rs_member = @mysql_fetch_array($query_member);
     
      $sgl_city = "select * from xml_cities where id='$city'";
      $query_city = @mysql_query($sgl_city);
      $rs_city = @mysql_fetch_array($query_city);
      $area_code = substr($rs_city['plz'], 0, 2);
      $first_nbr = substr($rs_city['plz'], 0, 1);
      
      /*
      $sql_city = "SELECT * FROM xml_cities WHERE id = '{$rs_member['city']}'";
      $query_city = @mysql_query($sql_city);
      $rs_city = @mysql_fetch_array($query_city);
      $area_code = substr($rs_city['plz'], 0, 2);
      $first_nbr = substr($rs_city['plz'], 0, 1);
	*/
      
      $sql = array();
      $sql[0]  = "select lha.userid,m.username,m.picturepath,m.area from member as m ";
      $sql[1] = "inner join lonely_heart_ads as lha on m.id=lha.userid where (m.isactive='1')";
      $sql[2] = ($gender!="")? " and (m.gender='$gender')" : "";
      $sql[2] .= ($self_gender == 1) ? " and (m.lookmen = '1')" : " and (m.lookwomen = '1')";   
      
      $sql[3] = " AND m.city IN(SELECT id FROM xml_cities WHERE plz LIKE '$area_code%')";
      //$sql .= " and (m.area like '".substr($rs_member[area],0,1)."%')";
      //$sql .= " and (CHAR_LENGTH(m.area)='".strlen(trim($rs_member[area]))."')";
      $sql[4] = ($idTmp!="")? " and (lha.userid not in ($idTmp))" : "";
      $sql[5] = " group by m.id order by m.picturepath desc, m.area asc"; 
      
      $total_Limit = $next+$limit;
      $sql[6] = " limit $next,$total_Limit";

      
      $count_sql = array($sql[0], $sql[1], $sql[2], $sql[3], $sql[4]);
      $sql_str = implode('', $sql);
      //echo $sql_str;
      $query = @mysql_query($sql_str);
      if(count($query) == 0){
      	$sql[3] = " AND m.city IN(SELECT id FROM xml_cities WHERE plz LIKE '$first_nbr%')";
      	$count_sql[3] = $sql[3];
      	$sql_str = implode('', $sql);
      	$query = @mysql_query($sql_str);
      }
	  if(count($query) > 0){
	      for($i=0;$rs=@mysql_fetch_array($query);$i++){
	          $sql_lha = "select * from lonely_heart_ads where userid='".$rs[userid]."' order by datetime desc";
	          $query_lha = @mysql_query($sql_lha);
	          
	          if(count($query_lha)>0){
	              $rs_lha = @mysql_fetch_array($query_lha);
	              $arrTmp[$i][username] = $rs[username];	
                  $arrTmp[$i][picturepath] = $rs[picturepath];	              
	              $arrTmp[$i][id] = $rs_lha[id];
	              $arrTmp[$i][userid] = $rs_lha[userid];
	              $arrTmp[$i][target] = $rs_lha[target];
	              $arrTmp[$i][category] = $rs_lha[category];		              
	              $arrTmp[$i][headline] = $rs_lha[headline];
	              $arrTmp[$i][text] = $rs_lha[text];
	              $arrTmp[$i][admin] = $rs_lha[admin];
	              $arrTmp[$i][datetime] = $rs_lha[datetime];
	          }	      
	      }	               
	  }
	  $count_sql_str = implode('', $count_sql);
      $query = @mysql_query($count_sql_str);
      $num = @mysql_num_rows($query);
	  return array($arrTmp, $num);    
  }
  
  static function GetNewLonelyHeart($sex, $limit){
      $sql  = "select m.id, m.username, m.city, m.birthday, m.picturepath, max(lha.datetime) as datetime,";
      $sql .= " m.lookmen, m.lookwomen, m.gender from lonely_heart_ads as lha inner join member as m on m.id=lha.userid where m.isactive=1 and m.picturepath!=''";
      switch($sex){
          case M : $sql .= " and (m.gender='1')"; break;
          case F : $sql .= " and (m.gender='2')"; break;
      }
      $sql .= " group by m.id order by lha.id desc ";
      $sql .= ($limit!="" && $limit>0)? " limit $limit" : "";      
      
      $query = @mysql_query($sql);
      if(@mysql_num_rows($query)>0){
          while($rs=@mysql_fetch_assoc($query)){
              $tmp[] = $rs;
          }
          
          return $tmp;
      }
  }
  
  /**
   * Accepts an associative array whose keys correspond to member
   * fields in most cases, there are some exceptions such as min_age and max_age
   * which are treated differently. The values are used to search with. Note that
   * the input array can look in any way, it is perfectly ok to just send an
   * untreated $_REQUEST array to this function.
   * 1) First we define which keys to work with and get rid of everything else.
   * 2) Next we remove empty values so we can safely loop through the array at
   * a later stage.
   * 3) We loop through the array and construct the conditional part of the sql.
   * 4) Next we replace some index references with the corresponding values.
   * @param array $search The array to use.
   * @return array the search result.
   * @uses Search::getMinMaxSql() to handle age to date conversions.
   */
  static function simpleSearch($search){
	// 1
  	$allowed_keys = array(
  		"gender", "city", "min_age", "max_age", "lookfor", "fake",
		"payment_start", "payment_end", "payment_received_start", "payment_received_end",
		"msg_sent_start", "msg_sent_end", "msg_received_start", "msg_received_end", "type", "flag",'picturepath','state','username','in_storno','forname','surname'
  	);
  	
  	
  	
  	$allowed_keys = array_combine($allowed_keys, $allowed_keys);
  	$new_search = array_intersect_key($search, $allowed_keys);
	$trim_search = array();
	
	/**
	 * Wenn min_age und max_age gleich 18 bis 99, dann
	 * nicht danach abfragen
	 */
	if ($new_search['min_age'] == '18' && $new_search['max_age'] == '99') {
	    $new_search['min_age'] = null;
	    $new_search['max_age'] = null;
	}
	
	// 2
	foreach($new_search as $key => $value){
		if($value !== null)
			$trim_search[$key] = $value;
	}
	$sql = "SELECT ".$search[felder]." FROM member";
	$first_loop = true;
	$where = "";
	// 3
	foreach($trim_search as $field => $val){
		if($first_loop){
			$where .= " WHERE ";
			if(!isset($search['type']))$where .= 'type != 1 and ';
			$first_loop = false;
		}else
			$where .= " AND ";
		switch($field){
			case'city':
				$where .= "city IN(SELECT id FROM xml_cities WHERE name = '$val')";
				break;
			case'min_age':
				$where .= "birthday <= now() - interval $val year";
				break;
			case'max_age':
				$where .= "birthday >= now() - interval $val year";
				break;
			case'lookfor':
				$groups = array("1" => "lookmen", "2" => "lookwomen", "3" => "lookpairs");
				$val = $groups[$val];
				$where .= "$val = 1";
				break;
			case'payment_start':
				$where .= "'$val' <= payment";
				break;
			case'payment_end':
				$where .= "'$val' >= payment";
				break;
			case'payment_received_start':
				$where .= "'$val' <= payment_received";
				break;
			case'payment_received_end':
				$where .= "'$val' >= payment_received";
				break;
			case'msg_sent_start':
				$where .= "id IN(SELECT from_id FROM message_outbox WHERE '$val' <= `datetime`)";
				break;
			case'msg_sent_end':
				$where .= "id IN(SELECT from_id FROM message_outbox WHERE '$val' >= `datetime`)";
				break;
			case'msg_received_start':
				$where .= "id IN(SELECT to_id FROM message_inbox WHERE '$val' <= `datetime`)";
				break;
			case'msg_received_end':
				$where .= "id IN(SELECT to_id FROM message_inbox WHERE '$val' >= `datetime`)";
				break;
         	case 'username':
				$where .= "username like '$val%'";
				break;
         	case 'forname':
				$where .= "forname like '$val%'";
				break;				
         	case 'surname':
				$where .= "surname like '$val%'";
				break;					
         	case 'picturepath':
				if($val==1)$where .= "picturepath != ''";
				break;
         	case 'flag':
				if($val==1)$where .= "flag = 1";
				break;	
         	case 'in_storno':
				if($val==1)$where .= "in_storno = 1";
				break;												
			default:
				$where .= "$field = '$val'";
				break;
		}
	}
	$where .= " AND isactive = 1";
	
	if(isset($search['start']) && isset($search['offset']))
		$limit = " LIMIT {$search['start']}, {$search['offset']}";
	
	if(isset($search['fake'])){
			$order = " ORDER BY rundmail ASC, last_action_from ASC";
	}
	$sql .= $where.$order.$limit;

	$content = DBconnect::row_retrieve_2D($sql);
	$content = $content != 0 ? $content : array();

    
        $darr=explode(',',$search[felder]);
        for($d=0;$d<count($darr); $d++){
            if($darr[$d] == 'city'){
               foreach($content as &$i){
        			$i[$d] = utf8_decode(funcs::getAnswerCity('ger',$i[$d]));
        	   }
            }
            elseif($darr[$d] == 'username'){
                 foreach($content as &$i){
        			$i[$d] = utf8_decode($i[$d]);
        	     }
            }

         }
	
	$result=array(
      'content'=>$content,
      'sql'=> $sql
   );
	
	return $result;
	
  }
  
	static function getMinMaxSql($field_name, $operand, $year){
		$minmax_year = date('Y')-$year;
		$minmax_date = $minmax_year.date("-m-d");
		return "DATEDIFF(NOW(), $field_name) $operand DATEDIFF(NOW(), '$minmax_date')";
	}

	static function  getPaymentStatistic($from_date, $to_date){
	
      $end_date=(preg_match("/^[0-9]+$/",$to_date))?" '$from_date' + interval $to_date day":"'$to_date'";
		
		$sql = "SELECT paid_via as '0', sum(payment_complete) as '1', sum(sum_paid) as '2' FROM payment_log WHERE payment_complete = 1 AND recall != 1 AND payday >= '$from_date' AND payday < $end_date GROUP BY paid_via";
		#echo $sql;
		$result = DBconnect::assoc_query_2D($sql);
		
		foreach($result as $key => $value){
			//$result[$key] = str_replace('1','Kreditkarte', $result[$key]);	
			//$result[$key] = str_replace('2','PayPal', $result[$key]);
			//$result[$key] = str_replace('3','Überweisung', $result[$key]);
			//$result[$key] = str_replace('4','ELV', $result[$key]);
					
		}
		$resultarray = array();
		foreach($result as $key => $value){
			$params = $result[$key]['summe_user'].",".$result[$key]['summe_euro'];
			switch($result[$key]['paid_via']){
				case 1:
						$stack = array('1',$params);
						array_push($resultarray,$stack);
						break;
				case 2:
						$stack = array('2', $params);
						array_push($resultarray,$stack);					
						break;					
				case 3:
						$stack = array('3', $params);
						array_push($resultarray,$stack);					
						break;					
				case 4:	
						$stack = array('4', $params);
						array_push($resultarray,$stack);				
						break;				
			}
		}
		return $result;
		//return $resultarray; 
	}	
	
	static function  getSMSStatistic($from_date, $to_date){
	
		$end_date=(preg_match("/^[0-9]+$/",$to_date))?" '$from_date' + interval $to_date day":"'$to_date'";
			
		$sql = "SELECT COUNT(*) FROM sms_log WHERE send_date >= '$from_date' AND send_date <= $end_date";
		#echo $sql;
		$result = DBconnect::get_nbr($sql);
		
		return $result;
	}

	static function  getWebcamStatistic($from_date, $to_date){
	
	    $end_date=(preg_match("/^[0-9]+$/",$to_date))?" '$from_date' + interval $to_date day":"'$to_date'";
			
		$sql = "SELECT COUNT(distinct(userid)) FROM webcam_log WHERE use_date >= '$from_date' AND use_date <= $end_date";
		#echo $sql;
		$result = DBconnect::get_nbr($sql);
		
		return $result;
	}		

  static function  getPaymentByType($type, $start, $offset){

        $feldname='payment_received';
        if($type==4) $feldname='signup_datetime';

        $gesamt= DBconnect::get_nbr("SELECT COUNT(*) FROM member where fake = 0 AND type =".$type." and $feldname>='$start' and $feldname<'$offset'");



		$sql = "SELECT id, username, forname, surname, signup_datetime, payment, payment_received, in_storno FROM member where fake = 0 AND type =".$type." and $feldname>='$start' and $feldname<'$offset' ORDER  BY $feldname desc";

		$query = @mysql_query($sql);
      	if(@mysql_num_rows($query)>0){
          while($rs=@mysql_fetch_row($query)){
              $tmp[] = $rs;
          }

          $retarray=array(
            'gesamt'=>$gesamt,
            'felder'=>array('ID', 'Nickname', 'Vorname', 'Nachname', 'Angemeldet seit', 'Aboende', 'Bezahlt am', 'Storno'),
            'content'=>$tmp
          );
          return $retarray;
      }

	}
	
	static function  getELVStatistic($from_date, $to_date){
	
      $end_date=(preg_match("/^[0-9]+$/",$to_date))?" '$from_date' + interval $to_date day":"'$to_date'";
		
		$sql1 = "SELECT sum(payment_complete) as 'payment_complete', sum(sum_paid) as 'sum_paid' FROM payment_log WHERE paid_via = 4 AND payment_complete = 1 AND payday >= '$from_date' AND payday < $end_date GROUP BY paid_via";
		$result = DBconnect::assoc_query_1D($sql1);
		
		$sql2 = "SELECT sum(recall) as 'recall', sum(sum_paid) as 'sum_paid' FROM payment_log WHERE paid_via = 4 AND payment_complete = 1 AND recall = 1 AND payday >= '$from_date' AND payday < $end_date GROUP BY paid_via";
		$result2 = DBconnect::assoc_query_1D($sql2);	
		
		$sql3 = "SELECT sum(payment_complete) as 'payment_complete', sum(sum_paid) as 'sum_paid' FROM payment_log WHERE paid_via = 4 AND payment_complete = 1 AND recall = 0 AND payday >= '$from_date' AND payday < $end_date GROUP BY paid_via";
		$result3 = DBconnect::assoc_query_1D($sql3);			
		
		$resultarray[] = array(	"payments_all" 		=> 	$result['payment_complete'],
								"sum_total"			=>	$result['sum_paid'],
								"recall"			=>	$result2['recall'],
								"recall_sum"		=>	$result2['sum_paid'],
								"payment_valid"		=>	$result3['payment_complete'],
								"sum_valid"			=>	$result3['sum_paid']);		
		
		return $resultarray;
	}	
	
	static function getUsersList($arr)
	{
		extract($arr);
		$sqlGetMember = "SELECT t1.*, t4.name as ".TABLE_MEMBER_CITY.",
						t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY."
						FROM ".TABLE_MEMBER." t1
						LEFT OUTER JOIN xml_countries t2
							ON t1.country = t2.id
						LEFT OUTER JOIN xml_states t3
							ON t1.state=t3.id
						LEFT OUTER JOIN xml_cities t4
							ON t1.city=t4.id
						WHERE (t1.".TABLE_MEMBER_ISACTIVE." = 1) AND ".TABLE_MEMBER_FLAG." != 1  AND ((YEAR(NOW()) - YEAR(t1.birthday)) >= '$minage') AND ((YEAR(NOW()) - YEAR(t1.birthday)) <= '$maxage')";

		if($country!=0 && $country!='')
			 $sqlGetMember .= " AND (t1.country='$country')";
		if($city!=0 && $city!='')
			$sqlGetMember .= " AND (t1.city='$city')";
		if($state!=0 && $state!='')
			$sqlGetMember .= " AND (t1.state='$state')";
		if($gender!=0 && $gender!='')
			$sqlGetMember .= " AND (t1.gender='$gender')";
		if($search_username!="")
			$sqlGetMember .= " AND (t1.username like '%{$search_username}%')";
		if(($fake == '0') || ($fake == '1'))
			$sqlGetMember .= " AND (t1.fake = '$fake')";
		if($have_pic == '1')
			$sqlGetMember .= " AND (t1.picturepath <> '')";

		$sqlGetMember .= " ORDER BY t1.signin_datetime DESC, t1.last_action_to DESC, t1.last_action_from DESC, t1.payment DESC";
		$sqlCountMember = $sqlGetMember;
		$sqlGetMember .= " LIMIT ".$start.", ".$limit;

		$data = DBconnect::assoc_query_2D($sqlGetMember);

		$sqlCountMember = "select count(*) " . substr($sqlCountMember, strpos($sqlCountMember, "F"));
		$countMember = DBconnect::retrieve_value($sqlCountMember);

		return array("data" => $data, "count" => $countMember);
	}

	static function getUsersListSameArea($arr, $location)
	{
		extract($arr);
		extract($location);
		$sqlGetMember = "SELECT t1.*, t4.name as ".TABLE_MEMBER_CITY.",
						t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY."
						FROM ".TABLE_MEMBER." t1
						LEFT OUTER JOIN xml_countries t2
							ON t1.country = t2.id
						LEFT OUTER JOIN xml_states t3
							ON t1.state=t3.id
						LEFT OUTER JOIN xml_cities t4
							ON t1.city=t4.id
						WHERE (t1.".TABLE_MEMBER_ISACTIVE." = 1) AND ".TABLE_MEMBER_FLAG." != 1 AND t1.id != '$id'";

		if($country!=0 && $country!='')
			 $sqlGetMember .= " AND (t1.country='$country')";
		if($city!=0 && $city!='')
			$sqlGetMember .= " AND (t1.city='$city')";
		if($state!=0 && $state!='')
			$sqlGetMember .= " AND (t1.state='$state')";
		if($gender!=0 && $gender!='')
			$sqlGetMember .= " AND (t1.gender='$gender')";
		if($search_username!="")
			$sqlGetMember .= " AND (t1.username NOT LIKE '%{$search_username}%')";
		if(($fake == '0') || ($fake == '1'))
			$sqlGetMember .= " AND (t1.fake = '$fake')";
		if($have_pic == '1')
			$sqlGetMember .= " AND (t1.picturepath <> '')";

		$sqlGetMember .= " ORDER BY t1.signin_datetime DESC, t1.last_action_to DESC, t1.last_action_from DESC, t1.payment DESC";
		$sqlCountMember = $sqlGetMember;
		$sqlGetMember .= " LIMIT ".$start.", ".$limit;

		$data = DBconnect::assoc_query_2D($sqlGetMember);

		$sqlCountMember = "select count(*) " . substr($sqlCountMember, strpos($sqlCountMember, "F"));
		$countMember = DBconnect::retrieve_value($sqlCountMember);

		return array("data" => $data, "count" => $countMember);
	}

	static function getUsersAd($arr)
	{
		extract($arr);
		$sqlGetMember = "SELECT DISTINCT t1.*, t4.name as ".TABLE_MEMBER_CITY.",
						t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY."
						FROM ".TABLE_MEMBER." t1
						LEFT OUTER JOIN xml_countries t2
							ON t1.country = t2.id
						LEFT OUTER JOIN xml_states t3
							ON t1.state=t3.id
						LEFT OUTER JOIN xml_cities t4
							ON t1.city=t4.id, lonely_heart_ads t5
						WHERE (t1.id = t5.userid) AND (t1.".TABLE_MEMBER_ISACTIVE." = 1) AND ".TABLE_MEMBER_FLAG." != 1  AND ((YEAR(NOW()) - YEAR(t1.birthday)) >= '$minage') AND ((YEAR(NOW()) - YEAR(t1.birthday)) <= '$maxage')";

		if($country!=0 && $country!='')
			 $sqlGetMember .= " AND (t1.country='$country')";
		if($city!=0 && $city!='')
			$sqlGetMember .= " AND (t1.city='$city')";
		if($state!=0 && $state!='')
			$sqlGetMember .= " AND (t1.state='$state')";
		if($gender!=0 && $gender!='')
			$sqlGetMember .= " AND (t1.gender='$gender')";
		if($search_username!="")
			$sqlGetMember .= " AND (t1.username like '%{$search_username}%')";
		if(($fake == '0') || ($fake == '1'))
			$sqlGetMember .= " AND (t1.fake = '$fake')";
		if($have_pic == '1')
			$sqlGetMember .= " AND (t1.picturepath <> '')";

		$sqlGetMember .= " ORDER BY t1.signin_datetime DESC, t1.last_action_to DESC, t1.last_action_from DESC, t1.payment DESC";
		$sqlCountMember = $sqlGetMember;
		$sqlGetMember .= " LIMIT ".$start.", ".$limit;

		$data = DBconnect::assoc_query_2D($sqlGetMember);
		foreach($data as &$member)
		{
			$sql = "select * from lonely_heart_ads where userid='".$member['id']."' order by datetime desc LIMIT 1";
			$rs_lha = DBConnect::assoc_query_1D($sql);
			$member[id] = $rs_lha[id];
			$member[userid] = $rs_lha[userid];
			$member[target] = $rs_lha[target];
			$member[category] = $rs_lha[category];
			$member[headline] = $rs_lha[headline];
			$member[text] = $rs_lha[text];
			$member[admin] = $rs_lha[admin];
			$member[datetime] = $rs_lha[datetime];
		}

		$sqlCountMember = "select DISTINCT t1.* " . substr($sqlCountMember, strpos($sqlCountMember, "F"));
		$countMember = DBconnect::assoc_query_2D($sqlCountMember);

		return array("data" => $data, "count" => count($countMember));
	}
}
?>