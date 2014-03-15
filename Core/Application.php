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

namespace Toucan\core;

use Toucan\Component\Registre\Registry;
use Toucan\Component\Dependency\Container;
use Toucan\Core\Dispatcher;
use Toucan\Component\Debuging\ErrorHandler;

/**
* @category Toucan
* @package Core/Application
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* Fichier de lancement de l'application.
*/
class Application
{
    protected $env;
    protected $debug;
    protected $startTime;
    protected $container;
    
   /**
   * Initialisation de l'application 
   * 
   * @param string $env
   * @param bool $debug
   * 
  */
   public function __construct($env, $debug)
    {
        $this->env = $env;
        $this->debug = (Boolean) $debug;
        
        $services = require '../App/Config/Services.php';
        $environnement = require '../App/Config/Application_'.$env.'.php';
        
        $container = new Container($services);
        $container->setService('config', array('class' => 'Toucan\Component\Config\Config',
                                               'constructor' => array($environnement),
                                               'singleton' => true));
        Registry::set('container', $container);
        $this->container = Registry::get('container');
        
        if ($this->debug) {
            ini_set('display_errors', 1);
            error_reporting(-1);
            $this->startTime = microtime(true);
        } else {
            ini_set('display_errors', 0);
        
        }
    }
    
    /**
     * Lancement de l'application
     */
    public function start()
    { 
        $debug = new ErrorHandler($this->debug);
        $debug->register();
        date_default_timezone_set($this->get('config')->get('time_zone'));
        $dispatch = new Dispatcher();
        $response = $dispatch->execute();
        $response->outPut();
    }
    
    /**
     * initilisation d'un service
     * 
     * @param string $key nom d'une classe service
     * @return instance du service 
     */
    public function get($key)
    {
        return $this->container->getService($key);
    }
}
?>