<?php
function smarty_function_find_photo_type($params){
	$person_id = $params[person_id];
	$site = $params[site];

	/*
		type = 1 :: have new profile photo
		type = 2 :: have new other photo
	*/

	$sql = "select * from phototemp where (userid='$person_id') and (status='1')";
	$type_1 = DBconnect::assoc_query_2D($sql);

	$sql = "select * from phototemp where (userid='$person_id') and (status='2')";
	$type_2 = DBconnect::assoc_query_2D($sql);

	$result = "";
	if(count($type_1)>0){
		$result .= "New Profile Photo";
	}
	
	if(count($type_2)>0){
		if($result != ""){ $result .= ", "; }
		$result .= "New Photo album";
	}

	return $result;
}
?>