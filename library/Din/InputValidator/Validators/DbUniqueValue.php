<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Select;

class DbUniqueValue extends AbstractValidator
{
    
  protected $_dao;
  protected $_tablename;
  protected $_id_field;
  protected $_id;
    
  public function __construct ( DAO $dao, $tablename, $id_field = null, $id = null ) {
    $this->_dao = $dao;
    $this->_tablename = $tablename;
    $this->_id_field = $id_field;
    $this->_id = $id;
  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    $arrCriteria = array(
        "{$prop} = ?" => $value
    );

    if ( $this->_id_field ) {
      $arrCriteria["{$this->_id_field} <> ?"] = $this->_id;
    }

    $select = new Select($this->_tablename);
    $select->where($arrCriteria);
    $count = $this->_dao->select_count($select);

    if ( $count )
      $this->addException("Já existe um registro com este {$label}: {$value}");
  }

}