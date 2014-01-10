<?php

namespace Din\Form\Dropdown;

/**
 * @package Form.Dropdown
 * @example examples.php
 *
 */
class Dropdown
{

  /**
   * Atributo name do elemento
   * @var strng
   */
  protected $_name;

  /**
   * Atributo id do elemento
   * @var string
   */
  protected $_id;

  /**
   * Atributo class do elemento
   * @var string
   */
  protected $_class;

  /**
   * Atributo style do elemento
   * @var string
   */
  protected $_style = null;

  /**
   * Chave da opção selecionada
   * @var string
   */
  protected $_selected;

  /**
   * Array de opções
   * @var array
   */
  protected $_options = array();

  /**
   * Array contendo a primeira option
   * @var array
   */
  protected $_firstOpt = array();

  /**
   * Elemento resultante do método getElement
   * @var string
   */
  protected $_element = '';

  /**
   * Constrói objeto Dropdown opcionalmente setando o nome do elemento
   * @param string $name
   */
  public function __construct ( $name = '' )
  {
    $this->setName($name);
  }

  /**
   * Seta o atributo nome do elemento
   * @param string $name
   */
  public function setName ( $name )
  {
    $this->_name = $name;
  }

  /**
   * Seta o atributo id do elemento
   * @param string $id
   */
  public function setId ( $id )
  {
    $this->_id = $id;
  }

  /**
   * Seta o atributo class do elemento
   * @param string $class
   */
  public function setClass ( $class )
  {
    $this->_class = $class;
  }

  /**
   * Seta o atributo style do elemento
   * @param string $style
   */
  public function setStyle ( $style )
  {
    $this->_style = $style;
  }

  /**
   * Seta a chave da option selecionada
   * @param string $selected
   */
  public function setSelected ( $selected )
  {
    $this->_selected = $selected;
  }

  /**
   * Seta as opções com base num array simples
   * @param array $_options
   */
  public function setOptionsArray ( array $_options )
  {
    $this->_options = $_options;
  }

  /**
   * Seta as opções com base em um array de result do banco
   * @param array $result Aray de objetos
   * @param string $prop_id Nome do índice que servirá de chave para a option
   * @param string $prop_name Nome do índice que servirá de texto para a option
   */
  public function setOptionsResult ( array $result, $prop_id, $prop_name )
  {
    foreach ( $result as $row ) {
      $this->_options[$row[$prop_id]] = $row[$prop_name];
    }
  }

  /**
   * Seta a primeira opção do option
   * @param string $text Texto da primeira opção
   * @param string $key [optional] chave da primeira opção
   */
  public function setFirstOpt ( $text, $key = '0' )
  {
    $this->_firstOpt[$key] = $text;
  }

  /**
   * Chamada interna para criação da tag <select>
   */
  protected function createOpenTag ()
  {
    $style = $this->_style ? ' style="' . $this->_style . '"' : '';

    $this->_element .= '<select name="' . $this->_name . '" id="' . $this->_id . '" class="';
    $this->_element .= $this->_class . '"' . $style . '>' . PHP_EOL;
  }

  /**
   * Chamada interna para criação da primeira tag <option>
   */
  protected function createFirstOption ()
  {
    foreach ( $this->_firstOpt as $k => $v ) {
      $this->_element .= '  <option value="' . $k . '">' . $v . '</option>' . PHP_EOL;
    }
  }

  /**
   * Chamada interna para criação das tags <option>
   */
  protected function createOptions ()
  {
    foreach ( $this->_options as $k => $v ) {
      $selected = (string) $this->_selected == (string) $k ? ' selected="selected"' : '';

      $this->_element .= '  <option value="' . $k . '"' . $selected . '>' . $v . '</option>' . PHP_EOL;
    }
  }

  /**
   * Chamada interna para criação da tag </select>
   */
  protected function createCloseTag ()
  {
    $this->_element .= '</select>' . PHP_EOL;
  }

  /**
   * Retorna o elemento <select> montado com todas as opções
   * @return string
   */
  public function getElement ()
  {
    $this->createOpenTag();
    $this->createFirstOption();
    $this->createOptions();
    $this->createCloseTag();

    return $this->_element;
  }

}
