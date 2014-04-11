<?php

namespace Din\Assets\Compressor;

use Din\File\Files;
use Exception;

class CompressorCreator extends Creator
{

  protected $_assetsFile;
  protected $_assets;

  public function __construct ( $config, $group = array() )
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
    $this->_assetGroup = $group;
  }

  public function factoryMethod ( iAsset $asset )
  {
    $this->create = new $asset();

    return($this->create->provideAsset($this->_assets, $this->_assetsFile, $this->_assetGroup));
  }

}
