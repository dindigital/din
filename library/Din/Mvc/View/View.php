<?php

namespace Din\Mvc\View;

use Din\File\Files;

class View
{

  private $_files = array();
  private $_data;
  private $_i; // utilizado para nao contaminar as variaveis utilizadas na  view
  private $_arr_file; // utilizado para nao contaminar as variaveis utilizadas na  view

  public function addFile ( $file, $placeholder = null )
  {
    if ( !Files::exists($file) )
      throw new \Exception('View não encontrada: ' . $file);

    $this->_files[] = array(
        'file' => $file,
        'placeholder' => $placeholder
    );

  }

  public function setData ( $data )
  {
    $this->_data = $data;

  }

  private function makeReplaces ()
  {
    $result = '';

    foreach ( $this->_files as $i => $arr_file ) {
      if ( !isset($arr_file['placeholder']) ) {
        $result .= $arr_file['content'];
      }
    }

    foreach ( $this->_files as $i => $arr_file ) {
      if ( $arr_file['placeholder'] ) {
        foreach ( $this->_files as $i2 => $arr_file2 ) {
          $result = str_replace($arr_file['placeholder'], $arr_file['content'], $result);
        }
      }
    }

    foreach ( $this->_files as $i => $arr_file ) {
      if ( $arr_file['placeholder'] ) {
        foreach ( $this->_files as $i2 => $arr_file2 ) {
          $result = str_replace($arr_file['placeholder'], $arr_file['content'], $result);
        }
      }
    }

    return $result;

  }

  private function readContents ()
  {
    foreach ( $this->_files as $this->_i => $this->_arr_file ) {
      $data = $this->_data;

      ob_start();

      include $this->_arr_file['file'];

      $this->_files[$this->_i]['content'] = ob_get_clean();
    }

  }

  public function getResult ()
  {
    $this->readContents();

    $r = $this->makeReplaces();

    return $r;

  }

  public function display_html ()
  {
    header("Content-Type: text/html");
    echo($this->getResult());

  }

  public function display_json ()
  {
    header('Content-Type: application/json');
    die(json_encode($this->getResult()));

  }

  public function display_xml ()
  {
    header("Content-Type: text/xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo($this->getResult());

  }

  public function display_html_result ( $html )
  {
    header("Content-Type: text/html");
    echo $html;

  }

  public function display_xml_result ( $xml )
  {
    header("Content-Type: text/xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo $xml;

  }

}
