<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();

$style='
table thead th{
text-align:left!important;	
}
table tbody tr td.type_'.JText::_('LANG_OPT4').' {
	color: #F63;
}
table tbody tr td.type_'.JText::_('LANG_OPT6').' {
	color: #060;
}
table tbody tr td.type_'.JText::_('LANG_OPT5').' {
	color: #003;
}
';

$doc->addStyleDeclaration( $style );
?>

<div id="j-sidebar-container" class="span2">
<?php echo JHtmlSidebar::render(); ?>
</div>

<br/>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<?php if(!version_compare(JVERSION,'1.6.0','ge')){ ?>
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			<?php } ?>
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
				<input type="checkbox" name="toggle" value="" onclick="Joomla.CheckAll(<?php echo count( $this->items ); ?>);" />
			</th>           
            <th>
            	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'ID' );
				      else
						echo JHTML::_('grid.sort',    JText::_( 'ID' ), 'm.id', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
            	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_TYPE' );
				      else
				        echo JHTML::_('grid.sort',    JText::_( 'LANG_TYPE' ), 'm.type', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
				<?php echo JText::_( 'LANG_XPOS' ); ?>
			</th>
            <th>
				<?php echo JText::_( 'LANG_YPOS' ); ?>
			</th>
             <th class="title">
             	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_TITLE' );
				      else
						echo JHTML::_('grid.sort',    JText::_( 'LANG_TITLE' ), 'm.title', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
			<th>
				<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_TEXT' );
				      else
						echo JHTML::_('grid.sort',    JText::_( 'LANG_TEXT' ), 'm.text', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
				<?php echo JText::_( 'LANG_URL' ); ?>
			</th>
            <th>
            	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_AUTHOR' );
				      else
						echo JHTML::_('grid.sort',   JText::_( 'LANG_AUTHOR' ), 'm.author', @$this->lists['order_Dir'], @$this->lists['order'] );
			    ?>
			</th>
            <th>
            	<?php 
				      if(version_compare(JVERSION,'1.6.0','ge'))
				      	echo JText::_( 'LANG_CREATED' );
				      else
				        echo JHTML::_('grid.sort',   JText::_( 'LANG_CREATED' ), 'm.created', @$this->lists['order_Dir'], @$this->lists['order'] );
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
		</tr>
   

			
	</thead>
    <tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		
		$published		= JHTML::_('jgrid.published', $row->published, $i );
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
	
		$link 		= JRoute::_( 'index.php?option=com_realpin&controller=edit&pinboard='.$this->lists['pinboard'].'&community='.$this->lists['community'].'&rpname='.$this->lists['rpname'].'&task=edit&cid[]='. $row->id );
		
		if ($row->title=="" or $row->title=="-"){$row->title="<i>".JText::_('LANG_NO_TITLE')."</i>";}
		if ($row->text=="" or $row->text=="-"){$row->text="<i>".JText::_('LANG_NO_TEXT')."</i>";}
		if ($row->url=="" or $row->url=="-"){$row->url="<i>".JText::_('LANG_NO_URL')."</i>";}
		
		if ($row->type=="pic")
		{$type=JText::_('LANG_OPT6');}
		elseif($row->type=="postit")
		{$type=JText::_('LANG_OPT4');}
		else
		{$type=JText::_('LANG_OPT5');}

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $i+1; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
            <td align="center">
				<?php echo $row->id; ?>
			</td>
            <td class="type_<?php echo $type; ?>">
				<?php echo $type; ?>
			</td>
            <td align="center">
				<?php echo $row->xPos; ?>
			</td>
            <td align="center">
				<?php echo $row->yPos; ?>
			</td>
            <td>
				<!--<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>-->
				<?php echo $row->title; ?>
			</td>
			<td>
				<!--<a href="<?php echo $link; ?>"><?php $text=substr($row->text, 0, 50); echo $text.'...'; ?></a>-->
				<?php $text=substr($row->text, 0, 50); echo $text; if(strlen($row->text) > 50) echo '...'; ?>
			</td>
            <td>
				<?php $tmp=substr($row->url, 0, 22); echo $tmp; ?>
			</td>
            <td align="center">
				<?php echo $row->author; ?>
			</td>
            <td align="center">
            <?php
              if($row->sticky)
			  {
			  $created="<span style='color:#F00'>STICKY</span>";
			  }
			  else
			  {
				  
				    if(version_compare(JVERSION,'1.6.0','ge')) {
					// Joomla! 1.6 code here
					$created=JHTML::_('date',$row->created, JText::_('DATE_RP_FORMAT'));	
					} else {
					// Joomla! 1.5 code here
					$created=JHTML::_('date',$row->created, JText::_('DATE_RP_FORMAT_J15'));	
					}  
	  
			  }
            ?>
            <?php echo $created; ?>
			</td>
			<td align="center">
				<?php echo $published;?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
    </tbody>
	</table>
	<?php if(!version_compare(JVERSION,'1.6.0','ge'))
          echo $this->pagination->getListFooter(); ?>
</div>

<input type="hidden" name="option" value="com_realpin" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="management" />
<input type="hidden" name="rpname" value="<?php echo $this->lists['rpname']; ?>" />
<input type="hidden" name="pinboard" value="<?php echo $this->lists['pinboard']; ?>" />
<input type="hidden" name="community" value="<?php echo $this->lists['community']; ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

</form>
