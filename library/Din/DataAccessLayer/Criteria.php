<?php

namespace Din\DataAccessLayer;

class Criteria
{

  private $_arr_in = array();
  private $_sql;

  private function hasInput ( $expression )
  {
    return strpos($expression, '?') !== false;
  }

  private function make_in ( $expression, $value )
  {
    $interroga = '(' . implode(',', array_fill(0, count($value), '?')) . ')';

    $expression = str_replace('?', $interroga, $expression);

    return $expression;
  }

  private function add_arr_in ( $expression, $value )
  {
    if ( $this->hasInput($expression) ) {
      if ( is_array($value) ) {
        $this->_arr_in = array_merge($this->_arr_in, $value);
      } else {
        $this->_arr_in[] = $value;
      }
    }
  }

  private function getSeparator ( $expression, $value )
  {
    if ( (string) $expression == 'OR' || $value == 'OR' ) {
      $r = ' OR ';
    } else {
      $r = ' AND ';
    }

    return $r;
  }

  private function is_in ( $expression, $value )
  {
    return is_array($value) && strpos($expression, ' IN ') !== false;
  }

  private function is_to_continue ( $expression, $value )
  {
    if ( is_array($value) || $expression == 'OR' ) {
      return true;
    }
  }

  private function continue_procedure ( $expression, $value )
  {
    if ( $this->is_in($expression, $value) ) {
      $r = $this->make_in($expression, $value);
    } else if ( is_array($value) ) {
      $r = '(' . $this->recursive_read($value) . ')';
    } else {
      $r = '';
    }

    return $r;
  }

  private function write_sql ( $expression, $value )
  {
    $r = $this->getSeparator($expression, $value);
    if ( $this->is_to_continue($expression, $value) ) {
      $r .= $this->continue_procedure($expression, $value);
    } else {
      $r .= $expression;
    }

    return $r;
  }

  private function make_replaces ( $SQL )
  {
    $SQL = substr($SQL, 5);
    $SQL = str_replace('OR  AND', 'OR', $SQL);
    $SQL = str_replace('AND  AND', 'AND', $SQL);
    $SQL = str_replace('  ', ' ', $SQL);

    return $SQL;
  }

  private function recursive_read ( $array )
  {
    $SQL = '';

    foreach ( $array as $expression => $value ) {
      $this->add_arr_in($expression, $value);
      $SQL .= $this->write_sql($expression, $value);
    }

    return $SQL;
  }

  public function __construct ( $arrCriteria )
  {
    $this->setCriteria($arrCriteria);
  }

  public function setCriteria ( array $arrCriteria )
  {
    $SQL = $this->make_replaces($this->recursive_read($arrCriteria));
    if ( $SQL ) {
      $SQL = 'WHERE ' . $SQL;
    }

    $this->_sql = $SQL;
  }

  public function getSQL ()
  {
    return $this->_sql;
  }

  public function getArrIn ()
  {
    return $this->_arr_in;
  }

}
