<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class Phone extends AbstractValidator
{

  public function validate ( $prop, $label )
  {
    $v = $this->getValue($prop);

    $v = preg_replace('/[^0-9]+/', '', $v);

    if ( !v::phone()->validate($v) )
      $this->addException("O número informado no campo {$label} está inválido");
    else
      return true;

  }

}
