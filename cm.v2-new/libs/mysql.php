  <?
  class mysql{
     var $db;
     var $last_query;
     var $tab_struct;

     //constructor
     function mysql($serv='',$user='',$pass='',$dbname=''){
        if($serv && $user && $pass){
            $this->connection($serv,$user,$pass,$dbname);
        }
     }

     // connect to DB
     function &connection($serv,$user,$pass,$dbname=''){
        $this->db=mysql_connect($serv,$user,$pass) or die("No connections: " . mysql_error());
        $this->tab_struct=array();
        if($dbname)mysql_select_db($dbname,$this->db) or die("No connect to DB".$dbname);
        return $this->db;
     }

     //disconnect from DB
     function &disconnect(){
        @mysql_close($this->db);
     }

     //get one result
     function &getOne($sql,$rdef=''){
        $this->last_query=$sql;
        $query=@mysql_query($sql,$this->db);
        $row=@mysql_fetch_row($query);
        @mysql_free_result($query);
        $ret=($row[0])?$row[0]:$rdef;
        return $ret;
     }

     //get array
     function &getArray($sql){
        $this->last_query=$sql;
        $query=@mysql_query($sql,$this->db);
        $row=@mysql_fetch_row($query);
        @mysql_free_result($query);
        if(is_array($row)){
            return $row;
        }
        else{
           return array();
        }
     }

     //get hash array
     function &getAssoc($sql){
        $this->last_query=$sql;
        $query=@mysql_query($sql,$this->db);
        $row=@mysql_fetch_assoc($query);
        @mysql_free_result($query);

        if(is_array($row)){
            return $row;
        }
        else{
           return array();
        }
     }

     //get hash array
     function &getAllAssoc($sql){
        $this->last_query=$sql;
        $query=@mysql_query($sql,$this->db);
        $ret=array();
        while($row=@mysql_fetch_assoc($query)){
          $ret[]=$row;
        }
        @mysql_free_result($query);
        return $ret;
     }

     //do
     function &query($sql){
        $this->last_query=$sql;
        $query=@mysql_query($sql,$this->db);
        return @mysql_affected_rows($this->db);
     }

     //insert into table table_name,param array, ignore array ,int, boolean
     function insert($tab,$obj_hash,$ignore,$auto_quote,$auto_safe=false){

	    $index=0;
  	    $tab_struct=$this->getTabStruct($tab);

        if(!is_array($ignore)){  $ignore=array();    }

	 $k_st='';
	 $v_st='';

	if(is_array($tab_struct) && is_array($obj_hash) ){

		foreach( array_keys($tab_struct) as $ky){
			if(isset($obj_hash[$ky]) && !isset($ignore[$ky])){

				if($auto_safe){
					$obj_hash[$ky]=addslashes(stripslashes($obj_hash[$ky]));
				}
				if($auto_quote==1 || ($auto_quote==2 && ($tab_struct[$ky] || preg_match("/^[0-9]+-[0-9]+-[0-9]+/",$obj_hash[$ky]) ) ) ){
					$obj_hash[$ky]="'".$obj_hash[$ky]."'";
				}

				$k_st.="$ky,";
				$v_st.=$obj_hash[$ky].",";
			}
		}

		$k_st=substr($k_st,0,-1);
        $v_st=substr($v_st,0,-1);

		$sql = "insert into $tab ($k_st) values ($v_st)";


		$this->last_query=$sql;
        $query=@mysql_query($sql,$this->db);
        $index=@mysql_insert_id($this->db);

        if(!is_int($index)){
            return 0;
        }
	}

	return $index;
}


function getTabStruct($tname){
     $str=array();
	 if( !isset($this->tab_struct[$tname]) ){
		 $query = @mysql_query("SHOW COLUMNS FROM ".$tname);

		 while($row = @mysql_fetch_row($query)){
			$str[$row[0]]=( preg_match("/date|time|int/i",$row[1]) )?0:1;
		 }

         @mysql_free_result($query);

		 $this->tab_struct[$tname]=$str;
	 }
    return $this->tab_struct[$tname];
}

     //get last query
     function get_last_query(){
       return $this->last_query;
     }

  }

  ?>