<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\String\Uri;

class Friendly extends AbstractFilter
{

  protected $_from_field;

  public function __construct ( $from_field )
  {
    $this->_from_field = $from_field;

  }

  public function filter ( $field )
  {
    $value = (string) $this->getValue($this->_from_field);

    $this->_table->{$field} = Uri::format($value);

  }

}
