<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

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
		JFactory::getApplication()->input->set( 'view', 'management' );
		
		parent::display();
	}
	
	function edit()
	{
		$rpname=JFactory::getApplication()->input->get( 'rpname' , '', 'REQUEST');
		$pinboard=JFactory::getApplication()->input->get( 'pinboard' , '', 'REQUEST');
		$community=JFactory::getApplication()->input->get( 'community' , '', 'REQUEST');
	    $cid		= JFactory::getApplication()->input->get('cid');
		ArrayHelper::toInteger( $cid );
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
	
        $pinboard=JFactory::getApplication()->input->get( 'pinboard' , '', 'REQUEST');
		$community=JFactory::getApplication()->input->get( 'community' , '', 'REQUEST');
		
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community);
	}
	
	
	function publish()
	{
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
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community);
		
		// Initialize variables
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$cid		= JFactory::getApplication()->input->get('cid');
		$task		= JFactory::getApplication()->input->get( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'LANG_CON6' ) );
		}

		ArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
		
		$query = 'UPDATE '.$table.''
		. ' SET published = ' . (int) $publish
		. ' WHERE id IN ( '. $cids .' )'
		;
		$db->setQuery( $query );
		if (!$db->execute()) {
			return JError::raiseWarning( 500, $db->getErrorMsg() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
	}
	
	function cancel()
	{	
		$community=JFactory::getApplication()->input->get( 'community', '', 'post');
		$msg = JText::_( 'LANG_CON7' );
		$this->setRedirect( 'index.php?option=com_realpin&controller=pinboards&community='.$community.'&task=display', $msg );
	}
	
	
}
?>
