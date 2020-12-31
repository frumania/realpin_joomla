<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class realpinViewajax extends JViewLegacy
{
	function display($tpl = NULL)
	{
		echo $tpl;
		
		  if(version_compare(JVERSION,'1.6.0','ge'))
		  {
		  // Joomla! 1.6 code here
			  $app = JFactory::getApplication();
			  $app->close();
		  } 
		  else 
		  {
		  // Joomla! 1.5 code here
			  	global $mainframe;
				$mainframe->close();
		  }
	}	
}
?>
