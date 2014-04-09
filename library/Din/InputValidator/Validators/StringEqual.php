<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class StringEqual extends AbstractValidator
{
    
  protected $_equal;
    
  public function __construct ( $equal ) {
    $this->_equal = $equal;
  }

  public function validate ( $prop, $label )
  {
    $value1 = $this->getValue($prop);
    $value2 = $this->getValue($this->_equal);

    if ( !v::string()->notEmpty()->validate($value1) )
      $this->addException("Os campos de {$label} precisam ser preenchidos");
        
    if ( !v::string()->equals($value1)->validate($value2) )
      $this->addException("Os campos de {$label} precisam ser iguais");
  }

}