<?php


namespace TYPO3\T3registration\Cache;


use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class CacheBuilder {

    const CACHE_FILE_LOCATION = 'typo3temp/Cache/Code/cache_phpcode/';

    private $usersClassExcludeProperties;

    private $classProperties = array();

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

    private function includeLibrary(){
        require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extbase') . 'Classes/Persistence/ObjectMonitoringInterface.php');
        require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extbase') . 'Classes/DomainObject/DomainObjectInterface.php');
        require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extbase') . 'Classes/DomainObject/AbstractDomainObject.php');
        require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extbase') . 'Classes/DomainObject/AbstractEntity.php');
        require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extbase') . 'Classes/Domain/Model/FrontendUser.php');
    }



    public function build() {
        $this->getT3RegistrationExtensions();
        return $this->temp($this->classProperties);
    }

    /**
     * Get all loaded extensions which try to extend EXT:news
     *
     * @return array
     */
    protected function getT3RegistrationExtensions() {
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
    function to_camel_case($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     * @param $columns
     * @param $gets
     * @param $sets
     * @param $variables
     * @return array
     */
    private function temp($columns) {
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
        //file_put_contents(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3registration') . '/Classes/Domain/Model/User.php', $code);
        file_put_contents(PATH_site . self::CACHE_FILE_LOCATION . 'User.php', $code);
        return array('typo3\t3registration\domain\model\user' => PATH_site . self::CACHE_FILE_LOCATION . 'User.php');
    }

}