<?php

namespace Din\DataAccessLayer\PDO;

use Din\DataAccessLayer\PDO\DSN\iDSN;

/**
 * Classe que extende o PDO e adiciona 2 métodos de execução de query para encapsular
 * o funcionamento mais comum da classe herdada.
 * Cada objeto representa uma instância do PDO conectada a um banco de dados.
 */
class PDODriver extends \PDO
{

  /**
   * Constrói um objeto PDO setando os dados de conexão.
   *
   * @param iDSN $DSN
   * @param type $host
   * @param type $schema
   * @param type $username
   * @param type $passwd
   */
  public function __construct ( iDSN $DSN, $host, $schema, $username, $passwd )
  {

    $dsn = $DSN->getDSN($schema, $host);
    parent::__construct($dsn, $username, $passwd);

    //$this->exec("set names utf8");
    $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

  }

  /**
   * Executa uma query e retorna um fetchAll do PDO.
   * Utilizada em consultas SELECT
   *
   * @param string $SQL
   * @param array $arrParams
   * @return array
   */
  public function select ( $SQL, array $arrParams = array() )
  {
    $stmt = $this->prepare($SQL);
    $stmt->setFetchMode(\PDO::FETCH_ASSOC);

    $stmt->execute($arrParams);
    $result = $stmt->fetchAll();

    return $result;

  }

  /**
   * Executa o SQL e retorna uma instancia de PDOStatement
   *
   * @param string $SQL
   * @param array $arrParams
   * @return int
   */
  public function execute ( $SQL, array $arrParams = array() )
  {
    $PDOStatement = $this->prepare($SQL);
    $PDOStatement->execute($arrParams);

    return $PDOStatement;

  }

}
