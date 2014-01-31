<?php

namespace Din\DataAccessLayer\Criteria;

class Field
{

  private $_expression;
  private $_separator;

  public function setExpression ( $expression )
  {
    $this->_expression = $expression;
  }

  public function setSeparator ( $separator )
  {
    $this->_separator = $separator;
  }

  public function getExpression ()
  {
    return $this->_expression;
  }

  public function getSeparator ()
  {
    return $this->_separator;
  }

}
