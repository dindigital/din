<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class Decimal extends AbstractFilter
{

  protected $_decimals;

  public function __construct ( $decimals = 2 )
  {
    $this->_decimals = $decimals;

  }

  public function filter ( $field )
  {
    $v = $this->getValue($field);
    $v = str_replace(',', '.', $v);

    $v = number_format($v, $this->_decimals, '.', '');

    $this->_table->{$field} = $v;

  }

}
