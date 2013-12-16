<?php

namespace Din\Mvc\Controller;

use Din\AssetCompressor\AssetCompressor;
use Din\Mvc\View\View;

/**
 *
 * @package MVC.Controller
 */
abstract class BaseController
{

  protected $_view;
  protected $_data = array();

  public function __construct ()
  {
    $this->_view = new View();
  }

  protected function getAssets ()
  {
    $assets = new AssetCompressor('config/assets.php', PATH_ASSETS, PATH_REPLACE);
    $assets->compress('js');
    $assets->compress('css');

    return $assets->getAllArray();
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

}
