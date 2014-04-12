<?php

namespace Din\TableFilter;

use Din\TableFilter\FilterInterface;
use Din\DataAccessLayer\Table\Table;
use InvalidArgumentException;

abstract class AbstractFilter implements FilterInterface
{

  protected $_table;
  protected $_input;

  public function __construct ()
  {
    ; // needed to isntanciate through ReflectionClass
    // possibility to add params for the filters

  }

  public function setTable ( Table $table )
  {
    $this->_table = $table;

  }

  public function setInput ( array $input )
  {
    $this->_input = $input;

  }

  protected function getValue ( $field )
  {
    if ( !array_key_exists($field, $this->_input) )
      throw new InvalidArgumentException("Índice {$field} não existe no array de input do filter");

    return $this->_input[$field];

  }

  abstract public function filter ( $field );
}
