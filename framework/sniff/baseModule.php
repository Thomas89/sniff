<?php
/**
 * Abstract class as module basic functions.
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource baseModule.php
 */

 // Constants
 define("BASEMODULE_MAIN_TEMPLATE_FILE", config_web::$main_template_file_name);
 define("BASEMODULE_MAIN_TEMPLATE_ERROR_FILE", config_web::$main_template_file_name);

/**
 * Class baseModule
 */
abstract class baseModule {
  /**
   * Store web root URL and use it when necessary.
   *
   * @var string
   * @access private
   */
  private $web_root = "";

  /**
   * Decide to display main template or not. Default to "true".
   *
   * @var boolean
   * @access private
   */
  private $display_main_template = true;

  /**
   * Main template file. Default to configuration.
   *
   * @var string
   * @access private
   */
  private $main_template_file = BASEMODULE_MAIN_TEMPLATE_FILE;

  /**
   * Default error template file. Default to configuration.
   *
   * @var string
   * @access private
   */
  private $main_template_error_file = BASEMODULE_MAIN_TEMPLATE_ERROR_FILE;

  /**
   * Template engine object. Default to "null".
   *
   * @var object
   * @access public
   */
  private $template = null;

  /**
   * Main template variables. This variable can be used as global template variables. Default to empty array.
   *
   * @var array
   * @access public
   */
  public $main_template_vars = array();

  /**
   * Abstract method for reading page argument.
   *
   * @access public
   * @param string $args The page arguments. Can be string or array depend on your needs.
   */
  public abstract function switcher($args);

  /**
   * Abstract method for retrieving module access rule.
   *
   * @access public
   * @return string The list of access rule available for the module.
   */
  public abstract function getAccessRule();

  /**
   * Abstract method for checking current user group permission.
   *
   * @access protected
   * @param string $access_rule The access rule to be checked.
   * @return boolean True if user has permission. Otherwise, return false.
   */
  protected abstract function checkPermission($access_rule);

  /**
   * Method for setting the main/parent template name.
   *
   * @access protected.
   * @param string $filename The main/parent template file name. If $filename is set to "null", the content will be displayed without main/parent template.
   * @param array $vars The main/parent template variables in associative array. Default to empty array.
   *   Array(
   *     [var name 1] => [var content 1],
   *     [var name 2] => [var content 2],
   *     ...
   *   )
   */
  protected function setMainTemplate($filename, $vars = array()) {
    if ($filename == null) {
      $this->display_main_template = false;
    }

    $this->main_template_file = $filename;
    $this->main_template_vars = array_merge($this->main_template_vars, $vars);
  }

  /**
   * Method for setting the template name.
   *
   * @access protected.
   * @param string $filename The template file name.
   * @param array $vars The template variables in associative array. Default to empty array.
   *   Array(
   *     [var name 1] => [var content 1],
   *     [var name 2] => [var content 2],
   *     ...
   *   )
   */
  protected function setTemplate($filename, $vars = array()) {
    $this->template = new templateEngine(config_web::$template_dir."/".$filename);
    $this->template->setVars($vars);
  }

  /**
   * Method for displaying the module output.
   *
   * @access protected.
   */
  protected function displayOutput() {
    // Get content of the module.
    $content = $this->template->getOutput();

    if ($this->display_main_template == true && !isset($_GET['raw'])) {
      // Set main content section for the main template.
      $this->main_template_vars[config_web::$main_template_container_var_name] = $content;

      // Display all template.
      $template=new templateEngine(config_web::$template_dir . "/" . $this->main_template_file);
      $template->setVars($this->main_template_vars);

      $template->displayOutput();
    }
    else {
      echo $content;
    }
  }

  /**
   * Method for displaying error page.
   *
   * @access protected
   * @param string $filename The template filename. Default to "null" which means the default configured error template will be used.
   * @param array $vars The template variables in associative array. Default to empty array.
   *   Array(
   *     [var name 1] => [var content 1],
   *     [var name 2] => [var content 2],
   *     ...
   *   )
   */
  protected function showErrorPage($filename = null, $vars = array()) {
    if ($filename == null) {
      $filename = $this->main_template_error_file;
    }

    // Tell user that the requested option is not known
    $template=new templateEngine(config_web::$template_dir . "/" . $filename);
    $template->setVars($vars);
    $template->displayOutput();
  }

  /**
   * Function for displaying the pagination.
   *
   * @access protected
   * @param integer $page The current/active page.
   * @param integer $totalPages Total of pages will be displayed.
   * @param string $path The path of page that will use this paging.
   * @param string $conjunction Conjunction for the page parameter (default=?).
   * @param integer $maxPages Maximum pages to be displayed (default=10).
   * @param string $page_qs The query string key for the page number (default=page).
   * @param string $tabs Actually it is the prefix for the link. We can use it as tab id when using jQuery UI Tabs. Default to empty string.
   * @return string The paging in HTML format.
   */
  protected function createPaging($page, $totalPages, $link, $conjunction="?", $maxPages=10, $page_qs="page", $tabs="") {
    $start=((($page%$maxPages==0) ? ($page/$maxPages) : intval($page/$maxPages)+1)-1)*$maxPages+1;
    $end=((($start+$maxPages-1)<=$totalPages) ? ($start+$maxPages-1) : $totalPages);

    $paging='<ul class="nice_paging">';
    if($page>1){
      $paging.='<li><a href="'.$link.$conjunction.$page_qs.'=1'.$tabs.'" title="First page">&lt;&lt;</a></li>';
      $paging.='<li><a href="'.$link.$conjunction.$page_qs.'='.($page-1).$tabs.'" title="Previous page">&lt;</a></li>';
    }

    if($start>$maxPages){
      $paging.='<li><a href="'.$link.$conjunction.$page_qs.'='.($start-1).$tabs.'" title="Page '.($start-1).'">...</a></li>';
    }

    for($i=$start;$i<=$end;$i++){
      if($page==$i){
        $paging.='<li class="current">'.$i.'</li>';
      }
      else{
        $paging.='<li><a href="'.$link.$conjunction.$page_qs.'='.$i.$tabs.'" title="Page '.$i.'">'.$i.'</a></li>';
      }
    }

    if($end<$totalPages){
      $paging.='<li><a href="'.$link.$conjunction.$page_qs.'='.($end+1).$tabs.'" title="Page '.($end+1).'">...</a></li>';
    }

    if($page<$totalPages){
      $paging.='<li><a href="'.$link.$conjunction.$page_qs.'='.($page+1).$tabs.'" title="Next page">&gt;</a></li>';
      $paging.='<li><a href="'.$link.$conjunction.$page_qs.'='.$totalPages.$tabs.'" title="Last page">&gt;&gt;</a></li>';
    }

    return $paging;
  }

  /**
   * Function to create breadcrumb.
   *
   * @access protected
   * @param array $data The breadcrumb data in associative array like following.
   *   Array(
   *     [backlink text 1] => [backlink URL 1],
   *     [backlink text 2] => [backlink URL 2],
   *     ...
   *   )
   * @param string $separator The separator between breadcrumb items. Default to "&raquo;".
   * @return string The breadcrumb in HTML.
   */
  protected function createBreadcrumb($data=array(), $separator="&raquo;") {
    if(!is_array($data)){
      return "";
    }

    $breadcrumb=array();
    while(list($k, $v) = each($data)){
      if($v==''){
        $breadcrumb[]=$k;
      }
      else{
        $breadcrumb[]='<a href="'.$v.'">'.$k.'</a>';
      }
    }

    return '<div class="breadcrumb">'.implode(" ".$separator." ", $breadcrumb).'</div>';
  }

  /**
   * Function to generate HTML tag for including Javascript or CSS file.
   *
   * @access protected
   * @param array $path The path of Javascript or CSS file.
   *   Array(
   *     [path1],
   *     [path2],
   *     ...
   *   )
   * @param string $type The type of files (js or css).
   * @return string The HTML tag for including Javascript or CSS file.
   */
  protected function addJsCss($path=array(), $type) {
    if(!is_array($path)){
      return "";
    }

    $str_tag = "";
    foreach ($path as $p) {
      if($p != ''){
        if ($type == "js") {
          $str_tag .= '<script languange="javascript" type="text/javascript" src="' . $p . '"></script>\n';
        }
        elseif ($type == "css") {
          $str_tag .= '<link rel="stylesheet" type="text/css" href="' . $p . '" />' . "\n";
        }
      }
    }

    return $str_tag;
  }
}
