<?

namespace lib\Form\Listbox;

use lib\Form\Dropdown\Dropdown;

/**
 * @package Form.Listbox
 * @example examples.php 
 */
class Listbox extends Dropdown
{

  /**
   * Atributo multiple do elemento
   * @var bool 
   */
  protected $_multiple = true;

  /**
   * Array contendo chaves de opções selecionadas
   * @var string 
   */
  protected $_selected = array();

  /**
   * Seta o atributo multiple do elemento
   * @param bool $multiple 
   */
  public function setMultiple ( $multiple )
  {
    $this->_multiple = $multiple;
  }

  /**
   * Seta as chaves de options selecionadas
   * @param array $selected 
   */
  public function setSelected ( $selected )
  {
    $this->_selected = $selected;
  }

  /**
   * Seta as chaves de options selecionadas, porém recebendo um array de objetos.
   * 
   * @param array $selected 
   * @param string $prop_id Nome da propriedade a ser extraida dos objetos
   */
  public function setSelectedObj ( $selected, $prop_id )
  {
    foreach ( $selected as $obj ) {
      $this->_selected[] = $obj->$prop_id;
    }
  }

  /**
   * Chamada interna para criação da tag <select> 
   */
  protected function createOpenTag ()
  {
    $multiple = $this->_multiple ? ' multiple="multiple"' : '';
    $style = $this->_style ? ' style="' . $this->_style . '"' : '';

    $this->_element .= '<select name="' . $this->_name . '[]" id="' . $this->_id . '" class="';
    $this->_element .= $this->_class . '"' . $multiple . '' . $style . '>' . PHP_EOL;
  }

  /**
   * Chamada interna para criação das tags <option> 
   */
  protected function createOptions ()
  {
    foreach ( $this->_options as $k => $v ) {
      if ( in_array($k, $this->_selected) ) {
        $this->_element .= '  <option value="' . $k . '" selected="selected">' . $v . '</option>' . PHP_EOL;
      }
    }

    foreach ( $this->_options as $k => $v ) {
      if ( !in_array($k, $this->_selected) ) {
        $this->_element .= '  <option value="' . $k . '">' . $v . '</option>' . PHP_EOL;
      }
    }
  }

}