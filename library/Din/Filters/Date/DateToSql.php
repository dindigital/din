<?php

namespace Din\Filters\Date;

use Respect\Validation\Validator as v;
use \Exception;

/**
 * Filtra a data no formato pt_BR para o formato de SQL
 */
class DateToSql
{

  public static function validate ( $date )
  {
    return v::date('d/m/Y')->validate($date);
  }

  public static function filter_date ( $date )
  {
    if ( self::validate($date) ) {
      $arrayDate = explode('/', $date);
      return "{$arrayDate[2]}-{$arrayDate[1]}-{$arrayDate[0]}";
    }
  }

  public static function filter_datetime ( $datetime )
  {
    $arrayDate = explode(' ', $datetime);
    if ( count($arrayDate) != 2 )
      throw new Exception('Data/Horário no formato inválido para conversão');

    $date_sql = self::filter_date($arrayDate[0]);

    if ( !v::date('H:i:s')->validate($arrayDate[1]) && !v::date('H:i')->validate($arrayDate[1]) )
      throw new Exception('Horário no formato inválido para conversão');

    $time_sql = date('H:i:s', strtotime($arrayDate[1]));

    $datetime_sql = $date_sql . ' ' . $time_sql;

    if ( !v::date()->validate($datetime_sql) )
      throw new Exception('Erro na conversão');

    return $datetime_sql;
  }

}
