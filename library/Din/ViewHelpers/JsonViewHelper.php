<?php

namespace Din\ViewHelpers;

use Exception;

class JsonViewHelper
{

  public static function display_error_message ( Exception $e )
  {
    $msg = $e->getMessage();
    if ( is_array($msg) ) {
      $msg = implode('<br />', json_decode($msg));
    }

    die(json_encode(array(
        'type' => 'error_message',
        'message' => $msg
    )));
  }

  public static function display_error_object ( Exception $e )
  {
    die(json_encode(array(
        'type' => 'error_object',
        'objects' => json_decode($e->getMessage(), true)
    )));
  }

  public static function redirect ( $uri )
  {
    die(json_encode(array(
        'type' => 'redirect',
        'uri' => $uri
    )));
  }

  public static function display_success_message ( $msg )
  {
    die(json_encode(array(
        'type' => 'success',
        'message' => $msg
    )));
  }

}
