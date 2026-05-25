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
		
	),
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
