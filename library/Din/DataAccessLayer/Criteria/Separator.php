<?php

namespace Din\DataAccessLayer\Criteria;

class Separator
{

  private $_expression;
  private $_value;

  public function __construct ( $expression, $value )
  {
    $this->setExpression($expression);
    $this->setValue($value);
  }

  private function setExpression ( $expression )
  {
    $this->_expression = $expression;
  }

  private function setValue ( $value )
  {
    $this->_value = $value;
  }

  public function shouldLoopIn ()
  {
    $r = false;

    if ( in_array(strtoupper($this->_expression), array('OR', 'AND')) ) {
      $r = true;
    }

    return $r;
  }

  public function getSeparator ()
  {
    if ( $this->shouldLoopIn() ) {
      $r = strtoupper($this->_expression);
    } else {
      $r = 'AND';
    }

    return $r;
  }

}
