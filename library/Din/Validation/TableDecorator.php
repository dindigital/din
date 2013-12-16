<?php

namespace lib\Validation;

use lib\Validation\StringTransform;

class TableDecorator
{

  public static function format_date ( $table, $prop_name, $formato = 'd/m/Y' )
  {
    if ( is_null($table->$prop_name) ) {
      $table->$prop_name = date('d/m/Y');
      return;
    }
    $table->$prop_name = DateTransform::format_date($table->$prop_name, $formato);
  }

  public static function sql_date ( $table, $prop_name )
  {
    $table->$prop_name = DateTransform::sql_date($table->$prop_name);
  }

}

