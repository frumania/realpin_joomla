<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

?>

<div id="j-sidebar-container" class="span2">
<?php echo JHtmlSidebar::render(); ?>
</div>

<br/>

<?php

function dir_size($array)
{
	$size=0;

	for ($i=0, $n=count( $array ); $i < $n; $i++)
	{
		$myfile = $array[$i][0];
		if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.$myfile))
		$size+=filesize(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.$myfile);
		if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.$myfile))
		$size+=filesize(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.$myfile);
		if(file_exists(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.'th_'.$myfile))
		$size+=filesize(JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS.'th_'.$myfile);
	}

    return $size;
}		
		

$doc = JFactory::getDocument();

$style='
table thead th{
text-align:left!important;	
}
.rp_private td{
	background-color:#FF9!important;
}
';

$doc->addStyleDeclaration( $style );
?>

<script language="JavaScript" type="text/javascript">
<!--
function submitbutton(pressbutton)
{
  document.adminForm.task.value=pressbutton;
  answer=true;
  
  if (pressbutton=="remove") 
  {
  answer=confirm( "<?php echo JText::_( 'LANG_REALLY_DELETE' ); ?> ?" );
  }
  
  if (answer){submitform(pressbutton);}


}
//-->
</script>

<form action="index.php" method="post" id="adminForm" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<?php if(!version_compare(JVERSION,'1.6.0','ge')){ ?>
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			<?php }; ?>
		</td>
		<td nowrap="nowrap">
			<?php if(!version_compare(JVERSION,'1.6.0','ge'))
			      echo $this->lists['state']; ?>
		</td>
	</tr>
</table>
<div id="tablecell">
	<table width="100%" class="adminlist">
<thead>
		<tr>
		<tr>
			<th>
				<?php echo JText::_( 'NUM' ); ?>
			</th>
            <th> 
            <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
			<th style="display:none">
				<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'id' );
				      else
					  	echo JHTML::_('grid.sort',    JText::_( 'id' ), 'm.config_id', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
				<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_NAME' );
				      else
						echo JHTML::_('grid.sort',    JText::_( 'LANG_NAME' ), 'm.config_name', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
				<?php echo JText::_( 'LANG_DESCRIPTION' ) ?>
			</th>
            <th>
            	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_ITEMS' );
				      else
						echo JHTML::_('grid.sort',    JText::_( 'LANG_ITEMS' ), 'm.config_items', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
            	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_SPACE' );
				      else
						echo JHTML::_('grid.sort',    JText::_( 'LANG_SPACE' ), 'm.config_space', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
            	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_UPDATED' );
				      else
						echo JHTML::_('grid.sort',  JText::_( 'LANG_UPDATED' ), 'm.config_updated', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
			<th>
			    <?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_PUB' );
				      else
						echo JHTML::_('grid.sort',    JText::_( 'LANG_PUB' ), 'm.published', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
           <th>
				<?php echo JText::_( 'LANG_ACTIONS' ) ?>
			</th>
		</tr>
   

			
	</thead>
    <tbody>
    <?php
	$k = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
	$row = &$this->items[$i];
	if($this->community==0){$check="private";}
	if($this->community==1){$check="community";}
	if($row->config_name!="default_".$check."" and $row->config_name!="default_".$check."_global")
	{
		$community=$row->config_community;
		$pinboard=$row->config_id;

		$published		= JHTML::_('jgrid.published', $row->published, $i );
		$checked 	= JHTML::_('grid.id',   $i, $row->config_id );
		 
		$table	= '#__realpin_items';	
		$query = 'SELECT COUNT(id) FROM '.$table.' WHERE pinboard="'.$pinboard.'"';
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$menge = $db->loadResult();
	
		$query = 'SELECT url FROM '.$table.' WHERE pinboard="'.$pinboard.'" AND type="pic" ORDER by created DESC';
		$db	= JFactory::getDBO();
		$db->setQuery( $query );
		$images = $db->loadRowList();

		$space2 = round(dir_size($images)/(1024*1024),2);
		$space = $space2.' MB';
		
		$table	= '#__realpin_settings';	
		$query = 'UPDATE '.$table.' Set config_space="'.$space2.'", config_items="'.$menge.'" WHERE config_id="'.$pinboard.'"';
		$db->setQuery($query);
		$db->execute();		

		?>
		<tr>
            <td width="20">
				<?php echo $k;  $k++;?>
			</td>
           <td width="40">
				 <?php echo $checked;  ?>
			</td>
            <td style="display:none" width="40">
				 <?php echo $row->config_id;  ?>
			</td>
			<td>
				<?php echo $row->config_name;  ?>
			</td>
            <td>
				 <?php echo $row->config_desc;  ?>
			</td>
            <td>
                <?php echo $menge;  ?>
			</td>
            <td>
                <?php echo $space;  ?>
			</td>
            <td>
              <?php  if(version_compare(JVERSION,'1.6.0','ge')) {
			  // Joomla! 1.6 code here
			  $created=JHTML::_('date',$row->config_updated, JText::_('DATE_RP_FORMAT')); echo $created;
			  } else {
			  // Joomla! 1.5 code here
			   $created=JHTML::_('date',$row->config_updated, JText::_('DATE_RP_FORMAT_J15')); echo $created;
			  }
			?>
            </td>
			<td>
               <?php echo $published; ?>
			</td>
            <td>
                 <a target="_blank" href="../index.php?option=com_realpin&pinboard=<?php echo $pinboard; ?>"><?php echo JText::_('LANG_BUTTON0'); ?></a>	&nbsp;	&nbsp;
                 <!--<a href="index.php?option=com_realpin&controller=pinboardso&task=edit&cid[]=<?php echo $row->id; ?>"><?php echo JText::_('Edit'); ?></a>-->
                 <a href="index.php?option=com_realpin&controller=management&community=<?php echo $community; ?>&pinboard=<?php echo $pinboard; ?>&rpname=<?php echo $row->config_name; ?>"><?php echo JText::_('LANG_BUTTON2'); ?></a>	&nbsp;&nbsp;
                 <a href="index.php?option=com_realpin&controller=global&community=<?php echo $community; ?>&global=false&pinboard=<?php echo $pinboard; ?>&rpname=<?php echo $row->config_name; ?>"><?php echo JText::_('LANG_BUTTON3'); ?></a>
			</td>
		</tr>
		<?php
	}
	}
	?>
    </tbody>
	</table>
    
    <?php //echo $this->myquery; ?>
    
    <?php if(!version_compare(JVERSION,'1.6.0','ge'))
          echo $this->pagination->getListFooter(); ?>
</div>

<input type="hidden" name="option" value="com_realpin" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="community" value="<?php echo $this->community; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="pinboards" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

</form>
