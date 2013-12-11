<?php

namespace Din\DataAccessLayer\Table;

abstract class POPO
{

  protected function hasProperty ( $k )
  {
    $o = new \ReflectionObject($this);
    return $o->hasProperty($k);
  }

  protected function getDocComent ( $k )
  {
    $o = new \ReflectionObject($this);
    $p = $o->getProperty($k);

    $dc = $p->getDocComment();

    if ( !preg_match("#@var\s+(.*)#", $dc, $a) ) {
      //throw new \Exception(sprintf("'%s'.'%s' não tem o tipo definido", get_class($this), $k));
      return false;
    }

    return $a[1];
  }

  protected function getPropertyType ( $k )
  {
    $dc = $this->getDocComent($k);

    $arrSpaces = explode(' ', $dc);

    return strtolower($arrSpaces[0]);
  }

  protected function getPropertyPk ( $k )
  {
    $dc = $this->getDocComent($k);

    $strpos = stripos($dc, ' pk');

    return $strpos !== false;
  }

  protected function getPropertyTitle ( $k )
  {
    $dc = $this->getDocComent($k);

    $strpos = stripos($dc, ' title');

    return $strpos !== false;
  }

  protected function getPropertyLogin ( $k )
  {
    $dc = $this->getDocComent($k);

    $strpos = stripos($dc, ' login');

    return $strpos !== false;
  }

  protected function getPropertyPass ( $k )
  {
    $dc = $this->getDocComent($k);

    $strpos = stripos($dc, ' pass');

    return $strpos !== false;
  }

  protected function getPropertyActive ( $k )
  {
    $dc = $this->getDocComent($k);

    $strpos = stripos($dc, ' active');

    return $strpos !== false;
  }

  protected function getPropertyNotNull ( $k )
  {
    $dc = $this->getDocComent($k);

    $strpos = stripos($dc, ' not null');

    return $strpos !== false;
  }

  protected function getProperties ()
  {
    $o = new \ReflectionObject($this);
    return $o->getProperties();
  }

}