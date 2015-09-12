<?php
/**
 * Class for retreiving HTTP request variable content, redirection, etc.
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource http.php
 */

/**
 * Class http
 */
class http {
  /**
   * Method for retreiving $_POST content safely to avoid NOTICE error.
   *
   * @param string $var The POST variable name.
   * @param string $default The default content if POST variable is not available. Default to "null".
   * @param boolean $trim Determine if the value will be trimmed from whitespace. Default to "false".
   * @return string The POST variable value.
   */
  public static function post($var, $default = null, $trim = false) {
    if (isset($_POST[$var])) {
      $return = $_POST[$var];
    }
    else {
      $return = $default;
    }

    if ($trim == true) {
      $return = trim($return);
    }

    return $return;
  }

  /**
   * Method for retreiving $_GET content safely to avoid NOTICE error.
   *
   * @param string $var The GET variable name.
   * @param string $default The default content if GET variable is not available. Default to "null".
   * @param boolean $trim Determine if the value will be trimmed from whitespace. Default to "false".
   * @return string The GET variable value.
   */
  public static function get($var, $default = NULL, $trim = false) {
    if (isset($_GET[$var])) {
      $return = $_GET[$var];
    }
    else {
      $return = $default;
    }

    if ($trim == TRUE) {
    $return = trim($return);
    }

    return $return;
  }

  /**
   * Method for retreiving the application root URL.
   * 
   * @access public
   * @return string The application main URL.
   */
  public static function getWebRoot() {
    preg_match("/(\/([A-Za-z0-9_-]+\/)*)[A-Za-z0-9_-]+\.php/", $_SERVER['SCRIPT_NAME'], $matches);

    return $matches[1];
  }

  /**
   * Method for redirecting page.
   *
   * @access public
   * @param string $page The page name for redirection. Can be absolute URL.
   */
  public static function redirect($page) {
    header("location:".$page);
    exit();
  }

  /**
   * Method for storing current and last 5 previous URL in session for used in later redirection process.
   *
   * @access public
   */
  public static function saveURL() {
    // HTTP Referer stores the previous URL from the last action so the current URL could be the same as the referer.
    // In this we need to store previous URL only if it's not the same as current URL.
    // We can use this for a lazzy, easy, and smart redirection method.
    $_SESSION[config_session::$current_url] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if ($_SESSION[config_session::$current_url] != $_SERVER['HTTP_REFERER']) {
      // Initialize the empty array at the first time.
			if (!isset($_SESSION[config_session::$previous_url])) {
        $_SESSION[config_session::$previous_url] = array();
      }
      else {
        // If it's not array, turn it into array.
        if (!is_array($_SESSION[config_session::$previous_url])) {
          $_SESSION[config_session::$previous_url] = array($_SESSION[config_session::$previous_url]);
        }
      }

      // Add the referer to the beginning.
      $_SESSION[config_session::$previous_url] = array_merge(array($_SERVER['HTTP_REFERER']), $_SESSION[config_session::$previous_url]);

      // We only store last 5 referer.
      if (count($_SESSION[config_session::$previous_url]) > 5) {
        array_pop($_SESSION[config_session::$previous_url]);
      }
    }
  }

  /**
   * Method for redirecting page to previous URL.
   *
   * @access public
   * @param number $step_back Decide how far we will go back. Default is -1 for one step back. For two steps back, use -2.
   */
  public static function redirectToPrev($step_back = -1){
    // Define the index based on the step back.
    $index = abs(1 + $step_back);

    // Make sure the previous URL exists. If it's not, we use the current URL.
    $url = $_SESSION[config_session::$current_url];
    if (isset($_SESSION[config_session::$previous_url][$index])) {
      $url = $_SESSION[config_session::$previous_url][$index];
    }

    self::redirect($url);
  }
}
