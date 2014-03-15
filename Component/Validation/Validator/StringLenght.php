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
* @subpackage StringLenght
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* class de validation de longueur de chaine de caractere.
*/
class StringLenght extends Base 
{
    const STRING_INVALID = 'Ce champs doit être une chaîne de caractère';
    const MIN_L          = 'Il faut %s caractères minimum';
    const MAX_L          = 'Il faut %s caractères maximunm';
    
    /**
     * Verifie la valeur definie
     * 
     * options pour $args
     * min_lenght, max_lenght, max_msg , min_msg.
     * 
     * @param string $value 
     * @return boolean true si la valeur est valid 
     */
    public function isValid($value) 
    {
        if (!array_key_exists('min_lenght', $this->options)) {
            $this->options['min_lenght'] = 0;
        }
        if (!is_string($value)) {
            $this->setMessage('strInvalid', self::STRING_MSG);
        }

        if ($this->hasOption('min_lenght')) {
            if ($this->hasOption('min_msg')) {
                $msg = $this->getOption('min_msg');
            } else {
                $msg = sprintf(self::MIN_L, $this->getOption('min_lenght'));
            }
            if (strlen($value) < $this->getOption('min_lenght')) {
                $this->setMessage('strTooShort', $msg);
            }
        }

        if ($this->hasOption('max_lenght')) {
            if ($this->hasOption('max_msg')) {
                $msg = $this->getOption('max_msg');
            } else {
                $msg = sprintf(self::MAX_L, $this->getOption('max_lenght'));
            }
            if (strlen($value) > $this->getOption('max_lenght')) {
                $this->setMessage('strTooLong', $msg);
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