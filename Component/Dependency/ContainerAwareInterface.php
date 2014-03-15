<?php
namespace Toucan\Component\Dependency;

interface ContainerAwareInterface
{
    function setContainer(ContainerInterface $container = null);
}
?>
