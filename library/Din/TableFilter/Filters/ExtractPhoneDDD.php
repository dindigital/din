<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class ExtractPhoneDDD extends AbstractFilter
{

  protected $_new_field;

  public function __construct ( $new_field = null )
  {
    $this->_new_field = $new_field;

  }

  public function filter ( $field )
  {
    $value = (string) $this->getValue($field);

    $value = preg_replace('/ (.*)/', '', $value);
    $value = preg_replace('/[^0-9+]/', '', $value);

    if ( !is_null($this->_new_field) ) {
      $field = $this->_new_field;
    }

    $this->_table->{$field} = $value;

  }

}
