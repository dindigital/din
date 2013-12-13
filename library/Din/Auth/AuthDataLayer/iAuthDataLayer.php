<?php

namespace Din\Auth\AuthDataLayer;

interface iAuthDataLayer
{

  public function test_login ( $user, $pass );

  public function getId ();
  
  public function is_active();
}