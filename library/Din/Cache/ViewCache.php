<?php

namespace Din\Cache;

use Doctrine\Common\Cache\FilesystemCache;

class ViewCache {
    
    protected $_bool = true;
    protected $_fileSystemCache;
    
    public function __construct($cache, $path) {
        
        if (!$cache) {
            $this->_bool = false;
        }
        
        if (!is_dir($path)) {
           $this->_bool = false;
        }
        
        $this->_fileSystemCache = new FilesystemCache($path);
    }
    
    public function get($name) {
        
        if (!$this->_bool) {
            return null;
        }
        
        if ($this->_fileSystemCache->contains($name)) {
            return $this->_fileSystemCache->fetch($name);
        }
        
        return null;
    }
    
    public function save($name,$html,$time = 3600) {

        if ($this->_bool) {
            $this->_fileSystemCache->save($name, $html, $time);
        }
        
    }
    
    public function delete($name) {
        
        if ($this->_bool) {
            $this->_fileSystemCache->delete($name);
        }
        
    }
    
    public function deleteAll() {
     
        if ($this->_bool) {
            $this->_fileSystemCache->deleteAll();
        }
        
    }

}