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
 * Class qUser
 */
class qUser {
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
    // Get the database object. This is a singleton object so there is
    // only one database object in one client request.
    $db = db::getObject();
    $result = $db->query("SELECT * FROM users ORDER BY name");
    return $db->fetchRows($result);
  }

  /**
   * Method for selecting one user data by ID.
   *
   * @access public
   * @return array The array with the following structure:
   *   array(
   *    "id" => 1,
   *     "name" => "John Doe",
   *   )
   */
  public function selectById($id) {
    $db = db::getObject();
    // Make sure the passed ID is number by using parseNumber().
    // We can also use pQuery() instead. See the pQuery function in
    // dbmysql.php.
    $result = $db->query("SELECT * FROM users WHERE id=" . $db->parseNumber($id));
    // Return one row using fetchRow() NOT fetchRows().
    return $db->fetchRow($result);
  }

  /**
   * Method for inserting new user data.
   *
   * @access public
   * @param string $name The new user name.
   * @return boolean TRUE when the process is success or FALSE when failed.
   */
  public function insert($name) {
    $db = db::getObject();
    // We use pExecute() for security reason because the input string
    // is escaped. If you use execute(), please use escapeString()
    // function to escape the input string.
    return $db->pExecute("INSERT INTO users (name) VALUES (:name)",
       array (
         ":name" => $name,
       ));
  }

  /**
   * Method for updating user data.
   *
   * @access public
   * @param string $name The new user name.
   * @param int $id The ID of user record that will be updated.
   * @return boolean TRUE when the process is success or FALSE when failed.
   */
  public function update($name, $id) {
    $db = db::getObject();
    // We use pExecute() for security reason because the input string
    // is escaped. If you use execute(), please use escapeString()
    // function to escape the input string or use the parseNumber() if
    // the input type is number.
    return $db->pExecute("UPDATE users SET name=:name WHERE id=:id",
       array (
         ":name" => $name,
         ":id" => $id,
       ));
  }

  /**
   * Method for inserting new user data.
   *
   * @access public
   * @param string $name The new user name.
   * @return boolean TRUE when the process is success or FALSE when failed.
   */
  public function delete($id) {
    $db = db::getObject();
    // Execute the delete command.
    return $db->pExecute("DELETE FROM users WHERE id=:id",
       array (
         ":id" => $id,
       ));
  }
}
