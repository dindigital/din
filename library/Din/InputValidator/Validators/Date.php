<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class Date extends AbstractValidator
{

  protected $_format;
  protected $_required;

  public function __construct ( $format = 'd/m/Y', $required = true )
  {
    $this->_format = $format;
    $this->_required = $required;

  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( $value == '' && !$this->_required )
      return;

    if ( !v::date($this->_format)->validate($value) )
      $this->addException("Campo {$label} com uma data inválida");

  }

}
