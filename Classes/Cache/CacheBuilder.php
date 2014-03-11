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


namespace TYPO3\T3registration\Cache;


use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class CacheBuilder
 * This class prepare the general Frontend User class extending all extension files
 *
 * @author Federico Bernardin <federico@bernardin.it>
 *
 * @package TYPO3\T3registration\Cache
 */
class CacheBuilder {

    /**
     * Frontend user cached file
     */
    const CACHE_FILE_LOCATION = 'typo3temp/Cache/Code/cache_phpcode/';

    /**
     * @var array list of class properties
     */
    private $classProperties = array();

    /**
     * @var array list of excluded properties name
     */
    private $usersClassExcludeProperties;

    /**
     * Fills the usersClassExcludeProperties with correct fields
     */
    public function __construct(){
        $this->usersClassExcludeProperties = array(
            'starttime',
            'endtime',
            'TSConfig',
            'txExtbaseType',
            'feloginRedirectPid',
            'feloginForgotHash',
            'TSconfig'
        );
    }

    /**
     * Generate the file and return the array suitable to ext_autoload.php
     * @return array suitable array to ext_autoload.php
     */
    public function build() {
        $this->getT3RegistrationExtensions();
        return $this->createTemporaryClass($this->classProperties);
    }

    /**
     * Get all loaded extensions which try to extend Frontend user class
     *
     * @return array
     */
    private function getT3RegistrationExtensions() {
        $loadedExtensions = ExtensionManagementUtility::getLoadedExtensionListArray();

        // Get the extensions which want to extend news
        $this->classProperties = array();
        foreach ($loadedExtensions as $extensionKey) {
            $extensionInfoFile = ExtensionManagementUtility::extPath($extensionKey, 't3registration_extension.php');
            if (file_exists($extensionInfoFile)) {
                $newFieldsList = include($extensionInfoFile);
                if(is_array($newFieldsList) && count($newFieldsList)){
                    $this->classProperties = array_merge($this->classProperties,$newFieldsList);
                }
            }
        }
        return $this->classProperties;
    }

    /**
     * Translates a string with underscores
     * into camel case (e.g. first_name -> firstName)
     *
     * @param string $str String in underscore format
     * @param bool $capitalise_first_char If true, capitalise the first char in $str
     * @return string $str translated into camel caps
     */
    private function to_camel_case($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     * Generates the final class for Frontend user model and saves it to cache directory
     * @param array $columns list of properties extending model
     * @return array
     */
    private function createTemporaryClass($columns) {
        $variables = array();
        $gets = array();
        $sets = array();
        foreach ($columns as $name => $type) {
            $name = $this->to_camel_case($name);
            if (!in_array($name, $this->usersClassExcludeProperties)) {
                $gets[] = 'public function get' . ucfirst($name) . '(){ return $this->' . $name . ';}';
                $sets[] = 'public function set' . ucfirst($name) . '($' . $name . '){ $this->' . $name . ' = $' . $name . '; return $this;}';
                $variables[] = "/** @var " . $type . " */\nprotected $" . $name . ';';
            }

        }

        $classSource = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3registration') . '/Resources/Private/PHPFile/UserBaseModel.txt');

        $code = implode("\n", $variables);
        $code .= "\n" . implode("\n", $gets);
        $code .= "\n" . implode("\n", $sets);

        $code = str_replace('}', $code . "\n}", $classSource);
        file_put_contents(PATH_site . self::CACHE_FILE_LOCATION . 'User.php', $code);
        return array('typo3\t3registration\domain\model\user' => PATH_site . self::CACHE_FILE_LOCATION . 'User.php');
    }

}