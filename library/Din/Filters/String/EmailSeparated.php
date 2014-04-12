<?php

namespace Din\Filters\String;

use Respect\Validation\Validator as v;
use \Exception;

class EmailSeparated
{

  public static function filter ( $emails )
  {
    $emails = str_replace(PHP_EOL, ',', $emails);
    $emails = str_replace(';', ',', $emails);
    $emails = str_replace(' ', '', $emails);
    $explode = explode(',', $emails);

    $arrayEmail = array();

    foreach ( $explode as $email ) {
      if ( v::email()->validate($email) ) {
        $arrayEmail[] = $email;
      }
    }

    if ( !count($arrayEmail) )
      throw new Exception('Nenhum e-mail válido');

    $emails = implode(',', $arrayEmail);

    return $emails;

  }

}
