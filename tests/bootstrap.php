<?php
define('TYPO3_MODE', 'FE');
//print(__DIR__ . '/../../../../');
//exit;
include_once(__DIR__ . '/../../../../typo3/sysext/core/Classes/Core/Bootstrap.php');
\TYPO3\CMS\Core\Core\Bootstrap::getInstance()
    ->baseSetup('typo3conf/ext/t3registration/Tests/')
    ->startOutputBuffering()
    ->loadConfigurationAndInitialize()
    ->loadTypo3LoadedExtAndExtLocalconf(TRUE)
    ->applyAdditionalConfigurationSettings();
?>