<?php
/**
 * Class for all session methods.
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource session.php
 */

/**
 * Class session
 */
class session {
  /**
   * Method to fill the value of userid session.
   *
   * @access public
   * @param string $value User ID that will be set
   */
  public static function setUserId($value) {
    if($value == null) {
      unset($_SESSION[config_session::$user_id]);
    }
    else {
      $_SESSION[config_session::$user_id] = $value;
    }
  }

  /**
   * Method to retrieve the content of userid session.
   *
   * @access public
   * @return string User ID
   */
  public static function getUserId() {
    return isset($_SESSION[config_session::$user_id]) ? $_SESSION[config_session::$user_id] : null;
  }

  /**
   * Method to set the value of username session
   *
   * @access public
   * @param string $value Username that will be set
   */
  public static function setUserName($value) {
    if($value == null) {
      unset($_SESSION[config_session::$user_name]);
    }
    else {
      $_SESSION[config_session::$user_name] = $value;
    }
  }

  /**
   * Method to retrieve the content of username session.
   *
   * @access public
   * @return string Username
   */
  public static function getUserName() {
    return isset($_SESSION[config_session::$user_name]) ? $_SESSION[config_session::$user_name] : null;
  }

  /**
   * Method to set user group session
   *
   * @access public
   * @param string $value User group that will be set
   */
  public static function setUserGroup($value) {
    if($value == null || $value == "") {
      unset($_SESSION[config_session::$user_group]);
    }
    else {
      $_SESSION[config_session::$user_group] = $value;
    }
  }

  /**
   * Method to get the content of user group session.
   *
   * @access public
   * @return string User group
   */
  public static function getUserGroup() {
    return isset($_SESSION[config_session::$user_group]) ? $_SESSION[config_session::$user_group] : config_session::$default_group;
  }

  /**
   * Method to set the message. The message is available in the next page after redirection process.
   *
   * @access public
   * @param string $value The message to be displayed. Can contain HTML tags too.
   */
  public static function setMessage($value) {
    if($value == null) {
      unset($_SESSION[config_session::$message]);
    }
    else {
      $_SESSION[config_session::$message] = $value;
    }
  }

  /**
   * Method to get the content of message that has been set previously.
   *
   * @access public
   * @return string The message content.
   */
  public static function getMessage() {
    return isset($_SESSION[config_session::$message]) ? $_SESSION[config_session::$message] : null;
  }

  /**
   * Function for displaying message stored in session.
   * 
   * @access public
   */
  public static function displayMessage(){
    if(self::getMessage()!=null){
      echo self::getMessage();
      self::setMessage(null);
    }
  }
}
