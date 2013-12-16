<?php

namespace Din\Validation;

class StringTransform
{

  /**
   * Retorna uma string após retirar tudo que não for número
   *
   * @param string $str
   * @return string
   */
  public static function only_numbers ( $str )
  {
    return preg_replace("/[^0-9]/", '', $str);
  }

  /**
   * Retorna a string após retirar todos os possíveis números
   * @param type $str
   * @return type
   */
  public static function not_numbers ( $str )
  {
    return preg_replace("/[0-9\-]/", '', $str);
  }

  /**
   * Pega somente os números e retorna em formato float
   * Exemplo: 'R$ 15.000,99' retorna 15000.99
   *
   * @param string $str
   * @return float
   */
  public static function sql_money ( $str )
  {
    return preg_replace("/[^0-9]/", '', $str) / 100;
  }

  /**
   * Pega um número float e transforma em moeda BR
   * Exemplo: '1000.00' retorna 'R$ 1.000,00'
   *
   * @param string $str
   * @return string
   */
  public static function format_money ( $str )
  {
    $r = doubleval($str);
    $r = 'R$ ' . number_format($r, 2, ',', '.');

    return $r;
  }

  /**
   * Pega uma string de 8 dígitos e retorna com traço
   *
   * @param string $str
   * @return string
   */
  public static function format_cep ( $str )
  {
    $r = $str;

    if ( strlen(Decorator::onlynumbers($str)) == 7 ) {
      $str = '0' . $str;
    }

    if ( strlen(Decorator::onlynumbers($str)) == 8 ) {
      $r = substr($str, 0, 5) . '-' . substr($str, 5, 3);
    }

    return $r;
  }

  public static function format_cnpj ( $str )
  {
    $r = $str;

    if ( strlen(Decorator::onlynumbers($str)) == 14 ) {
      $r = substr($str, 0, 2) . '.' . substr($str, 2, 3) . '.' . substr($str, 5, 3) .
              '/' . substr($str, 8, 4) . '-' . substr($str, 12, 2);
    }

    return $r;
  }

  /**
   * Transforma acento em caractere sem acento,
   * retira caracteres especiais e transforma espaço em $slug.
   * Em suma, utilizado para criação de URL`s Amigaveis.
   *
   * @param string $string
   * @param string $slug
   *
   * @return string
   *
   */
  public static function format_uri ( $string, $slug = '-' )
  {

    $string = mb_strtolower($string, 'utf-8');
    $string = utf8_decode($string);

    $ascii['a'] = range(224, 230);
    $ascii['e'] = range(232, 235);
    $ascii['i'] = range(236, 239);
    $ascii['o'] = array_merge(range(242, 246), array(240, 248));
    $ascii['u'] = range(249, 252);

    $ascii['b'] = array(223);
    $ascii['c'] = array(231);
    $ascii['d'] = array(208);
    $ascii['n'] = array(241);
    $ascii['y'] = array(253, 255);

    foreach ( $ascii as $key => $item ) {
      $acentos = '';
      foreach ( $item AS $codigo )
        $acentos .= chr($codigo);
      $troca[$key] = '/[' . $acentos . ']/i';
    }

    $string = preg_replace(array_values($troca), array_keys($troca), $string);

    if ( $slug ) {
      $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
      $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
      $string = trim($string, $slug);
    }

    return $string;
  }

  /**
   * Limita uma string para ter no máximio a quantidade de caracteres especificada
   * Caso a string não ultrapasse esse máximo, não é adicionado o $delimiter
   *
   * @param $str string
   * String a ser tratada
   *
   * @param $max int
   * Quantidade máxima de caracteres à limitar o texto
   *
   * @param $delimiter string
   * Delimita o fim da string com o valor desse parâmetro.
   * Usado somente quando a string ultrapassa o valor $max
   *
   * @return string
   *
   */
  public static function limit_chars ( $str, $max = 100, $delimiter = '(...)' )
  {
    if ( strlen($str) > $max ) {
      $max = $max - strlen($delimiter);
      $r = substr($str, 0, $max);
      $r = substr($r, 0, strrpos($r, " "));
      if ( substr($r, -1) == '-' ) {
        $r = substr($r, 0, strrpos($r, "-"));
      }
      if ( substr($r, -1) == ',' ) {
        $r = substr($r, 0, strrpos($r, ","));
      }
      $r = trim($r);
      $r .= $delimiter;
    } else {
      $r = $str;
    }

    return $r;
  }

  public static function amigavel ( $string, $slug = '-' )
  {

    $string = mb_strtolower($string, 'utf-8');
    $string = utf8_decode($string);

    $ascii['a'] = range(224, 230);
    $ascii['e'] = range(232, 235);
    $ascii['i'] = range(236, 239);
    $ascii['o'] = array_merge(range(242, 246), array(240, 248));
    $ascii['u'] = range(249, 252);

    $ascii['b'] = array(223);
    $ascii['c'] = array(231);
    $ascii['d'] = array(208);
    $ascii['n'] = array(241);
    $ascii['y'] = array(253, 255);

    foreach ( $ascii as $key => $item ) {
      $acentos = '';
      foreach ( $item AS $codigo )
        $acentos .= chr($codigo);
      $troca[$key] = '/[' . $acentos . ']/i';
    }

    $string = preg_replace(array_values($troca), array_keys($troca), $string);

    if ( $slug ) {
      $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
      $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
      $string = trim($string, $slug);
    }

    return $string;
  }

  public static function url_short ( $url, $login = 'dindigital', $appkey = 'R_5397f851143fc7c54f77617a40f8585b', $format = 'xml', $version = '2.0.1' )
  {
    $bitly = 'http://api.bit.ly/shorten?version=' . $version . '&longUrl=' . urlencode($url) . '&login=' . $login . '&apiKey=' . $appkey . '&format=' . $format;
    $response = file_get_contents($bitly);
    if ( strtolower($format) == 'json' ) {
      $json = @json_decode($response, true);
      return $json['results'][$url]['shortUrl'];
    } else {//xml
      $xml = simplexml_load_string($response);
      return 'http://bit.ly/' . $xml->results->nodeKeyVal->hash;
    }
  }

}
