<?php

namespace Din\Router;

use Din\Router\Router;
use Din\File\Files;
use Exception;

/**
 *
 * @package MVC.Router
 */
class GroupRouter extends Router
{

  public function __construct ( $array_files )
  {
    $this->setRoutesMultipleFiles($array_files);
    $this->_final_route = new FinalRoute;
  }

  public function setRoutesMultipleFiles ( $array_files )
  {
    foreach ( $array_files as $file ) {
      $vars = Files::get_return($file);

      if ( !is_array($vars) )
        throw new Exception('Arquivo de routes inválido');

      foreach ( $vars as $subdomain => $subroutes ) {
        if ( array_key_exists($subdomain, $this->_routes) ) {
          $this->_routes[$subdomain] = array_merge($this->_routes[$subdomain], $subroutes);
        } else {
          $this->_routes[$subdomain] = $subroutes;
        }
      }
    }
  }

}
