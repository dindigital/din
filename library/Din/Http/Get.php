<?php

namespace Din\Http;

class Get
{

  public static function text ( $fieldname )
  {
    return (isset($_GET[$fieldname])) ? trim($_GET[$fieldname]) : '';

  }

  /**
   * Facilitador de acesso ao get de um campo array (exemplo: fotos[])
   *
   * @param string $fieldname
   * @return array
   */
  public static function aray ( $fieldname )
  {
    return (isset($_GET[$fieldname])) ? $_GET[$fieldname] : array();

  }

}
