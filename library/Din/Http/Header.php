<?php

namespace Din\Http;

<<<<<<< HEAD
class Header
{

=======
/**
 * Classe Header
 * Trabalha com o Header do protocolo HTTP
 */
class Header
{

  /**
   * Redireciona browser
   * @param String $url - url de destino do browser
   */
>>>>>>> 0a6b7292686068358fca108b19bbe2dccec7e15e
  public static function redirect ( $url )
  {
    header("Location: {$url}");
    exit;
  }

}
