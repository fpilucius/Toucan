<?php

namespace Toucan\Component\TemplateEngine;

use Toucan\Component\Registre\Registry;
use Toucan\Component\Cache\CachePage;

class PhpEngine implements \ArrayAccess
{

    protected $container;
    protected $layout;
    protected $charset = null;
    protected $helpers;
    protected $cache;
    protected $caching = false;

    public function __construct()
    {
        $this->container = Registry::get('container');
        $this->setLayout($this->get('config')->get('layout'));
        $this->charset = $this->get('config')->get('charset');
    }

    public function render($file, $assign = array(), $layout = null)
    {
        $data = array();
        if ($layout != null)
            $this->setLayout($layout);
        if ($this->getLayout() != false) {
            //initialiser le cache
            if ($this->caching == true && $this->cache->update == 0) {
                $data['content'] = $this->cache->get();
            } else {
                $data['content'] = $this->fetch($file, $assign);
            }
            //enregistrer le cache
            if ($this->caching == true && $this->cache->update == 1) {
                $this->cache->set($data['content']);
            }
            return $this->fetch('', $data, true);
        } else {
            return $this->fetch($file, $assign);
        }
    }

    public function fetch($file, array $assign = null, $dirLayout = false, $rep = null)
    {
        if ($assign != null)
            extract($assign);
        ob_start();
        $application = $this->get('config')->get('app_name');
        if ($rep == null) {
            $dirClass = ucfirst($this->get('router')->controller);
        } else {
            $dirClass = $rep;
        }
        if ($dirLayout == false) {
            $view = $this;
            include '../Project/' . $application . '/Views/' . $dirClass . '/' . $file;
        } else {
            $view = $this;
            include '../Project/' . $application . '/Views/' . $this->getLayout();
        }
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    protected function getLayout()
    {
        return $this->layout;
    }

    public function get($key)
    {
        return $this->container->getService($key);
    }

    public function offsetSet($name, $value)
    {
        $this->helper[$name] = $value;
    }

    public function offsetExists($name)
    {
        return isset($this->helper[$name]);
    }

    public function offsetUnset($name)
    {
        unset($this->helper[$name]);
    }

    public function offsetGet($name)
    {
        return $this->$name = $this->helper($name);
    }

    public function helper($helper)
    {
        $helperName = ucfirst($helper);
        if (!isset($this->helpers[$helper])) {
            $className = "Toucan\\Component\\TemplateEngine\\Helpers\\" . $helperName;
            $this->helpers[$helper] = new $className();
        }
        return $this->helpers[$helper];
    }

    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, $this->charset);
    }

    public function setCaching($cacheId, $cacheLifeTime = null)
    {
        $this->cache = new CachePage();
        $this->cache->setCaching($cacheId, $cacheLifeTime);
        $this->caching = true;
    }

    public function exists()
    {
        return $this->cache->exists();
    }

}

?>