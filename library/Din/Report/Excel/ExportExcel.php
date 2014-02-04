<?php

namespace Din\Report\Excel;

use PHPExcel;
use PHPExcel_IOFactory;
use Exception;
use Din\File\Folder;

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
  $excel = new \Din\Report\Excel\ExportExcel();
  $excel->setRow($row);
  $excel->exportFile(); // exportar o arquivo na tela
  $file = $excel->saveFile('/public/system/'); // salvar o arquivo em um diretorio;
  echo $file; // nome do arquivo salvo
 *
 *
 */
class ExportExcel
{

  private $phpExcel;
  private $fileName;
  private $row;

  function __construct ( $name = null )
  {
    $this->phpExcel = new PHPExcel;
    $fileName = !is_null($name) ? $name : uniqid();
    $this->fileName = $fileName;
  }

  public function setRow ( $row )
  {
    $this->row = $row;
  }

  public function exportFile ()
  {

    $this->export();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $this->fileName . '.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel2007');
    $objWriter->save('php://output');
  }

  public function saveFile ( $path )
  {
    $this->export();

    $path = $_SERVER['DOCUMENT_ROOT'] . $path;
    Folder::make_writable($path);

    $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel2007');
    $objWriter->save($path . $this->fileName . '.xlsx');

    return $this->fileName . '.xlsx';
  }

  private function export ()
  {
    if ( is_null($this->row) )
      throw new Exception("Por favor utilizar o método setRow informando o array.");

    $arrTitles = array_keys($this->row[0]);

    foreach ( $arrTitles as $i => $title ) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i, 1, ($title));
    }

    foreach ( $this->row as $y => $row ) {
      $row = array_values($row);

      foreach ( $row as $x => $v ) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($x, ($y + 2), $v);
      }
    }

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'size' => 13,
    ));

    $range = range('A', 'Z');
    for ( $i = 0; $i < count($row); $i++ ) {
      $this->phpExcel->getActiveSheet()->getStyle($range[$i] . '1')->applyFromArray($styleArray);
      $this->phpExcel->getActiveSheet()->getColumnDimension($range[$i])->setAutoSize(true);
    }

    $this->phpExcel->setActiveSheetIndex(0);
  }

}

