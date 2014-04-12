<?php

namespace Din\DataAccessLayer\Criteria;

class Looper
{

  private $_fields = array();
  private $_params = array();

  public function persistCriteria ( $arrCriteria, $separator = null )
  {
    foreach ( $arrCriteria as $expression => $value ) {
      $sp = new Separator($expression, $value);

      if ( $sp->shouldLoopIn() ) {
        $new = new self;
        $new->persistCriteria($value, $sp->getSeparator());
        $this->_fields[] = $new->getFields();
      } else {
        $separator = $separator ? $separator : $sp->getSeparator();

        if ( is_array($value) ) {
          $interr = implode(',', array_fill(0, count($value), '?'));
          $expression = str_replace('?', $interr, $expression);
          $this->_params = array_merge($this->_params, $value);
        } else if ( strpos($expression, '?') !== false ) {
          $this->_params[] = $value;
        }

        $f = new Field();
        $f->setExpression($expression);
        $f->setSeparator($separator);

        $this->_fields[] = $f;
      }
    }

  }

  public function getFields ()
  {
    return $this->_fields;

  }

  public function getParams ()
  {
    return $this->_params;

  }

}
