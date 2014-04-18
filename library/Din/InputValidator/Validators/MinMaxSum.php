<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Respect\Validation\Validator as v;

class MinMaxSum extends AbstractValidator
{

  protected $_min;
  protected $_max;

  public function __construct ( $min, $max )
  {
    $this->_min = $min;
    $this->_max = $max;

  }

  public function validate ( $csv_prop, $label )
  {
    $v = 0;
    foreach ( explode(',', $csv_prop) as $prop ) {
      $v += $this->getValue($prop);
    }

    if ( !
                    v::min($this->_min, true)
                    ->max($this->_max, true)
                    ->validate($v) )
      $this->addException("A soma dos campos {$label} deve conter um número entre {$this->_min} e {$this->_max}");

  }

}
