<?php

namespace Din\Form\Textarea\Ckeditor;

use Din\Session\Session;

/**
 * Class de implementação do plugin javascript Ckeditor com Ckfinder.
 * Documentação: http://ckeditor.com/ http://ckfinder.com/
 *
 * @package Form.Textarea
 * @example example.php
 */
class Ckeditor
{

  /**
   * nome do campo html
   * @var string
   */
  private $_field_name;

  /**
   * nome da classe do elemento
   * @var type
   */
  private $_class;

  /**
   *
   * @param string $field_name
   */
  public function __construct ( $field_name )
  {
    $this->setName($field_name);
  }

  /**
   *
   * @param string $field_name
   */
  public function setName ( $field_name )
  {
    $this->_field_name = $field_name;
  }

  /**
   *
   * @param string $class
   */
  public function setClass ( $class )
  {
    $this->_class = $class;
  }

  /**
   * retorna o html/javscript do elemento textarea trocado pelo ckeditor
   * @param string $value
   * @return string
   */
  public function getElement ( $value = '' )
  {
    $session = new Session('IsAuthorized');
    $session->set(0, 1);

    $r = '<textarea id="' . $this->_field_name . '" name="' . $this->_field_name . '" class="' . $this->_class . '">' . $value . '</textarea>' . PHP_EOL;
    $r .= '<script type="text/javascript">' . PHP_EOL;
    $r .= ' var editor = CKEDITOR.replace( \'' . $this->_field_name . '\' );' . PHP_EOL;
    $r .= ' if (CKEDITOR.instances["' . $this->_field_name . '"]) delete CKEDITOR.instances["' . $this->_field_name . '"];' . PHP_EOL;
    $r .= ' CKFinder.setupCKEditor( editor, \'/backend/plugins/ckfinder23/\' ) ;' . PHP_EOL;
    $r .= '</script>' . PHP_EOL;

    return $r;
  }

}
