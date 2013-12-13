<?php

namespace Din\Crypt;

class Crypt implements iCrypt
{

  private $_modifier;

  public function __construct ( $modifier = 'Xx0a1QW85lpwv_3r6t_djf6691' )
  {
    $this->_modifier = md5($modifier);
  }

  public function crypt ( $string )
  {
    return md5($this->_modifier . sha1($string));
  }

}

