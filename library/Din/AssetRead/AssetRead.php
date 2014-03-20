<?php

namespace Din\AssetRead;

use Din\File\Files;
use Exception;

class AssetRead
{

  protected $_assets = null;
  protected $_js = array();
  protected $_css = array();
  protected $_mode = 'production';
  protected $_group;
  protected $_type;

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
  
  public function setMode($mode) {
      $this->_mode = $mode;
  }

  public function setGroup ( $type, Array $group )
  {
    if ( !isset($this->_assets[$type]) ) {
      throw new Exception("Não existem configurações para {$type}, verificar arquivo config");
    }
    
    if (!count($group)) {
        throw new Exception("Grupo vazio.");
    }
    
    $this->_type = $type;
    $this->_group = $group;
    
    if ($this->_mode == 'production') {
        $this->readProdution();
    } else {
        $this->readDevelopment();
    }

  }
  
  protected function readProdution() {
    foreach ( $this->_group as $gp ) {
      if ( !isset($this->_assets[$this->_type][$gp]) ) {
        throw new Exception("Gupo de assets {$this->_type} não encontrado: $gp");
      }
      if ( $this->_type == 'js' ) {
        $this->_js[$gp] = '<script src="' . $this->_assets['js'][$gp]['uri'] . '"></script>' . "\n";
      } else {
        $this->_css[$gp] = '<link href="' . $this->_assets['css'][$gp]['uri'] . '" rel="stylesheet"/>' . "\n";
      }
    }
  }

  protected function readDevelopment() {
    foreach ( $this->_group as $gp ) {
      if ( !isset($this->_assets[$this->_type][$gp]) ) {
        throw new Exception("Gupo de assets {$this->_type} não encontrado: $gp");
      }
      if ( $this->_type == 'js' ) {
        $files = '';
        foreach ($this->_assets['js'][$gp]['src'] as $src) {
            $file = str_replace('public', '', $src);
            $files .= '<script src="' . $file . '"></script>' . "\n";
        }
        $this->_js[$gp] = $files;
      } else {
        $files = '';
        foreach ($this->_assets['css'][$gp]['src'] as $src) {
          $file = str_replace('public', '', $src);
          $files .= '<link href="' . $file . '" rel="stylesheet"/>' . "\n";
        }
        $this->_css[$gp] = $files;
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
