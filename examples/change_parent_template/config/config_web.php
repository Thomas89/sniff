<?php
/**
 * Application configuration.
 */
class config_web{
  public static $application_name = "Change Parent Template";
  public static $application_version = "";
  public static $framework_dir = "../../framework/sniff";
  public static $module_dir = "module";
  public static $query_dir = "query";
  public static $template_dir = "template";
  public static $debug_mode = true;
  public static $home_page = "home";
  public static $default_group_id = array(0);
  public static $main_template_file_name = "index.tpl.php";
  public static $main_template_error_file_name = "error.tpl.php";
  public static $main_template_container_var_name = "content";
  public static $default_db_type = "mysql";
}
