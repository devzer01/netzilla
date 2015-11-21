<?php
require_once "HTTP/Request.php";
class HttpRequest{
 	static function sendReq($script, $post)
	{
 	 	$req =& new HTTP_Request($script);
 	 	$req->setMethod(HTTP_REQUEST_METHOD_POST);
 	 	$req->setBasicAuth('admin', 'westkit_1969');
 	 	foreach($post as $key => $value)
			$req->addPostData($key, $value);

		if (!PEAR::isError($req->sendRequest()))
		{
			$temp = unserialize($req->getResponseBody());
			if(empty($temp))
				$temp = $req->getResponseBody();
			
			return $temp;
		}

		return false;
	}
}
?>