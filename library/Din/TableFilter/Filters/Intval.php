<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class Intval extends AbstractFilter
{

  public function filter ( $field )
  {
    $value = intval($this->getValue($field));

    $this->_table->{$field} = $value;
  }

}
