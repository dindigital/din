<?php

namespace Din\Image;

/**
 *
 * @package lib.Image
 */
class Picuri
{

  /**
   * Redimensiona uma imagem de acordo com parâmetros e retorna a tag img
   *
   * @param string $uri
   * @param int $w
   * @param int $h
   * @param bool $crop
   * @param array $attribs
   * @param bool $tag
   * @example picUri('fulano.jpg', 100, 100, true, array('class'=>'pic'))
   * @return string
   */
  public static function picUri ( $uri, $w = null, $h = null, $crop = false, $attribs = array(), $tag = true, $std = false )
  {
    // _# SKIP GIF
    $pathinfo = pathinfo($uri);
    if ( isset($pathinfo['extension']) && strtolower($pathinfo['extension']) == 'gif' ) {
      $thumb = $uri;
    } else {

      $capa = 'public' . $uri;
      $fail = 'public' . IMAGEM_PADRAO;

      $image = new Image($capa);
      if ( !$image->is_file() ) {
        $image->setPath($fail);
      }

      $image->setWidth($w)->setHeight($h)->setCrop($crop);

      $image->setAutosavePath(PATH_IMAGE);
      if ( !$image->is_autosave_file() ) {
        $image->resize();
        $image->autosave();
      }

      if ( !$crop ) {
        list($w, $h) = getimagesize($image->getAutosavePath());
      }

      $thumb = str_replace(PATH_REPLACE, '', $image->getAutosavePath());
    }

    $attr = '';
    if ( count($attribs) ) {
      foreach ( $attribs as $k => $v ) {
        $attr[] = $k . '="' . $v . '"';
      }

      $attr = implode(' ', $attr);
    }

    if ( defined('IMG_SUBDOMAIN') && getenv('DOMAIN_NAME') ) {
      $thumb = 'http://' . IMG_SUBDOMAIN . '.' . getenv('DOMAIN_NAME') . $thumb;
    }

    if ( $tag ) {
      $r = '<img src="' . $thumb . '" width="' . $w . '" height="' . $h . '" ' . $attr . ' />';
    } else {
      $r = $thumb;
    }

    if ( $std ) {
      $std = new \stdClass();
      $std->width = $w;
      $std->height = $h;
      $std->src = $thumb;
      $std->tag = $r;

      $r = $std;
    }

    return $r;
  }

}
