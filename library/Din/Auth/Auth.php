<<<<<<< HEAD
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

    $this->_session->set('is_crypted', $is_crypted);

    $bool = $this->_dao->test_login($user, $crypted_pass);

    if ( $bool ) {
      $this->_session->set('user', $user);
      $this->_session->set('pass', $pass);
    } else {
      $this->_session->clear();
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
      if ( $this->_session->get('is_crypted') ) {
        $crypted_pass = $this->_session->get('pass');
      } else {
        $crypted_pass = $this->_crypt->crypt($this->_session->get('pass'));
      }
    } catch (\Exception $e) {
      return false;
    }

    $bool = $this->_dao->test_login($user, $crypted_pass);

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
=======
<?php

namespace Din\Auth;

use Din\Crypt\iCrypt;
use Din\Session\iSession;
use Din\Auth\AuthDataLayer\iAuthDataLayer;

class Auth
{

  private $_dao;
  private $_crypt;
  private $_session;
  private $_cookie;

  public function __construct ( iAuthDataLayer $AuthDataLayer, iCrypt $Crypt, iSession $ArraySession )
  {
    $this->_dao = $AuthDataLayer;
    $this->_crypt = $Crypt;
    $this->_session = $ArraySession;
  }

  public function login ( $user, $pass, $is_crypted = false )
  {
    if ( $is_crypted ) {
      $crypted_pass = $pass;
    } else {
      $crypted_pass = $this->_crypt->crypt($pass);
    }

    $this->_session->set('is_crypted', $is_crypted);

    $bool = $this->_dao->test_login($user, $crypted_pass);

    if ( $bool ) {
      $this->_session->set('user', $user);
      $this->_session->set('pass', $pass);
    } else {
      $this->_session->clear();
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
      if ( $this->_session->get('is_crypted') ) {
        $crypted_pass = $this->_session->get('pass');
      } else {
        $crypted_pass = $this->_crypt->crypt($this->_session->get('pass'));
      }
    } catch (\Exception $e) {
      return false;
    }

    $bool = $this->_dao->test_login($user, $crypted_pass);

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

>>>>>>> 0a6b7292686068358fca108b19bbe2dccec7e15e
