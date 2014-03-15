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
* @subpackage Numeric
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation de valeur numerique.
*/
class Numeric extends Base 
{
    const NOT_NUMERIC = 'Cette valeur doit être de type numeric';
    /**
     * Verifie la valeur definie
     * 
     * @param string $value numerique
     * @return boolean true si la valeur est valid 
     */
    public function isValid($value)
    {
        if ($this->hasOption('msg')) {
                $msg = $this->getOption('msg');
            } else {
                $msg = self::NOT_NUMERIC;
            }
        
        if (!preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $value)) {
            $this->setMessage('valueInvalidNumeric', $msg);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>