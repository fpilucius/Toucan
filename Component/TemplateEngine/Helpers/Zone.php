<?php

namespace Toucan\Component\TemplateEngine\Helpers;

use Toucan\Component\TemplateEngine\Helpers\TemplateHelperInterface;
use Toucan\Component\Registre\Registry;

class Zone implements TemplateHelperInterface
{

    protected $container;

    public function __construct()
    {
        $this->container = Registry::get('container');
    }

    public function get()
    {
        $application = $this->getService('config')->get('app_name');
        $args = func_get_args();
        $class = $application . '\\Zones\\' . $args[0];
        $zone = new $class();
        return $zone->outPut();
    }

    public function getService($key)
    {
        return $this->container->getService($key);
    }

}

?>
