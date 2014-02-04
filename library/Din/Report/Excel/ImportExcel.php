<?php

namespace Din\Report\Excel;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Exception;

/**
 * EXEMPLO DE UTILIZAÇÂO
 *
  $excel = new \Din\Report\Excel\ImportExcel();
  $excel->setFile('/public/system/52f001d5de865.xlsx');
  $excel->import();
  $data = $excel->getData();
  var_dump($data);
 *
 */
class ImportExcel
{

  private $phpExcel;
  private $data = array();
  private $position;
  private $file;

  function __construct ()
  {
    $this->phpExcel = new PHPExcel;
    $this->position = array(
        'x0' => 0,
        'x1' => -1,
        'y0' => 0,
        'y1' => -1
    );
  }

  public function setFile ( $file )
  {
    $file = $_SERVER['DOCUMENT_ROOT'] . $file;
    if ( !is_file($file) )
      throw new Exception("Arquivo inválido");

    $this->file = $file;
  }

  public function getData ()
  {
    return $this->data;
  }

  public function import ()
  {

    if ( is_null($this->file) )
      throw new Exception("O arquivo não foi declarado");


    $inputFileType = PHPExcel_IOFactory::identify($this->file);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(false);

    $objPHPExcel = $objReader->load($this->file);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    if ( $this->position['y1'] == -1 ) {
      $this->position['y1'] = $highestRow;
    }

    if ( $this->position['x1'] == -1 ) {
      $this->position['x1'] = $highestColumnIndex;
    }

    $this->position['y0']++;

    for ( $row = $this->position['y0']; $row <= $this->position['y1']; ++$row ) {
      for ( $col = $this->position['x0']; $col <= $this->position['x1']; ++$col ) {
        $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

        $this->data[$row - 1][$col] = $value;
      }
    }
  }

}

