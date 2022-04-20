<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Frontpage View class
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.5
 */
class realpinViewfeed extends JViewLegacy
{
	function display($tpl = null)
	{
		// parameters
		$model	  = &$this->getModel();
		$pin_data     = $model->getData();
		$pin_total     = $model->getTotal();

		$document	=& JFactory::getDocument();
		
		$mytype = strtolower(JFactory::getApplication()->input->get('rp_type','pic','REQUEST'));
		// Get some data from the model
		//JFactory::getApplication()->input->set('limit', $app->getCfg('feed_limit'));

		foreach ($pin_data as $row)
		{
			if($row->type==$mytype)
			{

			$title = "Bild.jpg";

			// url link to article
			$link =  PIC_THUMBS_URL."th_".$row->url;
            // JRoute::_(JURI::root().'index.php?option=com_realpin&pinboard='.PINBOARD);

			$author	= $row->author;
			
			$description = "<p><img src=\"".PIC_THUMBS_URL.$row->url."\" alt=\"\" /></p>";
			
			if($author != "User")
			{
				//$description .=  JText::_('LANG_ICON_INFO1')." ".$author."<br /> -";	
			}
			
			$description .=  "<i>".$row->text."</i>";	
			
			if($row->title != "")
			{
				$title = $row->title;
			}
			else
			{
				$title = "Bild";
			}

			// load individual item creator class
			$item = new JFeedItem();
			$item->title		= $title;
			$item->link			= $link;
			$item->category		= $mytype;
			$item->source	= $author;
			$item->description	= $description;
			$item->date			= $row->created;
			// loads item info into rss array
			$document->addItem($item);
			}
		}
		
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
