<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

class startControllermanagement extends startController
{
	
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'unpublish', 	'publish');
	}
	
    function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar( 'view', 'management' );
		
		parent::display();
	}
	
	function edit()
	{
		$rpname=JRequest::getVar( 'rpname' , '', 'REQUEST');
		$pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
	    $cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
        $this->setRedirect( 'index.php?option=com_realpin&controller=edit&task=edit&cid[]='.$cids.'&pinboard='.$pinboard.'&community='.$community.'&rpname='.$rpname);

	}


	function remove()
	{
		$model = $this->getModel('edit');

		$model->delete_pics();
		
		if(!$model->delete()) {
			$msg.= JText::_( 'LANG_CON4' );
		} else {
			$msg.= JText::_( 'LANG_CON5' );
		}
	
        $pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
		
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community);
	}
	
	
	function publish()
	{
		$pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
		if($community==1)
        {
		$check="community";
		}
		else
		{
		$check="private";
		}
		$table	= '#__realpin_items';
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community);
		
		// Initialize variables
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'LANG_CON6' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
		
		$query = 'UPDATE '.$table.''
		. ' SET published = ' . (int) $publish
		. ' WHERE id IN ( '. $cids .' )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $db->getErrorMsg() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
	}
	
	function cancel()
	{	
		$community=JRequest::getVar( 'community', '', 'post');
		$msg = JText::_( 'LANG_CON7' );
		$this->setRedirect( 'index.php?option=com_realpin&controller=pinboards&community='.$community.'&task=display', $msg );
	}
	
	
}
?>
