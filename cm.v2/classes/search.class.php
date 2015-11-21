<?php

include_once('DBconnect.php');
class Search{

	static function getExtra($next, $limit){
		//$total_limit = $next + $limit;
		$sql = "SELECT * FROM member ORDER BY picturepath DESC LIMIT $next, $limit";
		return DBconnect::assoc_query_2D($sql);
	}

	static function checkFavorite($userId, $searchId)
	{
		$sql = "select child_id from favorite where parent_id = $userId and child_id = $searchId";

		if($result = DBconnect::assoc_query_1D($sql))
			return $result;
		else
			return array();
	}

	static function searchName($username, $next, $limit, $current_id = null)
	{	
		if($_SESSION['lang']=="ger")
			$select_filed = "ct.name_de";
		else
			$select_filed = "ct.name";
		
		$arrTmp = array();
		$arrNotIn = array();

		if($current_id != null) 
			array_push($arrNotIn, $current_id);

		/**
		 * Match and Similar Search result need to separate sql.
		 * Becuase it need to show exactly match with keyword first then similar to keyword
		 **/
		//echo "searchName<br/>";
		$sql1 = "SELECT 
					m.id, 
					m.picturepath, 
					m.username, 
					$select_filed AS city, 
					m.civilstatus, 
					m.birthday, 
					m.appearance, 
					m.height, 
					m.description 
				 FROM member AS m 
				 LEFT JOIN xml_cities AS ct ON m.city = ct.id 
				 LEFT JOIN xml_countries AS c ON m.country = c.id 
				 WHERE m.isactive = 1 AND c.status = 1
		";
		$sql2 = ($username!="")? " AND m.username = '".$username."'" : "";
		$sql_not_in = "";//(count($arrNotIn)>0)? " AND id NOT IN (". join(',',$arrNotIn) .")" : "";
		$sql3 = " ORDER BY m.username LIMIT $next, $limit";

		$sql = $sql1.$sql2.$sql_not_in.$sql3; 
		//echo $sql."<br>";
		
		$query = @mysql_query($sql);
		$num = @mysql_num_rows($query);
		
		//if($num > 0){ #Noi changed 2012-03-31 : combine exactly username and similar username
		if($num > 0)
		{
			//echo "Total Match: ".$num."<br/>";
			//$j = 0;
			$rs=@mysql_fetch_assoc($query);
			//Adding Matching Profile to Array
			array_push($arrTmp, $rs);

			/*echo "<pre>";
			print_r($rs);
			echo "</pre>";*/
			
			/*while($rs=@mysql_fetch_array($query))
			{
				array_push($arrTmp, $rs);
				//$arrTmp[] = $rs;
				$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
				foreach($arrTmp as &$rec)
				{
					if(in_array($rec['id'], $arrFavor))
						$rec['added'] = 1;
					
					$arrIds[] = $rec['id'];
				}
				$j++;
			}*/

			//echo "Match Id: ".$rs['id']."<br/>";
			if(!(in_array($rs['id'],$arrNotIn))) 
				array_push($arrNotIn, $rs['id']);
		}

		
		
		//echo "<br/>";
	//}else{
		$sql4  = ($username!="")? " AND (m.username LIKE '%$username%')" : ""; 
		//Need to rewrite $sql_not_in again because if found matching profile then push id to $arrNotIn
		$sql_not_in = (count($arrNotIn)>0)? " AND m.id NOT IN (". join(',',$arrNotIn) .")" : "";
		$sql = $sql1.$sql4.$sql_not_in.$sql3; 
		//echo $sql."<br/>";
		$query = @mysql_query($sql);
		$num = @mysql_num_rows($query);
		//echo "Total Similar: ".$num."<br/>";

		if($num>0){
			while($rs2=@mysql_fetch_assoc($query))
			{
				/*echo "rs <br/>";
				echo "<pre>";
				print_r($rs2);
				echo "</pre>";*/
				array_push($arrTmp, $rs2);
				array_push($arrNotIn, $rs2['id']);
				//$arrTmp[] = $rs;
				/*$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
				foreach($arrTmp as &$rec)
				{
					if(in_array($rec['id'], $arrFavor))
						$rec['added'] = 1;
						
				}
				$j++;*/
			}
		}
		
		//}
		/*echo "<pre>";
		echo "Not in: ";
		print_r($arrNotIn);
		echo "</pre>";

		echo "----------------------<br/>";
		echo "<pre>";
		print_r($arrTmp);
		echo "</pre>";
		echo "----------------------<br/>";*/

		return array('datas' => $arrTmp, 'ids' => $arrNotIn);
	}

	static function countSearchName($username, $current_id = null)
	{
		/**
		 * No need to separate sql for count : Noi 2012-03-31
		 **/
		//echo "countSearchName<br/>";
		$sql1 = "SELECT m.id 
				 FROM member AS m 
				 LEFT JOIN xml_cities AS ct ON m.city = ct.id 
				 LEFT JOIN xml_countries AS c ON m.country = c.id 
				 WHERE m.isactive = 1 AND c.status = 1";
		//$sql2 = ($username!="")? " AND username = '".$username."'" : "";
		$sql2 = ($username!="")? " AND m.username LIKE '%$username%' " : "";
		$sql_not_in = ($current_id != null)? " AND m.id NOT IN ('".$current_id."')" : "";

		$sql = $sql1.$sql2.$sql_not_in;
// 		echo $sql."<br>";

		$query = @mysql_query($sql);
		$total_num = @mysql_num_rows($query); //echo $match_num." ";

		/*if ($num<1) 
		{
			$sql3 = " AND username LIKE '%$username%' ";
			$sql = $sql1.$sql3;
			echo $sql."<br>";
				
			$query = @mysql_query($sql);
			$similar_num = @mysql_num_rows($query); echo $similar_num." ";
				
		}*/
		
		//echo "Total : ".$total_num."<br/>";
		//echo "----------------------<br/>";
		return $total_num;

	}

	static function countLikeSearchName($username)
	{
		$sql1 = "select * from member where isactive = '1' ";
		$sql2 = ($name!="")? " and username like '%$username%' " : "";

		$sql = $sql1.$sql2;
		$query = @msyql_query($sql);
		$num = @mysql_num_rows($query);

		return $num;
	}

	static function getAllProfiles($username, $next, $limit)
	{
		if($limit > 1000)
		{
			$limit = 1000;
		}

		$sql1  = "select m.* from member as m where (m.isactive='1')";
		$sql2  = ($username!="")? " and (m.username like '%$username%') " : "";
		$sql3 .= " order by fake, birthday desc, last_action_to limit $next,$limit";

		$sql = $sql1.$sql2.$sql3;

		$query = @mysql_query($sql);
		$j = 0;
		if(@mysql_num_rows($query)>0){
			while($rs=@mysql_fetch_array($query))
			{
				$arrTmp[] = $rs;
				$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
				foreach($arrTmp as &$rec)
				{
					if(in_array($rec['id'], $arrFavor))
					$rec['added'] = 1;
				}
				$j++;
			}
		}else{
			$sql2  = ($username!="")? " and (m.username like '%$username%')" : "";
			$sql = $sql1.$sql2.$sql3;
				
			$query = @mysql_query($sql);
			if(@mysql_num_rows($query)>0){
				while($rs=@mysql_fetch_array($query))
				{
					$arrTmp[] = $rs;
					$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
					foreach($arrTmp as &$rec)
					{
						if(in_array($rec['id'], $arrFavor))
						$rec['added'] = 1;
					}
					$j++;
				}
			}
		}


		return $arrTmp;
	}

	//Einfache Suche:
	static function geProfile($username, $gender, $look_gender, $image, $country, $state, $city, $minage, $maxage, $next, $limit)
	{
		if($limit > 1000)
		{
			$limit = 1000;
		}

		$sql0  = "select count(*)";
		$sql1  = "select m.*";
		$sql2  = " from member as m where m.isactive='1' AND m.id>3";
		$sql2 .= ($username!="")? " and (m.username like '$username%')" : "";
		$sql3  = ($gender!="")? " and (m.gender='$gender')" : "";
		$sql3 .= ($look_gender!="")? " and (m.".($look_gender=="1"?"lookmen":"lookwomen")."='1')" : "";
		$sql3 .= ($image>0)? " and (m.picturepath!='')" : "";
		$sql3 .= ($country!="" && $country>0)? " and (country='$country')" : "";
		$sql3 .= ($state!="" && $state>0)? " and (state='$state')" : "";
		$sql3 .= ($city!="" && $city>0)? " and (city='$city')" : "";
		if($minage && $maxage)
		{
			$sql3 .= " and (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(birthday)), '%Y')+0 >= $minage) and (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(birthday)), '%Y')+0 <= $maxage)";
		}
		$sql4 = " order by if(picturepath = '',1,0), fake, birthday desc, last_action_to limit $next,$limit";
		$sql = $sql1.$sql2.$sql3.$sql4;
		$query = @mysql_query($sql);
		$j = 0;
		if(@mysql_num_rows($query)>0){
			while($rs=@mysql_fetch_array($query))
			{
				$arrTmp[] = $rs;
			}
		}

		$total = DBConnect::retrieve_value($sql0.$sql2.$sql3);
		return array("datas" => $arrTmp, "total" => $total);
	}


	static function geProfileByName($username, $gender, $image, $country, $state, $city, $minage, $maxage, $next, $limit, $arrNotIn = array())
	{
		if($_SESSION['lang']=="ger")
			$select_filed = "ct.name_de";
		else
			$select_filed = "ct.name";
		
// 		echo "geProfileByName<br/>";
		/* Phai edited on 2012/01/02 [yyyy/mm/dd] */
		//$sql1  = "select m.* from member as m where (m.isactive='1') and m.type != '1' and m.type != '9'";
		//$sql2  = ($username!="")? " and (m.username = '$username')" : "";

		/*jeab*/
		if($limit > 1000)
		{
			$limit = 1000;
		}

		$sql1  = "SELECT 
					m.id, 
					m.picturepath, 
					m.username, 
					$select_filed AS city, 
					m.civilstatus, 
					m.birthday, 
					m.appearance, 
					m.height, 
					m.description 
				 FROM member AS m 
				 LEFT JOIN xml_cities AS ct ON m.city = ct.id 
				 LEFT JOIN xml_countries AS c ON m.country = c.id 
				 WHERE m.isactive = 1 AND c.status = 1";
		
		$sql2  = ($username!="")? " AND (m.username not like '%$username%')" : "";
		$sql3  = ($gender!="")? " AND (m.gender='$gender')" : "";
		$sql3 .= ($image>0)? " AND (m.picturepath!='')" : "";
		$sql3 .= ($country!="" && $country>0)? " AND (m.country='$country')" : "";
		$sql3 .= ($state!="" && $state>0)? " AND (m.state='$state')" : "";
		$sql3 .= ($city!="" && $city>0)? " AND (m.city='$city')" : "";

		$sql3 .= " AND (((YEAR(NOW()) - YEAR(m.birthday)) >= '$minage')";
		$sql3 .= " AND ((YEAR(NOW()) - YEAR(m.birthday)) <= '$maxage') or (m.birthday='0000-00-00'))";

		$sql3 .= (count($arrNotIn)>0)? " AND m.id NOT IN (". join(',',$arrNotIn) .")" : "";

		//$totalLimit = $next + $limit;
		$sql3 .= " ORDER BY m.fake, m.birthday DESC, m.last_action_to LIMIT $next,$limit";
		//$sql3 .= " order by m.id asc limit $next,$totalLimit";
		$sql = $sql1.$sql2.$sql3;
		//echo $sql."<br><br>";
		$query = @mysql_query($sql);
		$j = 0;
		if(@mysql_num_rows($query)>0){
			while($rs=@mysql_fetch_array($query))
			{
				$arrTmp[] = $rs;
				$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
				foreach($arrTmp as &$rec)
				{
					if(in_array($rec['id'], $arrFavor))
					$rec['added'] = 1;
				}
				$j++;
			}
		}else{
			$sql2  = ($username!="")? " AND (m.username like '%$username%')" : "";
			$sql = $sql1.$sql2.$sql3;
			// 			echo "else:::".$sql."<br><br>";
			$query = @mysql_query($sql);
			if(@mysql_num_rows($query)>0){
				while($rs=@mysql_fetch_array($query))
				{
					$arrTmp[] = $rs;
					$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
					foreach($arrTmp as &$rec)
					{
						if(in_array($rec['id'], $arrFavor))
						$rec['added'] = 1;
					}
					$j++;
				}
			}
		}
		// 	echo $sql;

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

		$sql1  = "SELECT m.* FROM member AS m WHERE (m.isactive='1')";
		$sql2  = ($username!="")? " AND (m.username = '$username')" : "";
		$sql3  = ($gender!="")? " AND (m.gender='$gender')" : "";
		$sql3 .= ($image>0)? " AND (m.picturepath!='')" : " AND (m.picturepath='')";
		$sql3 .= ($country!="" && $country>0)? " AND (country='$country')" : "";
		$sql3 .= ($state!="" && $state>0)? " AND (state='$state')" : "";
		$sql3 .= ($city!="" && $city>0)? " AND (city='$city')" : "";

		$mny = date('Y')-$minage;
		$mxy = date('Y')-$maxage;
		$mindate = $mny.date("-m-d");
		$maxdate = $mxy.date("-m-d");

		$sql3 .= " AND (DATEDIFF(NOW(), m.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
		$sql3 .= " AND DATEDIFF(NOW(), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
		$sql3 .= "";

		$num = 0;
		$sql = $sql1.$sql2.$sql3;
		// 		echo $sql."<br><br>";
		$query = @mysql_query($sql);
		if(@mysql_num_rows($query)){
			$num = @mysql_num_rows($query);
		}else{
			$sql2  = ($username!="")? " and (m.username like '%$username%')" : "";
			$sql = $sql1.$sql2.$sql3;
			//echo $sql."<br><br>";
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

		$sql = array();
		$sql[0] = "select m.* from member as m where (m.isactive='1') and m.type != '1' and m.type != '9'";
		$sql[1] = " AND m.city IN(SELECT id FROM xml_cities WHERE plz LIKE '$area_code%')";
		$sql[2] = ($gender != "")? " and (m.gender='$gender')" : "";
		$sql[3] = ($idTmp!="")? " and (m.id not in ($idTmp))" : "";
		//$totalLimit = $next + $limit;
		$sql[4] = " order by m.picturepath desc, m.fake, m.birthday desc, m.last_action_to limit $next, $limit";
			
		$count_arr = array($sql[0], $sql[1], $sql[2], $sql[3]);
		$sql_str = implode('',$sql);
		//echo $sql_str;
		//echo "<br>".$city;

		$sql_count = implode('', $count_arr);
		$query = @mysql_query($sql_str);
		$query_count = @mysql_query($sql_count);
		$j=0;
		if(count($query)>0){
			while($rs=@mysql_fetch_array($query))
			{
				$arrTmp[] = $rs;
				//echo @mysql_num_rows($query_count)." ";
				$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
				foreach($arrTmp as &$rec)
				{
					if(in_array($rec['id'], $arrFavor))
					$rec['added'] = 1;
				}
				$j++;
			}

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
				{
					$arrTmp[] = $rs;
					$arrFavor = Search::checkFavorite($_SESSION[sess_id], $arrTmp[$j][id]);
					foreach($arrTmp as &$rec)
					{
						if(in_array($rec['id'], $arrFavor))
						$rec['added'] = 1;
					}
					$j++;
				}

				return array($arrTmp, @mysql_num_rows($query_count));
			}else
			return array(array(), 0);
		}

	}

	static function numAllProfiles()
	{
		$sql = "select * from member where isactive='1'";
		$query = @mysql_query($sql);
		$num = @mysql_num_rows($query);
			
		return $num;
	}
	
	static function numAllPorfileSameArea2($collectType, $arrGet, $pageStart, $maxLimit)
	{	
// 		echo "Start: ".$pageStart." Max Limit: ".$maxLimit."<br/>";
		$arrProfiles = array();
		$arrNotIn = array();
		$arrStickCount = array();
		$totalCount = 0;
		$totalDetails = 0;
		
		if($_SESSION['lang']=="ger")
			$select_filed = "ct.name_de";
		else
			$select_filed = "ct.name";
		
		switch ($collectType)
		{
			case 'profile':
				$sql_count 		=   "SELECT m.id 
									 FROM member AS m 
									 LEFT JOIN xml_cities AS ct ON m.city = ct.id 
									 LEFT JOIN xml_countries AS c ON m.country = c.id 
									 WHERE m.isactive = 1 AND c.status = 1";
				
				$sql_details	= 	"SELECT 
										m.id, 
										m.picturepath, 
										m.username, 
										$select_filed AS city, 
										m.civilstatus, 
										m.birthday, 
										m.appearance, 
										m.height, 
										m.description 
									FROM member AS m 
									LEFT JOIN xml_cities AS ct ON m.city = ct.id 
									LEFT JOIN xml_countries AS c ON m.country = c.id 
									WHERE m.isactive = 1 AND c.status = 1 AND m.id>3";
				break;
				
			case 'lonelyheart':
				$sql_count 		=   "SELECT DISTINCT lha.userid 
									 FROM lonely_heart_ads AS lha 
									 LEFT JOIN member AS m ON lha.userid = m.id 
									 WHERE m.isactive=1";
				
				$sql_details 	= 	"SELECT 
										DISTINCT lha.userid,
										lha.id, 
										lha.target, 
										lha.category, 
										lha.headline, 
										lha.text, 
										lha.admin, 
										lha.datetime, 
										m.username,
										m.picturepath 
									FROM lonely_heart_ads AS lha 
									LEFT JOIN member AS m ON lha.userid = m.id 
									WHERE m.isactive = 1 AND m.id>3";
				break;
		}
		//CONCAT(m.username,' ',m.birthday,' B:',DATEDIFF(NOW(), m.birthday),' N:',DATEDIFF(NOW(), '1996-04-07')) AS username, 
		$sql_con = Search::prepareConditionSQL($arrGet);	
		
// 		echo "SQL Con: ".$sql_con."<br/><br/>";
		
		$arrCondition = explode('AND (', $sql_con);
		
		$sql_order = " ORDER BY m.fake ASC, m.birthday DESC, m.last_action_to ASC";
		
		$max_round = count($arrCondition);
		$min_round = $max_round-1; // if can not find in the first round then find one more round : if want to find more round just change from 1 to the number you want
		$ads_round = $max_round-1;
		
		for($i=$max_round; $i>=$min_round; $i--)
		{
// 			echo "<pre>";
// 			print_r($arrStickCount);
// 			echo "</pre>";
			
			/*
			 * $arrStickCount for keeping how many record from previouse page until current page
			 */
			$last_record = array_sum($arrStickCount);
// 			echo "Last Record: ". $last_record ."<br/>";
// 			echo "Start At: ". ($pageStart-$last_record) ."<br/>";
			
			if($totalDetails<$maxLimit)
			{
				$limit_match = $maxLimit-$totalDetails;
				$start_match = $pageStart-$last_record;
				
				if($start_match<0)
					$start_match = 0;

				/*echo "-------------------------";
				echo "<pre>";
				print_r($arrNotIn);
				echo "</pre>";
				echo "-------------------------<br/>";*/
				$sql_not_in = "";//(count($arrNotIn)>0)? " AND id NOT IN (" . join(',', $arrNotIn) . ")" : "";
				
// 				echo "Current Cond Before: ".$arrCondition[$i]."<br/>";
				if(strpos($arrCondition[$i], "picturepath")!==false)
					$arrCondition[$i] = str_replace("m.picturepath IS NOT NULL AND m.picturepath != ''","m.picturepath IS NULL OR m.picturepath = ''",$arrCondition[$i]);
				if(strpos($arrCondition[$i], "birthday")!==false)
				{
					$convertCond = str_replace("AND", "OR", $arrCondition[$i]);
					$convertCond = str_replace(">=", "<", $convertCond);
					$convertCond = str_replace("<=", ">", $convertCond);
					$arrCondition[$i] = $convertCond;
				}
				else if(strpos($arrCondition[$i], "!=")!==false)
					$arrCondition[$i] = str_replace("!=","=",$arrCondition[$i]);
				else if(strpos($arrCondition[$i], "=")!==false)
					$arrCondition[$i] = str_replace("=","!=",$arrCondition[$i]);
				else
					unset($arrCondition[$i]);
				
// 				echo "Current Cond After: ".$arrCondition[$i]."<br/><br/>";
					
// 				echo $i.": ";
				/*echo "<pre>";
				print_r($arrCondition);
				echo "</pre>";*/
				
				//echo $sql_count . join('AND (', $arrCondition) . $sql_not_in;
				$query_count = @mysql_query($sql_count . join('AND (', $arrCondition) . $sql_not_in);
				$num_count = @mysql_num_rows($query_count);
				$arrStickCount[$i] = $num_count;
				$totalCount += $num_count;
// 				echo "<br/>$"."num_count: ". $num_count."<br/><br/>";
				//echo " LIMIT " . $start_ads . ", " . $end_ads."<br/>";
				
				$sql_limit = " LIMIT " . $start_match . ", " . $limit_match;
				
// 				echo $sql_count . join('AND (', $arrCondition) . "<br/>" .$sql_not_in . $sql_order . $sql_limit."<br/><br/>";
				$query_details = @mysql_query($sql_details . join('AND (', $arrCondition) . $sql_not_in . $sql_order . $sql_limit);
				$num_details = @mysql_num_rows($query_details);
				$totalDetails += $num_details;		
					
// 				echo "<br/>$"."num_details:".$num_details."<br/><br/>";
// 				echo "<br/>$"."totalDetails: ".$totalDetails."<br/><br/>";
					
				if($num_details > 0)
				{
// 					//echo "Total Match: ".$num."<br/>";
// 					//$j = 0;

// 					if current round is additonal ads then add remark to array
					if($i==$ads_round)
						$arrProfiles[]['advanced_result'] = "yes";
					
					while ($rs=@mysql_fetch_assoc($query_details))
					{
// 						echo $i." ".$rs['id']." ".$rs['username']."<br/>";
// 						array_push($arrNotIn, $rs['id']);
						array_push($arrProfiles, $rs);
// 						/*echo "<pre>";
// 						print_r($rs);
// 						echo "</pre>";
// 						echo "***********************************<br/>";*/
					}
				}
			}
			
		}
// 		echo "Total Count: ".$totalCount;
		
		return array('datas' => $arrProfiles, 'total' => $totalCount);
		
	}

	static function prepareConditionSQL($arrConditions)
	{
		$arrConditionsAccept = array(
									'picturepath' => 'q_picture',
									'gender' => 'q_gender',
									'lookmen' => 'lookmen',
									'lookwomen' => 'lookwomen',
									'lookpairs' => 'lookpairs',
									'birthday' => '',
									'country' => 'country',
									'state' => 'state',
									'city' => 'city'
									);
		/*echo "<pre>";
		print_r($arrConditions);
		echo "</pre>";*/

		$sql_con = "";
		foreach ($arrConditionsAccept as $key => $val)
		{
			switch ($key)
			{
				case 'birthday':
					$min_age = Search::check_input($arrConditions['q_minage']);
					$max_age = Search::check_input($arrConditions['q_maxage']);
					$mny = date('Y')-$min_age;
					$mxy = date('Y')-$max_age;
					$mindate = $mny.date("-m-d");
					$maxdate = $mxy.date("-m-d");
					
					if($min_age!="" && $max_age!="")
						$sql_con .= " AND (DATEDIFF(NOW(), m.birthday ) >= DATEDIFF(NOW(), '$mindate' ) AND DATEDIFF(NOW(), m.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
					break;
				case 'picturepath':
					$sql_con .= (Search::check_input($arrConditions['q_picture']==1))? " AND (m.picturepath IS NOT NULL AND m.picturepath != '')" : "";
					break;
				default:
					$input_val = Search::check_input($arrConditions[$val]);
					$sql_con .= ($input_val != 0)? " AND (". $key . " = '". $input_val ."')" : "";
					//echo $key." : ".$val."<br/>";
					//echo $sql_con."<br/>";
			}
		}
		
		//echo $sql."<br/>";
		return $sql_con;
	}

	static function numAllProfileSameArea($gender, $have_pic, $country, $state, $city, $min_age, $max_age)
	{
		$sql = "SELECT id FROM member WHERE isactive='1'";
		$sql .= ($gender!=0)? " AND (gender = '$gender')": "";
		$sql .= ($have_pic!=0)? " AND (picturepath!='')": "";
		$sql .= ($country!=0)? " AND (country = '$country')": "";
		$sql .= ($state!=0)? " AND (state = '$state')": "";
		$sql .= ($city!=0)? " AND (city = '$city')": "";
			
		if($min_age!='' and $max_age!='')
		{
			$mny = date('Y')-$min_age;
			$mxy = date('Y')-$max_age;
			$mindate = $mny.date("-m-d");
			$maxdate = $mxy.date("-m-d");

			$sql .= " AND (DATEDIFF(NOW(), birthday ) >= DATEDIFF(NOW(), '$mindate' )";
			$sql .= " AND DATEDIFF(NOW(), birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
			$sql .= "";
		}
		
		//echo "Same Area::::".$sql." : ";
		$query = @mysql_query($sql);
		$num = @mysql_num_rows($query);
		//echo $num."<br><br>";
			
		return $num;
	}
	
	/*static function numAllProfileSameArea($gender, $have_pic, $country, $state, $city, $min_age, $max_age)
	{
		$sql = "SELECT * FROM member WHERE isactive='1'";
		$sql .= ($gender!=0)? " AND (gender = '$gender')": "";
		$sql .= ($have_pic!=0)? " AND (picturepath!='')": "";
		$sql .= ($country!=0)? " AND (country = '$country')": "";
		$sql .= ($state!=0)? " AND (state = '$state')": "";
		$sql .= ($city!=0)? " AND (city = '$city')": "";
			
		if($min_age!='' and $max_age!='')
		{
			$mny = date('Y')-$min_age;
			$mxy = date('Y')-$max_age;
			$mindate = $mny.date("-m-d");
			$maxdate = $mxy.date("-m-d");
	
			$sql .= " AND (DATEDIFF(NOW(), birthday ) >= DATEDIFF(NOW(), '$mindate' )";
			$sql .= " AND DATEDIFF(NOW(), birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";
			$sql .= "";
		}
	
		echo "same area::::".$sql."<br><br>";
		$query = @mysql_query($sql);
		$num = @mysql_num_rows($query);
			
		return $num;
	}*/

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
		$sql3 .= ($image!="")? " and (m.picturepath!='')" : "";
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

		//
		$sql3 .= " order by lha.id limit $next, $limit";

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
		// echo $sql;
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


		$sql = array();
		$sql[0]  = "select lha.userid,m.username,m.picturepath,m.area from member as m ";
		$sql[1] = "inner join lonely_heart_ads as lha on m.id=lha.userid where (m.isactive='1')";
		$sql[2] = ($gender!="")? " and (m.gender='$gender')" : "";
		$sql[2] .= ($self_gender == 1) ? " and (m.lookmen = '1')" : " and (m.lookwomen = '1')";

		$sql[3] = " AND m.city IN(SELECT id FROM xml_cities WHERE plz LIKE '$area_code%')";

		$sql[4] = ($idTmp!="")? " and (lha.userid not in ($idTmp))" : "";
		$sql[5] = " group by m.id order by m.picturepath desc, m.birthday desc, m.area asc";

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
		$sql  = "SELECT m.id, m.username, m.city, m.birthday, m.picturepath, MAX(lha.datetime) AS datetime, ";
		$sql .= "m.lookmen, m.lookwomen, m.gender, lha.headline, lha.text ";
		$sql .= "FROM lonely_heart_ads AS lha LEFT JOIN member AS m ON lha.userid = m.id LEFT JOIN xml_countries as c ON m.country=c.id ";
		$sql .= "WHERE m.isactive=1 AND m.picturepath!='' AND c.status=1";

		switch($sex){
			case 'M' : $sql .= " AND m.gender='1'"; break;
			case 'F' : $sql .= " AND m.gender='2'"; break;
		}
		$sql .= "GROUP BY lha.userid ORDER BY lha.datetime DESC limit 30";
		//echo $sql; echo "<br/>";
		$query = @mysql_query($sql);
		if(@mysql_num_rows($query)>0){
			while($rs=@mysql_fetch_assoc($query)){
				$tmp[] = $rs;
			}

			if($limit!="" && $limit>0)
			{
				shuffle($tmp);
				$tmp = array_slice($tmp, 0, $limit);
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
  		"gender", "city",'state', 'country', "min_age", "max_age", "lookfor", "fake",
		"payment_start", "payment_end", "payment_received_start", "payment_received_end",
		"msg_sent_start", "msg_sent_end", "msg_received_start", "msg_received_end", "type", "flag",'picturepath','username','in_storno','forname','surname','for_newsletter'
		);
			
		foreach($search as $key=>$val)
		{
			$new_search[$key] = $val;
		}

		$search = $new_search;
			
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
		$sql_count = "SELECT count(id) FROM member";
		$first_loop = true;
		$where = "";
		// 3

		$result_array=array();

		foreach($trim_search as $field => $val){

			switch($field){
				case'city':
					$result_array[] = "city IN(SELECT id FROM xml_cities WHERE name like '$val%')";
					//$result_array[] = "city IN(SELECT id FROM xml_cities WHERE name = '$val')";
					break;
				case'min_age':
					//$result_array[] = "birthday <= now() - interval $val year";
					$result_array[] = "(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(birthday)), '%Y')+0) >= ".$val;
					break;
				case'max_age':
					//$result_array[] = "birthday >= now() - interval $val year";
					$result_array[] = "(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(birthday)), '%Y')+0) <= ".$val;
					break;
				case'lookfor':
					$groups = array("1" => "lookmen", "2" => "lookwomen", "3" => "lookpairs");
					$val = $groups[$val];
					$result_array[] = "$val = 1";
					break;
				case'payment_start':
					$result_array[] = "'$val' <= payment";
					break;
				case'payment_end':
					$result_array[] = "'$val' >= payment";
					break;
				case'payment_received_start':
					$result_array[] = "'$val' <= payment_received";
					break;
				case'payment_received_end':
					$result_array[] = "'$val' >= payment_received";
					break;
				case'msg_sent_start':
					$result_array[] = "id IN(SELECT m.from_id FROM message_outbox m INNER JOIN (SELECT MIN(datetime) AS datetime FROM message_outbox GROUP BY from_id) n on m.datetime=n.datetime WHERE '$val' <= DATE(m.datetime))";
					break;
				case'msg_sent_end':
					$result_array[] = "id IN(SELECT m.from_id FROM message_outbox m INNER JOIN (SELECT MAX(datetime) AS datetime FROM message_outbox GROUP BY from_id) n on m.datetime=n.datetime WHERE '$val' >= DATE(m.datetime))";
					break;
				case'msg_received_start':
					$result_array[] = "id IN(SELECT m.to_id FROM message_inbox m INNER JOIN (SELECT MIN(datetime) AS datetime FROM message_inbox GROUP BY to_id) n on m.datetime=n.datetime WHERE '$val' <= DATE(m.datetime))";
					break;
				case'msg_received_end':
					$result_array[] = "id IN(SELECT m.to_id FROM message_inbox m INNER JOIN (SELECT MAX(datetime) AS datetime FROM message_inbox GROUP BY to_id) n on m.datetime=n.datetime WHERE '$val' >= DATE(m.datetime))";
					break;
				case 'username':
					if(isset($search['username_exact']))
					{
						$result_array[] = "username like '$val'";
					}
					else
					{
						$result_array[] = "username like '$val%'";
					}
					break;
				case 'forname':
					$result_array[] = "forname like '$val%'";
					break;
				case 'surname':
					$result_array[] = "surname like '$val%'";
					break;
				case 'picturepath':
					if($val==1)$result_array[] = "picturepath != ''";
					break;
				case 'flag':
					if($val==1)$result_array[] = "flag = 1";
					break;
				case 'in_storno':
					if($val==1)$result_array[] = "in_storno = 1";
					break;
				default:
					$result_array[] = "$field = '$val'";
				break;
			}
		}

		$where .= " WHERE ";
		//if(!isset($search['type']))$where .= 'type != 1 and ';
		$where.= join(" and ",$result_array);
		$where .= " AND isactive = 1";

		if(isset($search['start']) && isset($search['offset']))
		$limit = " LIMIT {$search['start']}, {$search['offset']}";

		if(isset($search['order']))
		{
			$order = " ORDER BY ".$search['order'];
		}
		else
		{
			if(isset($search['fake'])){
				$order = " ORDER BY rundmail ASC, last_action_from ASC";
			}
		}

		$sql_count .= $where.$order;
		$sql .= $where.$order.$limit;

		if(isset($search['assoc']))
		{
			$content = DBconnect::assoc_query_2D($sql);
		}
		else
		{
			$content = DBconnect::row_retrieve_2D($sql);
		}
		$content = $content != 0 ? $content : array();
		$total = DBConnect::retrieve_value($sql_count);

		$result=array(
			'content'=>$content,
			'total' => $total,
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
	static function getPaymentUsersList($arr){
		extract($arr[criteria]);
		$sqlGetMember = "SELECT \n";
		$sqlGetMember .= "t1.username, t1.new_type, \n";
		$sqlGetMember .= " DATE_FORMAT(t1.new_paid_until, '%d/%b/%Y') AS new_paid_until,\n";
		$sqlGetMember .= "DATE_FORMAT(t1.payday, '%d/%b/%Y') AS payday, t1.paid_via, t1.sum_paid, \n";
		$sqlGetMember .= "t1.real_name, t1.real_street, t1.real_plz, \n";
		$sqlGetMember .= "t1.real_city, t1.ip_address, t1.bank_name, \n";
		$sqlGetMember .= "t1.bank_blz, t1.bank_account,\n";
		$sqlGetMember .= "t1.prolonging, t1.payment_complete,  \n";
		$sqlGetMember .= "t1.recall,  DATE_FORMAT(t1.cancelled_date, '%d/%b/%Y') AS cancelled_date \n";
		$sqlGetMember .= "FROM ".TABLE_PAY_LOG." t1 \n";
		$sqlGetMember .= "LEFT OUTER JOIN ".TABLE_MEMBER." t2 ON t1.username = t2.username \n";
		$sqlGetMember .= "WHERE (prolonging = ".$prolonging." OR payment_complete = ".$payment_complete.")\n";
		if($id){
			$sqlGetMember .= "AND t1.id = '$id' \n";
		}
		if($username){
			$sqlGetMember .= "AND t2.username LIKE '%$username%' \n";
		}
		if($forname){
			$sqlGetMember .= "AND t2.forname LIKE '%$forname%' \n";
		}
		if($email){
			$sqlGetMember .= "AND t2.email LIKE '%$email%' ";
		}

		//		$sqlGetMember .= "ORDER BY t1.signin_datetime DESC, t1.last_action_to DESC, t1.last_action_from DESC, t1.payment DESC \n";
		$sqlGetMember .= "ORDER BY t1.username ";
		$sqlCountMember = $sqlGetMember;
		$sqlGetMember .= "LIMIT ".$start.", ".$limit;

		$data = DBconnect::assoc_query_2D($sqlGetMember);
		$sqlCountMember = "select count(*) " . substr($sqlCountMember, strpos($sqlCountMember, "F"));
		$countMember = DBconnect::retrieve_value($sqlCountMember);
		return array("data"=>$data,"count"=>$countMember, "sql"=>$sqlGetMember);
	}


	static function getPaymentUser($arr){
		extract($arr[criteria]);
		$sqlGetMember = "SELECT \n";
		$sqlGetMember .= "t1.username, t1.new_type, \n";
		$sqlGetMember .= "DATE_FORMAT(t1.payday, '%d/%b/%Y') AS payday, t1.paid_via, t1.sum_paid, \n";
		$sqlGetMember .= "t1.real_name, t1.real_street, t1.real_plz, \n";
		$sqlGetMember .= "t1.real_city, t1.ip_address, t1.bank_name, \n";
		$sqlGetMember .= "t1.bank_blz, t1.bank_account,\n";
		$sqlGetMember .= "t1.prolonging, t1.payment_complete,  \n";
		$sqlGetMember .= "t1.recall, DATE_FORMAT(t1.cancelled_date, '%d/%b/%Y') AS cancelled_date  \n";
		$sqlGetMember .= "FROM ".TABLE_PAY_LOG." t1 \n";
		$sqlGetMember .= "WHERE t1.username LIKE '%".$username."%' \n";
		$sqlCountMember = $sqlGetMember;
		$sqlGetMember .= "LIMIT ".$start.", ".$limit;
		$data = DBconnect::assoc_query_2D($sqlGetMember);
		$sqlCountMember = "select count(*) " . substr($sqlCountMember, strpos($sqlCountMember, "F"));
		$countMember = DBconnect::retrieve_value($sqlCountMember);
		return array("data"=>$data,"count"=>$countMember, "sql"=>$sqlGetMember);
	}

	static function deletePaymentUser($arr){
		extract($arr[criteria]);
		$sqlGetMember = "DELETE \n";
		$sqlGetMember .= "FROM ".TABLE_PAY_LOG."  \n";
		$sqlGetMember .= "WHERE username = '".$username."' \n";
		$sqlCountMember = $sqlGetMember;
		$data = DBconnect::assoc_query_2D($sqlGetMember);
		$sqlCountMember = "select count(*) " . substr($sqlCountMember, strpos($sqlCountMember, "F"));
		$countMember = DBconnect::retrieve_value($sqlCountMember);
		return array("data"=>$data,"count"=>$countMember, "sql"=>$sqlGetMember);
	}

	static function searchDelMemberId($username)
	{
		$sql = "select id from member where username = '".$username."'";
		return DBconnect::assoc_query_1D($sql);
	}

	static function deleteFavorite($parent_id, $child_id)
	{
		$sql = "delete from favorite where parent_id = $parent_id and child_id = $child_id";
		if(DBconnect::execute_q($sql))
		{
			return true;
		}
	}


	/**
	 * NEW SEARCH FUNCTION 2012-03-26 BY NOI
	 **/
	function check_input($value)
	{
		// Stripslashes
		if (get_magic_quotes_gpc())
		{
			$value = stripslashes($value);
		}
		// Quote if not a number
		if (!is_numeric($value))
		{
			$value = mysql_real_escape_string($value) ;
		}
		return $value;
	}

	static function searchByNickName($q_nickname)
	{
		$q_nickname = funcs::check_input($q_nickname);
		$sql = "SELECT
					id, 
					picturepath, 
					username, 
					city, 
					civilstatus, 
					birthday, 
					appearance, 
					height, 
					description 
				FROM ".TABLE_MEMBER." 
				WHERE ".TABLE_MEMBER_USERNAME."='".$q_nickname."' 
				ORDER BY id DESC";

		return DBconnect::assoc_query_2D($sql);
	}

	static function searchByNickNameSimilar($q_nickname)
	{
		$q_nickname = funcs::check_input($q_nickname);
		$sql = "SELECT
					id, 
					picturepath, 
					username, 
					city, 
					civilstatus, 
					birthday, 
					appearance, 
					height, 
					description 
				FROM ".TABLE_MEMBER." 
				WHERE ".TABLE_MEMBER_USERNAME." LIKE '%".$q_nickname."%' 
				AND ".TABLE_MEMBER_USERNAME." != '".$q_nickname."' 
				ORDER BY id DESC";

		return DBconnect::assoc_query_2D($sql);
	}

	static function searchByNickNameAdditional($q_nickname)
	{
		$q_nickname = funcs::check_input($q_nickname);
		$sql = "SELECT
					id, 
					picturepath, 
					username, 
					city, 
					civilstatus, 
					birthday, 
					appearance, 
					height, 
					description 
				FROM ".TABLE_MEMBER." 
				WHERE ".TABLE_MEMBER_USERNAME." NOT LIKE '%".$q_nickname."%' 
				AND ".TABLE_MEMBER_USERNAME." != '".$q_nickname."' 
				ORDER BY id DESC";

		return DBconnect::assoc_query_2D($sql);
	}

}
?>