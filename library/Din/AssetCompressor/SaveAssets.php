<?php

namespace Din\AssetCompressor;

use Din\File\Files;
use Din\AssetCompressor\CompressAssets;

class SaveAssets
{

  private $_file_path;
  private $_file_contents;
  private $_type;

  public function __construct ( $type, $file_path, $file_contents )
  {
    $this->setType($type);
    $this->setFilePath($file_path);
    $this->setFileContents($file_contents);
  }

  public function setFilePath ( $file_path )
  {
    $this->_file_path = $file_path;
  }

  public function setFileContents ( $file_contents )
  {
    $this->_file_contents = $file_contents;
  }

  public function setType ( $type )
  {
    $this->_type = $type;
  }

  public function save ()
  {
    if ( !Files::exists($this->_file_path) ) {
      $compress_assets = new CompressAssets($this->_type, $this->_file_contents);

      file_put_contents($this->_file_path, $compress_assets->getResult());
    }
  }

}