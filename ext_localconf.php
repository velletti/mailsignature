<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Velletti.' . $_EXTKEY,
	'Mailsignature',
	array(
		'Signature' => 'addSignature, getSignature, sentHTMLmail',
		
	),
	// non-cacheable actions
	array(
		'Signature' => '',
		
	)
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

// use hook to change the Email before Send out
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['forgotPasswordMail'][] = "Velletti\Mailsignature\Service\SignatureService->sentHTMLmailService" ;