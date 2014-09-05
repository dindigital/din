<?php

namespace Din\Form\Upload;

class FileTypes
{

  /**
   *
   * @var array
   */
  private static $_types = array(
      'image' => array(
          'ext' => array(
              'JPG',
              'jpg',
              'jpeg',
              'gif',
              'png'
          ),
          'desc' => 'Arquivos de Imagem'
      ),
      'banner' => array(
          'ext' => array(
              'JPG',
              'jpg',
              'jpeg',
              'gif',
              'png',
              'swf',
          ),
          'desc' => 'Arquivos de Imagem / SWF'
      ),
      'zip' => array(
          'ext' => array(
              'zip',
              'rar'
          ),
          'desc' => 'Arquivos Zip'
      ),
      'pdf' => array(
          'ext' => array(
              'pdf',
          ),
          'desc' => 'Arquivos PDF'
      ),
      'excel' => array(
          'ext' => array(
              'xls',
              'xlsx',
          ),
          'desc' => 'Arquivos Excel'
      ),
      'document' => array(
          'ext' => array(
              'pdf',
              'doc',
              'docx',
              'xls',
              'xlsx',
              'jpg',
              'jpeg',
              'png',
              'gif',
              'txt',
              'psd',
              'eps',
              'tiff',
              'cdr',
              'ind',
              'indd',
              'zip',
              'rar',
              'ai',
          ),
          'desc' => 'Documentos'
      ),
      'mp3' => array(
          'ext' => array(
              'mp3'
          ),
          'desc' => 'Arquivos MP3'
      ),
      'audio' => array(
          'ext' => array(
              'mp3',
              'ogg',
              'wma',
          ),
          'desc' => 'Arquivos de Áudio'
      ),
      'video' => array(
          'ext' => array(
              'flv',
              'mpg',
              'mp4',
              '3gp',
              'wmv',
          ),
          'desc' => 'Arquivos de Vídeo'
      ),
      'csv' => array(
          'ext' => array(
              'csv',
          ),
          'desc' => 'Arquivos CSV'
      )
  );

  /**
   *
   * @param string $name
   * @return stdClass
   * @throws \Exception
   */
  static function typeByName ( $name )
  {
    if ( !array_key_exists($name, self::$_types) )
      throw new \Exception('Tipo de arquivo não cadastrado: ' . $name);

    $r = new \stdClass();
    $r->ext = self::$_types[$name]['ext'];
    $r->desc = self::$_types[$name]['desc'];

    return $r;

  }

}
