<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class startViewstart extends JViewLegacy
{
	function assignRef($mystring, $param)
	{
		$this->{$mystring} = $param;
	}

	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'RealPin' ), 'generic.png' );

		$model = $this->getModel();
		$settings=$model->getSettingsList();
		$this->assignRef('items',$settings);
		$pin_data     = $model->getData();
		$this->assignRef('pin_data'  , $pin_data);

		parent::display($tpl);
	}
}
