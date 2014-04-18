<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class MinMax extends AbstractValidator
{

  protected $_min;
  protected $_max;

  public function __construct ( $min, $max )
  {
    $this->_min = $min;
    $this->_max = $max;

  }

  public function validate ( $prop, $label )
  {
    $v = $this->getValue($prop);

    if ( !
                    v::min($this->_min, true)
                    ->max($this->_max, true)
                    ->validate($v) )
      $this->addException("O campo {$label} deve conter um número entre {$this->_min} e {$this->_max}");

  }

}
