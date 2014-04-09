<?php

namespace Din\Exception;

use Din\Exception\MultiExceptionInterface;
use Din\Exception\JsonException;

class JsonExceptionContainer implements MultiExceptionInterface
{

  private static $instance = null;
  protected $_exceptions = array();
  
  private function __construct() {}
  
  public static function getInstance() {

    if (!isset(self::$instance) && is_null(self::$instance)) {
      $c = __CLASS__;
      self::$instance = new $c;
    }

    return self::$instance;

  }

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
