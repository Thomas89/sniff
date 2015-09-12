<?php
/**
 * Class for converting error into Exception
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource error.php
 */

/**
 * Class error
 */
class error{
  /**
   * @var int Error code
   * @access public
   */
  public static $errorType;

  /**
   * Method converting error into Exception
   *
   * @access public
   * @param int $errno Error code
   * @param string $errmsg Error message
   * @param string $errfile File name containing error
   * @param int $errline Line number causing error
   */
  public static function errorHandler($errno, $errmsg, $errfile, $errline){
    $error_type=array(
      E_ERROR => 'Error',
      E_WARNING => 'Warning',
      E_PARSE => 'Parsing Error',
      E_CORE_ERROR => 'Core Error',
      E_CORE_WARNING => 'Core Warning',
      E_COMPILE_ERROR => 'Compile Error',
      E_COMPILE_WARNING => 'Compile Warning',
      E_USER_ERROR => 'User Error',
      E_USER_WARNING => 'User Warning',
      E_USER_NOTICE => 'User Notice',
      E_STRICT => 'Runtime Notice',
      E_RECOVERABLE_ERROR => 'Catchable Fatal Error'
    );

    // If error type is not NOTICE dan STRICT
    if($errno!=E_NOTICE && $errno!=E_STRICT){
      // Convert it into Exception
      self::$errorType=$error_type[$errno];
      throw new Exception($errmsg, "E".$errno);
    }
  }
}
