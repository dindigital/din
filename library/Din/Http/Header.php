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

  public static function setNoCache ()
  {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
  }

}
