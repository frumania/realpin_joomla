<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

class startController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		parent::display();

		return $this;

	}

}
?>
