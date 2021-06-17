<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Velletti.mailsignature' ,
	'Mailsignature',
	array(
		'Signature' => 'addSignature, getSignature, sentHTMLmail',
		
	),
	// non-cacheable actions
	array(
		'Signature' => '',
		
	)
);

// use hook to change the Email before Send out
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['forgotPasswordMail'][] = "Velletti\Mailsignature\Service\SignatureService->sentHTMLmailService" ;