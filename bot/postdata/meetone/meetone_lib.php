<?php

function getBirthDayFromAge($age)
{
	$year = date("Y", strtotime("-" . $age . " year"));
	$month = rand(1,12);
	
	if ($month != 2) $day = rand(1,30);
	else $day = rand(1,28);

	return date("Y-m-d", strtotime($year . "-" . $month . "-" . $day));
}

function getRandomCityFromRegion($region_id)
{
	$json = getCityList($region_id);
	if ($json['result'] == 1) {
		$cities = array_keys($json['cities']);
		$key = array_rand($cities, 1);
		$key = $cities[$key];
		return array($key, $json['cities'][$key]);
	}
	
	return array();
}

function getRegionList($country_code = "DE")
{
	$url = "http://api.meetone.com/api/phoneapi.php?format=json&service=location&action=getRegionList&countryCode=" . $country_code;
	return json_decode(file_get_contents($url), true);
}

function getCityList($region_id)
{
	$url = "http://api.meetone.com/api/phoneapi.php?format=json&service=location&action=getCityList&regionId=" . $region_id;
	return json_decode(file_get_contents($url), true);
}