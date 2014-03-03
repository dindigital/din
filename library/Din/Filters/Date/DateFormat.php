<?php

namespace Din\Filters\Date;

use Respect\Validation\Validator as v;

class DateFormat
{

  public static function validate ( $date )
  {
    return v::date()->validate($date);
  }

  public static function filter_date ( $date, $format = 'd/m/Y' )
  {
    self::validate($date);

    $date = date($format, strtotime($date));

    return $date;
  }

  public static function filter_month ( $date )
  {
    self::validate($date);

    $month = date('m', strtotime($date));

    $arryMonth = array(
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Deze,brp',
    );

    return $arryMonth[$month];
  }

  public static function filter_week ( $date )
  {
    self::validate($date);

    $week = date('w', strtotime($date));

    $arryWeek = array(
        '1' => 'Segunda-Feira',
        '2' => 'Terça-Feira',
        '3' => 'Quarta-Feira',
        '4' => 'Quinta-Feira',
        '5' => 'Sexta-Feira',
        '6' => 'Sabado',
        '7' => 'Domingo',
    );

    return $arryWeek[$week];
  }

  public static function filter_dateExtensive ( $date )
  {
    self::validate($date);

    $day = date('d', strtotime($date));
    $month = self::filter_month($date);
    $year = date('Y', strtotime($date));

    return "{$day} de {$month} de {$year}";
  }

  public static function filter_dateTimeExtensive ( $datetime )
  {
    $date = self::filter_dateExtensive($datetime);
    $time = date('H:i', strtotime($datetime));

    return "{$date} às {$time}";
  }

}
