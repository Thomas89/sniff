<?php
/**
 * Class for handling database connection
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource db.php
 */

/**
 * Class db
 */
class db {
  /**
   * Holds connection object references
   */
  private static $objects = array();

  /**
   * Constructor
   */
  private function __construct() {
    // Blank constructor so there will be no more than one instance.
  }

  /**
   * Method for retrieving singleton object for database connection.
   *
   * @access public
   * @param string $db_type The database type. This string will determine which database type. Also configuration file that will be used if $config_class is empty.
   * @param string $framework_dir The config class name. Default to "null". If it's "null", it will use "config_" . $db_type.
   * @param string $framework_dir The framework location. Default to "null". If it's "null", it will use the location defined in config_web class.
   */
  public static function getObject($db_type = "mysql", $config_class = null, $framework_dir = null) {
    $class = "db".$db_type;

    if ($framework_dir==null) {
      $framework_dir = config_web::$framework_dir;
    }

    require_once($framework_dir."/db/".$class.".php");

    if ($config_class==null) {
      $config_class = "config_db".$db_type;
    }

    $object = call_user_func_array(array($class, 'getObject'), array($config_class));

    self::$objects[$class] = $object;

    return $object;
  }

  /**
   * Method for closing all database connection.
   *
   * @access public
   */
  public static function closeAll() {
    foreach(self::$objects as $object){
      $object->close();
    }
  }
}
