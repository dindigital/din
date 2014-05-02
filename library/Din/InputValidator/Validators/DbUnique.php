<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Select;

class DbUnique extends AbstractValidator
{

  protected $_dao;
  protected $_tablename;
  protected $_id_field;
  protected $_id;
  protected $_fieldname;

  public function __construct ( DAO $dao, $tablename, $id_field = null, $id = null, $fieldname = null )
  {
    $this->_dao = $dao;
    $this->_tablename = $tablename;
    $this->_id_field = $id_field;
    $this->_id = $id;
    $this->_fieldname = $fieldname;

  }

  public function validate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( is_null($this->_fieldname) ) {
      $this->_fieldname = $prop;
    }

    $arrCriteria = array(
        "{$this->_fieldname} = ?" => $value
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
