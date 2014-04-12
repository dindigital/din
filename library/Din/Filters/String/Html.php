<?php

namespace Din\Filters\String;

class Html
{

  public static function scape ( $string )
  {
    $scaped_string = htmlspecialchars($string);

    return $scaped_string;

  }

}
