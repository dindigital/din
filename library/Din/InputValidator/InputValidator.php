<?php

namespace Din\InputValidator;

use ReflectionClass;
use Din\InputValidator\ValidatorInterface;
use InvalidArgumentException;

/**
 * @method \Din\InputValidator\ValidatorInterface arrayNotEmpty()
 * @method \Din\InputValidator\ValidatorInterface arrayExists()
 * @method \Din\InputValidator\ValidatorInterface arrayKeyExists()
 * @method \Din\InputValidator\ValidatorInterface string()
 * @method \Din\InputValidator\ValidatorInterface stringEmail()
 * @method \Din\InputValidator\ValidatorInterface stringEqual()
 * @method \Din\InputValidator\ValidatorInterface stringLenght()
 * @method \Din\InputValidator\ValidatorInterface date()
 * @method \Din\InputValidator\ValidatorInterface upload()
 * @method \Din\InputValidator\ValidatorInterface dbFk()
 * @method \Din\InputValidator\ValidatorInterface dbRecord()
 * @method \Din\InputValidator\ValidatorInterface dbUnique()
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
  
  public function addException ($msg) {
    $this->_exception->addException($msg);
  }

}
