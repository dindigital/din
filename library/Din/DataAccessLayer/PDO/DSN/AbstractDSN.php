<?php

namespace Din\DataAccessLayer\PDO\DSN;

abstract class AbstractDSN
{

  protected $_dsn;

  public function getDSN ( $schema, $host )
  {
    $r = str_replace('{$schema}', $schema, $this->_dsn);
    $r = str_replace('{$host}', $host, $r);

    return $r;

  }

}
