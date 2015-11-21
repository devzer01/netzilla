<?
	function smarty_function_location($countrycode) {
		foreach($countrycode as $key => $val){
				$countrycode = $val;
			}
		$result = @mysql_query("SELECT * FROM geocode WHERE geo_code LIKE '%" . $countrycode . "%'");
		$record = @mysql_fetch_array($result);

		return $record[geo_description];
}
?>