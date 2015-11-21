<?php
/**
* General purpose class for database connectivity.
*
* The main purpose of the class is to provide an interface for storing, retrieving and updating php-arrays into tables and vice versa.
* @uses ArrayExtension form post- or pre manipulation of arrays.
* @uses Config for globals and selection of database.
* @package General classes
*/
class DBconnect
{
	/**
	* Updates a field with the help of arguments. 
	* @param string $table_name The name of the table.
	* @param string $field_name The name of the field to be updated.
	* @param mixed $field_value The value to update the field with.
	* @param string $id_name The name of the identification field to be used.
	* @param mixed $id_value The value of the id.
	* @return bool Returns true if the update was successful, false otherwise.
	*/
	static function update_field($table_name, $field_name, $field_value, $id_name, $id_value)
	{
		$sql = "UPDATE ".$table_name." SET ".$field_name." = '".$field_value."' WHERE ".$id_name." = '".$id_value."'";
		$result = mysql_query($sql);
		if($result)
			return true;
		else
			return false;
	}
	
	/**
	* Simply performs a query. Main purpose is to save a few rows in the code.
	* @param string $sql The SQL string to be used for the query.
	* @return bool The return value, is false if the query was unsuccessful true otherwise.
	*/
	static function execute_q($sql)
	{
		$result = mysql_query($sql);
		if($result)
			return 1;
		else
			return 0;
	}

	/**
	* Same as DBconnect::execute_q() but with no return values.
	* @see DBconnect::execute_q()
	* @param string $sql The SQL string to be used for the query.
	* @return void
	*/
	static function execute($sql)
	{
		$result = mysql_query($sql);
	}
	
	/**
	* Updates a row with with the help of the key->value of an input array. The key string corresponds to the field name and the value is the value to put in the field.  
	* @param string $table_name The name of the table.
	* @param array &$arr The array with name->value pairs.
	* @param string $id_name The name of the identification field to use.
	* @param mixed $id_value The identification value to be used. Should normally be numeric, but can be string also.
	* @return bool Is false if the query was unsuccessful true otherwise.
	* @uses DBconnect::execute_q()
	*/
	static function update_1D_row_with_1D_array($table_name, &$arr, $id_name, $id_value)
	{
	  	$sql = "UPDATE ".$table_name." SET ";
		$i = 0;
		foreach($arr as $key => $value){
			if($i < (count($arr) - 1))
				$sql .= $key." = '".$value."',";
			else
				$sql .= $key." = '".$value."'";
			$i++;
		}
		$sql .= " WHERE ".$id_name." = ".$id_value;
		return self::execute_q($sql);
	}

	/**
	* Performs an insert with the help of three substrings.
	* @param string $table String with the table name.
	* @param string $value_names The string with the field names to use.
	* @param string $values The string with the values to insert.
	* @return bool Is true if the insert was successful.
	*/
	static function insert_row($table, $value_names, $values)
	{
		$sql = "INSERT INTO `".$table."`(".$value_names.") VALUES (".$values.")";
		$result = mysql_query($sql);
	}

	/**
	* Returns the number that a SELECT COUNT() string generates.
	* @param string $sql The sql string to use for the query.
	* @return integer The number of hits the query generated.
	*/
	static function get_nbr($sql)
	{
	  	$result = mysql_query($sql);
	  	if($result){
	  		$nbr = current(mysql_fetch_row($result));
	  		return $nbr;
	  	}else
	  		return 0;
	}

	/**
	* Basically same as get_nbr() but with parameters instead.
	* @see DBconnect::get_nbr()
	* @param string $table The name of the table to use.
	* @param string $id_name The name of the id field to use.
	* @param mixed $id_value The value of the id.
	* @return integer The number of hits the query generated.
	*/
	static function get_nbr_params($table, $id_name, $id_value)
	{
	  	$sql = "SELECT COUNT(*) FROM $table WHERE $id_name = '$id_value'";
		$result = mysql_query($sql);
	  	if($result){
	  	  	$nbr = current(mysql_fetch_row($result));
	  		if($nbr == 0)
	  			return 0;
	  		else
	  			return $nbr;
	  	}else
	  		return 0;
	}

	/**
	* Advances an sql statement, example: "color = 'green' AND", if the passed variable is different from some default value.
	* @param string &$sql The sql to build upon.
	* @param string $attribute The name of the field.
	* @param mixed $variable The value of the field.
	* @param string &$next_condition Reference to the conditional to use in the sql, will be AND as soon as this function is called. 
	* @param string $operand The comparison operand to use.
	* @param mixed $default The default value (is usually 0 or -1).
	* @return void
	*/
	static function advance_mysql(&$sql, $attribute, $variable, &$next_condition, $operand, $default)
	{
		if(isset($variable) && $variable != $default){
			$sql .= $next_condition;
			$sql .= $attribute." ".$operand." '".$variable."'";
			$next_condition = " AND ";
		}
	}

	/**
	* Advances an sql statement, uses an extra check reference for extra control in the calling function. Basically the same functionality as advance_mysql but with some differences, it passes an extra check variable and does not set the sql conditional
	* @param string &$sql The sql to build upon.
	* @see DBconnect::advance_mysql()
	* @param string $attribute The name of the field.
	* @param mixed $variable The value of the field.
	* @param string &$next_condition Reference to the conditional to use in the sql. 
	* @param string $operand The comparison operand to use.
	* @param mixed $default The default value (is usually 0 or -1).
	* @param bool &$check The check reference that the calling function can use.
	* @return void
	*/
	static function advance_mysql_check(&$sql, $attribute, $variable, &$next_condition, $operand, $default, &$check)
	{
		if(isset($variable) && $variable != $default){
			$sql .= $next_condition;
			$sql .= $attribute." ".$operand." '".$variable."'";
			$check = 1;
		}else
			$check = 0;
	}

	/**
	* Advances an sql statement, passes a variable that is used to set the sql conditional. Basically the same functionality as advance_mysql but with some differences, it passes an extra variable used to set the sql conditional with, it also sets the sql conditional after the advancement.
	* @see DBconnect::advance_mysql() 
	* @param string &$sql The sql to build upon.
	* @param string $attribute The name of the field.
	* @param mixed $variable The value of the field.
	* @param string &$next_condition Reference to the conditional to use in the sql. 
	* @param string $operand The comparison operand to use.
	* @param mixed $default The default value (is usually 0 or -1).
	* @param bool $logic The sql conditional to be used in the subsequent advancement.
	* @return void
	*/
	static function advance_mysql_cond(&$sql, $attribute, $variable, &$next_condition, $operand, $default, $logic)
	{
		if($variable != $default){
			$sql .= $attribute." ".$operand." '".$variable."'";
			$sql .= $next_condition;
			$next_condition = " ".$logic." ";
		}
	}

	/**
	* Advances an sql statement with an if condition. If expr1 is TRUE (expr1 <> 0 and expr1 <> NULL) then IF() returns expr2; otherwise it returns expr3.
	* @param string $expr1 The first expression used in IF().
	* @param string $expr2 The second expression used in IF().
	* @param string $expr3 The third expression used in IF().
	* @return void
	*/
	static function adv_mysql_if($expr1, $expr2, $expr3)
	{
	  	return " IF(".$expr1.",".$expr2.",".$expr3.")";
	}

	/**
	* Returns a 2D associative array as a result from the sql query, we use this when we know we will get a 2D result.
	* @param string $sql The sql string to use.
	* @return mixed The resultant 2D array if the query is successful, otherwise 0. 
	*/
	static function assoc_query_2D($sql)
	{
		$result = mysql_query($sql);
		$arr = array();
		$row = array();
		if($result){
			while($row = mysql_fetch_assoc($result))
				array_push($arr, $row);
		}else{
		  	return 0;
		}
			
		return $arr;
	}

	/**
	* Returns a 2D associative array as a result from the sql query, we use this when we know we will get a 2D result. Same functionality as assoc_query_2D but uses parameters instead of a complete sql string.
	* @see DBconnect::assoc_query_2D()
	* @param string $values The substring containing the values to use.
	* @param string $table The table name to use.
	* @return array The resultant 2D array if the query is successful, otherwise an empty array. 
	*/
	static function assoc_query_2D_param($values, $table)
	{
		$sql = "SELECT ".$values." FROM ".$table;
		$result = mysql_query($sql);
		$arr = array();
		while($temp = mysql_fetch_assoc($result)){
			array_push($arr, $temp);
		}
			
		return $arr;
	}

	/**
	* Uses arguments to gerate a sql query string to fetch a 1D array.
	* @see DBconnect::assoc_query_1D()
	* @param mixed $id_value The value of the id to use.
	* @param string $id_name The name of the id field to use.
	* @param string $table The name of the table to use.
	* @param string $value_name The name(s) of the values to retrieve.
	* @return mixed Returns the resultant array if the query is successful, otherwise 0.
	*/
	static function assoc_query_1D_param($id_value, $id_name, $table, $value_name)
	{
		$sql = "SELECT ".$value_name." FROM ".$table." WHERE ".$id_name."='".$id_value."';";
		$result = mysql_query($sql);
		if($result) 
			return mysql_fetch_assoc($result);
		else
			return 0;
	}

	/**
	* Same as assoc_query_2D but we also impose a limit with the help of two arguments.
	* @param TYPE $sql The main sql string to build upon.
	* @param TYPE $min The start value used in the LIMIT
	* @param TYPE $max The offset value used in the LIMIT
	* @return array Returns the resultant 2D array if the query was successful, otherwise the array will be empty.
	*/
	static function assoc_query_2D_limit($sql, $min, $max)
	{
		$sql .= " LIMIT ".$min." , ".$max;
		$result = mysql_query($sql);
		$arr = array();
		$row = array();
		while($row = mysql_fetch_assoc($result))
			array_push($arr, $row);
			
		return $arr;
	}

	/**
	* Simple fetch of 1D array with the help of a sql string.
	* @see DBconnect::assoc_query_1D_param()
	* @param string $sql The sql string to use.
	* @return mixed Returns the resultant array if the query is successful, otherwise 0.
	*/
	static function assoc_query_1D($sql)
	{
		$result = mysql_query($sql);
		if(!$result) 
			return 0;
		else
			return mysql_fetch_assoc($result);
	}

	/**
	* Returns a one dimensional associative array as the result from a query that will generate a 2D result. We do this with the help of array_merge().
	* @see DBconnect::row_retrieve_2D_Conv_1D()
	* @param string $sql The sql string to use.
	* @return array The resultant 1D array, will be empty in case of failure.
	*/
	static function assoc_retrieve_2D_conv_1D($sql)
	{
		$result = mysql_query($sql);
		$arr = array();
		$row = array();
		while($row = mysql_fetch_assoc($result))
			$arr = array_merge($arr, $row);
			
		return $arr;
	}

	/**
	* Returns a one dimensional array as the result from a query that will generate a 2D result. We do this with the help of current() and array_push().
	* @see DBconnect::assoc_retrieve_2D_conv_1D()
	* @param string $sql The sql string to use.
	* @return array The resultant 1D array, will be empty in case of failure.
	*/
	static function row_retrieve_2D_Conv_1D($sql)
	{
		$result = mysql_query($sql);
		$arr = array();
		$row = array();
		while($row = mysql_fetch_row($result))
			array_push($arr, current($row));
			
		return $arr;
	}
	
	
	/**
	* Returns a 2 dimensional array as the result from a query that will generate a 2D result. 
	* @see DBconnect::row_retrieve_2D()
	* @param string $sql The sql string to use.
	* @return array The resultant 2D array, will be empty in case of failure.
	*/
	static function row_retrieve_2D($sql)
	{
		$result = mysql_query($sql);
		$arr = array();
		$row = array();
		while($row = mysql_fetch_row($result))	$arr[]=$row;

		return $arr;
	}


	/**
	* Inserts the values in the array into the passed table name given that the field names fit with the keys. Takes a 2D array.
	* @uses DBconnect::assoc_insert_1D()
	* @param array &$arr The 2D array that contains the values to insert. 
	* @param string $table The table name.
	* @return bool Through assoc_insert_1D will return 1 on success, 0 otherwise.
	*/
	static function assoc_insert_2D(&$arr, $table)
	{
		reset($arr);
		do{ 
			self::assoc_insert_1D(current($arr), $table);
		}while(next($arr));
	}

	/**
	* Inserts the values in the array into the passed table name given that the field names fit with the keys. Takes a 1D array.
	* @uses DBconnect::assoc_insert_1D()
	* @param array &$arr The 1D array that contains the values to insert. 
	* @param string $table The table name.
	* @return bool Will return 1 on success, 0 otherwise.
	*/
	static function assoc_insert_1D(&$arr, $table)
	{
		$sql = "INSERT INTO ".$table;
		$names = " (";
		$values = " VALUES (";
		reset($arr);
		$i = 0;
		while (list($key, $val) = each($arr)) {
   			if($i < count($arr) - 1){
			   	$names .= $key.",";
			   	$values .= "'$val'" . ",";
			}else{
				$names .= $key.")";
			  	$values .= "'$val'" . ");";
			}
			$i++;
		}
		$sql .= $names.$values;
		$result = mysql_query($sql);
		if($result)
			return 1;
		else
			return 0;
	}

	/**
	* Simply retrieves one value with the help of a sql string.
	* @param string $sql The sql string to use.
	* @return mixed Returns the value upon success, otherwise FALSE
	*/
	static function retrieve_value($sql)
	{
	  	$result = mysql_query($sql);
		if($result!=null)
			$row = mysql_fetch_row($result);
		if($row)
			return current($row);
		else
			return false;
	}

	/**
	* Simply retrieves one value with the help of arguments.
	* @uses DBconnect::retrieve_value() to retrieve the value with the help of the generated string.
	* @param string $table The table to use.
	* @param string $field the name of the field to use.
	* @param string $id_name The name of the id field to use.
	* @param mixed $id_value The value of the id to look for.
	* @return mixed Returns the value upon success, otherwise FALSE
	*/
	static function retrieve_value_param($table, $field, $id_name, $id_value)
	{
	  	$sql = "SELECT ".$field." FROM ".$table." WHERE ".$id_name." = '".$id_value."'";
		return self::retrieve_value($sql);
	}

	/**
	* Logs in a user if the supplied information checks up with the table the user is stored in. The password check is made with LIKE BINARY.
	* @uses DBconnect::assoc_query_1D() to retrieve the user with the help of the generated string.
	* @param string $password The password to be tested.
	* @param string $username The username to be tested.
	* @param string $table The table to be used.
	* @param string $password_field The name of the field where the passwords are stored.
	* @param string $username_field The name of the field where the usernames are stored.
	* @return mixed Returns a 1D array if the user is found, 0 otherwise.
	*/
	static function login($password, $username, $table, $password_field, $username_field)
	{
	  	$sql = "SELECT *, ((now() - interval 1 hour) > signup_datetime) as tcheck FROM ".$table." WHERE ".$username_field."='$username' AND ".$password_field." LIKE BINARY '$password' AND ".TABLE_MEMBER_ISACTIVE."=1";
		return DBconnect::assoc_query_1D($sql);
	}

	/**
	* Retrieves all the field names in a table as a 1d array
	* @param string $username The username to be tested.
	* @return mixed Returns a 1D array if the user is found, 0 otherwise.
	*/
	static function get_col_names($table)
	{
	 	$sql = "SHOW COLUMNS FROM ".$table;
	 	$result = mysql_query($sql);
	 	$rarr = array();
	 	while($row = mysql_fetch_assoc($result))
	 		array_push($rarr, $row['Field']);
	 	return $rarr;
	}

	/**
	* Retrieves the maximum values
	* @param string $table The table name.
	* @param string $field The field name.
	* @return $max is the maximum values.
	* @author Pakin R.
	*/
	static function get_max_value($table,$field){
		$sql = "SELECT MAX($field) AS maxi FROM $table";
		$rec =  DBconnect::assoc_query_1D($sql); 
		$max = $rec['maxi'];
		return $max;
	}

    /**
	* Retrieves The number of query rows.
	* @param string $sql The sql statement. 
	* @return $rows is The number of query rows.
	* @author Pakin R.
	*/
	static function num_rows($sql){ 
		$que = mysql_query($sql);
		$rows = mysql_num_rows($que);
		return $rows;
	}

	/**
	* This function is used for delete record data from database. 
	* Delete record data from database
	* @param $tb_name  this is a table name
	* @param int $cond  this is a condition for delete the record 
	*/ 	
	static function delete_data ($tb_name,$cond)
	{
		 if (empty($cond)) {
			  print "Warning \$str_pk can not empty in method delete_data.... (If you want to delete all please use the function 'empty_tb' )";
		 }else{
			  mysql_query ("DELETE FROM $tb_name $cond");
		 } 
	}

	static function advance_search($table_name, &$arr, $id_name, $colum)
	{	  		
		$i = 0;
		foreach($colum as $key1 => $c_value)
		{
			foreach($arr as $key2 => $value)
			{
				if($i < (count($arr) - 1))
				{		
					if($value!="")
					{
						$cond .= ($cond=="")? " where " : " and ";
						$cond .= "(m.$c_value[$key1] like '%{$value}%')";
					}
				}
			$i++;
			}
		}
		$sql = "select m.* from ".$table_name." as m $cond order by picturepath desc";
		return self::assoc_query_2D($sql);
	}
	
	/**
	* This function is used for get record data from database. 
	* Get 2dimentions record data from database
	* @param $sql  this is full sql statement
	*/ 	
	static function profile($sql){
		$result=mysql_query($sql);
		while ($record=mysql_fetch_array($result)) {
			$arr_record[] = $record;	
		}	
		return $arr_record;
	}
	
	
	static function sql_to_assoc($sql)
	{
		$result = mysql_query($sql);
		$arr = array();
		
		while($row = mysql_fetch_row($result))	$arr[$row[0]]=$row[1];

		return $arr;
	}


}
?>