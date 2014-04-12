<?php

namespace Din\DataAccessLayer;

class Insert
{

  protected $_tbl;
  protected $_fields;
  protected $_interrogations;
  protected $_sql;
  protected $_params;
  protected $_ignore = false;

  public function setTbl ( $tbl )
  {
    $this->_tbl = "`{$tbl}`";

  }

  public function setRow ( array $row )
  {
    $this->_interrogations = implode(', ', array_fill(0, count($row), '?'));
    $this->_params = array_values($row);

    $fields = array();
    foreach ( array_keys($row) as $fieldname ) {
      $fields[] = "`{$fieldname}`";
    }
    $this->_fields = implode(', ', $fields);

  }

  public function setIgnore ( $ignore )
  {
    $this->_ignore = $ignore;

  }

  public function build ()
  {
    $ignore = $this->_ignore ? ' IGNORE ' : '';

    $this->_sql = "INSERT {$ignore}INTO {$this->_tbl} ({$this->_fields}) VALUES ({$this->_interrogations})";

  }

  public function getSQL ()
  {
    return $this->_sql;

  }

  public function getParams ()
  {
    return $this->_params;

  }

}
