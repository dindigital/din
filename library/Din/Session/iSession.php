<?php

namespace Din\Session;

interface iSession
{

  public function __construct ( $session_name );

  public function set ( $k, $v );

  public function get ( $k );

  public function clear ();
}
