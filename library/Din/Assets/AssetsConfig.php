<?php

namespace Din\Assets;

use Din\File\Files;
use Exception;

class AssetsConfig implements AssetsConfigInterface, AssetsConfigFileInterface
{

  protected $_assets;
  protected $_assetsFile;

  public function __construct ( $config )
  {
    if ( !Files::exists($config) ) {
      throw new Exception('Arquivo de assets não encontrado.');
    }

    $vars = Files::get_return($config);
    if ( !is_array($vars) ) {
      throw new Exception('Arquivo de assets inválido.');
    }

    $this->_assets = $vars;
    $this->_assetsFile = $config;

  }

  public function getAssets ()
  {
    return $this->_assets;

  }

  public function getAssetsFile ()
  {
    return $this->_assetsFile;

  }

}
