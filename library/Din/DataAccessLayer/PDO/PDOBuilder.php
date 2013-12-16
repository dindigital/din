<?php

namespace Din\DataAccessLayer\PDO;

use Din\DataAccessLayer\PDO\DSN\FactoryDSN;

/**
 * Builder da classe PDO_Driver.
 * O intúito desse builder é facilitar a criação da conexão padrão do site.
 * Utiliza-se de 5 constantes definidas em arquivo de configuração da APP.
 * São elas:
 * DB_TYPE: tipo do banco de dados. Ex: mysql ou mssql
 * DB_HOST: host do bando de dados
 * DB_SCHEMA: nome do banco de dados
 * DB_USER: nome de usuário do banco de dados
 * DB_PASS: senha do banco de dados
 *
 */
class PDOBuilder
{

  public static function build ( $db_type, $db_host, $db_schema, $db_user, $db_pass )
  {

    $DSN = FactoryDSN::build($db_type);

    return new PDODriver($DSN, $db_host, $db_schema, $db_user, $db_pass);
  }

}
