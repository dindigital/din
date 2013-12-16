<?

namespace lib\Form\Browser\CKFinder;

/**
 * @example example.php 
 */
class CKFinder
{

  private $_name;
  private $_startUpPath;
  private $_buttonText;
  private $_classTextfield;
  private $_classButton;

  public function __construct ( $name )
  {
    $this->setName($name);
  }

  public function setName ( $name )
  {
    $this->_name = $name;
  }

  public function setStartUpPath ( $path = 'Files:/' )
  {
    $this->_startUpPath = $path;
  }

  public function setButtonText ( $text = 'Selecionar Arquivo' )
  {
    $this->_buttonText = $text;
  }
  
  public function setClassTextfield($class)
  {
    $this->_classTextfield = $class;
  }

  public function setClassButton($class)
  {
    $this->_classButton = $class;
  }

  public function getElement ()
  {
    if ( !$this->_startUpPath )
      $this->setStartUpPath();
    if ( !$this->_buttonText )
      $this->setButtonText();
    
    $t_class = $this->_classTextfield ? 'class="'.$this->_classTextfield.'"' : '';
    $b_class = $this->_classButton ? 'class="'.$this->_classButton.'"' : '';
    
    $r = '<input id="' . $this->_name . '" name="' . $this->_name . '" type="text" ' . $t_class . ' />' . PHP_EOL;
    $r .= '<input type="button" value="' . $this->_buttonText . '"  ' . $b_class . ' ';
    $r .= 'onclick="BrowseServer( \'' . $this->_startUpPath . '\', \'' . $this->_name . '\' );" />' . PHP_EOL;

    return $r;
  }

}