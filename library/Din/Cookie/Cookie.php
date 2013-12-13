<<<<<<< HEAD
<?php

namespace Din\Cookie;

class Cookie implements iCookie
{

  private $_name;
  private $_time;

  public function __construct ( $cookie_name, $expiration_time = null )
  {
    $this->_name = $cookie_name;
    if ( is_null($expiration_time) )
      $expiration_time = time() + 60 * 60 * 24 * 30;

    $this->_time = $expiration_time;
  }

  public function is_set ()
  {
    return isset($_COOKIE[$this->_name]);
  }

  public function set ( $v )
  {
    setcookie($this->_name, $v, $this->_time, '/');
  }

  public function get ()
  {
    if ( !$this->is_set() )
      throw new \Exception('Cookie não existe: ' . $this->_name);

    return $_COOKIE[$this->_name];
  }

  public function clear ()
  {
    if ( $this->is_set() )
      unset($_COOKIE[$this->_name]);
  }

=======
<?php

namespace Din\Cookie;

class Cookie implements iCookie
{

  private $_name;
  private $_time;

  public function __construct ( $cookie_name, $expiration_time = null )
  {
    $this->_name = $cookie_name;
    if ( is_null($expiration_time) )
      $expiration_time = time() + 60 * 60 * 24 * 30;

    $this->_time = $expiration_time;
  }

  public function is_set ()
  {
    return isset($_COOKIE[$this->_name]);
  }

  public function set ( $v )
  {
    setcookie($this->_name, $v, $this->_time, '/');
  }

  public function get ()
  {
    if ( !$this->is_set() )
      throw new \Exception('Cookie não existe: ' . $this->_name);

    return $_COOKIE[$this->_name];
  }

  public function clear ()
  {
    if ( $this->is_set() )
      unset($_COOKIE[$this->_name]);
  }

>>>>>>> 0a6b7292686068358fca108b19bbe2dccec7e15e
}