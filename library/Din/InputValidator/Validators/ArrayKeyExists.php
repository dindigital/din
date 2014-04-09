<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;

class ArrayKeyExists extends AbstractValidator
{
    
  protected $_array;

  public function __construct ( $array ) {
    $this->_array = $array;
  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);
      
    if (!array_key_exists($value, $this->_array ) )
       $this->addException("Item {$value} não encontrado nas chaves do array de opções em {$label}");
  }

}