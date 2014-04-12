<?php

namespace Din\File;

class Files
{

  public static function exists ( $path )
  {
    return is_file($path);

  }

  public static function get_contents ( $path )
  {
    if ( self::exists($path) ) {
      return file_get_contents($path);
    }

  }

  public static function get_return ( $path )
  {
    if ( self::exists($path) ) {
      return require $path;
    } else {
      return '';
    }

  }

  public static function delete ( $path )
  {
    return @unlink($path);

  }

}
