<?php

namespace Din\Filters\String;

/**
 * Limita uma string para ter no máximio a quantidade de caracteres especificada
 * Caso a string não ultrapasse esse máximo, não é adicionado o $delimiter
 *
 * @param $str string
 * String a ser tratada
 *
 * @param $max int
 * Quantidade máxima de caracteres à limitar o texto
 *
 * @param $delimiter string
 * Delimita o fim da string com o valor desse parâmetro.
 * Usado somente quando a string ultrapassa o valor $max
 *
 * @return string
 *
 */
class LimitChars
{

  public static function filter ( $str, $max = 100, $delimiter = '(...)' )
  {
    if ( strlen($str) > $max ) {
      $max = $max - strlen($delimiter);
      $r = substr($str, 0, $max);
      $r = substr($r, 0, strrpos($r, " "));
      if ( substr($r, -1) == '-' ) {
        $r = substr($r, 0, strrpos($r, "-"));
      }
      if ( substr($r, -1) == ',' ) {
        $r = substr($r, 0, strrpos($r, ","));
      }
      $r = trim($r);
      $r .= $delimiter;
    } else {
      $r = $str;
    }

    return $r;
  }

}

