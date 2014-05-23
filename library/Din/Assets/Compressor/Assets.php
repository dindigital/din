<?php

namespace Din\Assets\Compressor;

use Assetic\AssetManager;
use Assetic\Asset\FileAsset;
use Assetic\Asset\AssetCollection;
use Assetic\AssetWriter;

abstract class Assets
{

  protected $_assets;
  protected $_file;
  protected $_type;
  protected $_yuiCompressor;
  protected $_am;
  protected $_uri;

  protected function setAssets ( $assets )
  {
    $this->_assets = $assets;

  }

  protected function setFile ( $file )
  {
    $this->_file = $file;

  }

  protected function setType ( $type )
  {
    $this->_type = $type;

  }

  protected function getCompressorFile ()
  {
    return __DIR__ . '/yuicompressor-2.4.7.jar';

  }

  protected function setCompressor ( $compressor )
  {
    $this->_yuiCompressor = $compressor;

  }

  protected function compress ( $group )
  {
    if ( isset($this->_assets[$this->_type][$group]) ) {
      $this->_am = new AssetManager();

      $files = $this->_assets[$this->_type][$group];
      $src = array();
      foreach ( $files['src'] as $file ) {
        $src[] = new FileAsset($file);
      }

      $filter = array($this->_yuiCompressor);

      $assetCollection = new AssetCollection($src, $filter);

      $this->_am->set($group, $assetCollection);
      $name = $group . uniqid() . '.' . $this->_type;
      $assetCollection->setTargetPath($name);

      $this->removeFile($files['uri']);
      $this->defineUri($files['uri']);
      $this->writer();
      $this->updateName($name);

      $this->_am = null;
      sleep(2);
    }

  }

  protected function removeFile ( $uri )
  {
    @unlink('public' . $uri);

  }

  protected function defineUri ( $uri )
  {
    $ex = explode('/', $uri);
    $this->_name = array_pop($ex);
    $uri = implode('/', $ex);
    $this->_uri = $uri;

  }

  protected function writer ()
  {
    $writer = new AssetWriter('public' . $this->_uri);
    $writer->writeManagerAssets($this->_am);

  }

  protected function updateName ( $name )
  {

    echo($this->_name . ' -> ' . $name . PHP_EOL);
    $file_contents = file_get_contents($this->_file);
    $file = str_replace($this->_name, $name, $file_contents);
    file_put_contents($this->_file, $file);

  }

}
