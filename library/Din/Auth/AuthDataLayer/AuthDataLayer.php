<?php

namespace Din\Auth\AuthDataLayer;

use Din\DataAccessLayer\PDO\PDODriver;

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

  public function __construct ( PDODriver $PDO, $tbl, $userField, $passField, $pkField, $activatedField )
  {
    $this->_tbl = $tbl;
    $this->_userField = $userField;
    $this->_passField = $passField;
    $this->_pkField = $pkField;
    $this->_activatedField = $activatedField;

    $this->_pdo = $PDO;
  }

  public function test_login ( $user, $pass )
  {
    $SQL = "SELECT * FROM {$this->_tbl} WHERE {$this->_userField} = ? AND {$this->_passField} = ? ";
    $result = $this->_pdo->select($SQL, array($user, $pass));

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
