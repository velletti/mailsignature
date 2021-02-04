<?php
defined('TYPO3_MODE') or die();
$_EXTKEY = 'mailsignature' ;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Mail Signature Service');
