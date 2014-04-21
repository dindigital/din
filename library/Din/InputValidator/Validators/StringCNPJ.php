<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class StringCNPJ extends AbstractValidator
{

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->cnpj()->validate($value) )
      $this->addException("O preenchimento do campo {$label} está inválido");

  }

}
