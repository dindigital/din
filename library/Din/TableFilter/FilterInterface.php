<?php

namespace Din\TableFilter;

use Din\DataAccessLayer\Table\Table;

interface FilterInterface
{

  public function setTable ( Table $table );

  public function setInput ( array $input );

  public function filter ( $field );
}
