<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\String\Money as MoneyFilter;

class Money extends AbstractFilter
{

  public function filter ( $field )
  {
    $v = $this->getValue($field);
    $value = MoneyFilter::filter_sql($v);

    $this->_table->{$field} = $value;

  }

}
