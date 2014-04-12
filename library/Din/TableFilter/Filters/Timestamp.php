<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class Timestamp extends AbstractFilter
{

  public function filter ( $field )
  {
    $this->_table->{$field} = date('Y-m-d H:i:s');

  }

}
