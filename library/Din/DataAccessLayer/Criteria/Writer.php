<?php

namespace Din\DataAccessLayer\Criteria;

class Writer
{

  private $_sql = '';

  public function write ( $fields )
  {
    foreach ( $fields as $i => $f ) {
      if ( is_array($f) ) {
        $new = new Writer;
        $new->write($f);
        $this->_sql .= '(' . $new->getSQL() . ')';
        if ( $i < count($fields) - 1 ) {
          $this->_sql .= ' AND ';
        }
      } else {
        $this->_sql .= $f->getExpression() . ' ';
        if ( $i < count($fields) - 1 ) {
          $this->_sql .= $f->getSeparator() . ' ';
        }
      }
    }

    $this->replaces();
  }

  public function getSQL ()
  {
    return $this->_sql;
  }

  private function replaces ()
  {
    $this->_sql = str_replace('((', '(', $this->_sql);
    $this->_sql = str_replace('))', ')', $this->_sql);
  }

}
