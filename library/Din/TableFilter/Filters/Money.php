<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\String\Money as MoneyFilter;

class Money extends AbstractFilter
{

  public function filter ( $field )
  {
    $v = $this->getValue($field);
    if ( !is_null($v) ) {
      $v = MoneyFilter::filter_sql($v);
    }

    $this->_table->{$field} = $v;

  }

}
