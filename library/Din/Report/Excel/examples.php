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
