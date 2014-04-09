<?php

namespace Din\InputValidator;

interface ValidatorInterface
{

  public function setInput ( array $input );
  
  public function validate ( $prop, $label );
  
}
