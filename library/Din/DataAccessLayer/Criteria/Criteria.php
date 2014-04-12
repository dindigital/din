<?php

namespace Din\DataAccessLayer\Criteria;

class Criteria
{

  private $_criteria;
  private $_sql;
  private $_params;

  public function __construct ( $arrCriteria )
  {
    $this->setCriteria($arrCriteria);

  }

  public function setCriteria ( $arrCriteria )
  {
    $this->_criteria = $arrCriteria;

  }

  public function buildSQL ()
  {
    $lp = new Looper;
    $lp->persistCriteria($this->_criteria);

    $this->_params = $lp->getParams();

    $w = new Writer;
    $w->write($lp->getFields());
    $this->_sql = $w->getSQL();

  }

  public function getSQL ()
  {
    return $this->_sql != '' ? 'WHERE ' . $this->_sql : '';

  }

  public function getParams ()
  {
    return $this->_params;

  }

}
