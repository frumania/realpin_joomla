<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class startModelstart extends JModelLegacy
{
	function _buildQuery()
	{
		
		$table	= '#__realpin_items';
		
		$query = ' SELECT * FROM '.$table.'';

		return $query;
	}

	/**
	 * Retrieves the hello data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}

		return $this->_data;
	}
	
		function _getSettings( &$options )
	{

		$db			= JFactory::getDBO();
		$table	= '#__realpin_settings';

		$query = "SELECT * FROM ".$table." WHERE config_name='default_private'";

		return $query;
	}
	

	
	function getSettingsList( $options=array() )
	{
		$query	= $this->_getSettings( $options );
		$db		= JFactory::getDBO();
		$db->setQuery($query);
        $result= $db->loadAssoc();

		return @$result;
	}
	
			

}
?>
