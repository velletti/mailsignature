<?php
defined('TYPO3_MODE') or die();
$_EXTKEY = 'mailsignature' ;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('mailsignature', 'Configuration/TypoScript', 'Mail Signature Service');
