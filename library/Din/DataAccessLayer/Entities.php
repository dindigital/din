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
    } else {
      throw new Exception('Entidade não cadastrada: ' . $tbl);
    }
  }

  public static function getThis ( $model )
  {
    $namespace = get_class($model);
    $model_name = substr($namespace, strrpos($namespace, '\\') + 1);

    $r = '';
    foreach ( self::$entities as $tbl ) {
      if ( $tbl['model'] == $model_name ) {
        $r = $tbl;
        break;
      }
    }

    if ( $r == '' )
      throw new Exception('Model não cadastrado na entidade: ' . $model_name);


    return $r;
  }

  public static function getFilhos ( $tbl )
  {
    $r = array();

    $atual = self::getEntity($tbl);
    if ( array_key_exists('filho', $atual) ) {
      $filhos = $atual['filho'];
      foreach ( $filhos as $filho ) {
        $r[$filho] = self::$entities[$filho];
      }
    }

    return $r;
  }

  public static function getPai ( $tbl )
  {
    $atual = self::getEntity($tbl);
    if ( array_key_exists('pai', $atual) ) {
      return self::$entities[$atual['pai']];
    }
  }

}
