<?php

namespace Din\Assets;

use Din\Assets\AssetsConfigInterface;
use Exception;

class AssetsRead
{

  protected $_config;
  protected $_assets = array();
  protected $_mode = 'production';
  protected $_replace = 'public';
  protected $_group;
  protected $_type;

  const MODE_PRODUCTION = 'production';
  const MODE_DEVELOPMENT = 'development';

  public function __construct ( AssetsConfigInterface $config )
  {
    $this->_config = $config->getAssets();
  }

  public function setMode ( $mode )
  {
    $this->_mode = $mode;
  }

  public function setReplace ( $replace )
  {
    $this->_replace = $replace;
  }

  public function setGroup ( $type, Array $group )
  {
    if ( !isset($this->_config[$type]) ) {
      throw new Exception("Não existem configurações para {$type}, verificar arquivo config");
    }

    if ( !count($group) ) {
      throw new Exception("Grupo vazio.");
    }

    $this->_type = $type;
    $this->_group = $group;

    if ( $this->_mode == self::MODE_PRODUCTION ) {
      $this->readProdution();
    } else {
      $this->readDevelopment();
    }
  }

  protected function readProdution ()
  {
    foreach ( $this->_group as $gp ) {
      if ( !isset($this->_config[$this->_type][$gp]) )
        throw new Exception("Gupo de assets {$this->_type} não encontrado: $gp");

      $file = $this->getFilePath($this->_type, $this->_config[$this->_type][$gp]['uri']);
      $this->addContainer($this->_type, $gp, $file);
    }
  }

  protected function readDevelopment ()
  {
    foreach ( $this->_group as $gp ) {

      if ( !isset($this->_config[$this->_type][$gp]) )
        throw new Exception("Gupo de assets {$this->_type} não encontrado: $gp");

      $file = '';
      foreach ( $this->_config[$this->_type][$gp]['src'] as $src ) {
        $src = str_replace($this->_replace, '', $src);
        $file .= $this->getFilePath($this->_type, $src);
      }

      $this->addContainer($this->_type, $gp, $file);
    }
  }

  protected function getFilePath ( $type, $file )
  {
    switch ($type) {
      case 'css':
        $asset = '<link href="' . $file . '" rel="stylesheet"/>' . "\n    ";
        break;
      case 'js':
        $asset = '<script src="' . $file . '"></script>' . "\n    ";
        break;
    }

    return $asset;
  }

  protected function addContainer ( $type, $group, $file )
  {
    $this->_assets[$type][$group] = $file;
  }

  public function getAssets ()
  {
    return $this->_assets;
  }

}
