<?php
/**
* Handles the communication with external sites. Is a wrapper around the PEAR
* package Request, check out the PEAR site for more documentation.
*  
* @uses Request for connect to other sites.
* @filesource
* @package General_Classes
*
*/

/**
* Handles the communication with external sites. Is a wrapper around the PEAR
* package Request, check out the PEAR site for more documentation.
* 
* @uses Request for connect to other sites.
* @package General_Classes
*
*/
require_once "HTTP/Request.php";
class HttpRequest{
	
	/**
	* Sends data to other sites.
	* 
	* @param string $script The variable is other site for send.
	* @param array $post The variable is data for send to ther site.
	* @return bool The variable is true:complete, false:incomplete.
	*/
 	static function sendReq($script, &$post){
 	 	$req =& new HTTP_Request($script);
 	 	$req->setMethod(HTTP_REQUEST_METHOD_POST);
 	 	$req->setBasicAuth("admin", "westkit_1969");
 	 	foreach($post as $key => $value){
			$req->addPostData($key, $value);
		}
		
		if (!PEAR::isError($req->sendRequest())) 
		    return $req->getResponseBody();

		return false;
	}	
}
?>