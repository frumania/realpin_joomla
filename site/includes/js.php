<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');
?>

<?php 
$doc = JFactory::getDocument();

if(ENABLE_JQUERY==1)
{
$doc->addScript(ROOT.'/js/jquery.min.js');
}
if(ENABLE_JQUERYUI==1)
{
$doc->addScript(ROOT.'/js/jquery-ui.custom.min.js');
}
//$doc->addScript(ROOT.'/js/rp_js1.js');
$doc->addScript(ROOT.'/js/rp_js2.js');

if (RP_MOBILE) {
$doc->addScript(ROOT.'/js/jquery_touch.js');
$doc->addScript(ROOT.'/js/photoswipe/klass.min.js');
$doc->addScript(ROOT.'/js/photoswipe/code.photoswipe.jquery-3.0.4.min.js');
}

ob_start();  //startet Buffer
include(JPATH_COMPONENT.'/includes/static_js.php');  //datei ist jetzt im Buffer
$script.=ob_get_contents();  //Buffer wird in $script geschrieben
ob_end_clean();  //Buffer wird gel�scht

$script = str_replace("<script type=\"text/javascript\">", "", $script);
$script = str_replace("</script>", "", $script);

$myFile = JPATH_COMPONENT.DS."includes".DS."js".DS."realpin".PINBOARD.".js";

if(@file_put_contents($myFile, $script))
{
$doc->addScript(ROOT.'/js/realpin'.PINBOARD.'.js?'.time());
}
else
{
$doc->addScriptDeclaration( $script );
}


?>