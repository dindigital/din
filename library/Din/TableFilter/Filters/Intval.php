<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class Intval extends AbstractFilter
{

  protected $_default_value;

  public function __construct ( $default_value = null )
  {
    $this->_default_value = $default_value;

  }

  public function filter ( $field )
  {
    $v = $this->getValue($field);

    if ( is_null($v) ) {
      $value = $this->_default_value;
    } else {
      $value = intval($v);
    }

    $this->_table->{$field} = $value;

  }

}
