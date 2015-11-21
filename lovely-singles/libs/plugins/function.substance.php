<?php
function smarty_function_substance($params){
	$name = $params[name];

	if(file_exists("thumbs/$name")){
		return $name;
	}else{
		return "default.jpg";
	}
}
?>