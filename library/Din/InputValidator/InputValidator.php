<?php

namespace Din\InputValidator;

use ReflectionClass;
use Din\InputValidator\ValidatorInterface;
use InvalidArgumentException;

/**
 * @method \Din\InputValidator\ValidatorInterface arrayExists($array)
 * @method \Din\InputValidator\ValidatorInterface arrayKeyExists($array)
 * @method \Din\InputValidator\ValidatorInterface arrayNotEmpty()
 * @method \Din\InputValidator\ValidatorInterface date($format = 'd/m/Y')
 * @method \Din\InputValidator\ValidatorInterface dbFk(DAO $dao, $foreign_tablename)
 * @method \Din\InputValidator\ValidatorInterface dbRecord(DAO $dao, $tablename)
 * @method \Din\InputValidator\ValidatorInterface dbUnique(DAO $dao, $tablename, $id_field = null, $id = null)
 * @method \Din\InputValidator\ValidatorInterface minMax($min, $max)
 * @method \Din\InputValidator\ValidatorInterface minMaxSum($min, $max)
 * @method \Din\InputValidator\ValidatorInterface phone()
 * @method \Din\InputValidator\ValidatorInterface positive()
 * @method \Din\InputValidator\ValidatorInterface string()
 * @method \Din\InputValidator\ValidatorInterface stringEmail()
 * @method \Din\InputValidator\ValidatorInterface stringEqual($equal)
 * @method \Din\InputValidator\ValidatorInterface stringLenght($min = 1, $max = null)
 * @method \Din\InputValidator\ValidatorInterface upload($extensions = array(), $mimes = array())

 */
class InputValidator
{

  protected $_validator;
  protected $_input;
  protected $_exception;

  public function __construct ( array $input )
  {
    $this->_input = $input;
    $this->_exception = new \Din\Exception\JsonExceptionContainer();

  }

  protected function instanciateFilter ( $namespace, $classname, $arguments )
  {
    $ref = new ReflectionClass($namespace . $classname);
    $this->_validator = $ref->newInstanceArgs($arguments);

  }

  public function __call ( $name, $arguments )
  {
    $classname = ucfirst($name);
    $namespace = __NAMESPACE__ . '\Validators\\';

    $this->instanciateFilter($namespace, $classname, $arguments);

    if ( !$this->_validator instanceof ValidatorInterface )
      throw new InvalidArgumentException("Validator {$classname} should implement ValidatorInterface");

    $this->_validator->setInput($this->_input);
    $this->_validator->setException($this->_exception);

    return $this;

  }

  public function validate ( $input, $label )
  {
    return $this->_validator->validate($input, $label);

  }

  public function throwException ()
  {
    $this->_exception->throwException();

  }

  public function addException ( $msg )
  {
    $this->_exception->addException($msg);

  }

  public function getInput ()
  {
    return $this->_input;

  }

}
