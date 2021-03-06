<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\Crypt\Crypt;

class Crypted extends AbstractFilter
{

  public function filter ( $field )
  {
    $value = $this->getValue($field);

    if ( $value != '' ) {
      $crypt = new Crypt();
      $this->_table->{$field} = $crypt->crypt($value);
    }

  }

}
