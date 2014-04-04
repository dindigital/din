<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\Date\DateToSql;

class Date extends AbstractFilter
{

  public function filter ( $field )
  {
    $value = $this->getValue($field);

    $this->_table->{$field} = DateToSql::filter_date($value);
  }

}
