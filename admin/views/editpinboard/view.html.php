<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class startVieweditpinboard extends JViewLegacy
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function assignRef($mystring, &$param)
	{
		$this->{$mystring} = $param;
	}

	function display($tpl = null)
	{
		$edit		= $this->get('Data');
		
		$isNew		= ($edit->config_id < 1);

		$text = $isNew ? JText::_( 'LANG_NEW' ) : JText::_( 'LANG_EDIT' );
		JToolBarHelper::title($text);
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', JText::_('LANG_CLOSE') );
		}
		
		$this->assignRef('edit',		$edit);

		parent::display($tpl);
	}
}
