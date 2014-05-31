<?php


namespace TYPO3\T3registration\Backend;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Federico Bernardin <federico.bernardin@immaginario.com>, BFConsulting
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\T3registration\Utility\ValidatorUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FlexformUtility
 *
 * @package TYPO3\T3registration\Backend
 */
class FlexformUtility {



    protected function getReflectedClassForUser(){
        return new \ReflectionClass('\TYPO3\T3registration\Domain\Model\User');
    }

    protected function getValidators(){
        return ValidatorUtility::getValidators();
    }

    protected function sL($str){
        return $GLOBALS['LANG']->sL($str);
    }

    /**
     * Return all properties of Frontend User
     * @param array                              $config
     * @param \TYPO3\CMS\Backend\Form\FormEngine $parentFormObject
     */
    public function getFrontendUserProperties(array &$config, \TYPO3\CMS\Backend\Form\FormEngine $parentFormObject) {
        /** @var \ReflectionClass $reflectedClass */
        $reflectedClass = $this->getReflectedClassForUser();
        $properties = $reflectedClass->getProperties();
        foreach ($properties as $property) {
            if (($label = $this->sL($property->getName())) != '') {
                array_push($config['items'], array($label, $property->getName(), ''));
            } else {
                array_push($config['items'], array($property->getName(), $property->getName(), ''));
            }
        }
    }

    /**
     * Get all validator objects
     * @param array $config
     * @param       $parentFormObject
     */
    public function getFrontendUserValidators(array &$config, $parentFormObject) {
        $validators = $this->getValidators();
        foreach ($validators as $key => $validator) {
            if (strstr('LLL', $validator->getLabel()) != false) {
                $label = $this->sL($validator->getLabel());
            } else {
                $label = $this->sL('LLL:EXT:t3registration/Resources/Private/Language/locallang_be.xlf:' . $validator->getLabel());
            }
            //TODO vedere se si può rimuovere il pezzo successivo
            if ($label == '') {
                $label = $validator->getLabel();
            }
            array_push($config['items'], array($label, $key, ''));
        }
    }

    protected function getArrayFromXml($xml){
        return GeneralUtility::xml2array($xml);
    }

    /**
     * This function create the field for regular expression string
     * @param array $DataArray array with data from record of tt_content
     * @param \TYPO3\CMS\Backend\Form\FormEngine $callerObject
     * @return string empty if regular expression validator was not chosen
     */
    public function getRegularExpressionField($DataArray, $callerObject) {
        $data = $this->getArrayFromXml($DataArray['row']['pi_flexform']);
        //try to extract the form element
        //it has the form: $data['data']['fieldsSheet']['lDEF']['settings.fields']['el'][xxx]['databaseField']['el']
        //where xxx is the number
        preg_match_all('/.*\[el\]\[(\d)\]\[databaseField\].*/iUs', $DataArray['itemFormElID'], $matches);
        if (isset($matches[1][0]) && \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($matches[1][0])) {
            $id = $matches[1][0];
            //verify if validators is set
            if (isset($data['data']['fieldsSheet']['lDEF']['settings.fields']['el'][$id]['databaseField']['el']['validators']['vDEF'])) {
                $validators = $data['data']['fieldsSheet']['lDEF']['settings.fields']['el'][$id]['databaseField']['el']['validators']['vDEF'];
                //if Regexp Validator is found return the field for validator
                if (GeneralUtility::inList($validators, 'TYPO3%5CT3registration%5CValidator%5CRegexpValidator|Regexp')) {
                    $formField = '<div style="padding: 5px;">';
                    $formField .= '<input type="text" name="' . $DataArray['itemFormElName'] . '"';
                    $formField .= ' value="' . htmlspecialchars($DataArray['itemFormElValue']) . '"';
                    $formField .= ' onchange="' . htmlspecialchars(implode('', $DataArray['fieldChangeFunc'])) . '"';
                    $formField .= $DataArray['onFocus'];
                    $formField .= ' /></div>';
                    return $formField;
                }
            }
        }
        return '';
    }
}