<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class Json extends AbstractFilter
{

  public function filter ( $field )
  {
    $value = (array) $this->getValue($field);

    $this->_table->{$field} = json_encode($value);

  }

}
