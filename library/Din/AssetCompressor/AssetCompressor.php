<?php

namespace Din\AssetCompressor;

use Din\File\Files;
use Din\File\Folder;
use Din\AssetCompressor\JoinAssets;
use Din\AssetCompressor\SaveAssets;

class AssetCompressor
{

  private $_destiny_path;
  private $_html_replace_path;
  private $_css = array();
  private $_js = array();
  private $_assets;

  public function __construct ( $assets_file, $destiny_path, $html_replace_path )
  {
    $this->setDestinyPath($destiny_path);
    $this->setAssetsFile($assets_file);
    $this->setHtmlReplacePath($html_replace_path);
  }

  public function setDestinyPath ( $destiny_path )
  {
    Folder::make_writable($destiny_path);
    $this->_destiny_path = $destiny_path;
  }

  public function setHtmlReplacePath ( $html_replace_path )
  {
    $this->_html_replace_path = $html_replace_path;
  }

  public function setAssetsFile ( $assets_file )
  {
    $vars = Files::get_return($assets_file);

    if ( is_array($vars) ) {
      $this->_assets = $vars;
    }
  }

  public function compress ( $type, $compress = true )
  {
    if ( !isset($this->_assets[$type]) )
      return;

    $assets_groups = $this->_assets[$type];

    foreach ( $assets_groups as $group_name => $assets ) {

      $join_assets = new JoinAssets($assets);
      $file_name = $join_assets->getFileName();
      $file_contents = $join_assets->getFileContents();

      $file_path = $this->_destiny_path . $file_name . '.' . $type;

      $save_assets = new SaveAssets($type, $file_path, $file_contents);
      $save_assets->save($compress);

      $html_path = str_replace($this->_html_replace_path, '', $file_path);

      $this->setHtmlTag($type, $group_name, $html_path);
    }
  }

  private function setHtmlTag ( $type, $group_name, $file_path )
  {
    if ( $type == 'css' ) {
      $this->_css[$group_name] = '<link href="' . $file_path . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
    } elseif ( $type == 'js' ) {
      $this->_js[$group_name] = '<script src="' . $file_path . '" type="text/javascript"></script>' . "\n";
    }
  }

  public function getCSS ( $group_name )
  {
    if ( !isset($this->_css[$group_name]) )
      throw new \Exception('Gupo de assets CSS não encontrado: ' . $group_name);

    return $this->_css[$group_name];
  }

  public function getJS ( $group_name )
  {
    if ( !isset($this->_js[$group_name]) )
      throw new \Exception('Gupo de assets JS não encontrado: ' . $group_name);

    return $this->_js[$group_name];
  }

  public function getAllArray ()
  {
    $r = array(
        'js' => $this->_js,
        'css' => $this->_css
    );

    return $r;
  }

}
