<?php

namespace Din\Session;

class Session implements iSession
{

  private $_name;

  public function __construct ( $session_name )
  {
    $this->_name = $session_name;

    if ( session_id() == '' )
      session_start();

  }

  public function clear ()
  {
    $_SESSION[$this->_name] = array();

  }

  public function is_set ( $k )
  {
    return isset($_SESSION[$this->_name][$k]);

  }

  public function set ( $k, $v )
  {
    $_SESSION[$this->_name][$k] = $v;

  }

  public function un_set ( $k )
  {
    unset($_SESSION[$this->_name][$k]);

  }

  public function get ( $k )
  {
    if ( !$this->is_set($k) )
      throw new \Exception('Session não existe: ' . $k);

    return $_SESSION[$this->_name][$k];

  }

}
