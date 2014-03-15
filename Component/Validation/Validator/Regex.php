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
* @subpackage Regex
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* Fichier de validation d'une donnée par expression régulière.
*/
class Regex extends Base
{
    const REGEX_INVALID = 'Cette donnée n\'est pas au format requis';
    
    /**
     * Fonction de validation de donnée par expression régulière.
     * 
     * @param string $value Une valeur
     * @return boolean retourne true si la valeur est vrai
     */
    public function isValid($value)
    {
        if ($this->hasOption('msg')) {
            $msg = $this->getOption('msg');
        } else {
            $msg = self::REGEX_INVALID;
        }
        
        if (!$this->hasOption('pattern')) {
            throw new \Exception('L\'option pattern est requis');
        }

        if (!preg_match($this->getOption('pattern'), $value)) {
            $this->setMessage('RegexInvalid', $msg);
        }
        
        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }

}
?>