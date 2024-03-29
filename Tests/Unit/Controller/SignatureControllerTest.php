<?php
namespace Velletti\Mailsignature\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Jörg Velletti <typo3@velletti.de>, Jörg Velletti EDV Service
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Velletti\Mailsignature\Controller\SignatureController.
 *
 * @author Jörg Velletti <typo3@velletti.de>
 */
class SignatureControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{

	/**
	 * @var \Velletti\Mailsignature\Controller\SignatureController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock(\Velletti\Mailsignature\Controller\SignatureController::class, array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

}
