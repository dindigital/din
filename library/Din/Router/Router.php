<?php

namespace Din\Router;

use Din\File\Files;
use Din\Router\iRouter;
use Exception;

/**
 *
 * @package MVC.Router
 */
class Router implements iRouter
{

  private $_routes;
  private $_final_route;
  private $_erro404;

  public function __construct ( $routes_file )
  {
    $this->setRoutesFile($routes_file);

    $this->_final_route = new FinalRoute;
  }

  public function setRoutesFile ( $routes_file )
  {
    $vars = Files::get_return($routes_file);

    if ( !is_array($vars) )
      throw new Exception('Arquivo de routes inválido');

    $this->_routes = $vars;
  }

  public function route ()
  {
    // _# Pega a uri limpa, livre de query string
    $uri = implode('', array_slice(array_values(parse_url($_SERVER['REQUEST_URI'])), 0, 1));

    if ( isset($this->_routes) ) {

      $subdomain = '';

      $subdomain_atual = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
      foreach ( array_keys($this->_routes) as $regexp ) {
        if ( preg_match('/' . $regexp . '/', $subdomain_atual, $matches) ) {
          $subdomain = $regexp;
          break;
        }
      }

      foreach ( $this->_routes[$subdomain] as $regexp => $route ) {
        $regexp = '/' . str_replace('/', '\/', $regexp) . '/';

        if ( preg_match($regexp, $uri, $matches) ) {

          if ( array_key_exists('type', $route) && $route['type'] == RouteTypes::ACTION ) {
            //_ O CONTROLLER REQUISITADO PELA URI É UMA ACTION
            $router_action = new RouterAction();
            $router_action->setUri($uri);
            $router_action->resolveRequest($route['path'], $this->_final_route);
          } else {
            //_ O CONTROLLER REQUISITADO PELA URI ESTÁ CONFIGURADO
            $this->_final_route->setPath($route['controller'], $route['method'], array_slice($matches, 1));
          }

          break;
        }
      }

      if ( is_null($this->_final_route->_controller) )
        $this->set404();
    }
  }

  public function set404 ()
  {
    if ( !is_null($this->_erro404) ) {
      //_ O CONTROLER REQUISITADO PELA URI NÃO FOI ENCONTRADO

      $this->_final_route->setPath($this->_erro404['controller'], 'get_' . $this->_erro404['method']);
    } else {
      //_ CONTROLLER NÃO ENCONTRADO, E NÃO HÁ UM ERRO 404 PADRÃO
      header('HTTP/1.0 404 Not Found');
      echo '<h1>Página não encontrada, por favor sete uma página 404</h1>';
      exit;
    }
  }

  public function getControllerName ()
  {
    return $this->_final_route->_controller;
  }

  public function getMethodName ()
  {
    return $this->_final_route->_method;
  }

  public function getArgs ()
  {
    return $this->_final_route->_args;
  }

}
