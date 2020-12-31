<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class startModelglobal extends JModelLegacy
{
	
	function _getSettingsID( $id)
	{
	
		$table = '#__realpin_settings';

		$query = "SELECT * FROM ".$table." WHERE config_id='".$id."' ";

		return $query;
	}
	
	function _getSettingsName( $name )
	{
	
		$table = '#__realpin_settings';

		$query = "SELECT * FROM ".$table." WHERE config_name='".$name."' ";

		return $query;
	}
	
	function getSettingsIDList($id)
	{
		$db		= JFactory::getDBO();
		$query	= $this->_getSettingsID($id);
		$db->setQuery($query);
        $result= $db->loadAssoc();

		return @$result;
	}
	
	function getSettingsNameList( $name )
	{
		$db		= JFactory::getDBO();
		$query	= $this->_getSettingsName( $name );
		$db->setQuery($query);
        $result= $db->loadAssoc();

		return @$result;
	}

			

}
?>
