<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;

class ArrayNotEmpty extends AbstractValidator
{

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( count($value) == 0 )
      $this->addException("É necessáio pelo menos 1 {$label}");
  }

}