<?php

namespace Din\Crypt;

interface iCrypt
{
  
  /**
   * Pega uma string e retorna um valor criptogracado de acordo.
   */
  public function crypt ( $string );
  
}