<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;

class ArrayExists extends AbstractValidator
{
    protected $_array;

    public function __construct($array)
    {
        $this->_array = $array;
    }

    public function validate($prop, $label, $fullmsg = null)
    {
        $value = $this->getValue($prop);

        if (!$fullmsg) {
            $fullmsg = "Item {$value} não encontrado no array de opções em {$label}";
        }

        if (!in_array($value, $this->_array)) $this->addException($fullmsg);
    }
}