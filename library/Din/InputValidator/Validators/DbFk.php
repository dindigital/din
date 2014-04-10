<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Select;

class DbFk extends AbstractValidator
{
    
  protected $_dao;
  protected $_foreign_tablename;
    
  public function __construct ( DAO $dao, $foreign_tablename ) {
    $this->_dao = $dao;
    $this->_foreign_tablename = $foreign_tablename;
  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    $arrCriteria = array(
        "{$prop} = ?" => $value
    );

    $select = new Select($this->_foreign_tablename);
    $select->where($arrCriteria);
    $count = $this->_dao->select_count($select);

    if ( !$count )
      $this->addException("{$label} não encontrado");
  }

}