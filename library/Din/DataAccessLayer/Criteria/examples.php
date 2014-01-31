<?php

$arrCriteria = array(
    'OR' => array(
        'campo3 >= ?' => 'valor3',
        'campo3 <= ?' => 'valor4',
    ),
    'AND' => array(
        'OR' => array(
            'campo4 >= ?' => 'valor3',
            'campo4 <= ?' => 'valor4',
        ),
        'AND' => array(
            'OR' => array(
                'campo4 >= ?' => 'valor3',
                'campo4 <= ?' => 'valor4',
            ),
        ),
    ),
);

// (campo3 >= ? OR campo3 <= ? ) AND (campo4 >= ? OR campo4 <= ? ) AND (campo4 >= ? OR campo4 <= ? )

$arrCriteria = array(
    'campo3 >= ?' => 'valor3',
    'campo3 <= ?' => 'valor4',
);

// campo3 >= ? AND campo3 <= ?

$arrCriteria = array(
    'campo3 >= ?' => 'valor3',
    'campo3 <= ?' => 'valor4',
    'campo1 IN (?)' => array('1', '2', '3')
);

// campo3 >= ? AND campo3 <= ? AND campo1 IN (?,?,?)