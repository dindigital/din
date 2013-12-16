<?php

namespace Din\Http;

class Header
{

  public static function redirect ( $url = '' )
  {
    if ( $url == '' ) {
      $url = $_SERVER['REQUEST_URI'];
    }

    header("Location: {$url}");
    exit;
  }

}
