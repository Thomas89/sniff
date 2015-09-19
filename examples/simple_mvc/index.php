<?php
define("INDEX", 1);

// Include the required configuration files.
require_once("config/config_web.php");
require_once("config/config_dbmysql.php");

// Include the required framework files.
require_once(config_web::$framework_dir . "/db.php");
require_once(config_web::$framework_dir . "/http.php");
require_once(config_web::$framework_dir . "/baseModule.php");
require_once(config_web::$framework_dir . "/templateEngine.php");

// Check if the request for specific controller is passed in the query string.
if (isset($_GET['module'])) {
  switch ($_GET['module']) {
    case "user":
      $controller = "user";
      break;
    case "group":
      $controller = "group";
      break;
    default:
      $controller = NULL;
  }
}
else {
  // Default controller if we didn't pass the "module" query string.
  $controller = "user";
}

// Check if the controller exists.
if ($controller !== NULL) {
  // Make sure the file exists.
  $file = config_web::$module_dir . "/". $controller . ".php";
  if (file_exists($file)) {
    // Include the file first.
    require_once($file);

    // Make the instance.
    $class = new $controller();

    // Main menu for main template
    $class->main_template_vars["menu"] = array(
      "View" => "?action=view",
      "Add" => "?action=add",
    );

    // Call the controller's main function and pass the action for.
    $class->switcher(http::get("action"));
  }
  else {
    echo "No valid controller found.";
  }
}
else {
  echo "No valid controller found.";
}
