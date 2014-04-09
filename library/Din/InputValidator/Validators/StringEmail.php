<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class StringEmail extends AbstractValidator
{

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::email()->validate($value) )
      $this->addException("O campo {$label} não contém um e-mail válido");
  }

}