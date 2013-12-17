<?php

namespace Din\DataAccessLayer;

use Din\DataAccessLayer\Criteria;

class Select
{

  private $_fields = array();
  private $_table;
  private $_table_alias;
  private $_table_obj;
  private $_tables = array();
  private $_join = array();
  private $_aliases = array(
      'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k'
  );
  private $_actual_alias = -1;
  private $_union = array();
  private $_order_by;
  private $_where_fields;
  private $_where_values = array();
  private $_count = false;
  private $_limit;
  private $_group_by;
  private $_group_by_field;

  private function getAutoAlias ()
  {
    $this->_actual_alias++;

    if ( $this->_actual_alias == count($this->_aliases) )
      throw new \Exception('Maximo de alias atingido.');

    return $this->_aliases[$this->_actual_alias];
  }

  public function __construct ( $table, $fields = array() )
  {
    $this->setTable($table);
    $this->setFields($fields);
  }

  public static function construct ( $table, $fields = array() )
  {
    return new self($table, $fields);
  }

  public function setTable ( $table )
  {
    if ( is_object($table) ) {
      $this->_table_obj = $table;
      $this->_table = $table->getName();
    } else {
      $this->_table_obj = new \stdClass();
      $this->_table = $table;
    }

    $this->_tables[$this->_table] = '';
  }

  public function getTable ()
  {
    return $this->_table;
  }

  public function getTableObj ()
  {
    return $this->_table_obj;
  }

  public function setAlias ( $alias )
  {
    $this->_table_alias = $alias;
  }

  public function getAlias ()
  {
    return $this->_table_alias;
  }

  public function setFields ( $fields )
  {
    foreach ( $fields as $key => $field ) {
      $alias = !is_numeric($key) ? $key : null;
      $this->addField($field, $alias);
    }

    return $this;
  }

  public function getFields ()
  {/*
    foreach ( $this->_fields as $i => $field ) {
    $this->_fields[$i] = str_replace('{$alias}', $this->getAlias(), $field);
    } */

    $fields = array();
    if ( count($this->_fields) ) {
      $fields = array(implode(', ', $this->_fields));
    }

    foreach ( $this->getJoin() as $join ) {
      if ( $join->join_fields != '' ) {
        $fields[] = $join->join_fields;
      }
    }

    $str_fields = implode(',' . PHP_EOL . '        ', $fields);

    return $str_fields;
  }

  public function addField ( $field, $alias = null )
  {
    $str_field = "{{$this->getTable()}}.{$field}";

    if ( $alias ) {
      $str_field .= ' as ' . $alias;
    }
    $this->_fields[$field] = $str_field;

    return $this;
  }

  public function addSField ( $alias, $value )
  {
    $this->_fields[$alias] = "'{$value}' as {$alias}";

    return $this;
  }

  public function addFField ( $alias, $function )
  {
    $this->_fields[$alias] = "{$function} as {$alias}";

    return $this;
  }

  public function delField ( $field )
  {
    unset($this->_fields[$field]);

    return $this;
  }

  public function setJoin ( $type, $join, $field, $field2 = null )
  {
    $master_table = $this->getTable();
    $joined_table = $join->getTable();

    if ( is_null($field2) ) {
      $field2 = $field;
    }

    $on = "{{$joined_table}}.{$field} = {{$master_table}}.{$field2}";

    $str_join = "{$type} JOIN
        {$joined_table} {{$joined_table}}
      ON
        {$on}
    ";

    $this->_tables = array_merge($this->_tables, $join->getTables());

    $std = new \stdClass();
    $std->join = $str_join . '  ' . $join->getJoins();
    $std->join_fields = $join->getFields();

    $this->_join[$joined_table] = $std;

    return $this;
  }

  public function delJoin ( $table )
  {
    unset($this->_join[$table]);

    return $this;
  }

  public function getTables ()
  {
    return $this->_tables;
  }

  public function getJoins ()
  {
    $joins = array();
    foreach ( $this->getJoin() as $join ) {
      $joins[] = $join->join;
    }

    $str_joins = implode('', $joins);

    return $str_joins;
  }

  public function getJoin ()
  {
    return $this->_join;
  }

  public function inner_join ( $field, $join, $field2 = null )
  {
    return $this->setJoin('INNER', $join, $field, $field2);
  }

  public function left_join ( $field, $join, $field2 = null )
  {
    return $this->setJoin('LEFT', $join, $field, $field2);
  }

  public function setWhere ( $arrCriteria )
  {
    $criteria = new Criteria($arrCriteria);
    $this->_where_fields = '  ' . $criteria->getSQL();
    $this->_where_values = $criteria->getArrIn();

    return $this;
  }

  public function getWhereValues ()
  {
    return $this->_where_values;
  }

  public function where ( $arrCriteria )
  {
    return $this->setWhere($arrCriteria);
  }

  public function setUnion ( $obj, $type = '' )
  {
    $std = new \stdClass();
    $std->obj = $obj;
    $std->type = $type;
    $this->_union[] = $std;
    $this->_where_values = array_merge($this->_where_values, $obj->getWhereValues());

    return $this;
  }

  public function getUnion ( $count = false )
  {
    $str_union = '';

    foreach ( $this->_union as $std ) {
      $str_union .= "

      UNION {$std->type}
      ";

      $str_union .= ($count) ? $std->obj->getSQLCount(true) : $std->obj->getSQL();
    }

    return $str_union;
  }

  public function setOrderBy ( $order_by )
  {
    $this->_order_by = "
      ORDER BY
        {$order_by}";

    return $this;
  }

  public function union ( $obj )
  {
    return $this->setUnion($obj);
  }

  public function union_all ( $obj )
  {
    return $this->setUnion($obj, 'ALL');
  }

  public function order_by ( $order_by )
  {
    return $this->setOrderBy($order_by);
  }

  public function setLimit ( $limit, $offset )
  {
    $limit = intval($limit);
    $offset = intval($offset);

    $this->_limit = "
      LIMIT
        {$offset},{$limit}";

    return $this;
  }

  public function limit ( $limit, $offset = 0 )
  {
    return $this->setLimit($limit, $offset);
  }

  public function setGroupBy ( $field )
  {
    $this->_group_by_field = $field;
    $this->_group_by = "  GROUP BY
        {$field}";

    return $this;
  }

  public function group_by ( $field )
  {
    return $this->setGroupBy($field);
  }

  private function setAliases ()
  {
    foreach ( $this->_tables as $table => $alias ) {
      $this->_tables[$table] = $this->getAutoAlias();
    }

    foreach ( $this->_join as $join ) {
      $join->join = $this->replace_alias($join->join);
      $join->join_fields = $this->replace_alias($join->join_fields);
    }

    $this->_table_alias = $this->_tables[$this->_table];

    foreach ( $this->_fields as $i => $field ) {
      $this->_fields[$i] = str_replace("{{$this->_table}}", $this->_table_alias, $field);
    }
  }

  private function replace_alias ( $s )
  {
    foreach ( $this->_tables as $table => $alias ) {
      $s = str_replace("{{$table}}", $alias, $s);
    }

    return $s;
  }

  public function getSQL ()
  {
    if ( $this->_count )
      return $this->getSQLCount();

    $obj = clone($this);
    $obj->setAliases();

    $str_fields = $obj->getFields();
    $str_joins = $obj->getJoins();
    $str_union = $obj->getUnion();
    $str_where = $obj->_where_fields;

    $r = "
      SELECT
        {$str_fields}
      FROM
        {$obj->_table} {$obj->_table_alias}
      {$str_joins}{$str_where}{$str_union}{$obj->_group_by}{$obj->_order_by}{$obj->_limit}
    ";

    return $r;
  }

  public function getSQLCount ( $union = false )
  {
    $obj = clone($this);
    $obj->setAliases();

    $str_joins = $obj->getJoins();
    $str_union = $obj->getUnion(true);
    $str_where = $obj->_where_fields;

    $count_expr = 'COUNT(*)';
    if ( $obj->_group_by ) {
      $count_expr = "COUNT(DISTINCT({$obj->_group_by_field}))";
    }

    if ( $union ) {
      $r = "
      SELECT
        {$count_expr} conta
      FROM
        {$obj->_table} {$obj->_table_alias}
      {$str_joins}{$str_where}{$str_union}{$obj->_group_by}
     ";

      return $r;
    } else {
      $r = "
      SELECT SUM(conta) total FROM (
        SELECT
          {$count_expr} conta
        FROM
          {$obj->_table} {$obj->_table_alias}
        {$str_joins}{$str_where}{$str_union}{$obj->_group_by}
      ) tabela
      ";

      return $r;
    }
  }

  public function setCount ( $count )
  {
    $this->_count = (bool) $count;

    return $this;
  }

  public function __toString ()
  {
    return $this->getSQL();
  }

}
