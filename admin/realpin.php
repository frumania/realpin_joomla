<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

require_once (JPATH_COMPONENT.DS.'controller.php');


if($controller = JRequest::getVar('controller')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}

$global = JRequest::getVar('global',"true",'REQUEST');
$community = JRequest::getVar('community',0,'REQUEST');

$dev=false;
if(JURI::base()=="http://localhost/toerpe/realpin/live/administrator/" or JURI::base()=="https://realpin.frumania.com/administrator/"){$dev=true;}
//Men�

if($controller == 'management') {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display');}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display');}
}
if($controller == 'pinboards' and $community=="0") {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display', true);
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display');}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display');}
}
if($controller == 'pinboards' and $community=="1") {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display', true);}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display');}
}
if($controller == 'global' and $global=="true" and $community=="0") {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display', true);
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display');}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display');}
}
if($controller == 'global' and $global=="false" and $community=="0") {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display', true);
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display');}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display');}
}
if($controller == 'global' and $global=="true" and $community=="1") {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display');}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display', true);}
}
if($controller == 'global' and $global=="false" and $community=="1") {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display', true);}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display');}
}
if($controller == '' or $controller == 'start') {
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON1'), 'index.php?option=com_realpin', true );
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4'), 'index.php?option=com_realpin&controller=pinboards&community=0&task=display');
JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5'), 'index.php?option=com_realpin&controller=global&global=true&community=0&task=display');
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON4').' (Community)', 'index.php?option=com_realpin&controller=pinboards&community=1&task=display');}
if($dev){JSubMenuHelper::addEntry(JText::_('LANG_BUTTON5').' (Community)', 'index.php?option=com_realpin&controller=global&global=true&community=1&task=display');}
}


// Create the controller
$classname	= 'startController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute(JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

?>
