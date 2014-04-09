<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class StringRequired extends AbstractValidator
{

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->notEmpty()->validate($value) )
      $this->addException("O campo {$label} é de preenchimento obrigatório");
  }

}