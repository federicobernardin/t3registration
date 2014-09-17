<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'Pi1',
	array(
		'Registration' => 'new, update, preview, delete, create, edit,accessDenied',

	),
	// non-cacheable actions
	array(
		'Registration' => 'new, update, preview, delete, create, edit,accessDenied',

	)
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['t3registration'] =
    'EXT:t3registration/Classes/Cache/CacheBuilder.php:TYPO3\T3registration\Cache\CacheBuilder->build';


\TYPO3\T3registration\Utility\ValidatorUtility::addValidator('TYPO3\\T3registration\\Validator\\IntegerValidator');
\TYPO3\T3registration\Utility\ValidatorUtility::addValidator('TYPO3\\T3registration\\Validator\\DateValidator');
\TYPO3\T3registration\Utility\ValidatorUtility::addValidator('TYPO3\\T3registration\\Validator\\StringValidator');
\TYPO3\T3registration\Utility\ValidatorUtility::addValidator('TYPO3\\T3registration\\Validator\\RequiredValidator');
\TYPO3\T3registration\Utility\ValidatorUtility::addValidator('TYPO3\\T3registration\\Validator\\RegexpValidator');
\TYPO3\T3registration\Utility\ValidatorUtility::addValidator('TYPO3\\T3registration\\Validator\\UniqueValidator');
\TYPO3\T3registration\Utility\ValidatorUtility::addValidator('TYPO3\\T3registration\\Validator\\UniqueInPidValidator');


\TYPO3\T3registration\Utility\DeciderUtility::addDecider('TYPO3\\T3registration\\Decider\\UpdaterDecider\\DummyUpdaterDecider');

//require_once(PATH_site . 'typo3temp/Cache/Code/cache_phpcode/user.php');
?>