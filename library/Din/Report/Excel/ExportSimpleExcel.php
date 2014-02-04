<?php

namespace Din\Report\Excel;

use Exception;

/**
 *
 * EXEMPLO DE UTILIZAÇÂO
 *
  $row = array(
  0 => array(
  'nome' => 'mario mello',
  'email' => 'mario@dindigital.com',
  ),
  1 => array(
  'nome' => 'jair junior',
  'email' => 'junior@dindigital.com',
  )
  );
  $excel = new \Din\Report\Excel\ExportSimpleExcel();
  $excel->setRow($row);
  $excel->export(); // exportar o arquivo na tela
 *
 *
 */
class ExportSimpleExcel
{

  private $fileName;
  private $row;

  function __construct ( $name = null )
  {
    $fileName = !is_null($name) ? $name : uniqid();
    $this->fileName = $fileName;
  }

  public function setRow ( $row )
  {
    $this->row = $row;
  }

  public function export ()
  {
    if ( !count($this->row) )
      throw new Exception('Sem registros para exportar.');

    $arrTitles = array_keys($this->row[0]);

    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"{$this->fileName}.xls\"");
    header("Content-Description: PHP Generated Data");

    echo '<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />';

    echo '<table border="1">';

    echo '<tr>';
    foreach ( $arrTitles as $title ) {
      echo '<th>' . $title . '</th>';
    }
    echo '</tr>';

    foreach ( $this->row as $row ) {
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

