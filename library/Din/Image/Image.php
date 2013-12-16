<?php

namespace Din\Image;

use Din\File\Folder;
use WideImage;

/**
 *
 * @package lib.Image
 */
class Image
{

  private $_wi;
  private $_path;
  private $_autosave_path;
  private $_width;
  private $_height;
  private $_crop;

  private function setWI ()
  {
    $this->_wi = WideImage::load($this->_path);
  }

  public function __construct ( $path = null )
  {
    if ( $path )
      $this->setPath($path);
  }

  public function setPath ( $path )
  {
    $this->_path = rawurldecode($path);
    return $this;
  }

  public function setAutosavePath ( $autosave_path )
  {
    $this->_autosave_path = $autosave_path;
  }

  public function setWidth ( $width )
  {
    $this->_width = $width;

    return $this;
  }

  public function setHeight ( $height )
  {
    $this->_height = $height;

    return $this;
  }

  public function setCrop ( $crop )
  {
    $this->_crop = $crop;

    return $this;
  }

  public function getAutosavePath ()
  {
    $ext = strtolower(pathinfo($this->_path, PATHINFO_EXTENSION));
    $final_name = md5(filemtime($this->_path) . filesize($this->_path) . $this->_path . intval($this->_width) . intval($this->_height) . intval($this->_crop)) . '.' . $ext;

    $path = $this->_autosave_path . $final_name;

    return $path;
  }

  public function is_autosave_file ()
  {
    return is_file($this->getAutosavePath());
  }

  public function is_file ()
  {
    return is_file($this->_path);
  }

  public function resize ()
  {
    $this->setWI();

    if ( $this->_crop ) {
      $im = getimagesize($this->_path);
      $lar = $im[0];
      $alt = $im[1];

      $x = round(($lar / $alt) * $this->_height);
      $y = null;
      if ( $x < $this->_width ) {
        $y = round(($alt / $lar) * $this->_width);
        $x = null;
      }

      $this->_wi = $this->_wi->resize($x, $y)->crop('center', 'center', $this->_width, $this->_height);
    } else {
      $this->_wi = $this->_wi->resizeDown($this->_width, $this->_height);
    }
  }

  public function save ( $path )
  {
    //$path = ds($path);
//    if ( strpos($path, WEBROOT) === false )
//      $path = WEBROOT . $path;

    $diretorio = dirname($path);
    Folder::make_writable($diretorio);

    // se houver uma barra no final, então concatene o nome do arquivo
    if ( strrpos($path, DIRECTORY_SEPARATOR) == (strlen($path) - 1) ) {
      $path .= basename($this->_path);
    }

    $this->_wi->saveToFile($path);

    return $path;
  }

  public function autosave ()
  {
    return $this->save($this->getAutosavePath());
  }

  public function getSize ()
  {
    $im = getimagesize($this->_path);
    $lar = $im[0];
    $alt = $im[1];

    $std = new \stdClass();
    $std->width = $lar;
    $std->height = $alt;

    return $std;
  }

}
