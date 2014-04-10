<?php

namespace Din\InputValidator;

use ReflectionClass;
use Din\InputValidator\ValidatorInterface;
use InvalidArgumentException;

/**
 * @method \Din\InputValidator\ValidatorInterface arrayNotEmpty()
 * @method \Din\InputValidator\ValidatorInterface arrayExists()
 * @method \Din\InputValidator\ValidatorInterface arrayKeyExists()
 * @method \Din\InputValidator\ValidatorInterface stringRequired()
 * @method \Din\InputValidator\ValidatorInterface stringEmail()
 * @method \Din\InputValidator\ValidatorInterface stringEqual()
 * @method \Din\InputValidator\ValidatorInterface stringLenght()
 * @method \Din\InputValidator\ValidatorInterface dateRequired()
 * @method \Din\InputValidator\ValidatorInterface UploadRequired()
 * @method \Din\InputValidator\ValidatorInterface DbFk()
 * @method \Din\InputValidator\ValidatorInterface DbRequireRecord()
 * @method \Din\InputValidator\ValidatorInterface DbUniqueValue()
 */
class InputValidator
{

  protected $_validator;

  public function __construct ( array $input )
  {
    $this->_input = $input;
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
    $this->_validator->setJsonException();
    
    return $this;
  }

  public function validate ( $input, $label )
  {
    return $this->_validator->validate($input, $label);
  }
  
  public function throwException ()
  {
    $this->_validator->jsonException->throwException();
  }

}
