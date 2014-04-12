<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;

class ArrayExists extends AbstractValidator
{

  protected $_array;

  public function __construct ( $array )
  {
    $this->_array = $array;

  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !in_array($value, $this->_array) )
      $this->addException("Item {$value} não encontrado no array de opções em {$label}");

  }

}
