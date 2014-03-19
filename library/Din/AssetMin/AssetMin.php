<?php

namespace Din\AssetMin;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\Yui;
use Assetic\Filter\JSMinPlusFilter;
use Assetic\AssetWriter;
use Assetic\AssetManager;
use Din\File\Files;
use Exception;

class AssetMin
{

  protected $_assetsFile;
  protected $_assets = null;
  protected $_am;
  protected $_name;
  protected $_uri;
  protected $_yuicompressor;

  public function __construct ( $assets_file )
  {
    if ( !Files::exists($assets_file) ) {
      throw new Exception('Arquivo de assets não encontrado.');
    }

    $vars = Files::get_return($assets_file);
    if ( !is_array($vars) ) {
      throw new Exception('Arquivo de assets inválido.');
    }

    $this->_assetsFile = $assets_file;
    $this->_assets = $vars;
    $this->_yuicompressor = __DIR__ . '/yuicompressor-2.4.7.jar';
  }

  public function compressorAll ()
  {
    foreach ( $this->_assets as $type => $gp ) {
      if ( $type == 'css' ) {
        foreach ( $gp as $group => $files ) {
          $this->process('css', $group);
        }
      } else {
        foreach ( $gp as $group => $files ) {
          $this->process('js', $group);
        }
      }
    }
  }

  public function compressor ( $type, Array $group )
  {
    foreach ( $group as $row ) {
      $this->process($type, $row);
    }
  }

  protected function process ( $type, $group )
  {
    if ( !isset($this->_assets[$type][$group]) ) {
      throw new Exception("Grupo de {$type} não definido.");
    }

    $files = $this->_assets[$type][$group];

    $this->_am = null;
    $this->_am = new AssetManager();

    $src = array();
    foreach ( $files['src'] as $file ) {
      $src[] = new FileAsset($file);
    }

    $filter = array();
    if ( $type == 'css' ) {
      $filter = array(new Yui\CssCompressorFilter($this->_yuicompressor));
    } else {
      $filter = array(new Yui\JsCompressorFilter($this->_yuicompressor));
    }

    $asset = null;
    $asset = new AssetCollection($src, $filter);

    $this->_am->set($group, $asset);
    $name = $group . uniqid() . '.' . $type;
    $asset->setTargetPath($name);

    $this->removeFile($files['uri']);
    $this->defineUri($files['uri']);
    $this->writer();
    $this->updateName($name);
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
    $file_contents = file_get_contents($this->_assetsFile);
    $file_contents = str_replace($this->_name, $name, $file_contents);
    file_put_contents($this->_assetsFile, $file_contents);
  }

}
