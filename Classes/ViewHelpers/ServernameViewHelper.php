<?php
namespace Velletti\Mailsignature\ViewHelpers;
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
 * <mailsig:servername/>
 * </code>
 */

class ServernameViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments()
	{
        $this->registerArgument('format', 'string', 'The wanted Environment Value from getIndEnv() ', false , "TYPO3_REQUEST_HOST"  );
	}
	/**
	 * Render a special sign if the field is required
	 * @return string
	 */
	public function render() {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv($this->arguments['format']) ;
	}
}
