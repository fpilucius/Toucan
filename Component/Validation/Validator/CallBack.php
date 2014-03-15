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
 * @subpackage CallBack
 * @copyright Copyright (c) 2012 (http://toucan-project.org)
 * @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *
 * class de validation CallBack(customiser une class ou utiliser un validateur externe).
 */
class CallBack extends Base
{

    public function isValid($value)
    {
        if (!$this->hasOption('msg')) {
            throw new \Exception('l\'option msg est requis ');
        }
        if (!$this->hasOption('object')) {
            throw new \Exception('l\'option object est requis ');
        }
        if (!$this->hasOption('method')) {
            throw new \Exception('l\'option method est requis ');
        }

        $class = $this->getOption('object');
        $func = $this->getOption('method');
        $obj = new $class;
        // la function de callback doit retournée un boléen
        if ($obj->$func($value) == false) {
            $this->setMessage('strInvalidCallBack',$this->getOption('msg'));
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        }
        return $result;
    }

}
?>