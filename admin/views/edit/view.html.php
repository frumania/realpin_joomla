<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class startViewedit extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		$edit		= $this->get('Data');
		$rpname=JRequest::getVar( 'rpname' , '', 'REQUEST');
		$pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
		
		$model	  = $this->getModel();
		$settings     = $model->getSettingsList();
		
		$isNew		= ($edit->id < 1);

		$text = $isNew ? JText::_( 'LANG_NEW' ) : JText::_( 'LANG_EDIT' );
		JToolBarHelper::title($text);
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', JText::_('LANG_CLOSE') );
		}
		
		$this->assignRef('rpname',		$rpname);
		$this->assignRef('edit',		$edit);
		$this->assignRef('pinboard',		$pinboard);
		$this->assignRef('community',		$community);
		$this->assignRef('settings',		$settings);

		parent::display($tpl);
	}
}
