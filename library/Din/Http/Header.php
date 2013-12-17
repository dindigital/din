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

  public static function getReferer ()
  {
    return $_SERVER['HTTP_REFERER'];
  }

  public static function set404 ()
  {
    header("HTTP/1.0 404 Not Found");
  }

}
