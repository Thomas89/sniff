<?php
/**
 * ABOUT THIS EXAMPLE
 *
 * This example will show you how to implement a simple Database
 * Connection application using SNiFF.
 *
 * The database connection will only select one table from database then
 * display the result into HTML table that we prepared in template.
 * In this example, we also will learn how to create Model to store the
 * queries needed for our application.
 */


// Define this is the index page.
// All other controller, model, and view files will be included from
// this file.
define("INDEX", 1);

// Include the required configuration files.
// We also need to include the database connection configuration file.
require_once("config/config_web.php");
require_once("config/config_dbmysql.php");

// Include the required framework files.
// We need to include the database connection class here.
require_once(config_web::$framework_dir . "/db.php");
require_once(config_web::$framework_dir . "/db/dbmysql.php");
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
