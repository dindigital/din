<?php

namespace Din\Exception;

use Din\Exception\MultiExceptionInterface;
use Din\Exception\JsonException;

class JsonExceptionContainer implements MultiExceptionInterface
{

  protected $_exceptions = array();

  public function addException ( $msg )
  {
    $this->_exceptions[] = $msg;

    return false;

  }

  public function throwException ()
  {
    if ( count($this->_exceptions) )
      throw new JsonException(json_encode($this->_exceptions));

  }

}
