<?php

namespace Din\DataAccessLayer\Table;

abstract class AbstractTable extends POPO
{

  public $setted_values = array();

  public function getName ( $object = false )
  {
    $classname = explode('\\', get_called_class());
    $classname = end($classname);
    if ( !$object ) {
      //$classname = preg_replace('/([a-z])([A-Z])/', '$1_$2', $classname);
      $classname = preg_replace('/\B([A-Z])/', '_$1', $classname);
      $classname = strtolower($classname);
      $classname = str_replace('_table', '', $classname);
    } else {
      $classname = str_replace('Table', '', $classname);
    }

    return $classname;
  }

  public function getPk ( $single = false )
  {
    $properties = $this->getProperties();

    $retorno = array();

    foreach ( $properties as $propertie ) {
      $k = $propertie->name;

      if ( $this->getPropertyPk($k) ) {
        if ( $single ) {
          $retorno = $k;
          break;
        }
        $retorno[] = $k;
      }
    }

    return $retorno;
  }

  public function getTitle ()
  {
    $properties = $this->getProperties();

    $retorno = 'titulo';

    foreach ( $properties as $propertie ) {
      $k = $propertie->name;

      if ( $this->getPropertyTitle($k) ) {
        $retorno = $k;
        break;
      }
    }

    return $retorno;
  }

  public function getArray ()
  {
    $retorno = array();

    foreach ( $this->setted_values as $k => $v ) {

      $type = $this->getPropertyType($k);

      if ( is_null($v) && $this->getPropertyNotNull($k) )
        throw new \Exception("Campo {$k} não pode ser nulo.");

      if ( !is_null($v) ) {
        $this->verify_field_integrity($k, $v, $type);
      }

      $retorno[$k] = $v;
    }

    return $retorno;
  }

  private function verify_field_integrity ( $field, $value, $full_type )
  {
    preg_match('/([a-z]+)(\()?([0-9]+)?/', $full_type, $a);

    if ( !isset($a[1]) ) {
      throw new \Exception('Campo `' . $field . '` não cadastrado na tabela `' . $this->getName() . '`');
    }

    $type = $a[1];
    $strlen = strlen($value);

    switch ($type) {
      case 'int':
      case 'tinyint':
      case 'smallint':
      case 'mediumint':
      case 'bigint':
      case 'decimal':
        if ( !is_numeric($value) )
          throw new \Exception("Campo '{$field}' deve ser numérico");

        break;

      case 'char':
      case 'varchar':
        $length = intval(@$a[3]);
        if ( $length > 0 && $strlen > $length )
          throw new \Exception("Campo '{$field}' contém {$strlen} caracteres. O máximo permitido é {$length}", 1);

        break;
      case 'tinytext':
        $length = 256;
        if ( $strlen > $length )
          throw new \Exception("Campo '{$field}' contém {$strlen} caracteres. O máximo permitido é {$length}", 1);

        break;
      case 'text':
        $length = 65535;
        if ( $strlen > $length )
          throw new \Exception("Campo '{$field}' contém {$strlen} caracteres. O máximo permitido é {$length}", 1);

        break;
      case 'mediumtext':
        $length = 16777215;
        if ( $strlen > $length )
          throw new \Exception("Campo '{$field}' contém {$strlen} caracteres. O máximo permitido é {$length}", 1);

        break;
      case 'longtext':
        $length = 4294967295;
        if ( $strlen > $length )
          throw new \Exception("Campo '{$field}' contém {$strlen} caracteres. O máximo permitido é {$length}", 1);

        break;

      case 'date':
      case 'datetime':
        if ( date('Y-m-d', strtotime($value)) != substr($value, 0, 10) )
          throw new \Exception("Campo '{$field}' deve conter uma data válida Y-m-d");
        break;
      case 'time':
        if ( date('H:i:s', strtotime($value)) != $value )
          throw new \Exception("Campo '{$field}' deve conter um horário válido H:i:s");
        break;
      default:
        throw new \Exception("Tipagem inválida: '{$full_type}'");
    }
  }

  public function __set ( $k, $v )
  {
    if ( !property_exists($this, 'setted_values') ) {
      $this->setted_values = array();
    }

    $this->setted_values[$k] = $v;
  }

  public function __get ( $k )
  {
    if ( isset($this->setted_values[$k]) )
      return $this->setted_values[$k];
  }

//  public function getFields ()
//  {
//    $r = array();
//    foreach ( $this as $k => $v ) {
//      if ( is_array($v) ) {
//        $v = count($v);
//      }
//
//      $r[$k] = $v;
//    }
//    unset($r['setted_values']);
//
//    return $r;
//  }
}
