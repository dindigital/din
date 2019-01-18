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

    //$range = range('A', 'Z');
    $range = $this->myRange('AZ');
    for ( $i = 0; $i < count($row); $i++ ) {
      $this->_phpexcel->getActiveSheet()->getStyle($range[$i] . '1')->applyFromArray($styleArray);
      $this->_phpexcel->getActiveSheet()->getColumnDimension($range[$i])->setAutoSize(true);
    }

    $this->_phpexcel->setActiveSheetIndex(0);

  }

protected function myRange($end_column = '', $first_letters = '') {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;
            // Add the column to the final array.
            $columns[] = $column;
            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->myRange($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }

}
