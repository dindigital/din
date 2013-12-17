<?php

namespace Din\DataAccessLayer;

class Criteria
{

  private $_arr_in;
  private $_sql;

  public function __construct ( $criteria )
  {
    $this->setCriteria($criteria);
  }

  public function setCriteria ( array $criteria )
  {
    $this->_sql = $this->recursive_read($criteria);
  }

  private function hasInput ( $expression )
  {
    return strpos($expression, '?') !== false;
  }

  private function recursive_read ( $array )
  {
    $SQL = '';

    $continued = false;
    foreach ( $array as $expression => $value ) {

      if ( $this->hasInput($expression) ) {
        $this->_arr_in[] = $value;
      }

      if ( is_array($value) ) {
        $SQL .= " {$expression} ";
        $SQL .= '(' . $this->recursive_read($value) . ')';
        $continued = true;
        continue;
      } else if ( $expression == 'OR' ) {
        $SQL .= ' OR ';
        $continued = true;
        continue;
      } else if ( $continued == false ) {
        $SQL .= ' AND ';
      }

      $continued = false;

      $SQL .= $expression;
    }

    $SQL = substr($SQL, 5);

    return $SQL;
  }

  public function getSQL ()
  {
    return 'WHERE ' . $this->_sql;
  }

  public function getArrIn ()
  {
    return $this->_arr_in;
  }

}
