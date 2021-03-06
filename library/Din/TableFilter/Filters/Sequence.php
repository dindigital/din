<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\DataAccessLayer\DAO;
use Din\Essential\Helpers\Entity;
use Din\DataAccessLayer\Select;

class Sequence extends AbstractFilter
{

  protected $_dao;
  protected $_entity;

  public function __construct ( DAO $dao, Entity $entity )
  {
    $this->_dao = $dao;
    $this->_entity = $entity;

  }

  public function filter ( $field )
  {

    $entity_sequence = $this->_entity->getSequence();

    $arrCriteria = array();

    if ( isset($entity_sequence['optional']) && $entity_sequence['optional'] ) {
      $sequence = 0;
    } else {
      if ( isset($entity_sequence['dependence']) ) {
        $dependence_field = $entity_sequence['dependence'];
        if ( is_array($dependence_field) ) {
          foreach ( $dependence_field as $dp ) {
            $dependence_value = $this->_table->{$dp};
            if ( is_null($dependence_value) ) {
              $arrCriteria[$dp . ' IS NULL'] = null;
            } else {
              $arrCriteria[$dp . ' = ?'] = $dependence_value;
            }
          }
        } else {
          $dependence_value = $this->_table->{$dependence_field};
          $arrCriteria[$dependence_field . ' = ?'] = $dependence_value;
        }
      }

      $sequence = $this->getMaxSequence($arrCriteria) + 1;
    }

    $this->_table->{$field} = $sequence;

  }

  protected function getMaxSequence ( $arrCriteria = array() )
  {
    $entity_tbl = $this->_entity->getTbl();

    $select = new Select($entity_tbl);

    if ( $this->_entity->hasTrash() ) {
      $arrCriteria['is_del = 0'] = null;
    }

    $select->where($arrCriteria);

    return $this->_dao->select_count($select);

  }

}
