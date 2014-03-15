<?php
namespace Toucan\Component\Dependency;

class Container
{
    protected $services = array();
    protected $cacheObject = array();

    public function __construct(array $definitions = array())
    {
        $this->services = $definitions;
    }
    
    public function getService($id)
    {
        if(isset($this->services[(string )$id]['singleton'])) {
            if($this->inCache($id)) {
                return $this->getFromcache($id);
            } else {
                $classObj = $this->createService($id);
                $this->putIncache($id, $classObj);
                return $classObj;
            }
        } else {
            return $this->createService($id);
        }
    } 

    public function createService($id)
    {
        if (isset($this->services[(string )$id]['class']) && !isset($this->services[(string )$id]['constructor'])) {
            $class = $this->services[(string )$id]['class'];
            $rc = new $class();
            return $rc;
        } else {
            $rc = new \ReflectionClass($this->services[(string )$id]['class']);
            $args = $this->evaluateArguments($this->services[(string )$id]['constructor']);
            return $rc->newInstanceArgs($args);
        }
    }

    public function evaluateArguments($args)
    {
        $arguments = array();
        foreach ($args as $arg) {
           if (is_string ($arg) && substr($arg, 0, 4) == 'ref.') {
                $arguments[] = $this->getService(substr($arg, 4));
            }else {
                $arguments[] = $arg;
            }
        }
        return $arguments;
    }

    public function setServiceFromArray(array $services)
    {
        $this->services = $services;
        return $this;
    }

    public function setService($id, array $service)
    {
        $this->services[strtolower($id)] = $service;
        return $this;
    }

    public function hasService($id)
    {
        return isset($this->services[strtolower($id)]);
    }


    public function __set($id, array $service)
    {
        $this->services[strtolower($id)] = $service;
    }

    public function __get($id)
    {
        return $this->services[strtolower($id)];
    }
    
    public function putInCache($key, $value) 
    {
        $this->cacheObject[$key] = $value;
    }
    
    public function getFromcache($key)
    {
        return $this->cacheObject[$key];
    }
    
    public function inCache($key)
    {
        return isset($this->cacheObject[$key]);
    }
}
?>