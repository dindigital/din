<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class NewId extends AbstractFilter
{

  public function filter ( $field )
  {
    return $this->_table->{$field} = md5(uniqid());
  }

}
