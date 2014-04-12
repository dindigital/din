<?php

namespace Din\Cookie;

interface iCookie
{

  public function __construct ( $cookie_name, $expiration_time = null );

  public function set ( $v );

  public function get ();

  public function clear ();

  public function is_set ();
}
