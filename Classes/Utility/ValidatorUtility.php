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


use TYPO3\T3registration\Validator\ValidatorInterface;

/**
 * Class ValidatorUtility manages the operation of validator (add, remove, get, etc...)
 *
 * @author Federico Bernardin <federico@bernardin.it>
 * @package TYPO3\T3registration\Utility
 */
class ValidatorUtility {

    static private $validators = array();

    /**
     * Add the validator
     * @param ValidatorInterface $validator the validator to add
     */
    static public function addValidator($validator){
        if(class_exists($validator) && !array_key_exists($validator,self::$validators)){
            $validatorClass = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($validator);
            if($validatorClass instanceof ValidatorInterface){
                self::$validators[$validator] = $validatorClass;
            }
        }
    }

    static public function removeAll(){
        foreach(self::$validators as $validatorKey => $validator){
            self::removeValidator($validatorKey);
        }
    }

    static public function removeValidator($validator){
        if(array_key_exists($validator,self::$validators)){
            unset(self::$validators[$validator]);
        }
    }

    /**
     * @return array validators array
     */
    static public function getValidators(){
        return self::$validators;
    }

    /**
     * return specific validator object or null id it's not found
     * @param string $key key to search
     * @return null|ValidatorInterface the specific validator or null
     */
    static public function getValidator($key){
        if(isset(self::$validators[$key])){
            return self::$validators[$key];
        }
        else{
            return null;
        }
    }
}