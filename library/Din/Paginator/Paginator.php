<?php

namespace Din\Paginator;

use Din\DataAccessLayer\PDO\PDODriver;

/**
 *
 * @package Paginator
 */
class Paginator
{

  protected $_itens_por_pag;
  protected $_qtd_numeros;
  protected $_atual_pag;
  protected $_total;
  protected $_total_pags;
  protected $_active_class = 'active';
  protected $_disabled_class = 'disabled';
  protected $_first = '<a href="{$link}" class="replace_container">Primeira</a>';
  protected $_prev = '<a href="{$link}" class="replace_container {$disabled}">Anterior</a>';
  protected $_numbers = '<a href="{$link}" class="replace_container {$active}">{$n}</a>';
  protected $_next = '<a href="{$link}" class="replace_container {$disabled}">Próxima</a>';
  protected $_last = '<a href="{$link}" class="replace_container">Última</a>';
  protected $_order = '{$_first}{$_prev}{$_numbers}{$_next}{$_last}';

  /**
   *
   * @param int $_itens_por_pag
   * @param int $_qtd_numeros
   * @param int $_atual_pag
   */
  public function __construct ( $_itens_por_pag, $_qtd_numeros, $_atual_pag = 0 )
  {
    $this->_itens_por_pag = $_itens_por_pag;
    $this->_qtd_numeros = $_qtd_numeros;

    $_atual_pag = $_atual_pag == 0 ? @$_GET['pag'] : $_atual_pag;
    $this->_atual_pag = intval($_atual_pag) == 0 ? 1 : intval($_atual_pag);
  }

  public function getOffset ( $total )
  {
    $this->_total = $total;

    $this->_total_pags = intval(ceil($this->_total / $this->_itens_por_pag));
    $offset = $this->_atual_pag * $this->_itens_por_pag - $this->_itens_por_pag;

    return $offset;
  }

  /**
   *
   * @param string $pref_link
   * @return string
   */
  public function getNumbers ( $prefixo = '', $pag_var = 'pag' )
  {
    if ( $this->_total_pags > 1 ) {
      unset($_GET[$pag_var]);
      $e_comercial = count($_GET) ? '&' : '';
      $pref_link = $prefixo . '?' . http_build_query($_GET) . $e_comercial . $pag_var . '=';

      // _# _FIRST
      $_first = '';

      if ( $this->_first ) {
        if ( $this->_atual_pag == 1 ) {
          $_first = str_replace('{$link}', '#', $this->_first);
          $_first = str_replace('{$disabled}', $this->_disabled_class, $_first);
        } else {
          $link = $pref_link . 1;
          $_first = str_replace('{$link}', $link, $this->_first);
        }
      }

      // _# _LAST
      $_last = '';

      if ( $this->_last ) {
        if ( $this->_atual_pag == $this->_total_pags ) {
          $_last = str_replace('{$link}', '#', $this->_last);
          $_last = str_replace('{$disabled}', $this->_disabled_class, $_last);
        } else {
          $link = $pref_link . ($this->_total_pags);
          $_last = str_replace('{$link}', $link, $this->_last);
        }
      }

      // _# _PREV
      $_prev = '';

      if ( $this->_prev ) {
        if ( $this->_atual_pag > 1 ) {
          $link = $pref_link . ($this->_atual_pag - 1);
          $disabled = '';
        } else {
          $link = '#';
          $disabled = $this->_disabled_class;
        }

        $_prev = str_replace('{$link}', $link, $this->_prev);
        $_prev = str_replace('{$disabled}', $disabled, $_prev);
      }

      // _# NEXT
      $_next = '';

      if ( $this->_next ) {
        if ( $this->_atual_pag < $this->_total_pags ) {
          $link = $pref_link . ($this->_atual_pag + 1);
          $disabled = '';
        } else {
          $link = '#';
          $disabled = $this->_disabled_class;
        }

        $_next = str_replace('{$link}', $link, $this->_next);
        $_next = str_replace('{$disabled}', $disabled, $_next);
      }

      // _# _NUMBERS
      $arrNumbers = array();

      for ( $i = 1; $i <= $this->_total_pags; $i++ ) {
        $link = $pref_link . $i;
        $selected = $this->_atual_pag == $i ? $this->_active_class : '';

        $number = str_replace('{$n}', $i, $this->_numbers);
        $number = str_replace('{$link}', $link, $number);
        $number = str_replace('{$active}', $selected, $number);

        $arrNumbers[] = $number;
      }

      $_numbers = '';

      if ( count($arrNumbers) ) {
        if ( $this->_qtd_numeros > 0 ) {
          $addSides = intval(floor($this->_qtd_numeros / 2));

          $addLeft = $this->_atual_pag - $addSides;
          $addRight = $this->_atual_pag + $addSides;

          if ( $addLeft < 1 ) {
            $addRight += (($addLeft - 1) * -1);
            $addLeft = 0;
          }


          if ( $addRight > $this->_total_pags ) {
            $addLeft += ( $this->_total_pags - $addRight );
            $addRight = $this->_total_pags;
          }

          foreach ( $arrNumbers as $i => $number ) {
            $i++;
            if ( $i >= $addLeft && $i <= $addRight ) {
              $_numbers .= $number;
            }
          }
        } else {
          $_numbers = implode('', $arrNumbers);
        }
      }

      // _# ORDER
      $_order = $this->_order;

      if ( $this->_first ) {
        $_order = str_replace('{$_first}', $_first, $_order);
      }

      if ( $this->_prev ) {
        $_order = str_replace('{$_prev}', $_prev, $_order);
      }

      if ( $this->_numbers ) {
        $_order = str_replace('{$_numbers}', $_numbers, $_order);
      }

      if ( $this->_next ) {
        $_order = str_replace('{$_next}', $_next, $_order);
      }

      if ( $this->_last ) {
        $_order = str_replace('{$_last}', $_last, $_order);
      }

      return $_order;
    }
  }

  /**
   *
   * @return int
   */
  public function getTotal ()
  {
    return $this->_total;
  }

  /**
   *
   * @return int
   */
  public function getSubTotal ()
  {
    $r = $this->_itens_por_pag;

    if ( $this->_total < $this->_itens_por_pag ) {
      $r = $this->_total;
    }

    return $r;
  }

}

// END