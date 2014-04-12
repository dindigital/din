<?php

namespace Din\DataAccessLayer\Table;

use Din\DataAccessLayer\Table\iTable;

class Table implements iTable
{

  private $_name;
  public $setted_values = array();

  public function __construct ( $name )
  {
    $this->setName($name);

  }

  public function setName ( $name )
  {
    $this->_name = $name;

  }

  public function getName ()
  {
    return $this->_name;

  }

  public function getArray ()
  {
    return $this->setted_values;

  }

  public function __set ( $k, $v )
  {
    if ( !property_exists($this, 'setted_values') ) {
      $this->setted_values = array();
    }

    $this->setted_values[$k] = $v;

  }

  public function __get ( $k )
  {
    if ( isset($this->setted_values[$k]) )
      return $this->setted_values[$k];

  }

}
