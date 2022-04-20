<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

?>

<div id="j-sidebar-container" class="span2">
<?php echo JHtmlSidebar::render(); ?>
</div>

<br/>

<?php

foreach ( $this->items as $key=>$value )
{ 
define(strtoupper($key),  $value);
}

echo JHTML::_('image', 'components/com_realpin/includes/css/admin/logo_version.png', 'logo_version.png', array('width' => '392', 'height' => '109'));

$i=0;
$data=array();
$Num_postits=0;
$Num_pics=0;
$Num_youtube=0;
$Num_published=0;
$Num_total=0;
$Num_last='';
$Num_user='-';

foreach ($this->pin_data as $dbdata)
{
$data[$i][0]=$dbdata->id;
$data[$i][1]=$dbdata->xPos;
$data[$i][2]=$dbdata->yPos;
$data[$i][3]=$dbdata->type;
$data[$i][4]=$dbdata->text;
$data[$i][5]=$dbdata->url;
$data[$i][6]=$dbdata->title;
$data[$i][7]=$dbdata->author;
$data[$i][8]=$dbdata->created;
$data[$i][9]=$dbdata->published;

$i++;

if($dbdata->type=='postit'){$Num_postits++;}
if($dbdata->type=='pic'){$Num_pics++;}
if($dbdata->type=='youtube'){$Num_youtube++;}
if($dbdata->published=='1'){$Num_published++;}

$Num_total++;
}

$Num_last=$data[$i-1][8];
$Num_last=substr($Num_last,0,10);
?>

<div style="text-align: left; margin-bottom:100px; height:200px">

<table width="500" cellspacing="1" cellpadding="1" border="0" align="left">
<tbody>
<tr>
    <td>
    <br />
    <b><span><?php echo JText::_( 'LANG_HEAD_STAT'); ?></span></b><br />
    <span><?php echo JText::_( 'LANG_HEAD_NUM_POSTIT'); ?><br />
    <?php echo JText::_( 'LANG_HEAD_PICS'); ?><br />
    <?php echo JText::_( 'LANG_HEAD_YOUTUBE'); ?><br />
    <?php echo JText::_( 'LANG_HEAD_ALL'); ?><br />
    <?php echo JText::_( 'LANG_HEAD_PUB'); ?><br />
    <?php echo JText::_( 'LANG_HEAD_LAST'); ?><br /><br />
    <?php echo JText::_('INSTALL_FOLDER').JText::_( ': <i>images/realpin/</i>'); ?><br />
    <?php echo JText::_('INSTALL_FOLDER').JText::_( ': <i>images/realpin/thumbs/</i>'); ?><br />
    <?php echo JText::_('INSTALL_FOLDER').JText::_( ': <i>images/realpin/tmp/</i>'); ?><br />
    <?php echo JText::_('INSTALL_FOLDER').JText::_( ': <i>images/realpin/misc/</i>'); ?><br />
    </td>
    
    <td>
    <br />
    <span>&#160;</span><br />
    <span>
    <?php echo $Num_postits; ?><br />
    <?php echo $Num_pics; ?><br />
    <?php echo $Num_youtube; ?><br />
    <?php echo $Num_total; ?><br />
    <?php echo $Num_published; ?><br />
    
    <?php  if(version_compare(JVERSION,'1.6.0','ge')) { ?>
	<?php echo JHTML::_('date',$Num_last, JText::_('DATE_RP_FORMAT')); ?><br /><br />
	<?php } else { ?>
	<?php echo JHTML::_('date',$Num_last, JText::_('DATE_RP_FORMAT_J15')); ?><br /><br />
	 <?php }?>
    
    <?php
	
	if(JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'))
	{
            if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin')), 1)=="0755")
            {
            echo "<span style='color:green;'>".JText::_('LANG_WRITABLE')."</span>";
            }
            else
            {
            echo "<span style='color:red;'>".JText::_('LANG_UNWRITABLE')."</span>";	
            }
	}
	else
	{
	echo "<span style='color:red;'>".JText::_('LANG_NOT_EXIST')."</span>";	
	}
			
            echo "<br/>";
	
	if(JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs'))
	{		
            if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs')), 1)=="0755")
            {
            echo " <span style='color:green;'>".JText::_('LANG_WRITABLE')."</span>";
            }
            else
            {
            echo "<span style='color:red;'>".JText::_('LANG_UNWRITABLE')."</span>";	
            }
	}
	else
	{
	echo "<span style='color:red;'>".JText::_('LANG_NOT_EXIST')."</span>";	
	}
            
			echo "<br/>";
	
	if(JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'tmp'))
	{		
            if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'tmp')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'tmp')), 1)=="0755")
            {
            echo "<span style='color:green;'>".JText::_('LANG_WRITABLE')."</span>";
            }
            else
            {
            echo "<span style='color:red;'>".JText::_('LANG_UNWRITABLE')."</span>";	
            }
	}
	else
	{
	echo "<span style='color:red;'>".JText::_('LANG_NOT_EXIST')."</span>";	
	}
				
		    echo "<br/>";
			
	if(JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'misc'))
	{
			if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'misc')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'misc')), 1)=="0755")
            {
            echo "<span style='color:green;'>".JText::_('LANG_WRITABLE')."</span>";
            }
            else
            {
            echo "<span style='color:red;'>".JText::_('LANG_UNWRITABLE')."</span>";	
            }
	}
	else
	{
	echo "<span style='color:red;'>".JText::_('LANG_NOT_EXIST')."</span>";	
	}
	            
?>
    </td>
</tr>
</tbody>
</table>
</div>

<br/>        

<?php
echo "<div>"; echo "RealPin v.".VERSION." - ".JText::_( 'LANG_COPYRIGHT' ); echo "</div>";
?>


