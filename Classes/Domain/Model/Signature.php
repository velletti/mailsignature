<?php
namespace Velletti\Mailsignature\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

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
 * Signature
 */
class Signature extends AbstractValueObject
{

    /**
     * name
     *
     * @var string
     */
    protected string $name = '';
    
    /**
     * html
     *
     * @var string
     */
    protected string $html = '';
    
    /**
     * plain
     *
     * @var string
     */
    protected string $plain = '';
    
    /**
     * Returns the html
     *
     * @return string $html
     */
    public function getHtml(): string
    {
        return $this->html;
    }
    
    /**
     * Sets the html
     *
     * @param string $html
     * @return void
     */
    public function setHtml(string $html): void
    {
        $this->html = $html;
    }
    
    /**
     * Returns the plain
     *
     * @return string $plain
     */
    public function getPlain(): string
    {
        return $this->plain;
    }
    
    /**
     * Sets the plain
     *
     * @param string $plain
     * @return void
     */
    public function setPlain(string $plain): void
    {
        $this->plain = $plain;
    }
    
    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}