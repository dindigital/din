<?php

namespace Din\Exception;

interface MultiExceptionInterface
{

  public function addException ( $msg );

  public function throwException ();
}
