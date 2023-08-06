<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class realpinViewrpdefault extends JViewLegacy
{
	function assignRef($mystring, &$param)
	{
		$this->{$mystring} = $param;
	}

	function display($tpl = null)
	{
		global $mainframe;

		$model	  = $this->getModel();
		$pin_data     = $model->getData();
		$pin_total     = $model->getTotal();
		$this->assignRef('pin_data'  , $pin_data);
		$this->assignRef('pin_total'  , $pin_total);
		$this->assignRef('vars'  , $this->vars);	
		parent::display($tpl);
	}
}
?>