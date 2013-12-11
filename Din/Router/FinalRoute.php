<?php

namespace Din\Router;

/**
 *
 * @package MVC.Router
 */
class FinalRoute
{

  public $_app;
  public $_controller;
  public $_method;
  public $_args;

  public function setPath ( $app, $controller, $method, $args = null )
  {
    $this->_app = $app;
    $this->_controller = '\src\app\\' . $app . '\controllers\\' .
            $controller . 'Controller';

    $get_post = (count($_POST)) ? 'post_' : 'get_';
    $this->_method = $get_post . $method;
    $this->_args = $args;
  }

}
