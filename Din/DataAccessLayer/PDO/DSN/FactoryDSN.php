<?php

namespace Din\DataAccessLayer\PDO\DSN;

class FactoryDSN
{

  public static function build ( $type )
  {

    switch ($type) {
      case 'mysql':
        $DSN = new DSNMySQL();
        break;
      case 'mssql':
        $DSN = new DSNSQLSRV();
        break;
      default:
        throw new Exception('Tipo de banco de dados não suportado pela DSN');
    }

    return $DSN;
  }

}
