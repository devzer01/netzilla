<?php
define('ARB_PATH_TEMP','/srv/www/phplibs/cache/');

/*   MYSQL */
class ARB_Mysql{
	var $db;
	var $last_query;
	var $tab_struct;

	private static $instance;


	public static function getInstance() {
		return self::$instance;
	}


	public function setDefInstance()
	{
		self::$instance=$this;
	}

	//constructor
	public function __construct ($serv='',$user='',$pass='',$dbname=''){
		if($serv && $user && $pass){
			$this->connection($serv,$user,$pass,$dbname);
		}
	}

	//disconnect from DB
	public function __destruct(){
		@mysql_close($this->db);
	}

	public function connection($serv,$user,$pass,$dbname=''){
		$this->db=mysql_connect($serv,$user,$pass) or die("No connections: " . mysql_error());
		$this->tab_struct=array();
		if($dbname)mysql_select_db($dbname,$this->db) or die("No connect to DB".$dbname);

		$this->loadTabStruct($dbname);

		return $this->db;
	}

	/**
	 * @see workspace/include/DB/DBObject::disconnect()
	 */
	public function disconnect(){
		@mysql_close($this->db);
	}

	/**
	 * get one parameter
	 */
	public function getOne($sql,$rdef=''){
		$this->last_query=$sql;
		$query=@mysql_query($sql,$this->db);
		$row=@mysql_fetch_row($query);
		@mysql_free_result($query);
		$ret=($row[0])?$row[0]:$rdef;
		return $ret;
	}

	/**
	 * @see workspace/include/DB/DBObject::getArray()
	 */
	public function getArray($sql){
		$this->last_query=$sql;
		$query=@mysql_query($sql,$this->db);
		$row=@mysql_fetch_row($query);
		@mysql_free_result($query);
		if(!is_array($row)){
			$row=array();
		}
		return $row;
	}

	/**
	 * @see workspace/include/DB/DBObject::getAssoc()
	 */
	public function getAssoc($sql){
		$this->last_query=$sql;
		$query=@mysql_query($sql,$this->db);
		$row=@mysql_fetch_assoc($query);
		@mysql_free_result($query);

		if(!is_array($row)){
			$row = array();
		}

		return $row;
	}

	/**
	 * @see workspace/include/DB/DBObject::getAllAssoc()
	 */
	public function getAllAssoc($sql){
		$this->last_query=$sql;
		$query=@mysql_query($sql,$this->db);
		$ret=array();
		while($row=@mysql_fetch_assoc($query)){
			$ret[]=$row;
		}
		@mysql_free_result($query);
		return $ret;
	}

	/**
	 * @see workspace/include/DB/DBObject::getAllArray()
	 */
	public function getAllArray($sql){
		$this->last_query=$sql;
		$query=@mysql_query($sql,$this->db);
		$ret=array();
		while($row=@mysql_fetch_array($query)){
			$ret[]=$row;
		}
		@mysql_free_result($query);
		return $ret;
	}

	/**
	 * @see workspace/include/DB/DBObject::getCol()
     * select name form table;
	 */
	public function getCol($sql)
	{
		$ret=array();

		$query = @mysql_query($sql,$this->db);
		while($row=@mysql_fetch_row($query)){
			$ret[]=$row[0];
		}
		@mysql_free_result($query);
		return $ret;
	}

	/**
	 * @see workspace/include/DB/DBObject::query()
	 */
	public function query($sql){
		$this->last_query=$sql;
		$query=@mysql_query($sql,$this->db);
		$result=@mysql_affected_rows($this->db);
		return $result;
	}

	/**
	 * @see workspace/include/DB/DBObject::insert()
     * $db->insert($tabname,array('name'=>"ad'",'age'=>34,ID=>12),array('ID'=>1),2,true);
     * $auto_quote 0-no, 1-allways, 2-from var type
	 */
	public function insert($tab,$obj_hash,$ignore,$auto_quote,$auto_safe=false){

		$index=0;
		$tab_struct=$this->getTabStruct($tab);

		if(!is_array($ignore)){  $ignore=array();    }

	 $k_st='';
	 $v_st='';

	 if(is_array($tab_struct) && is_array($obj_hash) ){

	 	foreach( array_keys($tab_struct) as $ky){
	 		if(isset($obj_hash[$ky]) && !isset($ignore[$ky])){

	 			if($auto_safe){
	 				$obj_hash[$ky]=mysql_real_escape_string($obj_hash[$ky]);
	 			}
	 			if($auto_quote==1 || ($auto_quote==2 && ($tab_struct[$ky] || preg_match("/^[0-9]/",$obj_hash[$ky] )|| $obj_hash[$ky]=='' ) ) ){
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


	public function replace($tab,$obj_hash,$ignore,$auto_quote,$auto_safe=false){

		$tab_struct=$this->getTabStruct($tab);

		if(!is_array($ignore)){  $ignore=array();    }

	 $k_st='';
	 $v_st='';

	 if(is_array($tab_struct) && is_array($obj_hash) ){

	 	foreach( array_keys($tab_struct) as $ky){
	 		if(isset($obj_hash[$ky]) && !isset($ignore[$ky])){

	 			if($auto_safe){
	 				$obj_hash[$ky]=mysql_real_escape_string($obj_hash[$ky]);
	 			}
	 			if($auto_quote==1 || ($auto_quote==2 && ($tab_struct[$ky] || preg_match("/^[0-9]/",$obj_hash[$ky]) || $obj_hash[$ky]=='' ) ) ){
	 				$obj_hash[$ky]="'".$obj_hash[$ky]."'";
	 			}

	 			$k_st.="$ky,";
	 			$v_st.=$obj_hash[$ky].",";
	 		}
	 	}

	 	$k_st=substr($k_st,0,-1);
	 	$v_st=substr($v_st,0,-1);

	 	$sql = "replace into $tab ($k_st) values ($v_st)";


	 	$this->last_query=$sql;
	 	$query=@mysql_query($sql,$this->db);


	 }


	}


	/**
	 * @see workspace/include/DB/DBObject::update()
	 */
	public function update($tab,$obj_hash,$ignore,$param_str,$auto_quote,$auto_safe=false){

		$index=0;
		$tab_struct=$this->getTabStruct($tab);

		if(!is_array($ignore)){  $ignore=array();    }

	 $v_st='';

	 if(is_array($tab_struct) && is_array($obj_hash) && $param_str ){

	 	foreach( array_keys($tab_struct) as $ky){
	 		if(isset($obj_hash[$ky]) && !isset($ignore[$ky])){

	 			if($auto_safe){
	 				$obj_hash[$ky]=mysql_real_escape_string($obj_hash[$ky]);
	 			}
	 			if($auto_quote==1 || ($auto_quote==2 && ($tab_struct[$ky] || preg_match("/^[0-9]/",$obj_hash[$ky]) || $obj_hash[$ky]=='' ) ) ){
	 				$obj_hash[$ky]="'".$obj_hash[$ky]."'";
	 			}

	 			$v_st.=$ky."=".$obj_hash[$ky].",";
	 		}
	 	}

	 	$v_st=substr($v_st,0,-1);

	 	$sql = "update $tab set $v_st where $param_str";


	 	$this->last_query=$sql;
	 	$query=@mysql_query($sql,$this->db);
	 	$index=@mysql_affected_rows($this->db);

	 	if(!is_int($index)){
	 		return 0;
	 	}
	 }

	 return $index;
	}


	/**
	 * @see workspace/include/DB/DBObject::getTabStruct()
	 */
	protected function getTabStruct($tname,$value=true){
		$str=array();
	 if( !isset($this->tab_struct[$tname]) ){
	 	$query = @mysql_query("SHOW COLUMNS FROM ".$tname);

		 while($row = @mysql_fetch_row($query)){
		 	if($value){
		 		$str[$row[0]]=( preg_match("/date|time/i",$row[1]) )?0:1;
		 	}
		 	else{
		 		if(preg_match("/int/i",$row[1]))$str[$row[0]]=0;
		 		else $str[$row[0]]='';
		 	}
		 }

		 @mysql_free_result($query);

		 $this->tab_struct[$tname]=$str;
	 }
	 return $this->tab_struct[$tname];
	}

	protected function saveTabStruct($dbname)
	{
		$this->query("use ".$dbname);
		$tabl = $this->getCol("show tables");


		foreach($tabl as $tmp)
		{
			$this->getTabStruct($tmp);
		}

		if(!file_exists(ARB_PATH_TEMP.'db_'.$dbname.'.php'))
		{
			$trs=var_export($this->tab_struct,true);
			file_put_contents(ARB_PATH_TEMP.'db_'.$dbname.'.php',
					'<?php $tab_struct ='.$trs.'; ?>');
		}
	}

	protected function loadTabStruct($dbname)
	{
		if(file_exists(ARB_PATH_TEMP.'db_'.$dbname.'.php'))
		{
			require_once ARB_PATH_TEMP.'db_'.$dbname.'.php';
			$this->tab_struct = $tab_struct;
		}
		else
		{
			$this->saveTabStruct($dbname);
		}
	}

	public function debug()
	{
		return $this->last_query;
	}


}// mysql end
