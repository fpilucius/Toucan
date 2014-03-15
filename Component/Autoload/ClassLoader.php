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
namespace Toucan\Component\Autoload;
/**
* @category Toucan
* @package Component/Autoload
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* Fichier de gestion du chargement des classes.
*/
class ClassLoader
{

    protected $namespaces = array();
    protected $defaultPath;
    protected $namespaceSeparator = '\\';
    
    /** 
     * 
     * @param string $defaultPath chemin par default
     */
    function __construct($defaultPath = './')
    {
        $this->defaultPath = $defaultPath;
    }
    
    /**
     * Ajout d'un namespace
     * 
     * @param string $namespace nom du namespace
     * @param string $path chemin du repertoire pour le namespace
     */
    public function addNamespace($namespace, $path = null)
    {
        if (isset($this->namespaces[(string) $namespace])) {
            throw new \Exception('Le namespace ' . $namespace . ' est déjà présent.');
        }
        if ($path !== null) {
            $length = strlen($path);
            if ($length == 0 || $path[$length - 1] != '/') {
                $path .= '/';
            }
            $this->namespaces[(string) $namespace] = $path;
        } else {
            $this->namespaces[(string) $namespace] = $this->defaultPath;
        }
    }
    
    /**
     * Initialisation de l'autoloader
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
    
    /**
     * Autochargement des classes
     * 
     * @param string $className 
     */
    public function loadClass($className)
    {
        $className = ltrim($className, $this->namespaceSeparator);
        $match = strstr($className, $this->namespaceSeparator, true);
        $class = str_replace($this->namespaceSeparator, '/', $className) . '.php';
        require $this->namespaces[$match] . $class;
    }
}
?>