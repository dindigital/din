<?php

namespace Din\AssetCompressor;

use Din\File\Files;

class JoinAssets
{

  private $_file_name;
  private $_file_contents;

  public function __construct ( $array_assets )
  {
    $this->setArrayAssets($array_assets);
  }

  public function setArrayAssets ( $array_assets )
  {
    $this->_array_assets = $array_assets;

    $file_name = '';
    $file_contents = '';
    foreach ( $array_assets as $asset ) {
      if ( !Files::exists($asset) )
        throw new \Exception('Asset não encontrado: ' . $asset);

      $file_name .= filemtime($asset) . filesize($asset) . ',';
      $file_contents .= file_get_contents($asset);
    }

    $file_name = md5($file_name);

    $this->_file_contents = $file_contents;
    $this->_file_name = $file_name;
  }

  public function getFileName ()
  {
    return $this->_file_name;
  }

  public function getFileContents ()
  {
    return $this->_file_contents;
  }

}
