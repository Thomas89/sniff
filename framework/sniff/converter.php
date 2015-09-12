<?php
/**
 * Class for all formatting functions.
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource converter.php
 */

/**
 * Class converter
 */
class converter {
  /**
   * Method for converting the MySQL date format to the general date format.
   *
   * @access public
   * @param string $date The MySQL date.
   * @param string $format The new date format to be returned. Acceptable formats are the same as PHP date() function. Default to "d/m/Y H:i:s".
   * @param string $invalid The returned string if the date is invalid..
   * @return string The formatted date.
   */
  public static function dateToStr($date, $format = "d/m/Y H:i:s", $invalid = "-") {
    $formatted_date = $invalid;

    // Check if the MySQL date format is valid.
    if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}([ ]+[0-9]{2}:[0-9]{2}:[0-9]{2})?$/", $date)) {
      $time=explode(" ", $date);
      $date=explode("-", $time[0]);

      $hour = 0;
      $minute = 0;
      $second = 0;
      if (isset($time[1])) {
        $arrTmp = explode(":", $time[1]);
        $hour = intval($arrTmp[0]);
        $minute = intval($arrTmp[1]);
        $second = intval($arrTmp[2]);
      }

      $formatted_date = date($format, mktime($hour, $minute, $second, intval($date[1]), intval($date[2]), intval($date[0])));
    }

    return $formatted_date;
  }

  /**
   * Method for converting the date string to timestamp.
   *
   * @access public
   * @param string $string The date string.
   * @param string $source_format The format of the date string. Acceptable formats are "d/m/Y" or "m/d/Y". Default to "d/m/Y".
   * @param string $target_format The new date format to be returned. Acceptable formats are the same as PHP date() function. Default to "d/m/Y H:i:s". If NULL,
   * @param string $invalid The returned string if the date string is invalid.
   * @return string The formatted data or the timestamp number depend on $target_format.
   */
  public static function strToDate($string, $source_format = "d/m/Y", $target_format = "Y-m-d H:i:s", $invalid = "-") {
    $date = $invalid;
    $source_format_valid = true;
    switch ($source_format) {
      case "d/m/Y":
        $string = str_replace("/", "-", $string);
        break;
      case "m/d/Y":
        break;
      default:
        $source_format_valid = false;
    }

    $timestamp = strtotime($string);
    if ($timestamp && $source_format_valid == true) {
      if ($target_format == NULL) {
        $date = $timestamp;
      }
      else {
        $date = date($target_format, $timestamp);
      }
    }

    return $date;
  }

  /**
   * Method for converting number to Indonesian day name.
   *
   * @access public
   * @param number $number The day number. Acceptable values are 1 to 7.
   * @param string $format Short day name or complete day name. Acceptable values are "short", "complete". Default to "complete".
   * @param string $invalid The returned string if the date string is invalid.
   * @return string The day name.
   */
  public static function numberToDayname($number, $format = "complete", $invalid = "-") {
    $day = $invalid;

    switch ($number) {
      case 1: $day="Senin"; break;
      case 2: $day="Selasa"; break;
      case 3: $day="Rabu"; break;
      case 4: $day="Kamis"; break;
      case 5: $day="Jumat"; break;
      case 6: $day="Sabtu"; break;
      case 7: $day="Minggu"; break;
    }

    switch ($format) {
      case "short":
        $day = substr($day, 0, 3);
        break;
      case "complete":
        break;
      default:
        $day = $invalid;
    }

    return $day;
  }

  /**
   * Method for converting number to Indonesian month name.
   *
   * @param number $number The month number. Acceptable values are 1 to 12.
   * @param string $format Short day name or complete month name. Acceptable values are "short", "complete". Default to "complete".
   * @param string $invalid The returned string if the date string is invalid.
   * @return string The month name.
   */
  public static function monthName($number, $format = "complete", $invalid = "-") {
    $month = $invalid;

    switch($number){
      case 1: $month="Januari"; break;
      case 2: $month="Februari"; break;
      case 3: $month="Maret"; break;
      case 4: $month="April"; break;
      case 5: $month="Mei"; break;
      case 6: $month="Juni"; break;
      case 7: $month="Juli"; break;
      case 8: $month="Agustus"; break;
      case 9: $month="September"; break;
      case 10: $month="Oktober"; break;
      case 11: $month="November"; break;
      case 12: $month="Desember"; break;
    }

    switch ($format) {
      case "short":
        $month = substr($month, 0, 3);
        break;
      case "complete":
        break;
      default:
        $month = $invalid;
    }

    return $month;
  }

  /**
   * Function for formatting currency.
   *
   * @access public
   * @param number $money The number to be formatted as money.
   * @param string $thousand_sep The thousand separator. Default to ".".
   * @param string $decimal_sep The decimal separator. Default to ",".
   * @param string $decimal_places The digit of the decimal. Default to "0".
   * @param string $prefix The prefix for the output. Default to "Rp ".
   * @param string $suffix The suffix for the output. Default to "".
   * @param boolean $absolute If set to true, negative value will be displayed inside the bracket. Default to "true".
   * @return string The formatted currency.
   */
  public static function toCurrency ($money, $thousand_sep = ".", $decimal_sep = ".", $decimal_places = 0, $prefix = "Rp ", $suffix = "", $absolute = true) {
    if ($money < 0 && $absolute == true) {
      $currency = "(" . number_format(abs($money), $decimal_places, $thousand_sep, $decimal_sep) . ")";
    }
    else {
      $currency = number_format($money, $decimal_places, $thousand_sep, $decimal_sep);
    }

    return $prefix . $currency . $suffix;
  }

  /**
   * Method for changing month number Arabic to Romans.
   *
   * @access public
   * @param number $no The Arabic number.
   * @param string $invalid The returned string if the month is invalid.
   * @return string The Romans month number.
   */
  public static function toRomansMonth($no, $invalid = "-") {
    $romans = $invalid;

    if('1' <= $no && $no <= '3'){
      for($g=0;$g<$no;$g++){
        $romans = $romans.'I';
      }
    }
    else if('4' == $no){
      $romans = 'IV';
    }
    else if('5' <= $no && $no <= '8'){
      $romans = 'V';
      for($g=5;$g<$no;$g++){
        $romans = $romans.'I';
      }
    }
    else if('9' == $no){
      $romans = 'IX';
    }
    else if('10' <= $no && $no <= '12'){
      $romans = 'X';
      for($g=10;$g<$no;$g++){
        $romans = $romans.'I';
      }
    }

    return $romans;
  }

  /**
   * Method to 0 as padding in front of number.
   *
   * @access public
   * @param number $number The number to be given padding.
   * @param number $lengt The character length for padding. Default to "4".
   * @return string The padded number.
   */
  public static function padZero($number, $length=4) {
    // count character of number
    $char = (string)$number;

    //total 0 that will be added
    $loop = $length - strlen($char);

    $result = "";
    for ($g=0; $g<$loop; $g++) {
      $result .= '0';
    }

    return $result.$number;
  }
}
