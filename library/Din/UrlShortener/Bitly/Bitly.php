<?php

namespace Din\UrlShortener\Bitly;

use Exception;

class Bitly
{

  private $_bitly_key;
  private $_domain = 'din.la';
  private $_short_url;

  public function __construct ( $key )
  {
    $this->_bitly_key = $key;

  }

  public function setDomain ( $domain )
  {
    $this->_domain = $domain;

  }

  public function shorten ( $longUrl )
  {
    $result = array();
    $url = "https://api-ssl.bit.ly/v3/shorten?access_token=" . $this->_bitly_key . "&longUrl=" . urlencode($longUrl);
    $url .= "&domain=" . $this->_domain;

    //die($url);

    $output_text = $this->bitly_get_curl($url);

    $output_json = json_decode($output_text);
    if ( json_last_error() || !is_object($output_json) )
      throw new Exception('Falha ao converter JSON: ' . $output_text);

    if ( $output_json->status_code != 200 )
      throw new Exception('Erro ao criar link curto: ' . $output_json->status_txt);

    $this->_short_url = $output_json->data->url;

  }

  private function bitly_get_curl ( $uri )
  {
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output = curl_exec($ch);

    return $output;

  }

  public function getShortUrl ()
  {
    return $this->_short_url;

  }

  public function __toString ()
  {
    return $this->getShortUrl();

  }

}
