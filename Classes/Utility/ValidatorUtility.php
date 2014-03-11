<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) Federico Bernardin 2014
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/



namespace TYPO3\T3registration\Utility;


/**
 * Class ValidatorUtility manages the operation of validator (add, remove, get, etc...)
 *
 * @author Federico Bernardin <federico@bernardin.it>
 * @package TYPO3\T3registration\Utility
 */
class ValidatorUtility {

    static private $validators = array();

    static public function addValidator($validator){
        if(class_exists($validator) && !array_key_exists($validator,self::$validators)){
            self::$validators[$validator] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($validator);
        }
    }

    static public function getValidators(){
        return self::$validators;
    }
}