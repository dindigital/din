<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class Crypted extends AbstractFilter
{

  public function filter ( $field )
  {
    $this->_table->{$field} = null;
  }

}
