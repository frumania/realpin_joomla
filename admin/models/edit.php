<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

class startModeledit extends JModelLegacy
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		//JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_realpin/tables');

		$array = JFactory::getApplication()->input->get('cid',  0, '', 'array');
		
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
		$settingstable = '#__realpin_settings';
		$folder = JPATH_ROOT.DS.'images'.DS.'realpin'.DS;
		
		$this->setTable($table);
		$this->setFolder($folder);
		$this->setSettingsTable($settingstable, $pinboard);
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the hello identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function setTable($table)
	{
		$this->_table = $table;
	}
	
	function setSettingsTable($table, $pinboard)
	{
		$this->_settingstable = $table;
		$this->_pinboard = $pinboard;
	}
	
	function setFolder($dir)
	{
		$this->_folder = $dir;
	}

	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			
			$db = JFactory::getDBO();
		
			$query = ' SELECT * FROM '.$this->_table.' WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->text = null;
			$this->_data->url = null;
			$this->_data->title = null;
			$this->_data->author = null;
			$this->_data->created = null;
			$this->_data->sticky = null;
			$this->_data->published = null;
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($data)
	{
		$row =& $this->getTable();

		//$data = JFactory::getApplication()->input->get( 'post' );

		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete_pics()
	{
	
	$cids = JFactory::getApplication()->input->get( 'cid', array(0), 'post', 'array' );
	$db	= JFactory::getDBO();
	$vars = new stdClass();
	jimport('joomla.filesystem.file');
	
		for ($i=0, $n=count($cids); $i < $n; $i++)
		{
		$query = ' SELECT id,type,url FROM '.$this->_table.' WHERE id = '.$cids[$i];
		$db->setQuery( $query );
		$data = $db->loadObject();

        $vars->type=$data->type;
		$vars->url=$data->url;
				
			if($vars->type=='pic' and $vars->url!="")
			{
			if (file_exists($this->_folder.$vars->url)){JFile::delete($this->_folder.$vars->url);}
		    if (file_exists($this->_folder.'thumbs'.DS.$vars->url)){JFile::delete($this->_folder.'thumbs'.DS.$vars->url);}
	        if (file_exists($this->_folder.'thumbs'.DS.'th_'.$vars->url)){JFile::delete($this->_folder.'thumbs'.DS.'th_'.$vars->url);}
			}
	
		}


	} 
	 
	 
	function delete()
	{
		$cids = JFactory::getApplication()->input->post->get('cid');

		$row = &$this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}	
			}						
		}
		return true;
	}
	
	function delete_thumb($post)
	{
		//$id = JFactory::getApplication()->input->get( 'id', 'post');
		$id=$post;
		$db	= JFactory::getDBO();
		$vars = new stdClass();
		jimport('joomla.filesystem.file');

		$query = ' SELECT id,type,url FROM '.$this->_table.' WHERE id = '.$id;
		$db->setQuery( $query );
		$data = $db->loadObject();

        $vars->type=$data->type;
		$vars->url=$data->url;
		
		if($vars->type=='pic' and $vars->url!="")
		{
		if (file_exists($this->_folder.'thumbs'.DS.$vars->url)){JFile::delete($this->_folder.'thumbs'.DS.$vars->url);}
	    if (file_exists($this->_folder.'thumbs'.DS.'th_'.$vars->url)){JFile::delete($this->_folder.'thumbs'.DS.'th_'.$vars->url);}
		return true;
		}
		else
		{
        return false;
		}
	}
	
	function _getSettings( &$options )
	{
		$query = "SELECT * FROM ".$this->_settingstable." WHERE config_id='".$this->_pinboard."'";

		return $query;
	}
	

	
	function getSettingsList( $options=array() )
	{
		$query	= $this->_getSettings( $options );
		$db		= JFactory::getDBO();
		$db->setQuery($query);
        $result= $db->loadAssoc();

		return @$result;
	}
	

			

}
?>
