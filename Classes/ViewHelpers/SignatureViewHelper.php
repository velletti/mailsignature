<?php
namespace Velletti\Mailsignature\ViewHelpers;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 * Copyright notice
 *
 * (c) 2011-13 Sebastian Fischer <typo3@evoweb.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Viewhelper to render a selectbox with values
 * in given steps from start to end value
 *
 * <code title="Usage">
 * {namespace mailsig=\\Veleltti\\Mailsignature\\ViewHelpers}
 * <mailsig:signature/>
 * </code>
 */
class SignatureViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * Initialize arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('type', 'string', 'signature Type = html = default or plain ', false , "html"  );
        $this->registerArgument('signatureId', 'integer', 'The wanted Environment Value from getIndEnv() ', false , 1  );
    }


	/**
	 * Render a special sign if the field is required
	 * @return string
	 */
	public function render() {
        $type = $this->arguments['type'] ;
        $signatureId = $this->arguments['signatureId'] ;

		$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

        $languageAspect = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getAspect('language') ;
        // (previously known as TSFE->sys_language_uid)
        $lng = $languageAspect->getId() ;

        /**
         * @var \TYPO3\CMS\Core\Database\ConnectionPool
         */
        $connectionPool = GeneralUtility::makeInstance( ConnectionPool::class);
        /** @var  QueryBuilder $queryBuilder */
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_mailsignature_domain_model_signature' );

        $queryBuilder->select('*')->from('tx_mailsignature_domain_model_signature')
            ->where( $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter( intval($lng) , Connection::PARAM_INT )) )
        ;
        if(intval($lng ) > 0){
            $queryBuilder->andWhere( $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter( intval($signatureId) , Connection::PARAM_INT )) ) ;
        } else {
            $queryBuilder->andWhere( $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter( intval($signatureId) , Connection::PARAM_INT )) ) ;
        }

        $row = $queryBuilder->execute()->fetch() ;

        $row['html'] = $cObj->parseFunc($row['html'], array(), '< lib.parseFunc_RTE');
        $row['plain'] = strip_tags($row['plain']);


		return $row[$type];
	}
}
