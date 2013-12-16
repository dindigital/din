<?php

namespace Din\Validation;

class Validate
{

  public static function email ( $email )
  {
    return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email) > 0;
  }

  public static function username ( $username )
  {
    return preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $username) > 0 &&
            strlen($username) >= 6;
  }

  public static function email_comma_separated ( $emails )
  {
    $emails = str_replace(PHP_EOL, ',', $emails);
    $emails = str_replace(';', ',', $emails);
    $emails = str_replace(' ', '', $emails);
    $explode = explode(',', $emails);

    $return = [];

    foreach ( $explode as $email ) {
      if ( self::email($email) ) {
        $return[] = $email;
      }
    }

    if ( !count($return) )
      return false;

    return implode(',', $return);
  }

  public static function cep ( $str )
  {
    $r = preg_match("/^[0-9]{5}-[0-9]{3}$/", $str);

    return $r;
  }

  public static function uf ( $str )
  {
    $str = strtoupper($str);
    $arrEstados = array("AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MG", "MT", "MS", "PA", "PB", "PE", "PI", "PR", "RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO");
    $r = in_array($str, $arrEstados, true);

    return $r;
  }

  public static function data ( $data )
  {
    $data = explode('/', $data);

    $r = false;

    if ( count($data) == 3 ) {
      list ( $d, $m, $y ) = $data;
      if ( is_numeric($d) && is_numeric($m) && is_numeric($y) ) {
        $r = checkdate($m, $d, $y);
      }
    }

    return $r;
  }

  public static function hora ( $hora )
  {
    return (bool) preg_match("/(2[0-3]|[01][0-9]):[0-5][0-9]/", $hora);
  }

  public static function data_hora ( $data_hora )
  {
    $explode = explode(' ', $data_hora);
    if ( count($explode) < 2 )
      return false;

    list($data, $hora) = $explode;

    return self::data($data) && self::hora($hora);
  }

  public static function cpf ( $cpf )
  {
    //$cpf = ereg_replace('[^0-9]', '', $cpf);
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    $proibidos = array('11111111111', '22222222222', '33333333333',
        '44444444444', '55555555555', '66666666666', '77777777777',
        '88888888888', '99999999999', '00000000000', '12345678909');
    if ( in_array($cpf, $proibidos) || strlen($cpf) != 11 ) {
      return false;
    }

    $a = 0;
    for ( $i = 0; $i < 9; $i++ ) {
      $a += ($cpf[$i] * (10 - $i));
    }

    $b = ($a % 11);
    $a = (($b > 1) ? (11 - $b) : 0);

    if ( $a != $cpf[9] ) {
      return false;
    }

    $a = 0;
    for ( $i = 0; $i < 10; $i++ ) {
      $a += ($cpf[$i] * (11 - $i));
    }
    $b = ($a % 11);
    $a = (($b > 1) ? (11 - $b) : 0);
    if ( $a != $cpf[10] ) {
      return false;
    }

    return $cpf != '';
  }

  public static function cnpj ( $str )
  {
    if ( !preg_match('|^(\d{2,3})\.?(\d{3})\.?(\d{3})\/?(\d{4})\-?(\d{2})$|', $str, $matches) )
      return false;

    array_shift($matches);

    $str = implode('', $matches);
    if ( strlen($str) > 14 )
      $str = substr($str, 1);

    $sum1 = 0;
    $sum2 = 0;
    $sum3 = 0;
    $calc1 = 5;
    $calc2 = 6;

    for ( $i = 0; $i <= 12; $i++ ) {
      $calc1 = $calc1 < 2 ? 9 : $calc1;
      $calc2 = $calc2 < 2 ? 9 : $calc2;

      if ( $i <= 11 )
        $sum1 += $str[$i] * $calc1;

      $sum2 += $str[$i] * $calc2;
      $sum3 += $str[$i];
      $calc1--;
      $calc2--;
    }

    $sum1 %= 11;
    $sum2 %= 11;

    //return ($sum3 && $str[12] == ($sum1 < 2 ? 0 : 11 - $sum1) && $str[13] == ($sum2 < 2 ? 0 : 11 - $sum2)) ? $str : false;
    return ($sum3 && $str[12] == ($sum1 < 2 ? 0 : 11 - $sum1) && $str[13] == ($sum2 < 2 ? 0 : 11 - $sum2));
  }

  public static function array_key ( $array, $key )
  {
    return isset($array[$key]);
  }

  public static function int_positive ( $number )
  {
    return is_numeric($number) && floatval($number) > 0;
  }

  public static function not_empty ( $string )
  {
    return trim($string) != '';
  }

  public static function numeric ( $string )
  {
    return is_numeric($string);
  }

  public static function file_extension ( $filename, $arrExt )
  {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    return in_array($ext, $arrExt);
  }

  public static function file_maxsize ( $filename, $maxsize )
  {
    return filesize($filename) / 1024 / 1024 <= floatval($maxsize);
  }

}
