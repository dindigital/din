<<<<<<< HEAD
<?php

namespace Din\Cookie;

interface iCookie
{

  public function __construct ( $cookie_name, $expiration_time = null );

  public function set ( $v );

  public function get ();

  public function clear ();

  public function is_set ();
=======
<?php

namespace Din\Cookie;

interface iCookie
{

  public function __construct ( $cookie_name, $expiration_time = null );

  public function set ( $v );

  public function get ();

  public function clear ();

  public function is_set ();
>>>>>>> 0a6b7292686068358fca108b19bbe2dccec7e15e
}