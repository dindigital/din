<?php

namespace Din\DataAccessLayer;

use Din\DataAccessLayer\PDO\PDODriver;
use Din\DataAccessLayer\Table\iTable;

/**
 * Class DAO
 *
 * @package default
 * @author
 */
class DAO
{

  public $_driver;

  public function __construct ( PDODriver $PDO )
  {
    $this->_driver = $PDO;
  }

  public function insert ( iTable $Table, $ignore = false )
  {
    $tbl = $Table->getName();
    $row = $Table->getArray();

    $stmt = implode(', ', array_fill(0, count($row), '?'));
    $fields = implode(', ', array_keys($row));
    $arrParams = array_values($row);

    $ignore = $ignore ? ' IGNORE ' : '';

    $SQL = "INSERT {$ignore}INTO {$tbl} ({$fields}) VALUES ({$stmt}) ";

    $this->_driver->execute($SQL, $arrParams);

    $last_insert_id = $this->_driver->lastInsertId();

    return $last_insert_id;
  }

  public function update ( iTable $Table, $arrCriteria )
  {
    $tbl = $Table->getName();
    $row = $Table->getArray();

    $arr_stmt = array();
    foreach ( array_keys($row) as $k ) {
      $arr_stmt[] = "{$k} = ?";
    }

    $stmt = implode(', ', $arr_stmt);

    $SQL = "UPDATE {$tbl} SET {$stmt} {\$strWhere} ";

    $CriteriaMaker = new CriteriaMaker($arrCriteria, $SQL, $Table);
    $arrIn = $CriteriaMaker->getArrIn();
    $SQL = $CriteriaMaker->getSQL();

    $arrParams = array_merge(array_values($row), $arrIn);

    $PDOStatement = $this->_driver->execute($SQL, $arrParams);

    return $PDOStatement->rowCount();
  }

  public function delete ( iTable $Table, $arrCriteria )
  {
    $tbl = $Table->getName();
    $SQL = "DELETE FROM {$tbl} {\$strWhere}";

    $CriteriaMaker = new CriteriaMaker($arrCriteria, $SQL, $Table);
    $arrIn = $CriteriaMaker->getArrIn();
    $SQL = $CriteriaMaker->getSQL();

    $PDOStatement = $this->_driver->execute($SQL, $arrIn);

    return $PDOStatement->rowCount();
  }

  public function select ( Select $select )
  {
    $arrIN = $select->getWhereValues();

    return $this->_driver->select($select->getSQL(), $arrIN);
  }

  public function select_count ( Select $select )
  {
    $arrIN = $select->getWhereValues();

    $result = $this->_driver->select($select->getSQLCount(), $arrIN);

    return intval($result[0]['total']);
  }

  public function execute ( $SQL, $arrCriteria = array() )
  {
    $CriteriaMaker = new CriteriaMaker($arrCriteria, $SQL);
    $arrIn = $CriteriaMaker->getArrIn();
    $SQL = $CriteriaMaker->getSQL();

    $PDOStatement = $this->_driver->execute($SQL, $arrIn);

    return $PDOStatement->rowCount();
  }

}

// END