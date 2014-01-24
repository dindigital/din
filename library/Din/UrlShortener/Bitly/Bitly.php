<?php

namespace Din\UrlShortener\Bitly;

class Bitly
{

  private $_bitly_key;
  private $_domain = 'din.la';
  private $_result = null;

  public function __construct ( $key )
  {
    $this->_bitly_key = $key;
  }

  public function shorten ( $longUrl )
  {
    $result = array();
    $url = "https://api-ssl.bit.ly/v3/shorten?access_token=" . $this->_bitly_key . "&longUrl=" . urlencode($longUrl);
    $url .= "&domain=" . $this->_domain;

    $output = json_decode($this->bitly_get_curl($url));
    if ( isset($output->{'data'}->{'hash'}) ) {
      $result['url'] = $output->{'data'}->{'url'};
      $result['hash'] = $output->{'data'}->{'hash'};
      $result['global_hash'] = $output->{'data'}->{'global_hash'};
      $result['long_url'] = $output->{'data'}->{'long_url'};
      $result['new_hash'] = $output->{'data'}->{'new_hash'};
    }
    $result['status_code'] = $output->status_code;
    $this->_result = $result;

    return $result;
  }

  public function check ()
  {
    $r = false;
    if ( is_array($this->_result) && $this->_result['status_code'] == 200 ) {
      $r = true;
    }
    return $r;
  }

  private function bitly_get_curl ( $uri )
  {
    $output = "";
    try {
      $ch = curl_init($uri);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_TIMEOUT, 4);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      $output = curl_exec($ch);
    } catch (Exception $e) {

    }
    return $output;
  }

  public function __toString ()
  {
    if ( is_array($this->_result) && $this->_result['url'] ) {
      return $this->_result['url'];
    }
  }

}

