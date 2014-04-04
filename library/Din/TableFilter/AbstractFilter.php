<?php

namespace Din\TableFilter;

use Din\Exception\JsonException;
use Din\TableFilter\FilterInterface;

abstract class AbstractFilter implements FilterInterface
{

  protected $_table;
  protected $_input;

  public function __construct ( $required )
  {
    $this->_table = $required['table'];
    $this->_input = $required['input'];
  }

  protected function getValue ( $field )
  {
    if ( !array_key_exists($field, $this->_input) )
      return JsonException::addException("Índice {$field} não existe no array de input do filter");

    return $this->_input[$field];
  }

//  protected function setOptions ()
//  {
//    //
//  }

  abstract public function filter ( $input );
}
