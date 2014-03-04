<?php
//require_once(PATH_site . 'typo3temp/Cache/Code/cache_phpcode/user.php');
$pippo = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3registration') . 'Classes/Cache/CacheBuilder.php';
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3registration') . 'Classes/Cache/CacheBuilder.php');
$cacheBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\T3registration\Cache\CacheBuilder');
return $cacheBuilder->build();
?>