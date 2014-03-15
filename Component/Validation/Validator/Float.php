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
 * @subpackage Float
 * @copyright Copyright (c) 2012 (http://toucan-project.org)
 * @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *
 * Fichier de validation d'un nombre décimal.
 */
class Float extends Base
{
    const FLOAT_INVALID = 'Cette donnée n\'est pas un nombre décimal';

    /**
     * Fonction de validation d'un nombre décimal
     * 
     * @param string $value Nombre décimal
     * @return boolean retourne true si la valeur est vrai
     */
    public function isValid($value)
    {
        if ($this->hasOption('msg')) {
            $msg = $this->getOption('msg');
        } else {
            $msg = self::FLOAT_INVALID;
        }

        if (!is_float($value)) {
            $this->setMessage('FloatInvalid', $msg);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        }
        return $result;
    }

}