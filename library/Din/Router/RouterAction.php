<?php

namespace Din\Router;

/**
 *
 * @package MVC.Router
 */
class RouterAction
{

  private $_controller;
  private $_method;
  private $_args;
  private $_uri_parts;

  public function setUri ( $uri )
  {
    $this->_uri_parts = explode('/', $uri);
  }

  public function resolveRequest ( $path, FinalRoute $final_route )
  {
    $arrPath = array_slice($this->_uri_parts, 1, 3);
    if ( count($arrPath) >= 3 ) {

      $args = array_slice($this->_uri_parts, 4);
      if ( count($args) && $args[count($args) - 1] == '' )
        unset($args[count($args) - 1]);

      $controllername = $this->translate_controller_name($path, $arrPath[1]);

      $final_route->setPath($controllername, $arrPath[2], $args);
    }
  }

  private function translate_controller_name ( $path, $controllername )
  {
    $controllername = str_replace('_', ' ', $controllername);
    $controllername = ucwords($controllername);
    $controllername = str_replace(' ', '', $controllername);
    $controllername .= 'Controller';
    $controllername = $path . $controllername;

    return $controllername;
  }

}
