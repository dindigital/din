<?php

namespace Din\Http;

class Get
{

  public static function text ( $fieldname )
  {
    return (isset($_GET[$fieldname])) ? trim($_GET[$fieldname]) : '';
  }

}
