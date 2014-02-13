<?php

/**
 * EXPORTAÇÃO
 */
$xls = new \Din\Report\Excel\ExportSimpleExcel('mailing_' . date('ymd-His'));
//OR
//$xls = new \Din\Report\Excel\ExportExcel('mailing_' . date('ymd-His'));
$xls->setResult(array(
    array(
        'name' => 'Mário',
        'email' => 'mario@dindigital.com'
    ),
    array(
        'name' => 'Júnior',
        'email' => 'junior@dindigital.com'
    ),
));
$xls->setTitles(array(
    'Nome',
    'E-mail',
));
$xls->export();

/**
 * IMPORTAÇÃO SIMPLES
 */
$i = new \Din\Report\Excel\ImportExcel;
$i->setFile("path/to/my/excel/file.xlsx");
$i->import();

$contents = $i->getData();

/**
 * IMPORTAÇÃO PRECISA
 */
$i = new \Din\Report\Excel\ImportExcel;
$i->setFile("path/to/my/excel/file.xlsx");
$i->setCoordinateX(2, 3); //da coluna 3 à coluna 4 (C à D)
$i->setCoordinateY(0, 15); //da linha 1 à linha 16
$i->import();

$contents = $i->getData();
