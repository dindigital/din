<?php

namespace Din\Assets\Compressor;

abstract class Creator
{
	protected $create;
	abstract public function factoryMethod(iAsset $asset);
}