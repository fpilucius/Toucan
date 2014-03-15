<?php

namespace Toucan\Component\Cache;

Use Toucan\Component\Cache\CacheInterface;

class CachePage implements CacheInterface
{

    protected $timeCache;
    protected $expire;
    protected $cache_dir;
    protected $id;
    protected $data;
    public $update = 0;

    public function __construct()
    {
        $this->cache_dir = '../App/Cache/';
    }

    public function setCaching($cacheId, $cacheLifeTime = null)
    {
        $this->id = md5(basename($cacheId));
        $this->timeCache = $cacheLifeTime;
        if ($this->timeCache != null) {
            $this->expire = time() - $this->timeCache;
        }
    }

    public function exists()
    {
        if ($this->timeCache != null) {
            if (file_exists($this->cache_dir . $this->id) && filemtime($this->cache_dir . $this->id) > $this->expire) {
                $this->read();
                return true;
            } else {
                $this->update = 1;
                return false;
            }
        } else {
            if (file_exists($this->cache_dir . $this->id)) {
                $this->read();
                return true;
            } else {
                $this->update = 1;
                return false;
            }
        }
    }

    protected function read()
    {
        //$this->data = file_get_contents($this->cache_dir . $this->id);
        $fp = fopen($this->cache_dir . $this->id, 'r');
        $this->data = fread($fp, filesize($this->cache_dir . $this->id));
        fclose($fp);
    }

    public function get()
    {
        return $this->data;
    }

    protected function write()
    {
        file_put_contents($this->cache_dir . $this->id, $this->data);
    }

    public function set($data)
    {
        if ($this->update == 1) {
            $this->data = $data;
            $this->data .= "<br/>Incache: " . date('Y/m/d : H:i:s') . "";
            $this->write();
        }
    }

    public function remove($key)
    {
        $this->id = md5(basename($key));
        unlink($this->cache_dir . $this->id);
    }

    // TODO removaAll CachePage
    public function removeAll()
    {
        
    }

}

?>
