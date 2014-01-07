<?php

namespace Din\Validation;

class File
{

  public static function file_extension ( $filename, $arrExt )
  {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    return in_array($ext, $arrExt);
  }

  public static function file_maxsize ( $filename, $maxsize )
  {
    return filesize($filename) / 1024 / 1024 <= floatval($maxsize);
  }

}
