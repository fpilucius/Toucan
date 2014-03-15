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
* @subpackage True
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation de valeurs boléenne True.
*/
class True extends Base 
{
    const NOT_TRUE = 'Cette valeur doit retourner "Vrai"';
    
    /**
     * Verifie la valeur definie
     * 
     * @param numeric $value 1 ou '1'
     * @return boolean true si la valeur est valid 
     */
    public function isValid($value)
    {
        $trueBoleens = array(1,'1');
        
        if ($this->hasOption('msg')) {
                $msg = $this->getOption('msg');
            } else {
                $msg = self::NOT_TRUE;
            }
            
        if (!in_array(strtolower($value), $trueBoleens)) {
            $this->setMessage('valueInvalidTrue', $msg);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>