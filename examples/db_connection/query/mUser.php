<?php
/**
 * This is the Model class for managing user. One Model should represent
 * one entity.
 *
 * The CRUD functions for the entity should be placed here.
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource mUser.php
 */

// Prevent direct access
defined("INDEX") or die("");

/**
 * Class mUser
 */
class mUser {
  /**
   * Holds connection object reference.
   */
  private static $instance = null;

  /**
   * Constructor method.
   */
  private function __construct() {
    // Blank and private constructor so there will be no more than one instance.
  }

  /**
   * Method for retrieving singleton object for this Model.
   *
   * @access public
   * @return object The full object for this Model.
   */
  public static function getObject() {
    if (self::$instance === NULL) {
      $class = __CLASS__;
      self::$instance = new $class();
    }

    return self::$instance;
  }

  /**
   * Method for selecting users data.
   *
   * @access public
   * @return array The array with the following structure:
   *   array(
   *     0 => array(
   *       "id" => 1,
   *       "name" => "John Doe",
   *     ),
   *     1 => array(
   *       "id" => 2,
   *       "name" => "Jane Doe",
   *     ),
   *     ...
   *   )
   */
   public function select() {
     $db = db::getObject();
     $result = $db->query("SELECT * FROM users");
     return $db->fetchRows($result);
   }
}
