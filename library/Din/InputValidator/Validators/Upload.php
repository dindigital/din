<?php

namespace Din\InputValidator\Validators;

use Din\InputValidator\AbstractValidator;
use finfo;
use Exception;

class Upload extends AbstractValidator
{

  protected $_extensions;
  protected $_mimes;

  public function __construct ( $extensions = array(), $mimes = array() )
  {
    $this->_extensions = $extensions;
    $this->_mimes = $mimes;

  }

  public function validate ( $prop, $label )
  {
    $file = $this->getValue($prop);

    if ( !isset($file [0]) )
      return; //Array de upload vazio

    $file = $file[0];

    if ( !(count($file) == 2 && isset($file['tmp_name']) && isset($file['name'])) )
      return; //Array de upload não possui os índices necessários: tmp_name, name

    if ( !is_writable('public/system') )
      return $this->addException('A pasta public/system/{$prop} precisa ter permissão de escrita');


    $tmp_name = $file['tmp_name'];
    $name = $file['name'];

    $origin = 'tmp' . DIRECTORY_SEPARATOR . $tmp_name;

    if ( !is_file($origin) )
      return $this->addException('O arquivo temporário de upload não foi encontrado para o campo {$label}, certifique-se de dar permissão a pasta tmp');

    //Valida extensão
    if ( count($this->_extensions) ) {
      $current_ext = pathinfo($name, PATHINFO_EXTENSION);
      if ( !in_array(strtolower($current_ext), $this->_extensions) )
        return $this->addException('Extensão de arquivo deve ser ' . implode(', ', $extensions) . "no campo {$label}");
    }

    //Valida mime type
    if ( count($this->_mimes) ) {
      $finfo = new finfo(FILEINFO_MIME);
      $current_mime = $finfo->file($origin);
      unset($finfo);

      if ( !in_array($current_mime, $this->_mimes) )
        throw new Exception('Cabeçalho de arquivo não permitido: ' . $current_mime . "no campo {$label}");
    }

    return true;

  }

}
