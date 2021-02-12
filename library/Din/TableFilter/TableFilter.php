<?php

namespace Din\TableFilter;

use Din\DataAccessLayer\Table\Table;
use ReflectionClass;
use Din\TableFilter\FilterInterface;
use Imagine\Exception\InvalidArgumentException;

/**
 * @method \Din\TableFilter\FilterInterface crypted()
 * @method \Din\TableFilter\FilterInterface date()
 * @method \Din\TableFilter\FilterInterface defaultUri($title, $id = '', $prefix = '')
 * @method \Din\TableFilter\FilterInterface extractPhoneDDD($new_field = null)
 * @method \Din\TableFilter\FilterInterface extractPhoneNumber($new_field = null)
 * @method \Din\TableFilter\FilterInterface friendly($from_field)
 * @method \Din\TableFilter\FilterInterface idParent()
 * @method \Din\TableFilter\FilterInterface intval($default_value = null)
 * @method \Din\TableFilter\FilterInterface json()
 * @method \Din\TableFilter\FilterInterface labelCredit()
 * @method \Din\TableFilter\FilterInterface money()
 * @method \Din\TableFilter\FilterInterface newId()
 * @method \Din\TableFilter\FilterInterface null()
 * @method \Din\TableFilter\FilterInterface sequence(DAO $dao, Entity $entity)
 * @method \Din\TableFilter\FilterInterface shortenerLink()
 * @method \Din\TableFilter\FilterInterface stringFilter()
 * @method \Din\TableFilter\FilterInterface timestamp()
 * @method \Din\TableFilter\FilterInterface uploaded($path, $has_upload, MoveFiles $mf )
 */
class TableFilter
{

  protected $_filter;

  public function __construct ( Table $table, array $input = array() )
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

  public function filterValue ( $value, $prop )
  {
    return $this->_filter->filterValue($value, $prop);

  }

}
