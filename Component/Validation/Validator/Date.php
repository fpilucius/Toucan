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
 * @subpackage Date
 * @copyright Copyright (c) 2012 (http://toucan-project.org)
 * @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *
 * class de validation des dates.
 */
class Date extends Base
{
    const NOT_DATE = 'Cette date est invalide';

    /**
     * Verifie la valeur definie
     * 
     * @param string $value date
     * @return boolean true si la valeur est valid 
     */
    public function isValid($value)
    {
        if ($this->hasOption('msg')) {
            $msg = $this->getOption('msg');
        } else {
            $msg = self::NOT_DATE;
        }
        if (!$this->hasOption('format')) {
            $format = 'DD/MM/YYYY';
        }else{
            $format = $this->getOption('format');
        }

        switch ($format) {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
                list( $y, $m, $d ) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
                list( $y, $d, $m ) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
                list( $d, $m, $y ) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
                list( $m, $d, $y ) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'YYYYMMDD':
                $y = substr($value, 0, 4);
                $m = substr($value, 4, 2);
                $d = substr($value, 6, 2);
                break;

            case 'YYYYDDMM':
                $y = substr($value, 0, 4);
                $d = substr($value, 4, 2);
                $m = substr($value, 6, 2);
                break;

            default:
                throw new \Exception("Format de date invalide");
        }
        if (checkdate($m, $d, $y) == false) {
            $this->setMessage('strInvalidDate',$msg);
        }

        $result = true;
        if (count($this->errors) > 0) {
            $result = false;
        }
        return $result;
    }

}
?>