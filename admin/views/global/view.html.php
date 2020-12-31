<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class startViewglobal extends JViewLegacy
{
	
function display($tpl = null)
	{
		$rpname=JRequest::getVar( 'rpname' , 'global', 'REQUEST');
		JToolBarHelper::title(JText::_( 'RealPin: ').JText::_('LANG_BUTTON3')." (".$rpname.")", 'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel( 'cancel', JText::_('LANG_CLOSE') );
		//JToolBarHelper::custom( 'makeDefault', 'default.png', 'icon over', 'Default', false, false );
		
		$community=JRequest::getVar( 'community', '', 'REQUEST');
		$pinboard=JRequest::getVar( 'pinboard', '', 'REQUEST');
		$isglobal=JRequest::getVar( 'global', '', 'REQUEST');
		$model = $this->getModel();
	
		if($community=="1")
		{		
		$global=$model->getSettingsNameList("default_community_global");
		if($isglobal=='true'){$settings=$model->getSettingsNameList("default_community"); $pinboard="3";}else{$settings=$model->getSettingsIDList($pinboard);}
		}
		else
		{
		$global=$model->getSettingsNameList("default_private_global");
		if($isglobal=='true'){$settings=$model->getSettingsNameList("default_private");$pinboard="1";}else{$settings=$model->getSettingsIDList($pinboard);}
		}
		
		$this->assignRef('items',$settings);
		$this->assignRef('globalitems',$global);
		$this->assignRef('community',$community);
		$this->assignRef('pinboard',$pinboard);
		$this->assignRef('isglobal',$isglobal);

		parent::display($tpl);
    }
	
}
