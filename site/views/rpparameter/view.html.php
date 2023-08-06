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
class realpinViewrpparameter extends JViewLegacy
{
	function assignRef($mystring, &$param)
	{
		$this->{$mystring} = $param;
	}
	
function display($tpl = null)
	{
		$rpname=JFactory::getApplication()->input->get( 'rpname' , 'global', 'REQUEST');
		//JToolBarHelper::title(JText::_( 'RealPin: ').JText::_('LANG_BUTTON3')." (".$rpname.")", 'generic.png' );
		//JToolBarHelper::save();
		//JToolBarHelper::cancel( 'cancel', 'Close' );
		//JToolBarHelper::custom( 'makeDefault', 'default.png', 'icon over', 'Default', false, false );
		
		$community=JFactory::getApplication()->input->get( 'community', '', 'REQUEST');
		$pinboard=JFactory::getApplication()->input->get( 'pinboard', 5, 'REQUEST');
		$isglobal=JFactory::getApplication()->input->get( 'global', 'true', 'REQUEST');
		$model = $this->getModel();
	
		if($community=="1")
		{		
		$global=$model->getSettingsNameList("default_community_global");
		if($isglobal=='true'){$settings=$model->getSettingsNameList("default_community");$pinboard="3";}else{$settings=$model->getSettingsIDList($pinboard);}
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
