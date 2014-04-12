<?php

namespace Din\DataAccessLayer;

use Din\DataAccessLayer\Criteria\Criteria;

class Update
{

  protected $_tbl;
  protected $_row;
  protected $_criteria;
  protected $_sql;
  protected $_params;

  public function setTbl ( $tbl )
  {
    $this->_tbl = "`{$tbl}`";

  }

  public function setRow ( array $row )
  {
    $new_row = array();
    foreach ( array_keys($row) as $k ) {
      $new_row[] = "`{$k}` = ?";
    }

    $this->_row = implode(', ', $new_row);
    $this->_params = array_values($row);

  }

  public function setCriteria ( array $arrCriteria )
  {
    $criteria = new Criteria($arrCriteria);
    $criteria->buildSQL();

    $this->_criteria = $criteria;

  }

  public function build ()
  {
    $this->_sql = "UPDATE {$this->_tbl} SET {$this->_row} " . $this->_criteria->getSQL();
    $this->_params = array_merge($this->_params, $this->_criteria->getParams());

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
