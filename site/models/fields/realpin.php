<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldrealpin extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'realpin';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 	
	
	<state>
		<name>REALPIN_MENU_CATEGORY_LIST_LAYOUT</name>
		<description>REALPIN_MENU_CATEGORY_LIST_LAYOUT_DESC</description>
		<url>
			<param name="pinboard" type="sql" default="0" label="REALPIN_MENU_CATEGORY_LIST_LABEL1" description="REALPIN_MENU_CATEGORY_LIST_LABEL1_DESC" query="SELECT config_id, config_name FROM #__realpin_settings WHERE published = 1 AND config_community = 0 AND config_name NOT LIKE 'default_private' AND config_name NOT LIKE 'default_private_global' ORDER BY config_name ASC" key_field="config_id" value_field="config_name"  />
		</url>
		<params>

		</params>
	</state>
	 
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$db->setQuery("SELECT config_id, config_name FROM #__realpin_settings WHERE published = 1 AND config_community = 0 AND config_name NOT LIKE 'default_private' AND config_name NOT LIKE 'default_private_global' ORDER BY config_name ASC");
		$messages = $db->loadObjectList();
		$options = array();
		if ($messages)
		{
			foreach($messages as $message) 
			{
				$options[] = JHtml::_('select.option', $message->config_id, $message->config_name);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}