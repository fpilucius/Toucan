<?php

namespace Toucan\Component\Dependency;

class ContainerAware implements ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
?>
