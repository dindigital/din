<?php

namespace Din\Exception;

use Exception;

class JsonException
{

  public static $_exceptions = array();

  public static function addException ( $msg )
  {
    self::$_exceptions[] = $msg;
    return false;
  }

  public static function throwException ()
  {
    if ( count(self::$_exceptions) )
      throw new Exception(json_encode(self::$_exceptions));
  }

  public static function clearExceptions ()
  {
    self::$_exceptions = array();
  }

}
