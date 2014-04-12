<?php

namespace Din\Filters\String;

class Number
{

  /**
   * Retorna uma string após retirar tudo que não for número
   *
   * @param string $str
   * @return string
   */
  public static function only_numbers ( $str )
  {
    return preg_replace("/[^0-9]/", '', $str);

  }

  /**
   * Retorna a string após retirar todos os possíveis números
   * @param type $str
   * @return type
   */
  public static function not_numbers ( $str )
  {
    return preg_replace("/[0-9\-]/", '', $str);

  }

}
