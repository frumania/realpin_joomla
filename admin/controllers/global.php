<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

class startControllerglobal extends startController
{
	
    function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar( 'view', 'global' );
		
		parent::display();
	}
	
	function getQueriesFromFile($file)
	{
		// import file line by line
		// and filter (remove) those lines, beginning with an sql comment token
		$file = array_filter(file($file),
							 create_function('$line',
											 'return strpos(ltrim($line), "--") !== 0;'));
		// this is a list of SQL commands, which are allowed to follow a semicolon
		$keywords = array('ALTER', 'CREATE', 'DELETE', 'DROP', 'INSERT', 'REPLACE', 'SELECT', 'SET',
						  'TRUNCATE', 'UPDATE', 'USE');
		// create the regular expression
		$regexp = sprintf('/\s*;\s*(?=(%s)\b)/s', implode('|', $keywords));
		// split there
		$splitter = preg_split($regexp, implode("\r\n", $file));
		// remove trailing semicolon or whitespaces
		$splitter = array_map(create_function('$line',
											  'return preg_replace("/[\s;]*$/", "", $line);'),
							  $splitter);
		// remove empty lines
		return array_filter($splitter, create_function('$line', 'return !empty($line);'));
	} 
	
	function makeDefault()
	{
		
	  $db		=& JFactory::getDBO();
	  
	  $settingstable = '#__realpin_settings';
	  //$folder = JPATH_SITE.DS.'images'.DS.'realpin'.DS.$check.DS.$pinboard.DS;
	  
	  $query="DROP TABLE IF EXISTS ". $settingstable;
	  $db->setQuery($query);
	  //$db->query();
      $queries=$this->getQueriesFromFile( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_realpin'.DS.'install.sql');
	 
		for ($i = 0, $ix = count($queries); $i < $ix; ++$i) { 
		      $queries[$i]=str_replace("`#__realpin_config`", $settingstable, $queries[$i]);
			  //$queries[$i]=str_replace("`#__realpin`", $table, $queries[$i]);
			  $db->setQuery($queries[$i]);
			  //$db->query();
		}
		
	  //$this->unlinkRecursive($folder.'thumbs',false);
		
	  $msg = JText::_( 'LANG_CON8' );
		
	  $link = 'index.php?option=com_realpin&controller=global&task=display';
	  $this->setRedirect($link, $msg);
		
	
	}
		
	
	function save()
	{
		$data = JRequest::get( 'post' );
		$community=JRequest::getVar( 'community', '', 'post');
		$pinboard=JRequest::getVar( 'pinboard', '', 'post');		
		$isglobal=JRequest::getVar( 'global', false, 'post');
		jimport('joomla.filesystem.file');
		
		$db	=& JFactory::getDBO();
		
		if(isset($_POST['ENABLE'][0])){$data['ENABLE_POSTIT']=$_POST['ENABLE'][0];}else{$data['ENABLE_POSTIT']=0;}
		if(isset($_POST['ENABLE'][1])){$data['ENABLE_YT']=$_POST['ENABLE'][1];}else{$data['ENABLE_YT']=0;}
		if(isset($_POST['ENABLE'][2])){$data['ENABLE_PIC']=$_POST['ENABLE'][2];}else{$data['ENABLE_PIC']=0;}

		$settingstable = '#__realpin_settings';
		foreach ( $data as $key => $val )
		{
			if($key!="option" && $key!="task" && $key!="picchange" && $key!="controller" && $key!="ENABLE" && $key!="pinboard" && $key!="community" && $key!="DESIGN" && $key!="global")
			{
				
			$key=strtolower($key);
			$sql="UPDATE ".$settingstable." Set $key='$val' WHERE config_id='".$pinboard."' ";
			//echo $sql."\n";
		    $db->setQuery($sql);
			$db->query();
			}
		}

		$msg= JText::_( 'LANG_CON3A' );
		
		if($data['picchange']==1) //delete Thumbnails
		{
          $table = '#__realpin_items';
		  if(isglobal)
		  {$query = 'SELECT url FROM '.$table.' WHERE type="pic" ORDER by created DESC';}
		  else{$query = 'SELECT url FROM '.$table.' WHERE pinboard="'.$pinboard.'" AND type="pic" ORDER by created DESC';}
		  $db	=& JFactory::getDBO();
		  $db->setQuery( $query );
		  $images = $db->loadResultArray();
		  
		  for ($i=0, $n=count( $images ); $i < $n; $i++)
		  {
		  if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.$images[$i]))
		  JFile::delete(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.$images[$i]);
		  if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.'th_'.$images[$i]))
		  JFile::delete(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.'th_'.$images[$i]);
		  }		
		
		$msg.= " ".JText::_( 'LANG_CON3B' );
		}
	
		
		$link = 'index.php?option=com_realpin&controller=global&task=display&community='.$community.'&pinboard='.$pinboard.'&global='.$isglobal;
		$this->setRedirect($link, $msg);
	}
	
	
	function cancel()
	{
		$community=JRequest::getVar( 'community', '', 'post');
		$msg = JText::_( 'LANG_CON7' );
		$this->setRedirect( 'index.php?option=com_realpin&controller=pinboards&community='.$community.'&task=display', $msg );
	}
	
    function makeglobal()
	{
	    global $mainframe;
	    $db		= JFactory::getDBO();

		$key=JRequest::getVar( 'key' , '', 'POST');
		$val=JRequest::getVar( 'val' , '', 'POST');
		$community=JRequest::getVar( 'community', '', 'REQUEST');
		$settingstable = '#__realpin_settings';
		
		if($community==1)
        {
		$check="community";
		}
		else
		{
		$check="private";
		}
			
		if($key!='' && $val!='')
		 {
				$key=strtolower($key);
			    $sql="UPDATE ".$settingstable." Set $key='$val' WHERE config_name='default_".$check."_global' ";
				$db->setQuery($sql);		
				$db->query();
				//echo $sql;
		        $msg= JText::_( 'LANG_CON3A' );
		 }
		
	}
	
	function upload()
	{
	
	    global $mainframe;
	    $db		= JFactory::getDBO();
		jimport('joomla.filesystem.file');
		$pinboard=JRequest::getVar( 'pinboard', '', 'REQUEST');	
		if($pinboard==""){$pinboard="rp_default";}
		
		$img=$_FILES['myfile']['name'];
		$path = JPath::clean(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'misc'.DS.$pinboard.'_'.$img);
						
				$vars = new stdClass();		
				$vars->url=JFile::makeSafe($_FILES['myfile']['name']);
				
				if($vars->url!='' && ($_FILES["myfile"]["type"] == "image/jpeg" or $_FILES["myfile"]["type"] == "image/pjpeg" or $_FILES["myfile"]["type"] == "image/png") && $_FILES['myfile']['size'] <  1000000)
				{			
					  if(!JFile::upload($_FILES['myfile']['tmp_name'], $path))
					  {
						 echo "error";
					  }
					  else
					  {	
		                 echo "success";
					  }
				}
				else
				{
					 echo "error";
				}
				
		
	}
	
		
}
?>
