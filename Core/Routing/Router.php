<?php
/*
 * LICENCE
 * 
 * (c) Franck Pichot <fpilucius@gmail.com>
 * 
 * Ce fichier est sous licence MIT.
 * Consulter le fichier LICENCE du projet. 
 * 
 */

namespace Toucan\Core\Routing;

use Toucan\Component\Registre\Registry;

/**
* @category Toucan
* @package Core/Routing
* @subpackage Router
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* Fichier de cheminement des routes de l'application(routage vers le controlleur,
* fonction et vue definit dans l'url).
*/
class Router
{
    protected $routes;
    protected $path;
    protected $uri = array();
    public $controller;
    public $action;
    public $args = array();
    
    /**
     * initialisation des routes
     */
    public function __construct()
    {
        $this->routes = require '../App/Config/Routes.php';
        $this->container = Registry::get('container');
        $this->path = $this->get('request')->request_uri;
        $this->route();
    }
    
    /**
     * Definition de la route definit dans l'url
     * 
     * @return new Router 
     */
    public function route()
    {
        $rootBaseUrl = $this->get('config')->get('root');

         $path = str_replace($rootBaseUrl, "", $this->path);

        if (strstr($path, '?')) {
            $path = substr($path, 0, strpos($path, '?'));
        }
        
        
        if ($path === "/") {
            $path =  '/' . $this->routes['default_class'] . '/'
                    . $this->routes['default_action'];
        }
        
        $arg = explode('/', trim($path, '/'));
        $this->uri = $arg;

        $nbArgs = count($arg);

        $class = isset($arg[0]) ? $arg[0] : $this->routes['default_class'];
        $action = isset($arg[1]) ? $arg[1] : $this->routes['default_action'];
        
        $this->controller = $class;
        $this->action = $action;
        
        if($nbArgs >= 3){
            $this->args = array_slice($this->uri, 2);
        }
        return $this;
    }
    
    /**
     * initilisation d'un service
     * 
     * @param string $key le nom d'une class service
     * @return instance du service  
     */
    public function get($key)
    {
        return $this->container->getService($key);
    }
}
?>