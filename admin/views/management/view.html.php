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
class startViewmanagement extends JViewLegacy
{
	/**
	 * Hellos view display method
	 * @return void
	 **/

	function assignRef($mystring, &$param)
	{
		$this->{$mystring} = $param;
	}

	function display($tpl = null)
	{
		$rpname=JFactory::getApplication()->input->get( 'rpname' , '', 'REQUEST');
		JToolBarHelper::title(JText::_( 'RealPin: ').JText::_('LANG_BUTTON2')." (".$rpname.")", 'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		//JToolBarHelper::editList();
		JToolBarHelper::cancel( 'cancel', JText::_('LANG_CLOSE') );
	//	JToolBarHelper::addNewX();
	
	    $pinboard=JFactory::getApplication()->input->get( 'pinboard' , '', 'REQUEST');
		$community=JFactory::getApplication()->input->get( 'community' , '', 'REQUEST');
		
		if($community==1)
        {
		$check="community";
		}
		else
		{
		$check="private";
		}
		$table	= '#__realpin_items';

        global $option;
		
		$mainframe = JFactory::getApplication();

		$db					= JFactory::getDBO();
		$filter_order		= $mainframe->getUserStateFromRequest( "$option.filter_order",		'filter_order',		'm.created',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "$option.filter_order_Dir",	'filter_order_Dir',	'DESC',		'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( "$option.filter_state",		'filter_state',		'',		'word' );
		$search				= $mainframe->getUserStateFromRequest( "$option.search",			'search',			'',		'string' );
		$search				= strtolower( $search );

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', 50, 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$where = array();
		
		$where[]="m.pinboard LIKE '".$pinboard."'";

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
			$where[] = 'LOWER(m.text) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
			.' OR LOWER(m.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;

		$query = 'SELECT COUNT(m.id)'
		. ' FROM '.$table.' AS m'
		. $where
		;
		$db->setQuery( $query );
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT m.*'
		. ' FROM '.$table.' AS m'
		. $where
		. ' GROUP BY m.id'
		. $orderby
		;
		$db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		
		try
		{
			$rows = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			$mainframe->enqueueMessage(JText::_($e->getMessage()), 'error');
			return false;
		}

		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;
		$lists['pinboard']= $pinboard;
		$lists['community']= $community;
		$lists['rpname']= $rpname;

		$user = JFactory::getUser();
		$this->assignRef('user',		$user);
		$this->assignRef('lists',		$lists);
		$this->assignRef('pagination',	$pagination);

		$this->assignRef('items',		$rows);


		parent::display($tpl);
	}
}
