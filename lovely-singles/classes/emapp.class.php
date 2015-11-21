<?php
class Photo {
	static function getNoActivePhoto(){		
		
		$sql = "select p.*, m.username as username from phototemp as p inner join member as m on m.id=p.userid";
		$result = DBconnect::assoc_query_2D($sql);		
	
		return $result;		
	}

	static function getPhotoProfileByEmailChatID($userid){

		$sql = "select * from phototemp where (userid='$userid') and (status='1')";
		$result = DBconnect::assoc_query_2D($sql);
	
		return $result;	

	}

	static function getPhotoAlbumByEmailChatID($EmailChatID,$userid){

		$sql = "select p.*, m.username as username from phototemp as p inner join member as m on m.id=p.userid where (p.userid='$EmailChatID') and (p.status='2')";
		$result = DBconnect::assoc_query_2D($sql);
		if(count($result)<=0){ $result=0;}
        
		return $result;
	}

	static function UpdatePhotoTpTmp($post){

		$sql = "insert into photo_tmp (person_id, sites, type, name, uploadtime)values('".$post[EmailChatID]."','".$post[sites]."','".$post[type]."','".$post[name]."','".date("Y-m-d H:i:s")."')";
		
	}

	static function getHostName($site){

		$sql = "select * from sites where (id='$site')";
		$result = DBconnect::assoc_query_1D($sql);

		return $result;
	}

	static function approvePhoto($userid,$photo,$fsk18){
		
		for($fsk18Index=0; $fsk18Index<count($fsk18); $fsk18Index++){
			DBconnect::execute("update phototemp set fsk18='y' where (userid='$userid') and (id='".$fsk18[$fsk18Index]."')");
		}
		
		for($i=0;$i<count($photo);$i++){
			$result = DBconnect::assoc_query_2D("select * from phototemp where (id='".$photo[$i]."')");
			if($result != 0){
				if($result[0][status]==1){
					$sql_updateMember  = "update member set picturepath='".$result[0][picturepath]."'";
					if(isset($fsk18[0])){ $sql_updateMember  .= ", fsk18='1'"; }
					$sql_updateMember .= " where (id='$userid')";
										
					DBconnect::execute($sql_updateMember);
					DBconnect::execute("delete from phototemp where (id='".$photo[$i]."')");
				}elseif($result[0][status]==2){		
					
					if($result[0][fsk18]=="y"){ 
							$fsk18Tmp = '1'; 
					}elseif($result[0][fsk18]=="n"){
						$fsk18Tmp = '0'; 
					}

					DBconnect::execute("insert into fotoalbum (userid,picturepath,fsk18)values('$userid','".$result[0][picturepath]."','$fsk18Tmp')");	
					DBconnect::execute("delete from phototemp where (id='".$photo[$i]."')");
				}
			}
		}

	}

	static function deninePhoto($userid,$photo){
		for($i=0;$i<count($photo);$i++){
			DBconnect::execute("delete from phototemp where (id='".$photo[$i]."')");
		}
	}
}
?>