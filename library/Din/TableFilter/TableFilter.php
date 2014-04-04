<?php

namespace Din\TableFilter;

use Exception;
use Din\DataAccessLayer\Table\Table;

/**
 * @method setCrypted($field)
 * @method setDate($field)
 * @method setIntval($field)
 * @method setJson($field)
 * @method setNewId($field)
 * @method setNull($field)
 * @method setString($field)
 * @method setTimestamp($field)
 */
class TableFilter
{

  protected $_required;
  protected $_fqn;

  public function __construct ( Table $table, array $input )
  {
    $this->setTable($table);
    $this->setInput($input);
  }

  protected function setTable ( Table $table )
  {
    $this->_required['table'] = $table;
  }

  protected function setInput ( array $input )
  {
    $this->_required['input'] = $input;
  }

  public function classExists ()
  {
    try {

      if ( !class_exists($this->_fqn) ) {
        return false;
      }
    } catch (Exception $ex) {
      return false;
    }

    return true;
  }

  public function __call ( $name, $arguments )
  {
    $classname = str_replace('set', '', $name);
    $this->_fqn = __NAMESPACE__ . '\Filters\\' . $classname;

    if ( !$this->classExists() ) {
      if ( !$this->getCustomFilter($classname) ) {
        throw new Exception('Filter não encontrado: ' . $this->_fqn);
      } else if ( !$this->classExists() ) {
        throw new Exception('Filter não encontrado: ' . $this->_fqn);
      }
    }

    $fqn = $this->_fqn;
    $obj = new $fqn($this->_required);

    if ( !$obj instanceof FilterInterface )
      throw new Exception('Filter deve implementar a interface FilterInterface: ' . $FQN);

    if ( count($arguments) > 1 ) {
      $options = array_slice($arguments, 1);

      call_user_func_array(array($obj, 'setOptions'), $options);

      $arguments = array_slice($arguments, 0, 1);
    }

    call_user_func_array(array($obj, 'filter'), $arguments);
  }

  protected function getCustomFilter ( $classname )
  {
    return false;
  }

}
