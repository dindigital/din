<?php

namespace Din\File;

use Din\File\Folder;

class CopyFiles
{

  private $_files = array();

  public function addFile ( $name, $origin, $destiny )
  {
    $this->_files[$name] = array(
        'origin' => 'public' . $origin,
        'destiny' => 'public' . $destiny
    );

  }

  /**
   * Move os arquivos do diretório temporário para o diretório correto.
   * Importante que na chamada ela precisa final depois de todos os addFile
   * e fora de qualquer if.
   */
  public function copy ()
  {
    foreach ( $this->_files as $index => $file ) {

      Folder::delete(dirname($file['destiny']));
      Folder::make_writable($file['destiny']);

      $path_parts = pathinfo($file['origin']);
      $basename = urldecode($path_parts['basename']);
      copy($path_parts['dirname'] . '/' . $basename, $file['destiny'] . $basename);

      $this->_files[$index]['final'] = str_replace('public', '', $file['destiny'] . $basename);
    }

  }

  public function getFiles ()
  {
    return $this->_files;

  }

  public function getFileByName ( $name )
  {
    return $this->_files[$name]['final'];

  }

}
