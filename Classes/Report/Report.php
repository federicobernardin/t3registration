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



namespace TYPO3\T3registration\Report;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;

/**
 * Class Report
 *
 * @author Federico Bernardin <federico@bernardin.it>
 * @package TYPO3\T3registration\Report
 *
 */
class Report {

    const DUPLICATE_FIELD = 20001;
    const DUPLICATE_USERNAME_FIELD = 20002;
    const USERNAME_FIELD_IS_MISSED = 20003;
    const USERNAME_MISS_UNIQUENESS = 20004;

    private $uniquenessValidator = '';

    /**
     * @var \TYPO3\CMS\Extbase\Error\Result
     */
    private $result = null;

    /**
     * The current view, as resolved by resolveView()
     *
     * @var \TYPO3\CMS\Extbase\Mvc\View\ViewInterface
     * @api
     */
    protected $view = NULL;

    /**
     * Typoscript Settings
     * @var array
     */
    private $settings = array();

    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    private $logger;

    /**
     * Controller View
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     */
    public function setView($view) {
        $this->view = $view;
    }


    /**
     * Main report function
     * @param array $settings
     * @return true if not error has found
     */
    public function checkConfiguration(array $settings) {
        $this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $this->result = new Result();
        $this->settings = $settings;
        $this->testDuplicate();
        $this->testForUniqueUsernameField();
        foreach($this->result->getErrors() as $error){
            $this->logger->log(\TYPO3\CMS\Core\Log\LogLevel::CRITICAL, $error->getMessage());
        }
        if(!GeneralUtility::cmpIP(GeneralUtility::getIndpEnv('REMOTE_ADDR'), $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'])){
            throw new \Exception('T3Registration misconfiguration. View TYPO3 log.');
        }
        if($this->result->hasErrors()){
            return false;
        }
        else{
            return true;
        }
    }

    public function hasError(){
        return $this->result->hasErrors();
    }

    public function getError(){
        return $this->result;
    }

    /**
     * Checks for field duplication
     */
    private function testDuplicate() {
        $unique = array();
        $duplicated = array();
        foreach ($this->settings['fields'] as $field) {
            if (!in_array($field['databaseField']['name'], $unique)) {
                $unique[] = $field['databaseField']['name'];
            } else {
                $duplicated[$field['databaseField']['name']] = $field['databaseField']['name'];
            }
        }
        if (count($duplicated)) {
            foreach ($duplicated as $field) {
                $this->result->addError(new Error(sprintf('The %s field are duplicated in flexform, you must fix it.', $field), self::DUPLICATE_FIELD, array($field), $field));
            }
        }
    }

    /**
     * Check for fields with setAsUsername check enable, raise error if more than one
     */
    private function testForUniqueUsernameField() {
        $setAsUsername = array();
        foreach ($this->settings['fields'] as $field) {
            if ($field['databaseField']['useAsUsername']) {
                $setAsUsername[] = $field['databaseField'];
            }
        }
        if (count($setAsUsername) > 1) {
            $this->result->addError(new Error(sprintf('This fields %s are set as username but only one can be username field, you must fix it.', implode(',', $setAsUsername['name'])), self::DUPLICATE_USERNAME_FIELD, array(implode(',', $setAsUsername['name'])), implode(',', $setAsUsername['name'])));
        }
        elseif(count($setAsUsername) == 0){
            $this->result->addError(new Error(sprintf('Miss username field in flexform, you must fix it.'), self::USERNAME_FIELD_IS_MISSED, array(), ''));
        }
        else{
            $this->verifyUniquenessForUsernameField($setAsUsername[0]);
        }
    }

    private function verifyUniquenessForUsernameField($setAsUsername) {
        if(!GeneralUtility::inList($setAsUsername['validators'],'TYPO3\\T3registration\\Validator\\UniqueValidator')){
            $this->result->addError(new Error(sprintf('This fields %s are set as username but without unique validator, you must fix it.', $setAsUsername['name']), self::USERNAME_MISS_UNIQUENESS, array($setAsUsername['name']), $setAsUsername['name']));
        }
    }
}