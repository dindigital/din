<?php

namespace Din\Mvc\Controller;

use Din\AssetCompressor\AssetCompressor;
use Din\Mvc\View\View;

/**
 *
 * @package MVC.Controller
 */
abstract class BaseController implements \Respect\Rest\Routable
{

  protected $_view;
  protected $_data = array();

  public function __construct ()
  {
    $this->_view = new View();

  }

  protected function display_html ()
  {
    $this->_view->setData($this->_data);
    $this->_view->display_html();

  }

  protected function display_json ()
  {
    $this->_view->setData($this->_data);
    $this->_view->display_json();

  }

  protected function return_html ()
  {
    $this->_view->setData($this->_data);
    return $this->_view->getResult();

  }

}
