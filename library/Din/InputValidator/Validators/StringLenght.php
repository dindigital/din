<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class StringLenght extends AbstractValidator
{
    
  protected $_min;
  protected $_max;
    
  public function __construct ( $min = 1, $max = null ) {
    $this->_min = $min;
    $this->_max = $max;
  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    $message = "O campo {$label} precisa ter no mínimo {$this->_min} caractere(s)";
    if ( !is_null($this->_max) ) {
        $message .= " e no máximo {$this->_max} caractere(s)";
    }

    if ( !v::string()->length($this->_min, $this->_max)->validate($value) ) {
        $this->addException($message);
    }
  }

}