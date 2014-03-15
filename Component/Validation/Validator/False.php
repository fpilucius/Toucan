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
* @subpackage False
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation de valeurs boléenne False.
*/
class False extends Base 
{
    const NOT_FALSE = 'Cette valeur doit retourner "Faux"';
    
    /**
     * Verifie la valeur definie
     * 
     * @param numeric $value 0 ou '0'
     * @return boolean true si la valeur est valid 
     */
    public function isValid($value)
    {
        $falseBoleens = array(0,'0');
        
        if ($this->hasOption('msg')) {
                $msg = $this->getOption('msg');
            } else {
                $msg = self::NOT_FALSE;
            }
        
        if (!in_array(strtolower($value), $falseBoleens)) {
            $this->setMessage('valueInvalidTrue',$msg);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>