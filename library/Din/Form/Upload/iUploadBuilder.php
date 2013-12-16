<?php

namespace Din\Form\Upload;

interface iUploadBuilder
{

  public static function getButton ( $field_name, $type, $obg = false, $multiple = false );
}
