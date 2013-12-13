<?php

namespace Din\Http;

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
  public static function redirect ( $url )
  {
    header("Location: {$url}");
    exit;
  }

}
