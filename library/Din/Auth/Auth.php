<?php

namespace Din\Auth;

use Din\Crypt\iCrypt;
use Din\Session\iSession;
use Din\Auth\AuthDataLayer\iAuthDataLayer;

abstract class Auth
{

  private $_dao;
  private $_crypt;
  private $_session;
  private $_cookie;

  public function __construct ( iAuthDataLayer $AuthDataLayer, iCrypt $Crypt, iSession $Session )
  {
    $this->_dao = $AuthDataLayer;
    $this->_crypt = $Crypt;
    $this->_session = $Session;

  }

  public function login ( $user, $pass, $is_crypted = false )
  {
    if ( $is_crypted ) {
      $crypted_pass = $pass;
    } else {
      $crypted_pass = $this->_crypt->crypt($pass);
    }

    $result = $this->_dao->test_login($user, $crypted_pass);

    if ( count($result) ) {
      $this->_session->set('user_table', $result[0]);
      $this->_session->set('user', $user);
      $this->_session->set('pass', $crypted_pass);
      $bool = true;
    } else {
      $this->_session->clear();
      $bool = false;
    }

    return $bool;

  }

  public function is_active ()
  {
    $this->getId();
    return $this->_dao->is_active();

  }

  public function is_logged ()
  {
    try {
      $user = $this->_session->get('user');
      $crypted_pass = $this->_session->get('pass');
    } catch (\Exception $e) {
      return false;
    }

    $bool = (bool) count($this->_dao->test_login($user, $crypted_pass));

    return $bool;

  }

  public function getId ()
  {
    $id = $this->_dao->getId();

    if ( is_null($id) )
      throw new \Exception('Usuário não logado');

    return $id;

  }

  public function logout ()
  {
    $this->_session->clear();

  }

}
