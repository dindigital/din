<?php

namespace Din\Report\Excel;

use PHPExcel;
use PHPExcel_IOFactory;
use Din\File\Folder;

class ExportExcel extends ExportSimpleExcel
{

  protected $_phpexcel;

  function __construct ( $name = null )
  {
    parent::__construct($name);
    $this->_phpexcel = new PHPExcel;

  }

  public function export ()
  {
    $this->generate();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $this->_file_name . '.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($this->_phpexcel, 'Excel2007');
    $objWriter->save('php://output');

  }

  public function save ( $folder_path )
  {
    $this->generate();

    Folder::make_writable($folder_path);

    $objWriter = PHPExcel_IOFactory::createWriter($this->_phpexcel, 'Excel2007');
    $objWriter->save($folder_path . $this->_file_name . '.xlsx');

    return $this->_file_name . '.xlsx';

  }

  protected function generate ()
  {
    $this->validate();

    foreach ( $this->_titles as $i => $title ) {
      $this->_phpexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i, 1, ($title));
    }

    foreach ( $this->_result as $y => $row ) {
      $row = array_values($row);

      foreach ( $row as $x => $v ) {
        $this->_phpexcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($x, ($y + 2), $v);
      }
    }

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'size' => 13,
    ));

    $range = range(0, 100);
    for ( $i = 0; $i < count($row); $i++ ) {
      $this->_phpexcel->getActiveSheet()->getStyle($range[$i] . '1')->applyFromArray($styleArray);
      $this->_phpexcel->getActiveSheet()->getColumnDimension($range[$i])->setAutoSize(true);
    }

    $this->_phpexcel->setActiveSheetIndex(0);

  }

}
