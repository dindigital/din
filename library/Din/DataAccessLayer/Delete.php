<?php

namespace Din\DataAccessLayer;

use Din\DataAccessLayer\Criteria\Criteria;

class Delete
{

  protected $_tbl;
  protected $_criteria;
  protected $_sql;
  protected $_params;

  public function setTbl ( $tbl )
  {
    $this->_tbl = "`{$tbl}`";
  }

  public function setCriteria ( array $arrCriteria )
  {
    $criteria = new Criteria($arrCriteria);
    $criteria->buildSQL();

    $this->_criteria = $criteria;
  }

  public function build ()
  {
    $this->_sql = "DELETE FROM {$this->_tbl} " . $this->_criteria->getSQL();
    $this->_params = $this->_criteria->getParams();
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
