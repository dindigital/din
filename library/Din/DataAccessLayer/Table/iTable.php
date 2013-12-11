<?php

namespace Din\DataAccessLayer\Table;

interface iTable
{

  public function clear ();

  public function getName ();

  public function getPk ($single = false);

  public function getArray ();

  public function getPkValue ();

  public function getLogin ();

  public function getPass ();

  public function getActive ();
}