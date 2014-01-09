<?php

namespace Din\Image;

use Imagine\Gd\Image;
use Imagine\Image\BoxInterface;
use Imagine\Image\Box;

class ImageCrop
{

  protected $imagine;
  protected $box;

  public function __construct ( Image $imagine, BoxInterface $box )
  {
    $this->imagine = $imagine;
    $this->box = $box;
  }

  public function crop ( $type = 'center' )
  {
    $srcBox = $this->imagine->getSize();

    $atual_w = $srcBox->getWidth();
    $atual_h = $srcBox->getHeight();
    $desejado_w = $this->box->getWidth();
    $desejado_h = $this->box->getHeight();

    $resize_w = $desejado_w;
    $resize_h = $desejado_h;

    if ( $atual_w < $atual_h ) {
      $resize_h = ($atual_h / $atual_w) * $desejado_w;
      if ( $resize_h < $desejado_h ) {
        $resize_h = $desejado_h;
        $resize_w = ($atual_w / $atual_h) * $desejado_h;
      }
    } else {
      $resize_w = ($atual_w / $atual_h) * $desejado_h;
      if ( $resize_w < $desejado_w ) {
        $resize_w = $desejado_w;
        $resize_h = ($atual_h / $atual_w) * $desejado_w;
      }
    }

    $image = $this->imagine->resize(new Box($resize_w, $resize_h));

    if ( $type == 'center' ) {
      $image = $image->thumbnail($this->box, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
    } else {
      $x = $resize_w / 2 - $desejado_w / 2;
      $image = $this->imagine->crop(new \Imagine\Image\Point($x, 0), $this->box);
    }

    return $image;
  }

}
