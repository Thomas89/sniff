<?php
/**
 * This is the controller class for managing group.
 * We must declare three abstract methods as public:
 * - switcher
 * - getAccessRule
 * - checkPermission
 *
 * @author Lucky <bogeyman2007@gmail.com>
 * @filesource group.php
 */

// Prevent direct access
defined("INDEX") or die("");

/**
 * Class group
 */
class group extends baseModule {
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
    // - Display group list
    // - Display the form to add new group
    switch($action) {
      case NULL:
      case "view":
        $this->view();
        break;
      case "add":
        $this->add();
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
   * Action for viewing the list of groups.
   *
   * @access private
   */
  private function view() {
    // Set the main page title in main template.
    $this->main_template_vars["page_title"] = config_web::$application_name . " - Group list";

    // Sample data for group list. We don't use database at this moment.
    $group_list = array(
      0 => array(
        "id" => "G001",
        "name" => "Administrator",
      ),
      1 => array(
        "id" => "G002",
        "name" => "Power User",
      ),
      2 => array(
        "id" => "G003",
        "name" => "Visitor",
      ),
    );

    // Load the view and pass the variable.
    $this->setTemplate("group/view.tpl.php",
      array(
        "title" => "Group list",
        "group_list" => $group_list,
      )
    );
    $this->displayOutput();
  }

  /**
   * Action for displaying the add new group form.
   *
   * @access private
   */
  private function add() {
    // Initialize empty message.
    $message = "";

    // Check if there is form submission action.
    if (http::post("submit") !== NULL) {
      // Just set the message to be displayed.
      $message = "The data has been saved.";
    }

    // Change parent template to the second alternative on add new data form.
    $this->setMainTemplate("index2.tpl.php");

    // Set the main page title in main template.
    $this->main_template_vars["page_title"] = config_web::$application_name . " - Add new group";

    // Load the view and pass the variable.
    $this->setTemplate("group/form.tpl.php",
      array(
        "title" => "Add new group",
        "message" => $message,
      )
    );
    $this->displayOutput();
  }
}
