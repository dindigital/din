<?php

namespace Din\Http;

class Post
{

  /**
   * Devolve o array do campo de upload contendo tmp_name e name.
   * Pronto para ser jogado ao model.
   *
   * @param string $fieldname
   * @return array
   */
  public static function upload ( $fieldname )
  {
    $r = array();
    $total_uploads = intval(@$_POST["{$fieldname}_count"]);

    if ( $total_uploads > 0 ) {
      for ( $i = 0; $i < $total_uploads; $i++ ) {
        $r[] = array(
            'tmp_name' => $_POST["{$fieldname}_{$i}_tmpname"],
            'name' => $_POST["{$fieldname}_{$i}_name"]
        );
      }
    }

    return $r;

  }

  /**
   * Facilitador de acesso ao post de um campo checkbox.
   *
   * @param string $fieldname
   */
  public static function checkbox ( $fieldname )
  {
    return array_key_exists($fieldname, $_POST) &&
            ($_POST[$fieldname] == '1' || $_POST[$fieldname] == 'on') ?
            '1' : '0';

  }

  /**
   * Facilitador de acesso ao post de um campo array (exemplo: fotos[])
   *
   * @param string $fieldname
   * @return array
   */
  public static function aray ( $fieldname )
  {
    return (isset($_POST[$fieldname])) ? $_POST[$fieldname] : array();

  }

  public static function text ( $fieldname )
  {
    return (isset($_POST[$fieldname])) ? trim($_POST[$fieldname]) : '';

  }

  public static function file ( $fieldname )
  {
    return (isset($_FILES[$fieldname])) ? $_FILES[$fieldname] : array();

  }

}
