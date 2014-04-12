<?php

namespace Din\Image;

use \stdClass;

/**
 * Classe para manipular imagens
 * Exemplo de uso:
 * $pic = new Picuri($file);
 * $pic->setHeight(200);
 * $pic->save();
 * echo $pic->getImage();
 *
 * Ou usar o método estático
 * echo Picuri::picUri($file, $width, $height, $crop, $atributos, $type);
 * @package lib.Image
 */
class Picuri
{

  private $_image;
  private $_width = 0;
  private $_height = 0;
  private $_newWidth;
  private $_newHeight;
  private $_newImage;
  private $_crop = false;
  private $_cropType = '';
  private $_attributos = '';
  private $_typeReturn = 'tag';
  private $_imageReturn;

  public function __construct ( $file )
  {
    $this->_image = new Image(PATH_REPLACE . $file, PATH_IMAGE, PATH_REPLACE . IMAGEM_PADRAO);

  }

  public function setWidth ( $width )
  {
    $this->_width = $width;

  }

  public function setHeight ( $height )
  {
    $this->_height = $height;

  }

  public function setCrop ( $crop )
  {
    $this->_crop = $crop;

  }

  public function setCropType ( $crop_type )
  {
    $this->_cropType = $crop_type;

  }

  public function setAtributos ( Array $atributos )
  {
    if ( count($atributos) ) {
      foreach ( $atributos as $k => $v ) {
        $attr[] = $k . '="' . $v . '"';
      }

      $this->_attributos = implode(' ', $attr);
    }

  }

  public function setTypeReturn ( $typeReturn )
  {
    $this->_typeReturn = $typeReturn;

  }

  public function save ()
  {
    $this->_image->setWidth($this->_width)->setHeight($this->_height)->setCrop($this->_crop)->setCropType($this->_cropType);
    if ( !$this->_image->is_saved_file() ) {
      $this->_image->resize()->autosave();
    }

    list($this->_newWidth, $this->_newHeight) = getimagesize($this->_image->getSavePath());

    $this->_newImage = str_replace(PATH_REPLACE, '', $this->_image->getSavePath());

    if ( defined('IMG_SUBDOMAIN') && getenv('DOMAIN_NAME') ) {
      $this->_newImage = 'http://' . IMG_SUBDOMAIN . '.' . getenv('DOMAIN_NAME') . $this->_newImage;
    }

  }

  public function getImage ()
  {
    switch ($this->_typeReturn) {
      case 'tag':
        $this->getImageTag();
        break;
      case 'std':
        $this->getImageStd();
        break;
      case 'path':
        $this->_imageReturn = $this->_newImage;
        break;
    }

    return $this->_imageReturn;

  }

  private function getImageTag ()
  {
    $this->_imageReturn = '<img src="' . $this->_newImage . '" width="' . $this->_newWidth . '" height="' . $this->_newHeight . '" ' . $this->_attributos . ' />';

  }

  private function getImageStd ()
  {
    $std = new stdClass();
    $std->width = $this->_newWidth;
    $std->height = $this->_newHeight;
    $std->src = $this->_newImage;

    $this->_imageReturn = $std;

  }

  public function __toString ()
  {
    return $this->getImage();

  }

  /**
   * Método estático para facilitar o uso da classe
   * @param type $file
   * @param type $width
   * @param type $height
   * @param type $crop
   * @param type $atributos
   * @param type $type
   * @param type $crop_type
   * @return string
   */
  public static function picUri ( $file, $width = false, $height = false, $crop = false, $atributos = array(), $type = 'tag', $crop_type = 'center' )
  {
    $pic = new self($file);

    if ( $width )
      $pic->setWidth($width);

    if ( $height )
      $pic->setHeight($height);

    $pic->setCrop($crop);
    $pic->setCropType($crop_type);

    if ( count($atributos) )
      $pic->setAtributos($atributos);

    $pic->setTypeReturn($type);

    $pic->save();

    return $pic->getImage();

  }

}
