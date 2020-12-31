<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

class startControllerpinboards extends startController
{
	
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'unpublish', 	'publish');
		$this->registerTask('preferences', 'display2');
		//$this->registerTask( 'add', 'edit' );
	}
	
    function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar( 'view', 'pinboards' );
		
		parent::display();
	}
	
	function displayprivate()
	{
     $this->setRedirect('index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
	}
	function displaycommunity()
	{
     $this->setRedirect('index.php?option=com_realpin&controller=global&global=true&community=1&task=display');
	}
	
    function add()
	{
	    $cid  = JRequest::getVar( 'cid', array(), 'REQUEST', 'array' );
		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );
        $this->setRedirect( 'index.php?option=com_realpin&controller=editpinboard&task=edit&cid[]='.$cids);
	}

	function remove()
	{
		$cids		= JRequest::getVar( 'cid', array(), 'post', 'array' );
	    $db	= JFactory::getDBO();
	    jimport('joomla.filesystem.file');		

        for ($i=0, $n=count($cids); $i < $n; $i++)
		{
			$table = "#__realpin_settings";
			$query = "DELETE FROM ".$table." WHERE config_id = ".$cids[$i];
			$db->setQuery( $query );
			$db->query();
			
			$items = "#__realpin_items";
			$query = 'SELECT url FROM '.$items.' WHERE pinboard="'.$cids[$i].'" AND type="pic" ORDER by created DESC';
		    $db->setQuery( $query );
		    $images = $db->loadResultArray();
		  
			for ($p=0, $z=count( $images ); $p < $z; $p++)
			{
			if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.$images[$p]))
			JFile::delete(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.$images[$p]);
			if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.$images[$p]))
			JFile::delete(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.$images[$p]);
			if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.'th_'.$images[$p]))
			JFile::delete(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.'th_'.$images[$p]);
			}	
			
			$query = "DELETE FROM ".$items." WHERE pinboard = '".$cids[$i]."'";
			$db->setQuery( $query );
			$db->query();
		}
								
		$msg= JText::_( 'LANG_CON9' );			
	
		$this->setRedirect( 'index.php?option=com_realpin&controller=pinboards&task=display');
	}
	
	function publish()
	{
		$this->setRedirect( 'index.php?option=com_realpin&controller=pinboards&task=display' );
		
		// Initialize variables
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'LANG_CON6' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );

		$table	= '#__realpin_settings';
		
		$query = 'UPDATE '.$table.' SET published = '.(int) $publish.' WHERE config_id IN ( '. $cids .' )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $db->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
	}
	
	
}
?>
