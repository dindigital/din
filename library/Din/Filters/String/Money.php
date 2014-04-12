<?php

namespace Din\Filters\String;

class Money
{

  /**
   * Pega somente os números e retorna em formato float
   * Exemplo: 'R$ 15.000,99' retorna 15000.99
   *
   * @param string $str
   * @return float
   */
  public static function filter_sql ( $str )
  {
    return preg_replace("/[^0-9]/", '', $str) / 100;

  }

  /**
   * Pega um número float e transforma em moeda BR
   * Exemplo: '1000.00' retorna 'R$ 1.000,00'
   *
   * @param string $str
   * @return string
   */
  public static function filter ( $str, $prefix = true )
  {
    $r = doubleval($str);
    $r = number_format($r, 2, ',', '.');
    if ( $prefix )
      $r = 'R$ ' . $r;

    return $r;

  }

}
