<?php
/**
 * This is the controller class for managing user.
 * We must declare three abstract methods as public:
 * - switcher
 * - getAccessRule
 * - checkPermission
 *
 * Constructor method should be used to initialized some variables.
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource user.php
 */

// Prevent direct access
defined("INDEX") or die("");

// Include Model file.
require_once(config_web::$query_dir."/qUser.php");

/**
 * Class user
 */
class user extends baseModule {
  /**
   * Constructor function. We can initialize some variables or anything
   * here.
   */
  public function __construct(){
    // Initialize some required variables such as web root URL,
    // and CSS files.
    $this->web_root = http::getWebRoot();
    $this->main_template_vars["css"] = "";

    // Include main CSS here.
    $this->main_template_vars["css"] = $this->addJsCss(array(
      $this->web_root . "css/main.css",
    ), "css");
  }

  /**
   * The abstract method that should be declared.
   * Main controller method. It will check which action will be executed.
   *
   * @access public
   * @param string $action The controller action.
   */
  public function switcher($action) {
    // Check the controller action that will be called.
    // In this example we only use two actions:
    // - Display user list
    switch($action) {
      case NULL:
      case "view":
        $this->view();
        break;
      case "add":
        $this->add();
        break;
      case "update":
        $this->update(http::get("id"));
        break;
      case "delete":
        $this->delete(http::get("id"));
        break;
      default:
        // Show error message when unknown actions is requested.
        $this->showErrorPage(NULL, array("content" => "Invalid controler action."));
    }
  }

  /**
   * The abstract method that should be declared.
   *
   * @access public
   */
  public function getAccessRule() {
    // We leave it blank for now.
  }

  /**
   * The abstract method that should be declared.
   *
   * @access public
   * @param string $access_rule The access rule to be checkeds.
   */
  public function checkPermission($access_rule) {
    // We leave it blank for now.
  }

  /**
   * Action for viewing the list of users.
   *
   * @access private
   */
  private function view() {
    // Set the main page title in main template.
    $this->main_template_vars["page_title"] = config_web::$application_name . " - User list";
    // No breadcrumb on user list.
    $this->main_template_vars["breadcrumb"] = "";

    // Create object from Model.
    $qUser = qUser::getObject();

    // Get user list from database.
    $user_list = $qUser->select();

    // Load the view and pass the variable.
    $this->setTemplate("user/view.tpl.php",
      array(
        "title" => "User list",
        "user_list" => $user_list,
      )
    );
    $this->displayOutput();
  }

  /**
   * Action for adding new user.
   *
   * @access private
   */
  private function add() {
    // Set the main page title in main template.
    $this->main_template_vars["page_title"] = config_web::$application_name . " - Add new user";
    // Set the breadcrumb.
    $this->main_template_vars["breadcrumb"] = $this->createBreadcrumb(array(
      "User" => "?module=user",
      "Add new" => "",
    ));

    // Check if there is posted data.
    if (http::post("submit") !== NULL) {
      // Create object from Model.
      $qUser = qUser::getObject();
      // Insert to table.
      $qUser->insert(http::post("name"));
      // Redirect back to list.
      http::redirect("index.php");
    }

    // Load the view and pass the variable.
    $this->setTemplate("user/form.tpl.php",
      array(
        "title" => "Add new user",
        "id" => "Auto",
        "name" => NULL,
      )
    );
    $this->displayOutput();
  }

  /**
   * Action for updating user.
   *
   * @access private
   */
  private function update($id) {
    // Set the main page title in main template.
    $this->main_template_vars["page_title"] = config_web::$application_name . " - Update user";
    // Set the breadcrumb.
    $this->main_template_vars["breadcrumb"] = $this->createBreadcrumb(array(
      "User" => "?module=user",
      "Update user" => "",
    ));

    // Create object from Model.
    $qUser = qUser::getObject();

    // Check if there is posted data.
    if (http::post("submit") !== NULL) {
      // Update the record.
      $qUser->update(http::post("name"), $id);
      // Redirect back to list.
      http::redirect("index.php");
    }

    // Fetch the latest user data by ID.
    $row = $qUser->selectById($id);

    // Load the view and pass the variable.
    $this->setTemplate("user/form.tpl.php",
      array(
        "title" => "Update user",
        "id" => $row["id"],
        "name" => $row["name"],
      )
    );
    $this->displayOutput();
  }

  /**
   * Action for deleting user.
   *
   * @access private
   */
  private function delete($id) {
    // Create object from Model.
    $qUser = qUser::getObject();
    // Delete the record.
    $qUser->delete($id);
    // Redirect back to list.
    http::redirect("index.php");
  }
}
