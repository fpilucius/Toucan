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

namespace Toucan\Component\Validation;

/**
* @category Toucan
* @package Component/Validation
* @subpackage validate
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation de valeurs.
*/
class Validate
{
    protected $valid = array();
    protected $messages = array();
    protected $errors = array();
    protected $source = array();
    
    /**
     * Ajouter un tableau de valeur a valider
     * 
     * @param array $source plusieurs valeurs de type array, $_POST, $_GET, $_SESSION
     */
    public function __construct(array $source)
    {
        $this->source = $source;
    }
    
    /**
     * Ajouter un tableau de valeur a valider
     * 
     * @param array $source plusieurs valeurs de type array, $_POST, $_GET, $_SESSION
     * @param return instance de validate
     */
    public function addSource(array $source)
    {
        $this->source = $source;
        return $this;
    }
    /**
     *
     * @param sting $value valeur a valider
     * @param string $validator nom du validateur
     * @param array $options options du validateur
     * @param return instance de validate
     */
    public function valid($value, $validator, array $options = null)
    {
        if( !isset( $this->validators[$value] ) )
		{
			$this->validators[$value] = array();
		}
        $this->valid[$value][] = array('validator' => $validator, 'options' => $options);
        return $this;
    }
    
    /**
     * Fonction de validation des valeurs definient avec $this->valid()
     */
    public function runValidate()
    {
        $path = "Toucan\\Component\\Validation\\Validator\\";
        
        foreach ($this->valid as $var => $args) {
            foreach ($args as $key) {
                $namespace = $path.ucfirst($key['validator']);
                $validator = new $namespace($key['options']);
                if ($validator->isValid($this->source[$var])) {
                    $result = true;
                } else {
                $result = false;
                $messages = $validator->getMessages();
                $this->messages[] = $messages[0];
                $this->errors = $messages;
                }
            }
        }
    }
    /**
     *
     * @param array $value une valeur de type array, $_POST, $_GET, $_SESSION 
     * @param string $validator
     * @param array $args 
     */
    public function validOne($value, $validator, array $args = null)
    {
        $path = "Toucan\\Component\\Validation\\Validator\\";
        $namespace = $path.$validator;
        $valid = new $namespace($args);
        if(!$valid->isValid($value)) {
            $messages = $valid->getMessages();
            $this->messages[array_keys($value)] = $messages;
            $this->errors = $valid->getErrors();
        }
    }
    
    /**
     * @return array retourne l'ensemble des messages d'erreur 
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * @param string $name nom d'une valeur
     * @return type retourne les messages d'erreur d'une valeur
     */
    public function getMessage($name)
    {
        return $this->messages[$name];
    }
    
    /**
     * @return boolean retourne false si des erreurs existent
     */
    public function hasErrors()
    {
        $result = true;
        if(count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>