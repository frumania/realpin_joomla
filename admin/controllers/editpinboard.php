<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

class startControllereditpinboard extends startController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 	'publish');
	}

	function edit()
	{
		JRequest::setVar( 'view', 'editpinboard' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		jimport('joomla.filesystem.file');
		$model = $this->getModel('editpinboard');
		
		$post = JRequest::get('post');
		$config_name = $post['config_name'];
		$config_desc = $post['config_desc'];
		$published = $post['published'];
		$config_community = $post['config_community'];
		
		  $msg = JText::_( 'LANG_CON1');

	      $db	=& JFactory::getDBO();
		  $table="#__realpin_settings";

		  $sql = "SELECT * FROM ".$table." WHERE config_id = '1'";
		  $db->setQuery($sql);
		  $row = $db->loadAssoc();
		  $sql = "INSERT INTO ".$table." SET ";
		  $RowKeys = array_keys($row);
		  $RowValues = array_values($row);
			  for ($i=2;$i<count($RowKeys);$i+=1) 
			  {
			  if($RowKeys[$i]=="config_name"){$RowValues[$i]=$config_name;}
			  if($RowKeys[$i]=="config_desc"){$RowValues[$i]=$config_desc;}	  
			  if($RowKeys[$i]=="config_community"){$RowValues[$i]=$config_community;}  
			  if($RowKeys[$i]=="published"){$RowValues[$i]=$published;}
			  if ($i!=2) { $sql .= ", "; }
			  $sql .= $RowKeys[$i] . " = '" . $RowValues[$i] . "'";
			  }
		  $db->setQuery($sql);
		  $db->query();
		
		  $this->setRedirect( 'index.php?option=com_realpin&controller=pinboards&task=display', $msg );

	}
	
	function cancel()
	{	
		$msg = JText::_( 'LANG_CON7' );
		$this->setRedirect( 'index.php?option=com_realpin&controller=pinboards&task=display', $msg );
	}
}
?>
