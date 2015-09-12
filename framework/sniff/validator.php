<?php
/**
 * Class containing validation methods
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource validator.php
 */

/**
 * Class validator
 */
class validator{
  /**
   * Function for validating the 'must be filled' field.
   *
   * @access public
   * @param string $field_content The field content for validation.
   * @return boolean True or false depending on the validation result.
   */
  public static function validateField($field_content){
    if(strlen(trim($field_content))>0){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Function for validating username field.
   *
   * @access public
   * @param string $field_content The field content for validation.
   * @return boolean True or false depending on the validation result.
   */
  public static function validateUsername($field_content){
    if(preg_match('/^[a-z0-9_.-]+$/i', $field_content)){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Function for validating the number.
   *
   * @access public
   * @param number $number The number field content for validation.
   * @param boolean $int_only Define if the number is integer only or not. Default to "false".
   * @return boolean True or false depending on the validation result.
   */
  public static function validateNumber($number, $int_only=false){
    if($int_only==true){
      // The number is integer only
      $pattern="/^[0-9]+$/";
    }
    else{
      // The number can be integer or float
      $pattern="/^[0-9]+([.][0-9]+)?$/";
    }

    // validate
    if(preg_match($pattern,$number)){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Function for validating the email address.
   *
   * @access public
   * @param string $email_address The e-mail address for validation.
   * @return boolean True or false depending on the validation result.
   */
  public static function validateEmail($email_address){
    if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email_address)){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Function for validating link.
   *
   * @access public
   * @param string $link The link field content for validation.
   * @return boolean True or false depending on the validation result.
   */
  public static function validateLink($link){
    if(preg_match("/^http:\/\//i", $link)){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Function for validating image file.
   *
   * @access public
   * @param string $file The image file name to be validated.
   * @return boolean True or false depending on the validation result.
   */
  public static function validateImage($file){
    if(preg_match('/.+\.(jpe?g|png|gif)$/i', $file)){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Function for validating file.
   *
   * @access public
   * @param string $file The file name to be validated.
   * @return boolean True or false depending on the validation result.
   */
  public static function validateFile($file, $extensions=array("pdf", "jpg", "jpeg", "png", "gif")){
    if(preg_match('/.+\.('.implode('|', $extensions).')$/i', $file)){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Function for validating date.
   *
   * @access public
   * @param string $date The date field content for validation.
   * @param string $format The date format for validation test. Acceptable values are "yyyy-mm-dd", "dd/mm/yyy". Default to "yyyy-mm-dd".
   * @return boolean True or false depending on the validation result.
   */
  public static function validateDate($date, $format="yyyy-mm-dd"){
    switch($format){
      case "yyyy-mm-dd":
        if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)){
          $date=explode("-", $date);
          return checkdate(intval($date[1]), intval($date[2]), intval($date[0]));
        }
        else{
          return false;
        }
      case "dd/mm/yyyy":
        if(preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $date)){
          $date=explode("/", $date);
          return checkdate(intval($date[1]), intval($date[0]), intval($date[2]));
        }
        else{
          return false;
        }
    }

    return false;
  }

  /**
   * Function for validating and comparing two password fields.
   *
   * @access public
   * @param string $password1 The first typed password.
   * @param string $password2 The confirmation password.
   * @return boolean True or false depending on the validation result.
   */
  public static function validatePassword($password1, $password2){
    if(self::validateField($password1) && ($password1===$password2)){
      return true;
    }
    else{
      return false;
    }
  }
}
