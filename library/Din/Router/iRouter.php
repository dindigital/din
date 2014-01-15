<?php

namespace Din\Router;

interface iRouter
{

  public function getControllerName ();

  public function getMethodName ();

  public function getArgs ();

  public function set404 ();
}
