<?php

namespace Din\Assets\Compressor;

use Assetic\Filter\Yui;

class Js extends Assets implements iAsset
{

  public function provideAsset ( $config, $group )
  {
    $assets = $config->getAssets();
    $this->setAssets($assets);
    $this->setCompressor(new Yui\JsCompressorFilter($this->getCompressorFile()));
    $this->setType('js');
    $this->setFile($config->getAssetsFile());

    if ( count($group) ) {
      foreach ( $group as $g ) {
        $this->compress($g);
      }
    } else {
      foreach ( $assets['js'] as $group => $file ) {
        $this->compress($group);
      }
    }

  }

}
