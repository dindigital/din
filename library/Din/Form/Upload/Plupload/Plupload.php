<?php

namespace Din\Form\Upload\Plupload;

use Din\Form\Upload\FileTypes;

/**
 * Class de implementação do plugin javascript Plupload.
 * Documentação: http://www.plupload.com/
 *
 * @package Form.Upload
 */
class Plupload
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
   * aceita multiplos arquivos? true|false
   * @var bool
   */
  private $_multiple;

  /**
   * opções chave/valor na hora de implementar o pupload
   * @var array
   */
  private $_opt = array();

  /**
   * tipo de arquivo. Exemplo: 'imagem'
   * @var string
   */
  private $_type;

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
   *
   * @param bool $bool
   */
  public function setMultiple ( $bool )
  {
    $this->_multiple = $bool;

  }

  /**
   *
   * @param string $type
   */
  public function setType ( $type )
  {
    $type = FileTypes::typeByName($type);
    $extensions = implode(',', $type->ext);
    $r = '  {title : "' . $type->desc . '", extensions : "' . $extensions . '"}';

    $this->_type = $r;

  }

  /**
   * seta opções de implementação.
   * exemplo: setOpt('runtimes', "'html5,flash'")
   * @param string $key
   * @param string $value
   */
  public function setOpt ( $key, $value )
  {
    $this->_opt[$key] = $value;

  }

  /**
   * retorna o html/javscript do botão de upload.
   * @return string
   */
  public function getButton ()
  {
    $r = '<div id="' . $this->_field_name . '" class="' . $this->_class . '"></div>' . PHP_EOL;
    $r .= '<script>' . PHP_EOL;
    $r .= '$("#' . $this->_field_name . '").pluploadQueue({' . PHP_EOL;

    if ( !$this->_multiple ) {
      $this->_opt['multi_selection'] = 'false';
    }

    foreach ( $this->_opt as $k => $v ) {
      $r .= " {$k} : {$v}," . PHP_EOL;
    }

    $r .= " filters : [" . PHP_EOL;
    $r .= $this->_type . PHP_EOL;
    $r .= " ]" . PHP_EOL;

    $r .= '});' . PHP_EOL;

    if ( !$this->_multiple ) {
      $r .= '$("#' . $this->_field_name . '").pluploadQueue().bind(\'FilesAdded\', function(up, files) {' . PHP_EOL;
      $r .= ' $.each(up.files, function(i, file){' . PHP_EOL;
      $r .= '   if (i < (up.files.length -1)){' . PHP_EOL;
      $r .= '     up.removeFile(file);' . PHP_EOL;
      $r .= '   }' . PHP_EOL;
      $r .= ' });' . PHP_EOL;
      $r .= '});' . PHP_EOL;
    }

    $r .= '</script>' . PHP_EOL;

    return $r;

  }

}
