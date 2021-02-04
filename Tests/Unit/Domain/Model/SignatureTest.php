<?php

namespace Velletti\Mailsignature\Tests\Unit\Domain\Model;

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
 * Test case for class \Velletti\Mailsignature\Domain\Model\Signature.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Jörg Velletti <typo3@velletti.de>
 */
class SignatureTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \Velletti\Mailsignature\Domain\Model\Signature
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \Velletti\Mailsignature\Domain\Model\Signature();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getNameReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getName()
		);
	}

	/**
	 * @test
	 */
	public function setNameForStringSetsName()
	{
		$this->subject->setName('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'name',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getHtmlReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getHtml()
		);
	}

	/**
	 * @test
	 */
	public function setHtmlForStringSetsHtml()
	{
		$this->subject->setHtml('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'html',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getPlainReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getPlain()
		);
	}

	/**
	 * @test
	 */
	public function setPlainForStringSetsPlain()
	{
		$this->subject->setPlain('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'plain',
			$this->subject
		);
	}
}
