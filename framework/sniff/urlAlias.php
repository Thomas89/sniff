<?php
/**
 * Class for handling URL alias function to produce SEO friendly URL.
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource urlAlias.php
 */

/**
 * Class urlAlias
 */
class urlAlias
  /**
   * Method for retreiving the real path from the alias.
   *
   * @access public
   * @param string $alias The path alias.
   * @return string The real path.
   */
  public static function getPath($alias) {
    $db=db::getObject();

    $row=$db->fetchRow($db->query("SELECT path FROM url_alias WHERE alias='".$db->escapeString($alias)."'"));
    if($row){
      return $row['path'];
    }
    else{
      return $alias;
    }
  }

  /**
   * Method for retreiving the alias of the real path.
   *
   * @access public
   * @param string $path The real path.
   * @return string The alias path.
   */
  public static function getAlias($path) {
    $db=db::getObject();

    $row=$db->fetchRow($db->query("SELECT alias FROM url_alias WHERE path='".$db->escapeString($path)."'"));
    if($row){
      return $row['alias'];
    }
    else{
      return $path;
    }
  }

  /**
   * Method for setting the alias path automatically by the given title.
   *
   * @access public
   * @param string $path The real path.
   * @param string $title The title for the alias auto generation.
   * @param string $prefix prefix for the generated alias. Default to empty
   * @return boolean True when process is completed. Throw exception if there is error.
   */
  public static function setAlias($path, $title, $prefix="") {
    $db=db::getObject();

    $path=$db->escapeString($path);

    // Remove non-alphanumeric characters
    $alias=preg_replace("/[^a-z0-9 -]/i", "", $title);
    // Remove multiple spaces
    $alias=preg_replace("/[ ]+/i", " ", $alias);
    // Replace space with dash
    $alias=preg_replace("/[ ]/i", "-", $alias);
    // Change all letters to lowercase
    $alias=$prefix.strtolower($alias);

    $taken=false;
    $db->query("SELECT * FROM url_alias WHERE alias='".$alias."' AND path<>'".$path."'");
    if($db->affectedRows()>0){
      $taken=true;
    }

    $i=1;
    while($taken == true){
      $tmp_alias = $alias;
      $tmp_alias .= "-".$i;
      $i++;

      $db->query("SELECT * FROM url_alias WHERE alias='".$tmp_alias."' AND path<>'".$path."'");
      if($db->affectedRows() == 0){
        $taken = false;
        $alias = $tmp_alias;
      }
    }

    $db->query("SELECT * FROM url_alias WHERE path='".$path."'");
    if($db->affectedRows()>0){
      $db->execute("UPDATE url_alias SET alias='".$alias."' WHERE path='".$path."'");
    }
    else{
      $db->execute("INSERT INTO url_alias (path, alias) VALUES ('".$path."', '".$alias."')");
    }

    return true;
  }

  /**
   * Method for deleting the alias of a real path.
   *
   * @access public
   * @param string $path The real path.
   * @return boolean The deletion process status.
   */
  public static function deleteAlias($path) {
    $db=db::getObject();

    return $db->query("DELETE FROM url_alias WHERE path='".$db->escapeString($path)."'");
  }

  /**
   * Method for parsing the path element into array.
   *
   * @access public
   * @param string $path The real path.
   * @param string $args The parsed path in array. Array is generated by explode function with "/" as separator.
   * @return string The real path.
   */
  public static function parsePath(&$path, &$args) {
    if(preg_match("/^([A-Za-z0-9_]+)((\/[A-Za-z0-9_]+)*)$/i", $path, $matches)){
      $path=preg_replace("/\./", "", $matches[1]);
      if(isset($matches[2])){
        $arr=explode("/", $matches[2]);
        for($i=0;$i<count($arr)-1;$i++){
          $args[$i]=$arr[$i+1];
        }
      }
    }
  }

  /**
   * Method for auto redirecting user to the alias path of the real path.
   *
   * @access public
   * @param string $path The real path.
   */
  public static function autoRedirect($path) {
    $db=db::getObject();
    $row=$db->fetchRow($db->query("SELECT alias FROM url_alias WHERE path='".$db->escapeString($path)."'"));
    if($row){
      http::redirect(http::getWebRoot() . $row['alias']);
    }
  }
}
