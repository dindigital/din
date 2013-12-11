<?php

namespace Din\AssetCompressor;

use Din\AssetCompressor\minify\CSS;
use Din\AssetCompressor\minify\JS;

class CompressAssets
{

  private $_type;
  private $_file_contents;

  public function __construct ( $type, $file_contents )
  {
    $this->setType($type);
    $this->setFileContents($file_contents);
  }

  public function setType ( $type )
  {
    $this->_type = $type;
  }

  public function setFileContents ( $file_contents )
  {
    $this->_file_contents = $file_contents;
  }

  public function getResult ()
  {
    if ( $this->_type == 'css' )
      $file_contents = $this->strCompress(CSS::process($this->_file_contents));
    elseif ( $this->_type == 'js' )
      $file_contents = JS::minify($this->_file_contents);

    return $file_contents;
  }

  private function strCompress ( $str )
  {

    $str = str_replace("\n", " ", $str);
    $str = str_replace("\r", " ", $str);
    $str = preg_replace('/[[:space:]]+/', ' ', $str);

    return $str;
  }

}
