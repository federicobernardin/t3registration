<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Registration'
);

$pluginSignature = str_replace('_','',$_EXTKEY) . '_pi1';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi1.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'T3Registration');

$tmp_t3registration_columns = array(

	'name' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:t3registration/Resources/Private/Language/locallang_db.xlf:tx_t3registration_domain_model_user.name',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim,required'
		),
	),
);

t3lib_extMgm::addTCAcolumns('fe_users',$tmp_t3registration_columns);

$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:t3registration/Resources/Private/Language/locallang_db.xlf:fe_users.tx_extbase_type.Tx_T3registration_User','Tx_T3registration_User');

$TCA['fe_users']['types']['Tx_T3registration_User']['showitem'] = $TCA['fe_users']['types']['1']['showitem'];
$TCA['fe_users']['types']['Tx_T3registration_User']['showitem'] .= ',--div--;LLL:EXT:t3registration/Resources/Private/Language/locallang_db.xlf:tx_t3registration_domain_model_user,';
$TCA['fe_users']['types']['Tx_T3registration_User']['showitem'] .= 'name';

?>