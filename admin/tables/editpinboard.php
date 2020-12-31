<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

class Tableeditpinboard extends JTable
{
	/** @var int Primary key */
	var $config_id					= 0;
	/** @var string */
	var $config_name				= '';
	/** @var string */
	var $config_desc	  		= '';
	/** @var string */
	var $published		= '1';
	/** @var string */
	//var $updated		= '';
	/** @var string */
	//var $items			= '';
	/** @var string */
	//var $space = '';
	/** @var string */	
	var $config_community = '0';

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function Tableeditpinboard(& $db) {
		
		$table	= '#__realpin_settings';
		
		$db =& JFactory::getDBO();
		
		parent::__construct($table, 'config_id', $db);
	}
}
?>
