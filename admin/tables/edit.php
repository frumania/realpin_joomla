<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

class Tableedit extends JTable
{
	/** @var int Primary key */
	var $id					= 0;
	/** @var string */
	var $text				= '';
	/** @var string */
	var $title	  		= '';
	/** @var string */
	var $author		= '';
	/** @var string */
	var $url		= '';
	/** @var int */
	var $published			= 0;
	/** @var string */
	var $created = '';
	/** @var int */
	var $sticky = 0;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(&$db){
	  parent::__construct('#__realpin_items', 'id', $db);
	}

	function Tableedit(& $db) {
		
		$table	= '#__realpin_items';
		
		$db =& JFactory::getDBO();
		
		parent::__construct($table, 'id', $db);
	}
}
?>
