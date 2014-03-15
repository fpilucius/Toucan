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
* @subpackage Required
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation de valeur requise, non vide.
*/
class Required extends Base
{
    const IS_EMPTY = 'Cette valeur ne doit pas être vide';
    const INVALID = 'Le type est invalide: float, string, array, boolean ou integer sont requis';
    /**
     * Verifie la valeur definie
     * 
     * @param string $value non vide
     * @return boolean true si la valeur est valid. 
     */
    public function isValid($value)
    {
        if (empty($value)) {
            if ($this->hasOption('msg')) {
                $msg = $this->getOption('msg');
            } else {
                $msg = self::IS_EMPTY;
            }
            $this->setMessage('strIsEmpty',$msg);
        }

        if (!is_null($value) && !is_string($value) && !is_int($value) && !is_float($value) &&
                !is_bool($value) && !is_array($value)) {
            $this->setMessage('strInvalidType',self::INVALID);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        } 
        return $result;
    }
}
?>