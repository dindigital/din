<?php

namespace Din\File;

use \Exception;

class Folder
{

  public static function create ( $diretorio )
  {
    return @mkdir($diretorio, 0777, true);

  }

  public static function delete ( $directory, $empty = FALSE )
  {
    if ( substr($directory, -1) == '/' ) {
      $directory = substr($directory, 0, -1);
    }
    if ( !file_exists($directory) || !is_dir($directory) ) {
      return FALSE;
    } elseif ( is_readable($directory) ) {
      $handle = opendir($directory);
      while (FALSE !== ($item = readdir($handle))) {
        if ( $item != '.' && $item != '..' ) {
          $path = $directory . '/' . $item;
          if ( is_dir($path) ) {
            self::delete($path, $empty);
          } else {
            unlink($path);
          }
        }
      }
      closedir($handle);
      if ( $empty == FALSE ) {
        if ( !rmdir($directory) ) {
          return FALSE;
        }
      }
    }
    return TRUE;

  }

  public static function make_writable ( $diretorio )
  {
    if ( !is_dir($diretorio) ) {
      if ( !self::create($diretorio) ) {
        throw new Exception('Permissão negada ao criar pasta: ' . $diretorio);
      }
    }

    if ( !is_writable($diretorio) )
      throw new Exception('A pasta deve possuir permissão de escrita: ' . $diretorio);

  }

}
