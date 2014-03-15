<?php

namespace Toucan\Component\TemplateEngine;

use Toucan\Component\Registre\Registry;

abstract class Zone
{

    protected $container;

    public function __construct()
    {
        $this->container = Registry::get('container');
    }

    abstract public function outPut();

    public function render($zone, array $parameters = null)
    {
        $render = $this->get('template')->fetch($zone, $parameters, null, 'Zones');
        return $render;
    }

    public function get($key)
    {
        return $this->container->getService($key);
    }

}

?>
