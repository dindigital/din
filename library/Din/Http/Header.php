<?php

namespace Din\Http;

class Header
{

  public static function redirect ( $url )
  {
    header("Location: {$url}");
    exit;
  }

}
