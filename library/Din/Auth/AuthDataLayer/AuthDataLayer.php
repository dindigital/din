<?php

namespace Din\Auth\AuthDataLayer;

use Din\DataAccessLayer\PDO\PDODriver;
use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class AuthDataLayer implements iAuthDataLayer
{

  private $_tbl;
  private $_userField;
  private $_passField;
  private $_pkField;
  private $_activatedField;
  private $_pdo;
  private $_pkValue;
  private $_activatedValue;
  private $_dao;

  public function __construct ( PDODriver $PDO, $tbl, $userField, $passField, $pkField, $activatedField )
  {
    $this->_tbl = $tbl;
    $this->_userField = $userField;
    $this->_passField = $passField;
    $this->_pkField = $pkField;
    $this->_activatedField = $activatedField;

    $this->_pdo = $PDO;
    $this->_dao = new DAO($PDO);

  }

  public function test_login ( $user, $pass )
  {
    $select = new Select2($this->_tbl);
    $select->addAllFields();
    $select->where(new Criteria(array(
        $this->_userField . ' = ?' => $user,
        $this->_passField . ' = ?' => $pass
    )));

    $result = $this->_dao->select($select);

    if ( count($result) ) {
      $this->_pkValue = $result[0][$this->_pkField];
      $this->_activatedValue = $result[0][$this->_activatedField];
    }

    return $result;

  }

  public function getId ()
  {
    return $this->_pkValue;

  }

  public function is_active ()
  {
    $bool = $this->_activatedValue == '1';

    return $bool;

  }

}
