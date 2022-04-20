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
class startViewpinboards extends JViewLegacy
{
	function assignRef($mystring, $param)
	{
		$this->{$mystring} = $param;
	}
	
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$community = JFactory::getApplication()->input->get('community',0,'REQUEST');
		
		JToolBarHelper::title(JText::_( 'RealPin: ').JText::_('LANG_BUTTON4'), 'generic.png' );
		
		if(version_compare(JVERSION,'1.6.0','ge')) {
		// Joomla! 1.6 code here
				if($community==0){JToolBarHelper::custom( 'displayprivate', 'options', 'icon over', JText::_('LANG_BUTTON5'), false, false );}
				if($community==1){JToolBarHelper::custom( 'displaycommunity', 'options', 'icon over', JText::_('LANG_BUTTON5'), false, false );}

		} else {
		// Joomla! 1.5 code here
				if($community==0){JToolBarHelper::custom( 'displayprivate', 'config', 'icon over', JText::_('LANG_BUTTON5'), false, false );}
				if($community==1){JToolBarHelper::custom( 'displaycommunity', 'config', 'icon over', JText::_('LANG_BUTTON5'), false, false );}
        }
	 
	 
	   	JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		//JToolBarHelper::editListX();
		if($community==0){JToolBarHelper::addNew();}
				
        global $option;
		
		$mainframe = JFactory::getApplication();
		
		$table	= '#__realpin_settings';	

        $uri = JUri::getInstance();
        $query = $uri->getQuery();
		$db					= JFactory::getDBO();
		$filter_order		= $mainframe->getUserStateFromRequest( $query."filter_order",		'filter_order',		'm.config_updated',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $query."filter_order_Dir",	'filter_order_Dir',	'DESC',		'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $query."filter_state",		'filter_state',		'',		'word' );
		$search				= $mainframe->getUserStateFromRequest( $query."search",			'search',			'',		'string' );
		$search				= strtolower( $search );

        $limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', 50, 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$where = array();
		
		$where[]='m.config_community = '.$community.'';

		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 'm.published = 1';
			}
			else if ($filter_state == 'U' )
			{
				$where[] = 'm.published = 0';
			}
		}
		if ($search)
		{
			$where[] = 'LOWER(m.config_name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
			.' OR LOWER(m.config_desc) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$orderby 	= ' ORDER BY config_community ASC,'. $filter_order .' '. $filter_order_Dir;

		$query = 'SELECT COUNT(m.config_id) FROM '.$table.' AS m'. $where;
		$db->setQuery( $query );
		$total = $db->loadResult();
		
		$myquery = "test";

		jimport('joomla.html.pagination');
		
		$pagination = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT m.*'
		. ' FROM '.$table.' AS m'
		. $where
		. ' GROUP BY m.config_id'
		. $orderby
		;
		$db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $db->loadObjectList();
		
		try
		{
			$rows = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			$app->enqueueMessage(JText::_($e->getMessage()), 'error');
			return false;
		}

		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$user = JFactory::getUser();
        $this->assignRef('community',		$community);
		$this->assignRef('user', $user);
		$this->assignRef('lists',		$lists);
		$this->assignRef('pagination',	$pagination);
		
		$this->assignRef('myquery',	$myquery);

		$this->assignRef('items',		$rows);
				
		

		parent::display($tpl);
	}
}
