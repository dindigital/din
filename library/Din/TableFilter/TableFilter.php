<?php

namespace Din\TableFilter;

use Din\DataAccessLayer\Table\Table;
use ReflectionClass;
use Din\TableFilter\FilterInterface;
use Imagine\Exception\InvalidArgumentException;

/**
 * @method \Din\TableFilter\FilterInterface crypted()
 * @method \Din\TableFilter\FilterInterface date()
 * @method \Din\TableFilter\FilterInterface intval()
 * @method \Din\TableFilter\FilterInterface json()
 * @method \Din\TableFilter\FilterInterface newId()
 * @method \Din\TableFilter\FilterInterface null()
 * @method \Din\TableFilter\FilterInterface string()
 * @method \Din\TableFilter\FilterInterface timestamp()
 */
class TableFilter
{

  protected $_filter;

  public function __construct ( Table $table, array $input )
  {
    $this->_table = $table;
    $this->_input = $input;
  }

  protected function instanciateFilter ( $namespace, $classname, $arguments )
  {
    $ref = new ReflectionClass($namespace . $classname);
    $this->_filter = $ref->newInstanceArgs($arguments);
  }

  public function __call ( $name, $arguments )
  {
    $classname = ucfirst($name);
    $namespace = __NAMESPACE__ . '\Filters\\';

    $this->instanciateFilter($namespace, $classname, $arguments);

    if ( !$this->_filter instanceof FilterInterface )
      throw new InvalidArgumentException("Filter {$classname} should implement FilterInterface");

    $this->_filter->setTable($this->_table);
    $this->_filter->setInput($this->_input);

    return $this;
  }

  public function filter ( $input )
  {
    return $this->_filter->filter($input);
  }

}