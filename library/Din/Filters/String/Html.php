<?php

namespace Din\Filters\String;

class Html
{

  public static function scape ( $string )
  {
    $scaped_string = htmlspecialchars($string);

    return $scaped_string;

  }

  public static function scapeArray ( array &$array, array $indexes )
  {
    foreach ( $indexes as $k ) {
      $array[$k] = self::scape($array[$k]);
    }


    return $array;

  }

}
