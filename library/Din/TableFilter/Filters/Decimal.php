<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\String\Number;

class Decimal extends AbstractFilter
{

  protected $_decimals;

  public function __construct ( $decimals = 2 )
  {
    $this->_decimals = $decimals;

  }

  public function filter ( $field )
  {
    $v = Number::only_numbers($this->getValue($field));

    $v = number_format($v, $this->_decimals, '.', '');

    $this->_table->{$field} = $v;

  }

}
