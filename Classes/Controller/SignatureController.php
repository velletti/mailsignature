<?php
namespace Velletti\Mailsignature\Controller;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Velletti\Mailsignature\Domain\Repository\SignatureRepository;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Jörg Velletti <typo3@velletti.de>, Jörg Velletti EDV Service
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * SignatureController
 */
class SignatureController extends ActionController
{

    /**
     * signatureRepository
     *
     * @var SignatureRepository
     */
    protected $signatureRepository = NULL;

    /**
     * settings
     *
     * @var array
     */
    public $settings ;

    /**
     * Extension configuration
     *
     * @var	array
     */
    private $extConf = array();


    /*
	 * Initialize
	 *
	 * @param none
	 * @return void
	 */
    public function initializeAction(){
        $this->extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)
                    ->get('mailsignature');
        $this->settings = $GLOBALS ['TSFE']->tmpl->setup ['plugin.'] ['tx_mailsignature.']['settings.'];

    }

    public function injectSignatureRepository(SignatureRepository $signatureRepository): void
    {
        $this->signatureRepository = $signatureRepository;
    }



}