<?php

namespace Din\Assets\Compressor;

use Assetic\Filter\Yui;

class Css extends Assets implements iAsset
{

  public function provideAsset ( $assets, $file, $group )
  {
    $this->setAssets($assets);
    $this->setCompressor(new Yui\CssCompressorFilter($this->getCompressorFile()));
    $this->setType('css');
    $this->setFile($file);

    if ( count($group) ) {
      foreach ( $group as $g ) {
        $this->compress($g);
      }
    } else {
      foreach ( $assets['css'] as $group => $file ) {
        $this->compress($group);
      }
    }
  }

}
