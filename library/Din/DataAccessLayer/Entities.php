<?php

namespace Din\DataAccessLayer;

use Din\File\Files;
use Exception;

class Entities
{

  public static $entities;

  public static function readFile ( $file )
  {
    if ( !Files::exists($file) )
      throw new Exception('Arquivo de entidades não encontrado: ' . $file);

    self::$entities = Files::get_return($file);
  }

  public static function getLixeiraItens ()
  {
    $r = self::$entities;
    foreach ( self::$entities as $tbl => $item ) {
      if ( !$item['lixeira'] ) {
        unset($r[$tbl]);
      }
    }

    return $r;
  }

  public static function getEntity ( $tbl )
  {
    if ( array_key_exists($tbl, self::$entities) ) {
      return self::$entities[$tbl];
    }
  }

  public static function getFilhos ( $tbl )
  {
    $r = array();

    if ( array_key_exists($tbl, self::$entities) ) {
      $id_pai = self::$entities[$tbl]['id'];
      if ( array_key_exists('filho', self::$entities[$tbl]) ) {
        $filhos = self::$entities[$tbl]['filho'];
        foreach ( $filhos as $filho ) {
          $r[$filho] = self::$entities[$filho];
        }
      }
    }

    return $r;
  }

  public static function getPai ( $tbl )
  {
    if ( array_key_exists($tbl, self::$entities) ) {
      if ( array_key_exists('pai', self::$entities[$tbl]) ) {
        return self::$entities[self::$entities[$tbl]['pai']];
      }
    }
  }

}
