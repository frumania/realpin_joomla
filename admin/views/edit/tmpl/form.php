<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addScript(JURI::root().'components/com_realpin/includes/js/jquery.min.js');
$doc->addScript(JURI::root().'components/com_realpin/includes/js/rp_js2.js');
?>

<script language="javascript" type="text/javascript">
jQuery.noConflict();
jQuery(document).ready
(
		   
	function()
	{
		
	 jQuery('#adminForm').bind('blur keyup',function()
	 { 
	 	 
		 if(jQuery('textarea[name=text]').val().length>0)
		 {
		 jQuery('.rp_postit_content').text(jQuery('textarea[name=text]').val());
		 jQuery('.rp_postit_content').html(jQuery('.rp_postit_content').html().replace(/\n/g,'<br />'));
		 }		 
		
	 });
	 
	 jQuery('.rp_postit_content').bind({
	mouseenter: function()
	    {
		elem=jQuery(this).find('div.rp_postit_content');
		if(elem.scrollTop()>0){jQuery('div.rpscrollup').show();};		
		if(elem[0].scrollHeight - elem.scrollTop() > elem.outerHeight()){jQuery('div.rpscrolldown').show();};
		},
	mouseleave: function()
	   {
		jQuery(this).find('div.rpscrollup').hide();
		jQuery(this).find('div.rpscrolldown').hide();
		}
	});
	
	
	jQuery('.rpscrolldown').bind({
	mousedown: function()
		{ 
		        jQuery(this).parent().find('div.rp_postit_content').stop().scrollTo( '+=120', 1000, { axis:'y',onAfter:
				function()
				{
				elem=jQuery(this).parent().find('div.rp_postit_content');	
				if(elem.scrollTop()>0){jQuery('.rpscrollup').show();};
				if(elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()){jQuery('.rpscrolldown').hide();};
				}
				});		
		}
	});
	
	jQuery('.rpscrollup').bind({
	mousedown: function()
	{ 
	
                jQuery(this).parent().find('div.rp_postit_content').stop().scrollTo( '-=120', 1000, { axis:'y',onAfter:
				function()
				{
				elem=jQuery(this).parent().find('div.rp_postit_content');
				if(elem.scrollTop()==0){jQuery('.rpscrollup').hide();};
				if(elem[0].scrollHeight - elem.scrollTop() > elem.outerHeight()){jQuery('.rpscrolldown').show();};
				}
				});		
	
	}
	});
	 
	 
		
	}
);	


</script>

<?php


foreach ( $this->settings as $key=>$value )
{ 
define(strtoupper($key),  $value);
}

if($this->community==1)
{
$check="community";
}
else
{
$check="private";
}

$style="";

if (POSTIT_FONT_REPLACE=="1")
{ 

	$font=substr(POSTIT_FONT, 0, -4);
	
	$style.='
	@font-face
	{
	font-family: '.$font.';
	src: url(\'../components/com_realpin/includes/fonts/'.$font.'.eot\');
	src: local(\'none\'), url(\'../components/com_realpin/includes/fonts/'.$font.'.woff\') format(\'woff\'), url(\'../components/com_realpin/includes/fonts/'.$font.'.ttf\') format(\'truetype\'), url(\'../components/com_realpin/includes/fonts/'.$font.'.svg#webfont\') format(\'svg\');
	font-weight: normal;
	font-style: normal;
	}';

}

$style.='

.rp_postit_layer
{
font-family: '.$font.', Arial, sans-serif;
font-size:'.POSTIT_FONT_SIZE.'px;
color:#'.POSTIT_FONT_COLOR.';
line-height:normal;
white-space:normal;
margin: 40px auto;
text-align:center!important;
}

.rpscrolldown
{
position:absolute;
bottom:20px;
left:165px;
}

.rpscrolldown a
{
display:block;	
width:10px;
height:12px;
background-image:url(\'../components/com_realpin/includes/css/scrolldown.png\');
border-color:transparent;
outline:none;
}

.rpscrollup
{
position:absolute;
bottom:20px;
left:15px;
}

.rpscrollup a
{
display:block;	
width:10px;
height:12px;
background-image:url(\'../components/com_realpin/includes/css/scrollup.png\');
border-color:transparent;
outline:none;
}

.rp_postit_content
{
overflow:hidden;
margin: 40px auto;
width:150px;
height:150px;
text-align:center!important;
}

.rp_author_center
{
text-align:center;
display: block;
}

.rp_postit_layer a
{
color:blue; display: block;
}

.rp_postit_layer a:hover 
{
color:red; display: block;
}

#rp_postit
{
position:absolute;
width: 200px;
height: 207px;
background-image: url(../components/com_realpin/includes/css/postit_advanced.png);
}

#rp_pic
{
text-align:left;
border:1px solid #CCCCCC;
}

.in
{
font-family: Arial, Helvetica, sans-serif;
}

.inputboxdis
{
color:#999;	
}

#rpeditform
{
positon:absolute;
top:40px;
left:30px;
width:300px;
float:left;
}

#rppreview
{
position:absolute;
float:right;
margin-top:10px;
left:500px;	
}

#rpedittotal
{
width:800px;
}

fieldset label, fieldset span.faux-label
{
    clear: none!important;
    float: none!important;
}
fieldset.adminform textarea {
    width: 200px!important;
}
';


$doc->addStyleDeclaration( $style );
?>

<?php 

if ($this->edit->type=="pic")
{$type=JText::_('LANG_OPT6');}
elseif($this->edit->type=="postit")
{$type=JText::_('LANG_OPT4');}
else
{$type=JText::_('LANG_OPT5');}

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="rpedittotal">
<div id="rpeditform">
    <fieldset class="adminform">
    <legend><?php echo JText::_( 'LANG_EDIT4').' ['.$type.']'; ?></legend>
    
    <table class="admintable">

                <?php if($this->edit->type=="postit")
                {
                ?>
                    <input class="inputbox" type="hidden" name="title" id="title" size="32" value="<?php echo $this->edit->title; ?>" />
                <?php
                }
                else
                {
                ?>
                <tr>
                    <td width="110" class="key">
                    <label for="title">
                    <?php echo JText::_( 'LANG_TITLE' ); ?>:
                    </label>
                    </td>
                    <td>
                    <input class="inputbox" type="text" name="title" id="title" size="32" value="<?php echo $this->edit->title; ?>" />
                    </td>
                </tr> 
                <?php
                }
                ?>           

        <tr>
			<?php if ($this->edit->type=="postit")
            {
            ?> 
                <td width="110" class="key">
                <label for="alias">
                <?php echo JText::_( 'LANG_TEXT' ); ?>:
                </label>
                </td>
                <td>
                <p>
                <textarea name="text" id="textfield1" cols="18" rows="7"><?php echo $this->edit->text; ?></textarea>
                </p>       
                
                </td>
               
                
                
            <?php
            }
            if ($this->edit->type=="youtube")
            {
            ?>	
            <td width="110" class="key">
            <label for="alias">
            <?php echo JText::_( 'LANG_TEXT' ); ?>:
            </label>
            </td>    
            <td> <textarea name="text" id="textfield1" cols="18" rows="7" ><?php echo $this->edit->text; ?></textarea></td>
            <br />
            <?php
            }
			if ($this->edit->type=="pic")
			{
            ?>	
            <td width="110" class="key">
            <label for="alias">
            <?php echo JText::_( 'LANG_TEXT' ); ?>:
            </label>
            </td>   
            <td> <textarea name="text" id="textfield1" cols="18" rows="7" ><?php echo $this->edit->text; ?></textarea></td>
            <?php
            }
            ?>
         
        </tr>
        <tr>
			<?php if ($this->edit->type!="postit")
            {
            ?> 
                <td class="key">
                <label for="lag">
                <?php echo JText::_( 'URL' ); ?>:
                </label>
                </td>
                <td>
				<?php if ($this->edit->type=="youtube")
                {
                ?>
                    <input class="inputboxdis" type="text" name="url" id="url" size="32" readonly="true" value="<?php echo $this->edit->url; ?>" />
                <?php
                }
                else
                {
                ?>
                    <input class="inputboxdis" type="text" name="url" id="url" size="32" readonly="true" value="<?php echo $this->edit->url; ?>" />
                <?php
                }
                ?>
                </td>
                
            <?php
            }
            else
            {
            ?>	   
            <td><input class="inputbox" type="hidden" name="url" id="text" size="32" value="<?php echo $this->edit->url; ?>" /></td>
            <?php
            }
            ?>
        </tr>    
       	
        <tr>
            <td class="key">
            <label for="lag">
            <?php echo JText::_( 'LANG_AUTHOR' ); ?>:
            </label>
            </td>
            <td>
            <input class="inputbox" type="text" name="author" id="author" size="32" value="<?php echo $this->edit->author; ?>" />
            </td>
        </tr>
        <tr>        		
            <td class="key">
            <label for="lag">
            <?php echo JText::_( 'LANG_UPDATED' ); ?>:
            </label>
            </td>       
            <td>
            <input class="inputboxdis" type="text" name="created" id="created" size="32" readonly="true" value="<?php  if(version_compare(JVERSION,'1.6.0','ge')) { echo JHTML::_('date', $this->edit->created , JText::_('DATE_RP_FORMAT')); } else {echo JHTML::_('date', $this->edit->created , JText::_('DATE_RP_FORMAT_J15'));}?>" />
            </td>
        </tr>
        <tr>
            <td width="120" class="key">
            <?php echo JText::_('LANG_STICKY'); ?>:
            </td>
            <td>
            <?php echo JHTML::_( 'select.booleanlist',  'sticky', 'class="inputbox"', $this->edit->sticky ); ?>
            </td>
        </tr>
        <tr>
            <td width="120" class="key">
            <?php echo JText::_( 'LANG_PUB' ); ?>:
            </td>
            <td>
            <?php echo JHTML::_( 'select.booleanlist',  'published', 'class="inputbox"', $this->edit->published ); ?>
            </td>
        </tr>
    </table>
    
    </fieldset>
</div>

<div id="rppreview">
        
            <?php
			
			if ($this->edit->type=="pic")
            {
				$pic=JPATH_ROOT.DS.'images'.DS.'realpin'.DS.$this->edit->url;
				$imagesize = getimagesize ($pic);
				$x=round($imagesize[0],0);
				$y=round($imagesize[1],0);
				if($x!=0){$width=$x/$y*250;$height=250;}
				echo "<div id=\"rp_pic\">";
				echo JHTML::_('image', JURI::root().'images/realpin/thumbs/'.$this->edit->url, $this->edit->url, array('width' => $width, 'height' => $height));
				echo "</div>";
            }
            
            if ($this->edit->type=="youtube")
            {	
				$url= $this->edit->url;  $url = str_replace("&", "&amp;", $url); $url = str_replace("watch?v=", "v/", $url);  $url = str_replace("de.", "www.", $url);
				?>
				<object type="application/x-shockwave-flash" data="<?php echo $url; ?>&amp;hl=de&amp;fs=1&amp;color1=0x3a3a3a&amp;color2=0x999999&amp;border=1" 
				width="280" height="240" align="middle">
				<param name="src" value="<?php echo $url; ?>&amp;hl=de&amp;fs=1&amp;color1=0x3a3a3a&amp;color2=0x999999&amp;border=1"></param>
				<param name="allowFullScreen" value="true"></param><param name="wmode" value="transparent"></param>
				</object>
				<?php
            } 

            if ($this->edit->type=="postit")
			{
            ?>
                <div id="rp_postit">
                <?php 
				$text=str_replace('src="','src="'.$this->baseurl.'/../',$this->edit->text);
                $text=str_replace(array("\r\n", "\r", "\n"), "<br />",$text);
				$avar = explode("<br />", $text);
                $len = count($avar);
				?>
                
                   <div class="rpscrolldown"><a href="javascript:void(0)"></a></div> 
                   <div class="rpscrollup"><a href="javascript:void(0)"></a></div> 
                
                <div class="rp_postit_layer">
                   <div class="rp_postit_content"> 
                   <?php echo $text;?>
                   </div>    
                </div>   
	
                
              </div>
            <?php
			}
			?>


</div>
</div>

<div class="clr"></div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_realpin" />
<input type="hidden" name="id" value="<?php echo $this->edit->id; ?>" />
<input type="hidden" name="rpname" value="<?php echo $this->rpname; ?>" />
<input type="hidden" name="pinboard" value="<?php echo $this->pinboard; ?>" />
<input type="hidden" name="community" value="<?php echo $this->community; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="edit" />
</form>


