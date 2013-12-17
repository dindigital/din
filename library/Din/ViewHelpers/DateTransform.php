<?php

namespace Din\ViewHelpers;

class DateTransform
{

  /**
   * Pega uma data formato BR e retorna no formato SQL
   * Opcionalmente pode ser passada a hora junto
   * Exemplo: '10/08/1987 10:00' retorna '1987-08-10 10:00:00'
   *
   * @param string $str
   * @return string
   */
  public static function sql_date ( $str )
  {

    $arrDataHora = explode(' ', $str);
    $arrData = explode('/', $arrDataHora[0]);

    $r = '';

    if ( count($arrData) == 3 ) {
      list ( $d, $m, $y ) = $arrData;

      if ( !checkdate(intval($m), intval($d), intval($y)) ) {
        return ''; //wrong date format
      }

      if ( $m < 10 )
        $m = '0' . $m;

      if ( $d < 10 )
        $d = '0' . $d;

      $r = "{$y}-{$m}-{$d}";
      if ( count($arrDataHora) == 2 ) {
        $r .= " {$arrDataHora[1]}";
      }
    }

    return $r;
  }

  /**
   * Pega uma data e retorna no formato desejado, que é setado por parametro
   * Opcionalmente pode usar como formato o termo full, que retornaria conforme
   * exemplo abaixo:
   * Exemplo: '07/06/1988 às 12:35'
   *
   * @param string $data
   * @param string $formato
   * @return string
   */
  public static function format_date ( $data, $formato = 'd/m/Y' )
  {
    if ( !$data ) {
      $data = date('Y-m-d H:i:s');
    }
    $formato = $formato == 'full' ? 'd/m/Y \à\s H:i' : $formato;
    $r = date($formato, strtotime($data));
    return $r;
  }

  /**
   * Pega uma data e retorna por extenso
   *
   * @param string $data
   * @param string $formato
   * @return string
   */
  public static function format_date_extenso ( $data_sql )
  {
    $day = date('d', strtotime($data_sql));
    $month = self::month(date('m', strtotime($data_sql)));
    $year = date('Y', strtotime($data_sql));
    $week = self::week(date('w', strtotime($data_sql)));
    return "$week, $day de {$month} de {$year}";
  }

  /**
   * Retonar o nome do mês baseado no número
   *
   * @param numeric $month
   * @return string
   */
  public static function month ( $month )
  {
    switch ($month) {
      case 1: $month = "Janeiro";
        break;
      case 2: $month = "Fevereiro";
        break;
      case 3: $month = "Março";
        break;
      case 4: $month = "Abril";
        break;
      case 5: $month = "Maio";
        break;
      case 6: $month = "Junho";
        break;
      case 7: $month = "Julho";
        break;
      case 8: $month = "Agosto";
        break;
      case 9: $month = "Setembro";
        break;
      case 10: $month = "Outubro";
        break;
      case 11: $month = "Novembro";
        break;
      case 12: $month = "Dezembro";
        break;
    }
    return $month;
  }

  /**
   * Retonar o nome da semana baseado no número
   *
   * @param numeric $week
   * @return string
   */
  public static function week ( $week )
  {
    switch ($week) {
      case 0: $week = "Domingo";
        break;
      case 1: $week = "Segunda-Feira";
        break;
      case 2: $week = "Terça-Feira";
        break;
      case 3: $week = "Quarta-Feira";
        break;
      case 4: $week = "Quinta-Feira";
        break;
      case 5: $week = "Sexta-Feira";
        break;
      case 6: $week = "Sabado";
        break;
    }
    return $week;
  }

}
