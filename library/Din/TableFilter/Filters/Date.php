<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\Date\DateToSql;

class Date extends AbstractFilter
{

  protected $_with_time;

  public function __construct ( $with_time = false )
  {
    $this->_with_time = $with_time;

  }

  public function filter ( $field )
  {
    $value = $this->getValue($field);

    if ( (string) $value == '' ) {
      $this->_table->{$field} = null;
      return;
    }

    if ( $this->_with_time ) {
      $explode = explode(' ', $value);
      $date = $explode[0];
      $time = $explode[1];

      $this->_table->{$field} = DateToSql::filter_date($date) . ' ' . $time;
    } else {
      $this->_table->{$field} = DateToSql::filter_date($value);
    }

  }

}
