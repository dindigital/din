<?php

namespace Din\DataAccessLayer\PDO\DSN;

class DSNSQLSRV extends AbstractDSN implements iDSN
{

  protected $_dsn = 'sqlsrv:server={$host};Database={$schema}';

}
