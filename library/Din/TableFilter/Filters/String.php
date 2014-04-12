<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class String extends AbstractFilter
{

  public function filter ( $field )
  {
    $value = (string) $this->getValue($field);

    $this->_table->{$field} = $value;

  }

}
