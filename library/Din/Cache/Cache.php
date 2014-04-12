<?php

namespace Din\Cache;

use Doctrine\Common\Cache\Cache as CacheInterface;
use Memcache;

class Cache
{

  protected $_cacheDriver = null;

  public function setCacheDriver ( CacheInterface $cacheDriver )
  {
    $this->_cacheDriver = $cacheDriver;

  }

  public function setMemcache ( $host, $port )
  {
    $memcache = new Memcache();
    $memcache->connect($host, $port);
    $this->_cacheDriver->setMemcache($memcache);

  }

  public function get ( $name )
  {

    if ( $this->_cacheDriver instanceof CacheInterface && $this->_cacheDriver->contains($name) )
      return $this->_cacheDriver->fetch($name);

    return null;

  }

  public function save ( $name, $html, $time = 60 )
  {
    if ( $this->_cacheDriver instanceof CacheInterface )
      $this->_cacheDriver->save($name, $html, $time);

  }

  public function delete ( $name )
  {
    if ( $this->_cacheDriver instanceof CacheInterface )
      $this->_cacheDriver->delete($name);

  }

  public function deleteAll ()
  {
    if ( $this->_cacheDriver instanceof CacheInterface )
      $this->_cacheDriver->deleteAll();

  }

}
