<?php

namespace Din\DataAccessLayer;

class Criteria
{

  /**
   * Array de valores inputados
   * @var array
   */
  private $_arr_in = array();

  /**
   * SQL final
   * @var sring
   */
  private $_sql;

  /**
   * Constrói utilizando setCriteria
   * @param array $arrCriteria
   */
  public function __construct ( array $arrCriteria )
  {
    $this->setCriteria($arrCriteria);
  }

  /**
   * Seta o array de criterias que a classe deve trabalhar e armazena o resultado final.
   * @param array $arrCriteria
   */
  public function setCriteria ( array $arrCriteria )
  {
    $SQL = $this->make_replaces($this->recursive_read($arrCriteria));
    if ( $SQL ) {
      $SQL = 'WHERE ' . $SQL;
    }

    $this->_sql = $SQL;
  }

  /**
   * Retorna o SQL final Ee: "WHERE nome LIKE ? AND email = ?"
   * @return string
   */
  public function getSQL ()
  {
    return $this->_sql;
  }

  /**
   * Retorna o array dos parâmetros utilizados na ordem em que aparecem as
   * interrogações. Utilizado para posterior Prepared Statement.
   * @return type
   */
  public function getArrIn ()
  {
    return $this->_arr_in;
  }

  //_# PRIVATE HELPERS =========================================================

  /**
   * Verifica se a expressão possui interrogação para categorizar como input.
   * @param string $expression
   * @return bool
   */
  private function hasInput ( $expression )
  {
    return strpos($expression, '?') !== false;
  }

  /**
   * Forma uma string contendo a quantidade de interrogações de um valor passado
   * em array.
   * @param string $expression
   * @param array $value
   * @return string
   */
  private function make_in ( $expression, $value )
  {
    $interroga = '(' . implode(',', array_fill(0, count($value), '?')) . ')';

    $expression = str_replace('?', $interroga, $expression);

    return $expression;
  }

  /**
   * Adiciona valores no arr_in
   * @param string $expression
   * @param string|array $value
   */
  private function add_arr_in ( $expression, $value )
  {
    if ( $this->hasInput($expression) ) {
      if ( is_array($value) ) {
        $this->_arr_in = array_merge($this->_arr_in, $value);
      } else {
        $this->_arr_in[] = $value;
      }
    }
  }

  /**
   * Retorna o separador de clausulas correto.
   * @param string $expression
   * @param string $value
   * @return string
   */
  private function getSeparator ( $expression, $value )
  {
    if ( $expression === 'OR' || $value === 'OR' ) {
      $r = ' OR ';
    } else {
      $r = ' AND ';
    }

    return $r;
  }

  /**
   * Verificia se a expressão é um WHERE IN (...)
   * @param string $expression
   * @param string|array $value
   * @return bool
   */
  private function is_in ( $expression, $value )
  {
    return is_array($value) && strpos($expression, ' IN ') !== false;
  }

  /**
   * Verifica se a expressão é um separador. Ex: 'OR', 'AND'
   * Verifica se o valor é um array, no caso de IN, será
   * Retorna true se encontrar, pois é uma expressão de passagem
   * @param type $expression
   * @param type $value
   * @return boolean
   */
  private function is_to_continue ( $expression, $value )
  {
    if ( is_array($value) || $expression == 'OR' ) {
      return true;
    }
  }

  /**
   * Helper da write_sql
   * @param type $expression
   * @param type $value
   * @return string
   */
  private function continue_procedure ( $expression, $value )
  {
    if ( $this->is_in($expression, $value) ) {
      $r = $this->make_in($expression, $value);
    } else if ( is_array($value) ) {
      $r = '(' . $this->recursive_read($value) . ')';
    } else {
      $r = '';
    }

    return $r;
  }

  /**
   * Retorna o SQL de acordo com parâmetros
   * @param string|int $expression
   * @param string|array $value
   * @return string
   */
  private function write_sql ( $expression, $value )
  {
    $r = $this->getSeparator($expression, $value);
    if ( $this->is_to_continue($expression, $value) ) {
      $r .= $this->continue_procedure($expression, $value);
    } else {
      $r .= $expression;
    }

    return $r;
  }

  /**
   * Faz replaces na string final do SQL para evitar comandos duplicados
   * @param string $SQL
   * @return string
   */
  private function make_replaces ( $SQL )
  {
    $SQL = substr($SQL, 4);
    $SQL = str_replace('OR  AND', 'OR', $SQL);
    $SQL = str_replace('AND  AND', 'AND', $SQL);
    $SQL = str_replace('AND ( AND', 'AND (', $SQL);
    $SQL = str_replace('  ', ' ', $SQL);

    return $SQL;
  }

  /**
   * Percorre o $arrCriteria passado por parâmetro e chama funções para retornar
   * o SQL
   * @param array $array
   * @return string
   */
  private function recursive_read ( $array )
  {
    $SQL = '';

    foreach ( $array as $expression => $value ) {
      $this->add_arr_in($expression, $value);
      $SQL .= $this->write_sql($expression, $value);
    }

    return $SQL;
  }

}
