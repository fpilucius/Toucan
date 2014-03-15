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
* @subpackage Url
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation d'url.
*/
class Url extends Base 
{
    const NOT_URL = 'Cet url est invalide';
    /**
     * Verifie la valeur definie
     * 
     * @param string $value url
     * @return boolean true si la valeur est valid 
     */
    public function isValid($value)
    {
        if ($this->hasOption('msg')) {
                $msg = $this->getOption('msg');
            } else {
                $msg = self::NOT_URL;
            } 
            
        if (!preg_match("#((https?|ftps?)://[a-z.-]+\.[a-z]{2,4}|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}):?(\d+)?/?#",$value)) {
            $this->setMessage('strInvalidUrl', $msg);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>