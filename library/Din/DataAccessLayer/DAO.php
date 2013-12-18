<?php

namespace Din\DataAccessLayer;

use Din\DataAccessLayer\PDO\PDODriver;
use Din\DataAccessLayer\Table\iTable;
use Din\DataAccessLayer\Criteria;
use Din\DataAccessLayer\Select;
use \Exception;

class DAO
{

  /**
   * Instancia PDODriver
   * @var object
   */
  public $_driver;

  /**
   * Data Access Object para operações CRUD.
   * @param \Din\DataAccessLayer\PDO\PDODriver $PDO
   */
  public function __construct ( PDODriver $PDO )
  {
    $this->_driver = $PDO;
  }

  /**
   * Realiza insert recebendo instancia da table como parâmetro.
   * Retorna o lastInsertId
   * @param \Din\DataAccessLayer\Table\iTable $table
   * @param bool $ignore ignorar ao inserir id duplicado?
   * @return int
   */
  public function insert ( iTable $table, $ignore = false )
  {
    $tbl = $table->getName();
    $row = $table->getArray();

    $stmt = implode(', ', array_fill(0, count($row), '?'));
    $fields = implode(', ', array_keys($row));
    $arrParams = array_values($row);

    $ignore = $ignore ? ' IGNORE ' : '';

    $SQL = "INSERT {$ignore}INTO {$tbl} ({$fields}) VALUES ({$stmt}) ";

    $this->_driver->execute($SQL, $arrParams);

    $last_insert_id = $this->_driver->lastInsertId();

    return $last_insert_id;
  }

  /**
   * Realiza update recebendo  instancia de table como parametro.
   * Retorna o número de linhas afetadas.
   * @param \Din\DataAccessLayer\Table\iTable $Table
   * @param array $arrCriteria criterio no formato da class Criteria
   * @return int
   */
  public function update ( iTable $table, array $arrCriteria )
  {
    $tbl = $table->getName();
    $row = $table->getArray();

    $arr_stmt = array();
    foreach ( array_keys($row) as $k ) {
      $arr_stmt[] = "{$k} = ?";
    }

    $stmt = implode(', ', $arr_stmt);

    $criteria = new Criteria($arrCriteria);

    $SQL = "UPDATE {$tbl} SET {$stmt} " . $criteria->getSQL();

    $arrParams = array_merge(array_values($row), $criteria->getArrIn());

    $this->safe_operation($criteria->getSQL());

    $PDOStatement = $this->_driver->execute($SQL, $arrParams);

    return $PDOStatement->rowCount();
  }

  /**
   * Realiza delete recebendo nome da tabela e criteria.
   * Retorna o número de linhas afetadas.
   * @param string $tablename
   * @param array $arrCriteria criterio no formato da class Criteria
   * @return int
   * @throws Exception
   */
  public function delete ( $tablename, array $arrCriteria )
  {
    $criteria = new Criteria($arrCriteria);

    $SQL = "DELETE FROM {$tablename} " . $criteria->getSQL();

    $this->safe_operation($SQL);
    $PDOStatement = $this->_driver->execute($SQL, $criteria->getArrIn());

    return $PDOStatement->rowCount();
  }

  public function select_debug ( Select $select )
  {
    $SQL = $select->getSQL();
    $arrIN = $select->getWhereValues();

    foreach ( $arrIN as $parameter ) {
      $SQL = str_replace('?', '"' . $parameter . '"', $SQL);
    }

    var_dump($SQL);
    exit;
  }

  /**
   * Realiza select utilizando instancia da class Select como parametro
   * Retorna resultado em array
   * @param \Din\DataAccessLayer\Select $select
   * @return array
   */
  public function select ( Select $select )
  {
    $arrIN = $select->getWhereValues();

    return $this->_driver->select($select->getSQL(), $arrIN);
  }

  /**
   * Realiza SELECT COUNT utilizando instancia da class Select como parametro
   * Retorna um número inteiro.
   * @param \Din\DataAccessLayer\Select $select
   * @return int
   */
  public function select_count ( Select $select )
  {
    $arrIN = $select->getWhereValues();

    $result = $this->_driver->select($select->getSQLCount(), $arrIN);

    return intval($result[0]['total']);
  }

  /**
   * Executa uma query livre, passando por parametro ela e o criterio.
   * Retorna o número de linhas afetadas.
   * @param string $SQL
   * @param array $arrCriteria criterio no formato da class Criteria
   * @return int
   */
  public function execute ( $SQL, array $arrCriteria = array() )
  {
    $criteria = new Criteria($arrCriteria);
    $SQL .= ' ' . $criteria->getSQL();

    $PDOStatement = $this->_driver->execute($SQL, $criteria->getArrIn());

    return $PDOStatement->rowCount();
  }

  /**
   * Bloqueia execução em caso de execução de query com where nulo
   * @param string $SQL
   * @throws Exception
   */
  private function safe_operation ( $SQL )
  {
    if ( !strpos($SQL, '=') && !strpos($SQL, '<') && !strpos($SQL, '>') )
      throw new Exception('Necessário operador de comparação na query: ' . $SQL);
  }

}
