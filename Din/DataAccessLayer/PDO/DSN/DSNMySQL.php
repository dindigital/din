<?php

namespace Din\DataAccessLayer\PDO\DSN;

class DSNMySQL extends AbstractDSN implements iDSN
{

  protected $_dsn = 'mysql:dbname={$schema};host={$host};charset=utf8';

}
