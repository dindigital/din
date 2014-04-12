<?php

namespace Din\Mvc\Controller;

use Din\Router\iRouter;

/**
 *
 * @package MVC.Controller
 */
class FrontController
{

  private $_router;

  public function __construct ( iRouter $_router )
  {
    $this->_router = $_router;

  }

  public function dispatch ()
  {
    $controller_name = $this->_router->getControllerName();
    $method_name = $this->_router->getMethodName();

    try {
      if ( !class_exists($controller_name) || ( class_exists($controller_name) &&
              !method_exists($controller_name, $method_name) )
      ) {
        throw new \Exception('Class or method not found.');
      }
    } catch (\Exception $e) {
      $this->_router->set404();
      $controller_name = $this->_router->getControllerName();
      $method_name = $this->_router->getMethodName();
    }

    //$app_name = $this->_router->getAppName();
    $args = $this->_router->getArgs();

    $controller = new $controller_name();

    if ( !is_null($args) ) {
      call_user_func_array(array($controller, $method_name), $args);
    } else {
      call_user_func(array($controller, $method_name));
    }

  }

}
