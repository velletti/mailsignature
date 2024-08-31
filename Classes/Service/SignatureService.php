<?php
namespace Velletti\Mailsignature\Service;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Context\AspectInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidControllerNameException;
use TYPO3\CMS\Extbase\Service\ExtensionService;
use TYPO3\CMS\Fluid\View\StandaloneView;
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
class SignatureService extends ExtensionService
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

    /**
     *
     * Injects a  repository.
     *
     * @param  SignatureRepository $signatureRepository
     *
     * @return void
     *
     */
    public function injectSignatureRepository(SignatureRepository $signatureRepository) {

        $this->signatureRepository = $signatureRepository ;
    }

    /**
	 * action Initialize
	 *
	 * @return void
	 */
    public function initializeAction(){
        $this->extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('mailsignature');
        if (class_exists(ExtensionConfiguration::class)) {
            $this->extConf =
                GeneralUtility::makeInstance(ExtensionConfiguration::class)
                    ->get('mailsignature');
        } else {
            $this->extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('mailsignature');
        }
        $this->settings = $GLOBALS ['TSFE']->tmpl->setup ['plugin.'] ['tx_mailsignature.']['settings.'];

    }


    /**
     * getSignature will deliver Boolian false or an array with two values: plain and html signature
     * @param integer $signatureId UID of the Data record
     *
     * @param mixed $lng
     * @return mixed
     */
    public function getSignature($signatureId = 1 , $lng = NULL)
    {
        $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $cObj->start([] );
        $request = $GLOBALS['TYPO3_REQUEST'] ?? new \TYPO3\CMS\Core\Http\ServerRequest();
        $cObj->setRequest( $request );

        if( $signatureId == 0 ) {
            // if we get no ID take one from settings
            $signatureId = $this->settings['signatureId'] ;
        }
        if( $signatureId == 0 ) {
            // If no settings in Typoscript exists, use ID 1 as Default to be compatible with previous bahavior ..
            $signatureId = 1  ;
        }
        /**
         * @var ConnectionPool $connectionPool
         * @var QueryBuilder $queryBuilder
         */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_mailsignature_domain_model_signature');

        $queryBuilder->select('*')->from('tx_mailsignature_domain_model_signature') ;



        if( is_null( $lng ) ) {
            if (class_exists(Context::class)) {
                /** @var AspectInterface $languageAspect */
                $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language') ;
                // (previously known as TSFE->sys_language_uid)
                $lng = $languageAspect->getId() ;
            } else {
                $lng = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id') ;
            }
        }


        // Check, if signature exists in requested Language : if lng = 0  sys_language_uid = 0
        if( $lng == 0 ){
            $queryBuilder->where(
                $queryBuilder->expr()->eq( 'uid', $queryBuilder->createNamedParameter($signatureId, Connection::PARAM_INT) )
            )->andWhere(
                $queryBuilder->expr()->eq( 'l10n_parent', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT) )
            );

            // Other languages
        } else {
            $queryBuilder->where(
                $queryBuilder->expr()->eq( 'l10n_parent', $queryBuilder->createNamedParameter($signatureId, Connection::PARAM_INT) )
            )->andWhere(
                $queryBuilder->expr()->eq( 'sys_language_uid', $queryBuilder->createNamedParameter($lng, Connection::PARAM_INT) )
            );

        }

        $result = $queryBuilder->executeQuery()->fetchAssociative();

        if(empty($result)){
            return array( "htlm" => '' , "plain" => ''  );
        }

        $row['html'] = $cObj->parseFunc($result['html'], array(), '< lib.parseFunc_RTE');
        $row['plain'] = strip_tags($result['plain']);
        return $row;
    }

    /**
     * service sentHTMLmail
     * @param array $params
     *        parameter : ['message']  fitst line is Subject with following lines as text
     *                    ['user']['email'] = the email of the User that should get the Email
     *
     *                   ['signatureId'] = if used with different Etension, you can send ID of signature
     *                   ['sendHtmlTemplate'] = full path to a template File /typo3conf/ext/mailsignature/Resources/Private/Templates/Email/Default
     *
     *
     * @param mixed $feloginThis
     *
     * @return array
     * @throws InvalidActionNameException
     * @throws InvalidControllerNameException
     */
    public function sentHTMLmailService($params , $feloginThis = false )
    {
        $this->initializeAction() ;
        if( key_exists("signatureId" , $params ) && $params['signatureId'] ) {
            $signatureId = $params['signatureId'] ;
        } else {
            $signatureId = $this->settings['forgotPassword.']['addSignature'] ;
        }

        if( key_exists("sendHtmlTemplate" , $params ) && $params['sendHtmlTemplate'] ) {
            $template = $params['sendHtmlTemplate'] ;
        } else {
            $template = $this->settings['forgotPassword.']['sendHtmlTemplate'] ;
        }
        if ($template == '') {
            $template = 'EXT:mailsignature/Resources/Private/Templates/Email/Default.html' ;
        }


        if( key_exists("email_from" , $params) && $params['email_from']) {
            $fromEmail = $params['email_from'];
        } else {
            $fromEmail  = $feloginThis->conf ['email_from'] ;
        }

        if( key_exists("email_fromName" , $params) && $params['email_fromName']) {
            $fromEmailName = $params['email_fromName'];
        } else {
            $fromEmailName  = $feloginThis->conf ['email_fromName'] ;
        }

        if( key_exists("sendCCmail" , $params )  ) {
            $sendCCmail = $params['sendCCmail'] ;
        } else {
            $sendCCmail = $this->settings['forgotPassword.']['sendCCmail'] ;
        }

        $messArr = explode( "http" , $params['message'] , 2) ;
        $htmlMessage = '' ;
        if( is_array($messArr)) {
            $newMess = $messArr[0] ;
            $messArr2 = explode("\n" , $messArr[1] ) ;
            if( is_array($messArr2)) {


                $newMess .= "<div  style=\"max-width:450px ; overflow: hidden; margin: 0 auto; text-align: center;\"> \n<a style=\"font-size: 90%; word-wrap: break-word\" href=\"http"  . $messArr2[0] . "\" > "
                    . "http" . $messArr2[0] ."</a></div>" ;
                foreach($messArr2 as $key => $value ) {
                    if( $key > 0) {
                        $newMess .= "\n" . $value ;
                    }
                }
                $htmlMessage = $newMess ;
            }
        } else {
            $htmlMessage = $params['message'] ;
        }
        $messageParts = explode("\n", $htmlMessage, 2);
        $htmlMessage = "<h2>" . trim($messageParts[0]) . "</h2><br>" .  trim($messageParts[1]) ;


       // $signature = $this->getSignature( $signatureId  ) ;

        // use FLUID to render the Template

        /** @var $renderer  StandaloneView */
        $renderer = GeneralUtility::makeInstance(StandaloneView::class);

        $renderer->setFormat("html");
        $renderer->setTemplatePathAndFilename($template);
        $renderer->assign('settings', $this->settings );
        $renderer->assign('params', $params );
        $renderer->assign('message', nl2br( $htmlMessage ) );
        $renderer->assign('signature', $signature['html'] );
        $renderer->assign('server',  GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'));


        $htmlMessage = $renderer->render();

        $plainMessage = strip_tags($params['message']) . "\n\n" .   $signature['plain']  ;
        $success = false;
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $message = trim($plainMessage);
        $senderName = trim($fromEmail);
        $senderAddress = trim($sendCCmail);
        if ($senderAddress !== '') {
            $mail->from(new Address($senderAddress, $senderName));
        }
        $parsedReplyTo = MailUtility::parseAddresses($fromEmail);
        if (!empty($parsedReplyTo)) {
            $mail->setReplyTo($parsedReplyTo);
        }
        if ($message !== '') {
            $messageParts = explode(LF, $message, 2);
            $subject = trim($messageParts[0]);
            if( array_key_exists("user" , $params) && array_key_exists("email" , $params["user"])) {
                $parsedRecipients[] = $params["user"]["email"] ;
            } else {
                $parsedRecipients = MailUtility::parseAddresses($htmlMessage);
            }
            $plainMessage = trim($messageParts[1]);
            if (!empty($parsedRecipients)) {
                $mail->to(...$parsedRecipients)->subject($subject)->text($plainMessage);
                $mail->send();
            }
            $success = true;
        }

        $success ;

        // j.v.: now remove Email from Array so the default plain Email is not set out anymore
        unset( $params['user']['email'] ) ;
        return $params ;
    }

    /**
     * Sends a notification email
     *
     * @param string $message The message content. If blank, no email is sent.
     * @param string $htmlMessage The message content in HTML Format using Template
     * @param string $recipients Comma list of recipient email addresses
     * @param string $cc Email address of recipient of an extra mail. The same mail will be sent ONCE more; not using a CC header but sending twice.
     * @param string $senderAddress "From" email address
     * @param string $senderName Optional "From" name
     * @param string $replyTo Optional "Reply-To" header email address.
     * @return bool Returns TRUE if sent
     */
    public function sendNotifyEmail($message, $htmlMessage , $recipients, $cc, $senderAddress, $senderName = '', $replyTo = '')
    {
       
        $senderName = trim($senderName);
        $senderAddress = trim($senderAddress);
        if ($senderName !== '' && $senderAddress !== '') {
            $sender = array($senderAddress => $senderName);
        } elseif ($senderAddress !== '') {
            $sender = array($senderAddress);
        } else {
            $sender = MailUtility::getSystemFrom();
        }
        /** @var MailMessage  $mail */
        $mail = GeneralUtility::makeInstance( MailMessage::class );

        $mail->setFrom($sender);
        $parsedReplyTo = MailUtility::parseAddresses($replyTo);

        if (!empty($parsedReplyTo)) {
            $mail->setReplyTo($parsedReplyTo);
        }
        /** @var Typo3Version $tt */
        $tt = GeneralUtility::makeInstance( Typo3Version::class ) ;


        $message = trim($message);
        if ($message !== '') {
            // First line is subject
            $messageParts = explode(LF, $message, 2);
            $subject = trim($messageParts[0]);
            $plainMessage = trim($messageParts[1]);

            $parsedRecipients = MailUtility::parseAddresses($recipients);
            $parsedRecipients = $parsedRecipients[0] ;



            if (!empty($parsedRecipients)) {
                $mail->setTo($parsedRecipients)
                    ->setSubject($subject)
                    ->setReplyTo($replyTo) ;
                $mail->html( $htmlMessage  , 'utf-8'  );
                $mail->text( $plainMessage  , 'utf-8'  );
                if (GeneralUtility::validEmail($parsedRecipients) ) {
                    $mail->send();
                }
            }

            $parsedCc = MailUtility::parseAddresses($cc);
            $parsedCc = $parsedCc[0] ;
            if (!empty($parsedCc)) {
                if (!empty($parsedReplyTo)) {
                    $mail->setReplyTo($parsedReplyTo);
                }
                $mail->setFrom($sender )
                    ->setTo($parsedCc)
                    ->setReplyTo($replyTo)
                    ->setSubject( "CC: " . $subject . " -> " . $parsedRecipients );

                $mail->html( $htmlMessage  , 'utf-8'  );
                $mail->text( $plainMessage  , 'utf-8'  );
                if (GeneralUtility::validEmail($parsedCc) ) {
                    $mail->send();
                }
            }
            return true;
        }
        return false;
    }

}