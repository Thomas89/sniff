<?php
// Prevent direct access
defined("INDEX") or die("");

/**
 * Controller class for user.
 * We must declare three abstract methods as public
 * - switcher
 * - getAccessRule
 * - checkPermission
 */

class user extends baseModule {
  /**
   * The abstract method that should be declared.
   * Main controller method. It will check which action will be executed.
   */
  public function switcher($action) {
    // Check the controller action that will be called.
    switch($action) {
      case NULL:
      case "view":
        $this->view();
        break;
      case "add":
        $this->add();
        break;
      default:
        $this->showErrorPage(NULL, array("content" => "Invalid controler action."));
    }
  }

  /**
   * The abstract method that should be declared.
   */
  public function getAccessRule() {
    // We leave it blank for now.
  }

  /**
   * The abstract method that should be declared.
   */
  public function checkPermission($access_rule) {
    // We leave it blank for now.
  }

  /**
   * Action for viewing the list of users.
   */
  private function view() {
    // Set the main page title in main template.
    $this->main_template_vars["page_title"] = config_web::$application_name . " - User list";

    // Load the view and pass the variable.
    $this->setTemplate("user/view.tpl.php",
      array(
        "title" => "User list",
      )
    );
    $this->displayOutput();
  }

  /**
   * Action for displaying the add new user form.
   */
  private function add() {
    // Set the main page title in main template.
    $this->main_template_vars["page_title"] = config_web::$application_name . " - Add new user";

    // Load the view and pass the variable.
    $this->setTemplate("user/form.tpl.php",
      array(
        "title" => "Add new user",
      )
    );
    $this->displayOutput();
  }
}
