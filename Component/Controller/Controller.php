<?php
namespace Toucan\Component\Controller;

use Toucan\Component\Registre\Registry;
use Toucan\Component\Dependency\Container;
use Toucan\Core\Http\Response;
use Toucan\Core\Http\ResponseRedirect;

class Controller
{
    protected $container;
    
    public function __construct()
    {
        $this->container = Registry::get('container');
    }
    
    public function render($view, array $parameters = null)
    {
        $rendered = $this->get('template')->render($view, $parameters, null);
        return new Response($rendered);
        
    }
    
    public function redirect($url, $status = 302)
    {
        return new ResponseRedirect($url, $status);
    }
    
    public function get($key)
    {
        return $this->container->getService($key);
    }
    
}
?>
