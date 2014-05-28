<?php

namespace Din\Upload\SimpleUpload;

use Din\File\Folder;

class SimpleUpload
{

  protected $file = null;
  protected $max_filesize = 2097152;
  protected $file_type = 'image';
  protected $file_validation = array(
      'image' => array(
          'mime' => array('image/png', 'image/jpeg'),
          'extension' => array('jpg', 'jpeg', 'png'),
      )
  );
  protected $extension;
  protected $path;
  protected $name;

  public function setFileType ( $type )
  {
    $validation = $this->getFileValidation();
    if ( !array_key_exists($type, $validation) )
      throw new \InvalidArgumentException('Formato de validação inválido');

    $this->file_type = $type;

  }

  public function getFileType ()
  {
    return $this->file_type;

  }

  public function getFileValidation ()
  {
    return $this->file_validation;

  }

  public function setFile ( $file = null )
  {
    if ( is_null($file) || $file == '' )
      return null;

    if ( !is_array($file) )
      throw new \InvalidArgumentException('Arquivo inválido');

    if ( !count($file) )
      throw new \InvalidArgumentException('Arquivo inválido');

    if ( !isset($file['name']) || !isset($file['type']) || !isset($file['size']) || !isset($file['tmp_name']) )
      throw new \InvalidArgumentException('Arquivo inválido');

    $this->file = $file;

    $this->validationMimeFile();
    $this->validationExtensionFile();
    $this->validationFileSize();

  }

  public function getFile ()
  {
    return $this->file;

  }

  /**
   * Configura o máximo de um arquivo
   * usar número em megas
   * Ex: 5 para 5 mb de limite, 10 para 10 mb de limite
   */
  public function setMaxFileZize ( $size )
  {
    if ( !is_numeric($size) )
      throw new \InvalidArgumentException('O limite do arquivo deve ser um número');

    $this->max_filesize = 1440000 * $size;

  }

  public function getMaxFileZize ()
  {
    return $this->max_filesize;

  }

  public function setPath ( $path )
  {
    try {
      Folder::make_writable($path);
      $this->path = $path;
    } catch (\Exception $e) {
      throw new \InvalidArgumentException('Caminho de destino inválido');
    }

  }

  public function getPath ()
  {
    return $this->path;

  }

  public function setName ( $name = null )
  {
    if ( is_null($name) ) {
      $name = uniqid();
    }

    $name .= ".{$this->extension}";

    $this->name = $name;

  }

  public function getName ()
  {
    return $this->name;

  }

  protected function validationMimeFile ()
  {
    $validation = $this->getFileValidation();
    if ( !in_array($this->file['type'], $validation[$this->getFileType()]['mime']) )
      throw new \InvalidArgumentException('MIME inválido');

  }

  protected function validationExtensionFile ()
  {
    $validation = $this->getFileValidation();
    $extension = pathinfo($this->file['name'], PATHINFO_EXTENSION);
    if ( !in_array(strtolower($extension), $validation[$this->getFileType()]['extension']) )
      throw new \InvalidArgumentException('Extensão de arquivo inválida');

    $this->extension = $extension;

  }

  protected function validationFileSize ()
  {

    if ( $this->getMaxFileZize() < $this->file['size'] )
      throw new \InvalidArgumentException("Tamanho de arquivo inválido.");

  }

}
