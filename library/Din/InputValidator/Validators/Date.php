<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class Date extends AbstractValidator
{
    
  protected $_format;
    
  public function __construct($format = 'd/m/Y') {
      $this->_format = $format;
  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::date($this->_format)->validate($value) )
      $this->addException("Campo {$label} com uma data inválida");
  }

}