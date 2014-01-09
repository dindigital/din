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

  public function crop ()
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
    } else {
      $resize_w = ($atual_w / $atual_h) * $desejado_h;
    }

    $image = $this->imagine->resize(new Box($resize_w, $resize_h))->thumbnail($this->box, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);

    return $image;
  }

}
