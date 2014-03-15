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
* @package Component/Validation/validator
* @subpackage Ip
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* Fichier de validation d'une adresse Ip.
*/
class Ip extends Base
{
    const IP_INVALID = 'Adresse ip invalide';
    
    /**
     * Fonction de validation d'une adgesse ip (ipv4 ou ipv6)
     * 
     * @param string $value Adresse Ip
     * @return boolean retourne true si la valeur est vrai
     */
    public function isValid($value)
    {
        if ($this->hasOption('msg')) {
                $msg = $this->getOption('msg');
            } else {
                $msg = self::IP_INVALID;
            }
            
        if (!$this->hasOption('ipv6')) {
            if(!preg_match('/^((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?1)){3}\z/', $value)) {
                $this->setMessage('IpInvalid', $msg);
            } 
        }else{
            if(!preg_match('/^(((?=(?>.*?(::))(?!.+\3)))\3?|([\dA-F]{1,4}(\3|:(?!$)|$)|\2))(?4){5}((?4){2}|((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?7)){3})\z/i', $value)) {
                $this->setMessage('IpInvalid', $msg);
            } 
        }
        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>