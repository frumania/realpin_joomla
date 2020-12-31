<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

class startControlleredit extends startController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 	'publish');
	}

	function edit()
	{
		JRequest::setVar( 'view', 'edit' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('edit');
		
		$post = JRequest::get('post');
        $post['text'] = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
		

		if ($post['author']=="" or $post['author']=="-"){$post['author']="User";}

		$post['created']=date("Y-m-d H:i:s");			

		if ($model->store($post)) {
			$msg = JText::_( 'LANG_CON1');
		} else {
			$msg = JText::_( 'LANG_CON2' );
		}
		
		if ($model->delete_thumb($post['id'])) {
			$msg = JText::_( 'LANG_CON3A' );
		}

        $rpname=JRequest::getVar( 'rpname' , '', 'REQUEST');
		$pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community.'&rpname='.$rpname, $msg );

	}

	function remove()
	{
		
		$model = $this->getModel('edit');
		if(!$model->delete()) {
			$msg = JText::_( 'LANG_CON4' );
		} else {
			$msg = JText::_( 'LANG_CON5' );
		}
		
		$model->delete_pics();

        $rpname=JRequest::getVar( 'rpname' , '', 'REQUEST');
		$pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community.'&rpname='.$rpname, $msg );
	}

	
	function publish()
	{
        $rpname=JRequest::getVar( 'rpname' , '', 'REQUEST');
		$pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community.'&rpname='.$rpname);

		
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
        
		$table	= '#__realpin_items';
		
		$query = 'UPDATE '.$table.''
		. ' SET published = ' . (int) $publish
		. ' WHERE id IN ( '. $cids .' )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $row->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );

	}
	
	
	function cancel()
	{
        $rpname=JRequest::getVar( 'rpname' , '', 'REQUEST');
		$pinboard=JRequest::getVar( 'pinboard' , '', 'REQUEST');
		$community=JRequest::getVar( 'community' , '', 'REQUEST');
		$msg = JText::_( 'LANG_CON7' );
		$this->setRedirect( 'index.php?option=com_realpin&controller=management&task=display&pinboard='.$pinboard.'&community='.$community.'&rpname='.$rpname, $msg );
	}
}
?>
