<?php

namespace Din\ViewHelpers;

use Exception;

class JsonViewHelper
{

  public static function display_error_message ( Exception $e )
  {
    $msg = implode('<br />', json_decode($e->getMessage()));
    die(json_encode(array(
        'type' => 'error',
        'message' => $msg
    )));
  }

  public static function display_error_object ( Exception $e )
  {
    die(json_encode(array(
        'type' => 'error',
        'errorDetails' => json_decode($e->getMessage())
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
