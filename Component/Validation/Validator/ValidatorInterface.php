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
/**
* @category Toucan
* @package Component/Validation/Validator
* @subpackage ValidatorInterface
* @copyright Copyright (c) 2012 (http://toucan-project.org)
* @license  MIT License (http://www.opensource.org/licenses/mit-license.php)
*
*
* interface pour class de validation.
*/
interface ValidatorInterface
{
    public function isValid($value);
}
?>
