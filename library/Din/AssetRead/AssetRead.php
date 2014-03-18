<?php

namespace Din\AssetRead;

use Din\File\Files;
use Exception;

class AssetRead
{

  protected $_assets = null;
  protected $_js = array();
  protected $_css = array();

  public function __construct ( $assets_file )
  {
    if ( !Files::exists($assets_file) ) {
      throw new Exception('Arquivo de assets não encontrado.');
    }

    $vars = Files::get_return($assets_file);
    if ( !is_array($vars) ) {
      throw new Exception('Arquivo de assets inválido.');
    }

    $this->_assets = $vars;
  }

  public function setGroup ( $type, Array $group )
  {
    if ( !isset($this->_assets[$type]) ) {
      throw new Exception("Não existem configurações para {$type}, verificar arquivo config");
    }

    foreach ( $group as $gp ) {
      if ( !isset($this->_assets[$type][$gp]) ) {
        throw new Exception("Gupo de assets {$type} não encontrado: $gp");
      }
      if ( $type == 'js' ) {
        $this->_js[$gp] = '<script src="' . $this->_assets['js'][$gp]['uri'] . '" type="text/javascript"></script>' . "\n";
      } else {
        $this->_css[$gp] = '<link href="' . $this->_assets['css'][$gp]['uri'] . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
      }
    }
  }

  public function getAssets ()
  {
    $r = array(
        'js' => $this->_js,
        'css' => $this->_css
    );

    return $r;
  }

}
