<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class Positive extends AbstractValidator
{

  public function validate ( $prop, $label )
  {
    $v = $this->getValue($prop);

    if ( !v::positive()->validate($v) )
      $this->addException("O campo {$label} deve conter um valor positivo");

  }

}
