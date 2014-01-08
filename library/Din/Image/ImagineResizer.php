<?php

namespace Din\Image;

use Imagine\Gd\Image;
use Imagine\Image\BoxInterface;
use Imagine\Image\Point;
use Imagine\Image\Box;

class ImagineResizer
{

  protected $imagine;
  protected $box;

  public function __construct ( Image $imagine, BoxInterface $box )
  {
    $this->imagine = $imagine;
    $this->box = $box;
  }

  public function resize ()
  {
    $image = $this->imagine;
    //original size
    $srcBox = $image->getSize();
    //we scale on the smaller dimension
    if ( $srcBox->getWidth() > $srcBox->getHeight() ) {
      $width = $srcBox->getWidth() * ($this->box->getHeight() / $srcBox->getHeight());
      $height = $this->box->getHeight();
      //we center the crop in relation to the width
      $cropPoint = new Point((max($width - $this->box->getWidth(), 0)) / 2, 0);
    } else {
      $width = $this->box->getWidth();
      $height = $srcBox->getHeight() * ($this->box->getWidth() / $srcBox->getWidth());
      //we center the crop in relation to the height
      $cropPoint = new Point(0, (max($height - $this->box->getHeight(), 0)) / 2);
    }

    $box = new Box($width, $height);
    //we scale the image to make the smaller dimension fit our resize box
    $image = $image->thumbnail($box, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);

    //and crop exactly to the box
    $image->crop($cropPoint, $this->box);

    return $image;
  }

}
