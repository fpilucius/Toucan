<?php
namespace Toucan\Component\Cache;

interface CacheInterface
{
    public function set($data);
    public function get();
    public function remove($key);
    public function exists();
}
?>
