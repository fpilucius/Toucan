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
namespace Toucan\Component\Validation\Validator;
use Toucan\Component\Validation\Validator\Base;
/**
* @category Toucan
* @package Component/Validation/Validator
* @subpackage Email
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation d'email.
*/
class Email extends Base 
{
    const NOT_EMAIL = 'Cet email est invalide';
    
    /**
     * Verifie la valeur definie
     * 
     * @param string $value email
     * @return boolean true si la valeur est valid 
     */
    public function isValid($value)
    {
        if ($this->hasOption('msg')) {
                $msg = $this->getOption('msg');
            } else {
                $msg = self::NOT_EMAIL;
            }
            
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value)) {
            $this->setMessage('strInvalidEmail',$msg);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>