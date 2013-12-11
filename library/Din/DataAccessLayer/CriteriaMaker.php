<?php

namespace Din\DataAccessLayer;

class CriteriaMaker
{

  private $_criteria;
  private $_table;
  private $_arrIn;
  private $_sql;

  public function __construct ( $arrCriteria, $SQL = null, $Table = null )
  {
    $this->_criteria = $arrCriteria;
    $this->_table = $Table;
    $this->_sql = $SQL;

    $this->make();
  }

  public function recursive_read ( $arrCriteria )
  {
    $arrWhere = array();
    $separator = '';

    foreach ( $arrCriteria as $k => $v ) {

      if ( (string) $k == 'OR' || (is_numeric($k) && $v == 'OR') ) {
        $separator = 'OR';
        continue;
      }

      if ( is_array($v) ) {

        foreach ( $v as $operator => $value ) {

          if ( is_numeric($operator) && is_array($value) || !in_array($operator, array('LIKE', '=', '>', '<', '>=', '<=', '<>', 'IN', 'NOT IN')) ) {
            $arrWhere[] = $separator . ' ' . $this->recursive_read($v);
            break;
          }

          $operator = is_numeric($operator) ? $value : $operator;
          if ( (string) $operator == 'OR' ) {
            $separator = 'OR';
            continue;
          }
          //var_dump($operator);
          switch (strtoupper((string) $operator)) {
            case 'IN':
            case 'NOT IN':
              if ( is_array($value) ) {
                if ( count($value) ) {
                  $arrWhere[] = "{$separator} {$k} {$operator} (" . implode(',', array_fill(0, count($value), '?')) . ")";
                  $this->_arrIn = array_merge($this->_arrIn, $value);
                } else {
                  $arrWhere[] = "{$separator} {$k} {$operator} (?)";
                  $this->_arrIn[] = '';
                }
              } else {
                $arrWhere[] = "{$separator} {$k} {$operator} (?)";
                $this->_arrIn[] = $value;
              }

              break;
            default:
              if ( is_array($value) ) {
                $arrWhere[] = "{$separator} {$k} {$operator} $value[0]";
              } else {
                $arrWhere[] = "{$separator} {$k} {$operator} ?";
                $this->_arrIn[] = $value;
              }

              break;
          }

          $separator = 'AND';
        }
      } else {
        if ( is_null($v) ) {
          $arrWhere[] = "{$separator} {$k} IS NULL";
        } else {
          $arrWhere[] = "{$separator} {$k} = ?";
          $this->_arrIn[] = $v;
        }
      }

      $separator = 'AND';
    }

    return '(' . implode(' ', $arrWhere) . ')';
  }

  public function make ()
  {
    $arrCriteria = $this->_criteria;
    $Table = $this->_table;
    $SQL = $this->_sql;

    if ( !is_array($arrCriteria) && $Table ) {
      $pk_fields = $Table->getPk();

      if ( count(explode(',', $arrCriteria)) != count($pk_fields) )
        throw new \Exception('Número de chaves primárias enviadas não coincide com os da tabela ' . $this->_table->getName());

      $newCriteria = array();
      foreach ( explode(',', $arrCriteria) as $i => $criteria )
        $newCriteria[$pk_fields[$i]] = $criteria;

      $arrCriteria = $newCriteria;
      unset($newCriteria);
    }

    $this->_arrIn = array();
    $strWhere = $this->recursive_read($arrCriteria);

    if ( count($arrCriteria) )
      $strWhere = "WHERE
        {$strWhere}";
    else
      $strWhere = '';

    if ( $SQL ) {
      $SQL = str_replace('{$strWhere}', $strWhere, $SQL);
    } else {
      $SQL = $strWhere;
    }

    $this->_sql = $SQL;

    return $SQL;
  }

  public function getArrIn ()
  {
    return $this->_arrIn;
  }

  public function getSQL ()
  {
    return $this->_sql;
  }

}
