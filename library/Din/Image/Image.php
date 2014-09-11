<?php

namespace Din\Image;

use Din\File\Folder;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Din\Image\ImageCrop;
use \Exception;

/**
 *
 * @package lib.Image
 */
class Image
{

  /**
   * Iinstancia da classe Imagine
   * @var Object
   */
  private $_imagine;

  /**
   * Instrancia da imagem após ser redimencionada
   * @var String
   */
  private $_resizeImage;

  /**
   * Caminho completo da imagem
   * @var String
   */
  private $_path;
  private $_failPath = false;

  /**
   * Caminho onde a imagem será salva
   * @var type
   */
  private $_save_path;

  /**
   * Largura da imagem
   * @var Int
   */
  private $_width;

  /**
   * altura da imagem
   * @var Int
   */
  private $_height;

  /**
   * Properidade para definir se imagem será cortada ou apenas redimencionada
   * @var Boolean
   */
  private $_crop;

  /**
   * Define o tipo do crop, se é center ou top
   * @var string
   */
  private $_cropType = 'center';

  /**
   * Construtor da classe Image Define o path da imagem que será redimecionada
   * e o caminho onde a nova imagem será salva
   * @param String $path Caminho completo da imagem que será redimecionada
   * @param String $save_path Caminho completo onde a nova imagem será salva
   */
  public function __construct ( $path, $save_path, $fail_path = false )
  {
    $this->setFailPath($fail_path);
    $this->setPath($path);
    $this->_save_path = rawurldecode($save_path);

  }

  private function setFailPath ( $path )
  {
    if ( is_file($path) )
      $this->_failPath = $path;

  }

  private function getFailPath ()
  {
    if ( !$this->_failPath )
      throw new Exception('Arquivo inválido');

    return $this->_failPath;

  }

  private function setPath ( $path )
  {
    if ( !is_file($path) )
      $path = $this->getFailPath();

    $this->_path = rawurldecode($path);

  }

  /**
   * Define a largura da desejada
   * Caso de valor 0, seta uma largura grande.
   * @param Number $width
   * @return \Din\Image\Image
   */
  public function setWidth ( $width = 0 )
  {
    $this->_width = $width;
    return $this;

  }

  /**
   * Define a altura da desejada
   * Caso de valor 0, seta uma altura grande.
   * @param Number $height
   * @return \Din\Image\Image
   */
  public function setHeight ( $height = 0 )
  {
    $this->_height = $height;
    return $this;

  }

  /**
   * Define se imagem será cortada ou apenas redimencionada
   * @param Boolean $crop
   * @return \Din\Image\Image
   */
  public function setCrop ( $crop = false )
  {

    if ( $crop ) {
      /**
       * Caso utilize o método crop, valida se os os 2 tamanhos foram definidos
       * No caso de não definidos, seta a Exception
       */
      if ( $this->_width == 0 || $this->_height == 0 )
        throw new Exception('Para utilizar CROP as dimen precisa ser definida');
    }

    $this->_crop = $crop;
    return $this;

  }

  public function setCropType ( $crop_type = 'center' )
  {
    $this->_cropType = $crop_type;

  }

  public function getSavePath ()
  {
    $ext = strtolower(pathinfo($this->_path, PATHINFO_EXTENSION));
//    $final_name = md5($this->_path . intval($this->_width) . intval($this->_height) . intval($this->_crop) . $this->_cropType) . '.' . $ext;

    $final_name = md5(filemtime($this->_path) . filesize($this->_path) . $this->_path . intval($this->_width) . intval($this->_height) . intval($this->_crop)) . $this->_cropType . '.' . $ext;

    $path = $this->_save_path . $final_name;

    return $path;

  }

  public function is_saved_file ()
  {
    return is_file($this->getSavePath());

  }

  public function is_file ()
  {
    return is_file($this->_path);

  }

  public function resize ()
  {
    try {
      $this->_imagine = new Imagine();
      $this->_resizeImage = $this->_imagine->open($this->_path);

      if ( $this->_crop ) {
        $image_crop = new ImageCrop($this->_resizeImage, new Box($this->_width, $this->_height));
        $this->_resizeImage = $image_crop->crop($this->_cropType);
      } else {
        $mode = ImageInterface::THUMBNAIL_INSET;
        $this->calcWidth();
        $this->calcHeight();

        $size = new Box($this->_width, $this->_height);
        $this->_resizeImage = $this->_resizeImage->thumbnail($size, $mode);
      }
    } catch (\Imagine\Exception\RuntimeException $e) {
      $this->_path = ($this->getFailPath());
      return $this->resize();
    }

    return $this;

  }

  private function calcWidth ()
  {
    if ( $this->_width == 0 ) {
      $size = $this->_resizeImage->getSize();
      $ratio = $this->_height / $size->getHeight();
      $this->_width = $size->getWidth() * $ratio;
    }

  }

  private function calcHeight ()
  {
    if ( $this->_height == 0 ) {
      $size = $this->_resizeImage->getSize();
      $ratio = $this->_width / $size->getWidth();
      $this->_height = $size->getHeight() * $ratio;
    }

  }

  public function save ( $path )
  {
    $diretorio = dirname($path);
    Folder::make_writable($diretorio);

    if ( strrpos($path, DIRECTORY_SEPARATOR) == (strlen($path) - 1) ) {
      $path .= basename($this->_path);
    }

    $opt = array('jpeg_quality' => 100, 'png_compression_level' => 9);

    $this->_resizeImage->save($path, $opt);

    return $path;

  }

  public function autosave ()
  {
    return $this->save($this->getSavePath());

  }

}
