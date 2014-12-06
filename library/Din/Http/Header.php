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
    return @$_SERVER['HTTP_REFERER'];

  }

  public static function getUri ()
  {
    return $_SERVER ['REQUEST_URI'];

  }

  public static function getUrl ()
  {
    $s = $_SERVER;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
    //$host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;

    return $protocol . '://' . $host . self::getUri();

  }

  public static function set401 ()
  {
    header("HTTP/1.1 401 Unauthorized");

  }

  public static function set404 ()
  {
    header("HTTP/1.0 404 Not Found");

  }

  public static function set500 ()
  {
    header("HTTP/1.0 500 Internal Server Error");

  }

  public static function setNoCache ()
  {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

  }

  public static function getDomain ()
  {
    return $_SERVER['HTTP_HOST'];

  }

  public static function json ()
  {
    header('Content-Type: application/json');

  }

}
