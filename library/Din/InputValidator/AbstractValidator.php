<?php

namespace Din\InputValidator;

use Din\InputValidator\ValidatorInterface;
use Din\Exception\JsonExceptionContainer;
use InvalidArgumentException;

abstract class AbstractValidator implements ValidatorInterface
{

  protected $_input = array();
  public $jsonException;

  public function __construct ()
  {
    ; // needed to instantiate through ReflectionClass
    // possibility to add params for the filters
  }

  public function setInput ( array $input )
  {
    $this->_input = $input;
  }
  
  public function setJsonException() {
      $this->jsonException = JsonExceptionContainer::getInstance();
  }

  protected function getValue ( $field )
  {
    if ( !array_key_exists($field, $this->_input) )
      throw new InvalidArgumentException("Índice {$field} não existe no array de input do validator");

    return $this->_input[$field];
  }
  
  public function addException($msg) {
    $this->jsonException->addException($msg);
  }

  abstract public function validate ( $prop, $label );
}
