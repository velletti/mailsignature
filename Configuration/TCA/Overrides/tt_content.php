<?php
defined('TYPO3') or die();
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mailsignature_domain_model_signature');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Mailsignature', 'Mailsignature', 'mailsignature');
