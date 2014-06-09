<?php

namespace Din\InputValidator;

use Din\InputValidator\ValidatorInterface;
use InvalidArgumentException;

abstract class AbstractValidator implements ValidatorInterface
{

  protected $_input = array();
  protected $exception;

  public function __construct ()
  {
    ; // needed to instantiate through ReflectionClass
    // possibility to add params for the filters

  }

  public function setInput ( array $input )
  {
    $this->_input = $input;

  }

  public function setException ( $exception )
  {
    $this->exception = $exception;

  }

  protected function getValue ( $field )
  {
    if ( !array_key_exists($field, $this->_input) )
      throw new InvalidArgumentException("Índice {$field} não existe no array de input do validator");

    return $this->_input[$field];

  }

  public function addException ( $msg )
  {
    $this->exception->addException($msg);

  }

  //abstract public function validate ( $prop, $label );

  public function validateValue ( $value, $label )
  {
    $this->setInput(array(
        'field' => $value
    ));

    return $this->validate('field', $label);

  }

}
