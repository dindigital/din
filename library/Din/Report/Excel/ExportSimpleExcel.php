<?php

namespace Din\Report\Excel;

use Exception;

class ExportSimpleExcel
{

  protected $_file_name;
  protected $_result;
  protected $_titles;

  function __construct ( $file_name = null )
  {
    $this->setFileName($file_name);

  }

  public function setFileName ( $file_name )
  {
    $this->_file_name = is_null($file_name) ? uniqid() : $file_name;

  }

  public function setResult ( array $result )
  {
    $this->_result = $result;

  }

  public function setTitles ( array $titles )
  {
    $this->_titles = $titles;

  }

  protected function validate ()
  {
    if ( !count($this->_result) )
      throw new Exception('Sem registros para exportar.');

    if ( count($this->_result[0]) != count($this->_titles) )
      throw new Exception('Quantidade de campos deve ser igual a quantidade de títulos');

  }

  public function export ()
  {
    $this->validate();

    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"{$this->_file_name}.xls\"");
    header("Content-Description: PHP Generated Data");

    echo '<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />';

    echo '<table border="1">';

    echo '<tr>';
    foreach ( $this->_titles as $title ) {
      echo '<th>' . $title . '</th>';
    }
    echo '</tr>';

    foreach ( $this->_result as $row ) {
      echo '<tr>';
      foreach ( $row as $val ) {
        $val = trim((string) $val) == '' ? '&nbsp;' : $val;
        echo '<td>' . $val . '</td>';
      }
      echo '</tr>';
    }

    echo '</table>';

    exit;

  }

}
