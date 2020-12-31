<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class realpinModelrpdefault extends JModelLegacy
{	
    function getData()
	{

		$db			= JFactory::getDBO();
		
		$query="SELECT * FROM ".RP_TABLE." WHERE pinboard='".PINBOARD."' AND (sticky='1' OR created>=DATE_ADD(NOW(), INTERVAL -".LIFETIME." DAY)) AND published='1' ORDER BY sticky DESC, created DESC";

		$db->setQuery( $query);
		$result = $db->loadObjectList();

		return $result;
	}
	
	function getTotal()
	{

		$db			= JFactory::getDBO();
		
		$query="SELECT * FROM ".RP_TABLE." WHERE pinboard='".PINBOARD."' AND (sticky='1' OR created>=DATE_ADD(NOW(), INTERVAL -".LIFETIME." DAY)) AND published='1' ORDER BY sticky DESC, created DESC";

		$db->setQuery( $query);
		$db->query();
		$result = $db->getNumRows();

		return $result;
	}
	
	function _getSettings()
	{
		$query = "SELECT * FROM ".RP_SETTINGS_TABLE." WHERE config_id='".PINBOARD."' ";

		return $query;
	}
	
	function _getGlobalSettings()
	{
		$query = "SELECT * FROM ".RP_SETTINGS_TABLE." WHERE config_name='default_".CHECK."_global' ";

		return $query;
	}
	
	function _getDefaultSettings()
	{
		$query = "SELECT * FROM ".RP_SETTINGS_TABLE." WHERE config_name='default_".CHECK."' ";

		return $query;
	}
	
	function getSettingsList()
	{
		$db		= JFactory::getDBO();
		$query	= $this->_getSettings();
		$db->setQuery($query);
        $result= $db->loadAssoc();

		return @$result;
	}
	
	function getGlobalSettingsList()
	{
		$db		= JFactory::getDBO();
		$query	= $this->_getGlobalSettings();
		$db->setQuery($query);
        $result= $db->loadAssoc();

		return @$result;
	}
	
	function getDefaultSettingsList()
	{
		$db		= JFactory::getDBO();
		$query	= $this->_getDefaultSettings();
		$db->setQuery($query);
        $result= $db->loadAssoc();

		return @$result;
	}
}
?>