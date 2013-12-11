<?php

namespace Din\Mvc\Model;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;

/**
 *
 * @package MVC.Model
 */
abstract class BaseModel
{

  public $_dao;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build());
  }

}
