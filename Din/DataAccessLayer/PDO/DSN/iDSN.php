<?php

namespace Din\DataAccessLayer\PDO\DSN;

interface iDSN
{

  /**
   * Recebe o schema e o host. Devolve a DSN formatada 
   */
  public function getDSN ( $schema, $host );
  
}