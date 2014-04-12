<?php

namespace Din\Config;

use Din\File\Files;

class Config
{

  private $_defines = array();

  public function __construct ( array $config_files = array() )
  {
    $this->add_config_files($config_files);

  }

  public function add_config_files ( array $config_files )
  {
    $config_files = array_reverse($config_files);
    foreach ( $config_files as $file ) {
      $vars = Files::get_return($file);

      if ( is_array($vars) ) {
        $this->_defines = array_merge($this->_defines, $vars);
      }
    }

  }

  public function define ()
  {
    foreach ( $this->_defines as $k => $v )
      define(strtoupper($k), $v);

  }

}
