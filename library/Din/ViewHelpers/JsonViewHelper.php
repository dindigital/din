<?php

namespace Din\ViewHelpers;

use Exception;

class JsonViewHelper
{

  public static function display_error_message ( Exception $e )
  {
    $msg = implode('<br />', json_decode($e->getMessage()));
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

}
