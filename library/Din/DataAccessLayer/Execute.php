<?php

namespace Din\DataAccessLayer;

use Din\DataAccessLayer\Criteria\Criteria;

class Execute
{

  protected $_sql;
  protected $_criteria;
  protected $_params;
  protected $_ignore = false;

  public function setSQL ( $sql )
  {
    $this->_sql = $sql;

  }

  public function setCriteria ( array $arrCriteria )
  {
    $criteria = new Criteria($arrCriteria);
    $criteria->buildSQL();

    $this->_criteria = $criteria;

  }

  public function build ()
  {
    $this->_sql = $this->_sql . ' ' . $this->_criteria->getSQL();
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
