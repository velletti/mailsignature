<?php
if (!defined('TYPO3')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Mailsignature' ,
	'Mailsignature',
	array(
		\Velletti\Mailsignature\Controller\SignatureController::class => 'addSignature, getSignature, sentHTMLmail',
		
	),
	// non-cacheable actions
	array(
		\Velletti\Mailsignature\Controller\SignatureController::class => '',
		
	)
);

// use hook to change the Email before Send out
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['forgotPasswordMail'][] = "Velletti\Mailsignature\Service\SignatureService->sentHTMLmailService" ;