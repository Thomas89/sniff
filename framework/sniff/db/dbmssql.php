<?php
/**
 * Class for handling database MS-SQL connection (under development)
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource dbmssql.php
 */

/**
 * Class dbmssql
 */
class dbmssql{
  /**
   * @var connection Active database connection
   * @access private
   */
  private $con;

  /**
   * @var object Instance for singleton function
   * @access private
   */
  private static $instance=array();

  /**
   * Constructor
   *
   * @access private
   * @param string $host Hostname
   * @param string $user Username
   * @param string $password Password
   * @param string $database Database name
   */
  private function __construct($host, $user, $password, $database){
    $this->con=@mssql_connect($host, $user, $password);
    if($this->con){
      if(!@mssql_select_db($database, $this->con)){
        throw new Exception("ERROR WHEN OPENING DATABASE! Please check your configuration file.");
      }
    }
    else{
      throw new Exception("DATABASE CONNECTION ERROR! Please check your configuration file.");
    }
  }

  /**
   * Method for retrieving singleton object
   *
   * @access public
   */
  public static function getObject($config_class = null){
    if (!isset(self::$instance[$config_class])) {
      $class = __CLASS__;
      $c = new $config_class();
      self::$instance[$config_class] = new $class($c->dbHost, $c->dbUser, $c->dbPass, $c->dbName);
    }

    return self::$instance[$config_class];
  }

  /**
   * Method for adding escape character
   *
   * @access public
   * @param string $string String yang akan di-escape
   * @return string String yang telah di-escape
   */
  public function escapeString($string){
    if(get_magic_quotes_gpc()){
      $string=stripslashes($string);
    }

    $string=str_replace("'","''",$string);

    return $string;
  }

  /**
   * Method for executing SELECT
   *
   * @access public
   * @param string $sql SQL command
   * @return resultset Query result
   */
  public function query($sql){
    $result=@mssql_query($sql, $this->con);
    if(!$result){
      throw new Exception(mssql_get_last_message());
    }

    return $result;
  }

  /**
   * Method executing SELECT with paging (LIMIT)
   *
   * @access public
   * @param string $sql SQL command
   * @param int $rowsPerPage Displayed rows per page
   * @param int &$page Nomor Current page number
   * @param int &$totalPages Total pages produced from query
   * @return resultset Query result
   */
  public function pagerQuery($sql, $rowsPerPage, &$page, &$totalPages){
    $result=$this->query($sql);

    $totalRows=$this->affectedRows($result);

    $regex='[ ]*order[ ]+by[ ]+.+$';
    preg_match('/'.$regex.'/i', $sql, $matches);
    $order_by=$matches[0];
    $sql=preg_replace('/'.$regex.'/i', '', $sql);

    $totalPages=intval($totalRows/$rowsPerPage) + ($totalRows%$rowsPerPage==0 ? 0 : 1);
    if($totalPages<1){
      $totalPages=1;
    }

    $page=intval($page);
    if($page<1){
      $page=1;
    }
    if($page>$totalPages){
      $page=$totalPages;
    }

    $start=($page-1)*$rowsPerPage+1;

    // For older SQL server that didn't support row number query.
    /*
    $sql = "select * from (
      select
        row_number() over(".$order_by.") as rn,
      t.*
      from
        (".$sql.") t
    ) t
    where
      t.rn BETWEEN ".$start." AND ".($start+$rowsPerPage-1);

    $result=$this->query($sql);

    return $result;
    */

    /*
    mssql_query("select identity(int, 1, 1) as rownum, t.* into #tmp from (".$sql.") t ".$order_by);
    $result = mssql_query("select * from #tmp where rownum between ".$start." AND ".($start+$rowsPerPage-1));

    mssql_query("drop table #tmp");

    return $result;
    */

    $result = $this->query($sql);

    return $result;
  }

  /**
   * Method for fetching one row from result set
   *
   * @access public
   * @param resultset $result Resultset
   * @return array Array in following format
   *    Array(
   *      [field1] => field1 content
   *      [field2] => field2 content
   *      ...
   *    )
   */
  public function fetchRow($result){
    return mssql_fetch_assoc($result);
  }

  /**
   * Method for fetching all rows from result set into 2 dimensional array
   *
   * @access public
   * @param resultset $result Resultset
   * @return array 2 dimensional array in following format
   *    Array(
   *      [0] => Array(
   *        [field1] => field1 content
   *        [field2] => field2 content
   *        ...
   *      )
   *      ...
   *    )
   */
  public function fetchRows($result){
    $arr=array();
    while($rows=$this->fetchRow($result)){
      array_push($arr, $rows);
    }

    return $arr;
  }

  /**
   * Method for executing INSERT, UPDATE, or DELETE
   *
   * @access public
   * @param string $sql SQL command
   * @return boolean TRUE if execution success
   */
  public function execute($sql){
    if(!@mssql_query($sql, $this->con)){
      throw new Exception(mssql_get_last_message());
    }

    return true;
  }

  /**
   * Method for getting the last inserted row id
   *
   * @access public
   * @return integer The last inserted row id
   */
  public function getLastInsertId(){
    $id = 0;
    $res = $this->query("SELECT @@identity AS id");
    if ($row = $this->fetchRow($res)) {
      $id = $row["id"];
    }

    return $id;
  }

  /**
   * Method for getting affected rows by INSERT, UPDATE, DELETE, and SELECT
   *
   * @access public
   * @param mixed $result MS-SQL resultset (if any)
   * @return int Number of affected rows
   */
  public function affectedRows($result = null){
    if($result == null){
      return mssql_rows_affected($this->con);
    }
    else{
      return mssql_num_rows($result);
    }
  }

  /**
   * Method for starting transaction
   *
   * @access public
   */
  public function begin(){
    return $this->execute("BEGIN TRANSACTION", $this->con);
  }

  /**
   * Method for executing commit
   *
   * @access public
   * @return boolean TRUE if commit success
   */
  public function commit(){
    return $this->execute("COMMIT", $this->con);
  }

  /**
   * Method for executing rollback
   *
   * @access public
   * @return boolean TRUE if rollback success
   */
  public function rollback(){
    return $this->execute("ROLLBACK", $this->con);
  }

  /**
   * Alternative method for executing INSERT
   *
   * @param string $table Table name
   * @param array $data Data to be inserted
   * @param array $option Additional options
   * @return boolean Execution status
   */
  public function lazzyInsert($table, $data, $option=array()){
    $fields=array();
    $values=array();
    $option_default=array('raw_data'=>array());

    $option=array_merge($option_default, $option);

    while(list($k,$v)=each($data)){
      $fields[]="[".$k."]";

      // Get data type
      $type = gettype($v);

      // Don't add quote for fields listed in "raw_key" option or have NULL or boolean data type
      if(in_array($k, $option['raw_data']) || $type=="NULL" || $type=="boolean"){
        $values[]=($type == "NULL" ? $type : $v);
      }
      else{
        if($type=="integer" || $type=="double"){
          $values[]=$this->parseNumber($this->escapeString($v));
        }
        else{
          $values[]="'".$this->escapeString($v)."'";
        }
      }
    }

    // Generate INSERT statement and execute it
    return $this->execute("INSERT INTO [".$table."] (".implode(", ", $fields).") VALUES (".implode(", ", $values).")");
  }

  /**
   * Method for creating filter criteria
   *
   * @param array $data Filter data
   * @param array $option Additional options
   * @return array Data for filtering query
   */
  public function createFilter($data, $option=array()){
    $filter=array();

    // Populate criteria
    while(list($k, $v)=each($data)){
      // Populate option
      $option_default=array('raw_key'=>array(), 'key_opr'=>array());
      $option=array_merge($option_default, $option);

      // Check key operator based on "key_opr" option
      if(isset($option['key_opr'][$k])){
        $opr=" ".$option['key_opr'][$k]." ";
      }
      else{
        $opr=" = ";
      }

      // Get data type
      $type = gettype($v);

      // Don't add quote for fields listed in "raw_key" option or have NULL or boolean data type
      if(in_array($k, $option['raw_key']) || $type=="NULL" || $type=="boolean"){
        $filter[]="[".$k."]".$opr.($type == "NULL" ? $type : $v);
      }
      else{
        if($type=="integer" || $type=="double"){
          $filter[]="[".$k."]".$opr.$this->parseNumber($v);
        }
        else{
          $filter[]="[".$k."]".$opr."'".$this->escapeString($v)."'";
        }
      }
    }

    return $filter;
  }

  /**
   * Method for creating order by
   *
   * @param array $columns List of columns to be ordered
   * @param array $desc_columns List of columns to be ordered descendingly
   * @return array List of columns for ordering
   */
  public function createOrderBy($columns, $desc_columns=array()){
    for($i=0;$i<count($columns);$i++){
      $tmp = $columns[$i];
      $columns[$i] = "[".$columns[$i]."]";
      if(in_array($tmp, $desc_columns)){
        $columns[$i] .= " DESC";
      }
    }

    return $columns;
  }

  /**
   * Alternative method for executing SELECT
   *
   * @param string $table Table name
   * @param array $column Selected columns
   * @param array $key Filter criteria
   * @param array $option Additional options
   * @return boolean Execution status
   */
  public function lazzySelect($table, $column, $key=array(), $option=array()){
    $fields=array();
    $where=array();
    $option_default=array('raw_key'=>array(), 'key_opr'=>array(), 'order_column'=>array(), 'order_column_desc'=>array());

    $option=array_merge($option_default, $option);

    // List of fields
    for($i=0;$i<count($column);$i++){
      if(preg_match('/^([0-9]+|[*])$/', $column[$i]))
        $fields[]=$column[$i];
      else
        $fields[]="[".$column[$i]."]";
    }

    // Create filter for "WHERE"
    $where=$this->createFilter($key, $option);

    // Create order by
    $order_by=$this->createOrderBy($option['order_column_desc'], $option['order_column_desc']);

    // Generate SELECT statement and execute
    return $this->fetchRows($this->query("SELECT ".implode(", ", $fields)." FROM [".$table."] WHERE ".(count($where)>0 ? " WHERE ".implode(" AND ", $where) : "").(count($order_by)>0 ? " ORDER BY ".implode(", ", $order_by) : "")));
  }

  /**
   * Alternative method for executing UPDATE
   *
   * @param string $table Table name
   * @param array $data Data to be updated
   * @param array $key Filter criteria
   * @param array $option Additional options
   * @return boolean Execution status
   */
  public function lazzyUpdate($table, $data, $key, $option=array()){
    $values=array();
    $where=array();
    $option_default=array('raw_data'=>array(), 'raw_key'=>array(), 'key_opr'=>array());

    $option=array_merge($option_default, $option);

    while(list($k,$v)=each($data)){
      // Get data type
      $type = gettype($v);

      // Don't add quote for fields listed in "raw_key" option or have NULL or boolean data type
      if(in_array($k, $option['raw_data']) || $type=="NULL" || $type=="boolean"){
        $values[]="[".$k."] = ".($type == "NULL" ? $type : $v);
      }
      else{
        if($type=="integer" || $type=="double"){
          $values[]="[".$k."] = ".$this->parseNumber($v);
        }
        else{
          $values[]="[".$k."] = '".$this->escapeString($v)."'";
        }
      }
    }

    // Create filter for "WHERE"
    $where=$this->createFilter($key, $option);

    // Generate UPDATE statement and execute
    return $this->execute("UPDATE [".$table."] SET ".implode(", ", $values).(count($where)>0 ? " WHERE ".implode(" AND ", $where) : ""));
  }

  /**
   * Alternative method for executing DELETE
   *
   * @param string $table Table name
   * @param array $key Filter criteria
   * @param array $option Additional options
   * @return boolean Execution status
   */
  public function lazzyDelete($table, $key, $option=array()){
    $option_default=array('raw_key'=>array(), 'key_opr'=>array());

    $option=array_merge($option_default, $option);

    // Create filter for "WHERE"
    $where=$this->createFilter($key, $option);

    // Generate DELETE statement and execute
    return $this->execute("DELETE FROM [".$table."]".(count($where)>0 ? " WHERE ".implode(" AND ", $where) : ""));
  }

  /**
   * Prepared statement for querying result
   *
   * @param string $sql SQL string
   * @param array $parameters Query parameter
   * @return array 2 dimensional array in following format
   *    Array(
   *      [0] => Array(
   *        [field1] => field1 content
   *        [field2] => field2 content
   *        ...
   *      )
   *      ...
   *    )
   */
  public function pQuery($sql, $parameters){
    //print_r($parameters);
    // Replace the parameter in SQL string with the parameter from array
    $sql = $this->replaceSqlParameter($sql, $parameters);
    //echo $sql."<br><br>";
    $result = $this->query($sql);

    // Return the rows
    return $result;
  }

  /**
   * Method executing Procedure with paging
   *
   * @access public
   * @param string $sql SQL command
   * @param array $parameters Query parameter
   * @param int $rowsPerPage Displayed rows per page
   * @param int &$page Nomor Current page number
   * @param int &$totalPages Total pages produced from query
   * @return resultset Query result
   */
  public function pPagerQuery($sql, $parameters, $rowsPerPage, &$page, &$totalPages){
    $parameters['@pstart'] = null;
    $parameters['@plimit'] = null;
    $result=$this->pQuery($sql, $parameters);

    $totalRows=$this->affectedRows($result);

    $totalPages=intval($totalRows/$rowsPerPage) + ($totalRows%$rowsPerPage==0 ? 0 : 1);
    if($totalPages<1){
      $totalPages=1;
    }

    $page=intval($page);
    if($page<1){
      $page=1;
    }
    if($page>$totalPages){
      $page=$totalPages;
    }

    $page-=1;
    if($page<0){
      $page=0;
    }

    $parameters['@pstart'] = $page*$rowsPerPage+1;
    $parameters['@plimit'] = $rowsPerPage;

    $result=$this->pQuery($sql, $parameters);
    $page+=1;

    return $result;
  }

  /**
   * Prepared statement for executing query
   *
   * @param string $sql SQL string
   * @param array $parameters Query parameter
   * @return boolean TRUE on sucess, FALSE on failure
   */
  public function pExecute($sql, $parameters){
    // Replace the parameter in SQL string with the parameter from array
    $sql = $this->replaceSqlParameter($sql, $parameters);
    //echo $sql."<br>";
    $this->execute($sql);

    // Return the execution result
    return true;
  }

  /**
   * Replace SQL string parameter with the given array
   *
   * @access private
   * @param string $sql SQL string
   * @param array $parameters Query parameter
   * @return string SQL string
   */
  private function replaceSqlParameter($sql, $parameters){
    // Replace the parameter in SQL string with the parameter from array
    while(list($k, $v)=each($parameters)){
      $type = gettype($v);
      if($type=="integer" || $type=="double"){
        $sql = str_replace($k, $this->parseNumber($v), $sql);
      }
      elseif($type=="NULL"){
        $sql = str_replace($k, "NULL", $sql);
      }
      else{
        $sql = str_replace($k, "'".$this->escapeString($v)."'", $sql);
      }
    }

    return $sql;
  }

  /**
   * Method for parsing string to number.
   *
   * @access public
   * @param string $string Number
   * @return string If the given number is valid, it will be returned. Otherwise "0" will be returned
   */
  public function parseNumber($string){
    if(preg_match("/^[0-9]+(\.[0-9]+)?$/", $string)){
      return $string;
    }
    else{
      return "0";
    }
  }

  /**
   * Method for parsing array of string to number.
   *
   * @access public
   * @param array $arr_number Number
   * @return array If each given number is valid, it will be returned. Otherwise "0" will be returned
   */
  public function parseNumbers($arr_number){
    $arr = array();
    for ($i = 0; $i < count($arr_number); $i++) {
      if(preg_match("/^[0-9]+(\.[0-9]+)?$/", $arr_number[$i])){
        $arr[] = $arr_number[$i];
      }
    }

    return $arr;
  }

  /**
   * Method for closing database connection
   *
   * @access public
   */
  public function close(){
    if(!@mssql_close($this->con)){
      throw new Exception(mssql_get_last_message());
    }
  }
}
