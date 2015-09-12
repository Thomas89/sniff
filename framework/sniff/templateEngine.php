<?php
/**
 * The class for separating the application logic from the user interface
 *
 * @author bogeyman <bogeyman2007@gmail.com>
 * @filesource templateEngine.php
 */

/**
 * The templateEngine class
 */
class templateEngine{
  /**
   * The template name with its complete path
   *
   * @var string
   * @access protected
   */
  protected $templateFile='';

  /**
   * The pairs of variable names and their values which will be written on the template file
   *
   * @var array
   * @access protected
   */
  protected $values=array();

  /**
   * Constructor
   *
   * @access public
   * @param string $templateFile The template name with its complete path
   */
  public function __construct($templateFile){
    $this->templateFile=$templateFile;
  }

  /**
   * The function to set the variables and their values which will be put on the template file
   *
   * @access public
   * @param string $key The variable name
   * @param string $value The variable value
   */
  public function setVar($key, $value){
    $this->values[$key]=$value;
  }

  /**
   * The function to set the variables and their values which will be put on the template file from array
   *
   * @access public
   * @param array $arrValues The pairs of variable name and value as the array key and value
   *      Array(
   *        [var1]=>value 1
   *        [var2]=>value 2
   *        ...
   *      )
   */
  public function setVars($arrValues=array()){
    while(list($key, $value)=each($arrValues)){
      $this->values[$key]=$value;
    }
  }

  /**
   * The function to display the final result of the template parsing proccess
   *
   * @access public
   */
  public function displayOutput(){
    $this->parseValues();
  }

  /**
   * The function to return the final result of the template parsing proccess as string
   *
   * @access public
   * @return string Template parsing result
   */
  public function getOutput(){
    return $this->parseValues(true);
  }

  /**
   * The function to parse the content of values into object's properties.
   *
   * @access protected
   * @param boolean $__return Set this parameter to "true" to automatically return rendered content after passing the variables.
   * @return string The rendered template.
   */
  protected function parseValues($__return = false) {
    if (is_array($this->values) && count($this->values)>0){
      while(list($__name, $__value)=each($this->values)){
        $__name=trim($__name);
        if (!empty($__name)) {
          $$__name=$__value;
        }
      }
    }

    if($__return == true) {
      ob_start();

      require_once($this->templateFile);

      $__output=ob_get_contents();

      ob_end_clean();

      return $__output;
    }
    else {
      require_once($this->templateFile);
    }
  }
}
