<?php

namespace Din\Assets\Compressor;

use Din\Assets\AssetsConfig;

class CompressorCreator extends Creator
{

  protected $_assetsConfig;
  protected $_assetsGroup;

  public function __construct ( AssetsConfig $config, $group = array() )
  {
    $this->_assetsConfig = $config;
    $this->_assetsGroup = $group;

  }

  public function factoryMethod ( iAsset $asset )
  {
    $this->create = $asset;
    return($this->create->provideAsset($this->_assetsConfig, $this->_assetsGroup));

  }

}
