<?php

namespace Din\Report\Excel;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Exception;

class ImportExcel
{

  protected $_phpexcel;
  protected $_data = array();
  protected $_coordinates = array();
  protected $_file;

  function __construct ()
  {
    $this->_phpexcel = new PHPExcel;
    $this->setCoordinateX();
    $this->setCoordinateY();
  }

  public function setCoordinateX ( $start = 0, $end = -1 )
  {
    $this->_coordinates['x0'] = intval($start);
    $this->_coordinates['x1'] = intval($end);
  }

  public function setCoordinateY ( $start = 0, $end = -1 )
  {
    $this->_coordinates['y0'] = intval($start);
    $this->_coordinates['y1'] = intval($end);
  }

  public function setFile ( $file )
  {
    if ( !is_file($file) )
      throw new Exception("Arquivo não encontrado: " . $file);

    $this->_file = $file;
  }

  protected function validate ()
  {
    if ( is_null($this->_file) )
      throw new Exception("O arquivo não foi declarado, utilize o método setFile");
  }

  public function import ()
  {
    $this->validate();

    $inputFileType = PHPExcel_IOFactory::identify($this->_file);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(false);

    $objPHPExcel = $objReader->load($this->_file);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    if ( $this->_coordinates['y1'] == -1 ) {
      $this->_coordinates['y1'] = $highestRow;
    }

    if ( $this->_coordinates['x1'] == -1 ) {
      $this->_coordinates['x1'] = $highestColumnIndex;
    }

    $this->_coordinates['y0'] ++;

    for ( $row = $this->_coordinates['y0']; $row <= $this->_coordinates['y1']; ++$row ) {
      for ( $col = $this->_coordinates['x0']; $col <= $this->_coordinates['x1']; ++$col ) {
        $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

        $this->_data[$row - 1][$col] = $value;
      }
    }
  }

  public function getData ()
  {
    return $this->_data;
  }

}
