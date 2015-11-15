<?php
/**
 * ABOUT THIS EXAMPLE
 *
 * We have two controller for displaying User and Group. They will be
 * loaded as requested inside a parent/main template file like in
 * simple_mvc example, but we can easily change the main template file
 * on demand. In this example, when the application is in adding new
 * data mode, the main template will be switched to the one that use
 * left sidebar menu. Otherwise the template with the top menu will be
 * loaded.
 * 
 * Regarding to the method we change the main template file, we can
 * also load the required additional CSS file in specific action. In
 * this example, we load one additional CSS file when we load the 2
 * column template file.
 */


// Define this is the index page.
// All other controller, model, and view files will be included from
// this file.
define("INDEX", 1);

// Include the required configuration files.
require_once("config/config_web.php");

// Include the required framework files.
require_once(config_web::$framework_dir . "/http.php");
require_once(config_web::$framework_dir . "/baseModule.php");
require_once(config_web::$framework_dir . "/templateEngine.php");

// Check if the request for specific controller is passed in the query
// string.
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
      "User" => "?module=user",
      "Group" => "?module=group",
    );

    // Call the controller's main function and pass the action query
    // string so the controller can decide which method will be
    // executed.
    $class->switcher(http::get("action"));
  }
  else {
    echo "No valid controller found.";
  }
}
else {
  echo "No valid controller found.";
}
