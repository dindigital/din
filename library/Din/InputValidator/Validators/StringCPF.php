<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class StringCPF extends AbstractValidator
{

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->cpf()->validate($value) )
      $this->addException("O preenchimento do campo {$label} está inválido");

  }

}
