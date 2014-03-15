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
namespace Toucan\Core;

use Toucan\Core\Http\Request;
use Toucan\Component\Registre\Registry;
use Toucan\Component\Controller\Controller;

/**
* @category Toucan
* @package Core/Dispatcher
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* Fichier d'initialisation des routes, controlleur et vue de l'application.
*/
class Dispatcher
{

    protected $container;
    protected $router;
    /**
     * Initialisation
     */
    public function __construct()
    {
        $this->container = Registry::get('container');
        $this->router = $this->get('router');
    }  
    
    /**
     * Chargement du controlleur
     * 
     * @return instance du controlleur 
     */
    protected function loadController()
    {
        $application = $this->get('config')->get('app_name');
        $class = ucfirst($this->router->controller);
        if (is_dir("../Project/" . $application)) {
            $file = "../Project/" . $application . "/Controllers/" . $class . ".php";
            if (!file_exists($file)) {
                throw new \Exception('Le fichier ' . $file . ' n\'éxiste pas');
            }
        } else {
            $msg = 'Le répertoire ' . $application . ' n\'éxiste pas dans Project';
            throw new \Exception($msg);
        }
        include $file;
        $className = $application . "\\Controllers\\" . ucfirst($class);
        if (!class_exists($className)) {
            throw new \Exception('Le controller ' . $className . ' n\'éxiste pas');
        }
        return new $className();
    }
    
    /**
     *
     * @return string retourne la reponse (vue: html, xml, text, ect...)
     */
    public function execute()
    {
        $controller = $this->loadController();
        $action = strtolower($this->router->action);
        $args = $this->router->args;

        if (!$controller instanceof Controller) {
            $msg = $controller . ' n\'est pas une instance de Controller';
            throw new \Exception($msg);
        }
      
       // return $response
        if (method_exists($controller, $action)) {
            $response = call_user_func_array(array($controller, $action), $args);
        }
        
        return $response;
    }

    /**
     * initilisation d'un service
     * 
     * @param string $service le nom d'une class service
     * @return instance du service  
     */
    private function get($service)
    {
        return $this->container->getService($service);
    }
}
?>