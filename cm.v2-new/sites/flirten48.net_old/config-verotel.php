<?php
$shopID = 93775;
$signaturekey = "S6wXduK8S7YTsZrcyeTBATHfjGAQfE";

function getVerotelURL($details,$signaturekey,$shopID)
{
	$signature = sha1(
		$signaturekey
		//.":custom1=".$details['site_id']
		.":description=".$details['id']
		.":priceAmount=".$details['price']
		.":priceCurrency=".$details['currency']
		.":referenceID=".$details['id']
		.":shopID=".$shopID
		.":version=1");

	$verotelURL = "https://secure.verotel.com/order/purchase?"
		//."&custom1=".$details['site_id']
		."&description=".$details['id']
		."&priceAmount=".$details['price']
		."&priceCurrency=".$details['currency']
		."&referenceID=".$details['id']
		."&shopID=".$shopID
		."&signature=".$signature
		."&version=1";

	return $verotelURL;
}

function getVerotelSignature($signaturekey,$arr)
{
	$arrString = $signaturekey;
	foreach($arr as $key=>$item)
	{
		if(($key!="signature") && ($key!="action"))
		{
			$str=":".$key."=".$item;
			$arrString.=$str;
		}
	}
	return sha1($arrString);
}

?>