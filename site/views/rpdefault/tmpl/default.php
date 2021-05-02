<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

$i=0;
$data=array();

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
$data[$i][9]=$dbdata->author_id;

$i++;
}

if(ITEMSPERPAGE>sizeof($data))
{$layers=sizeof($data);}
else
{$layers=ITEMSPERPAGE;}
$total_pin=sizeof($data);
$image_total=0;

for($h = 0; $h < $layers; $h++)
{
		
$id= $data[$h][0];
$type= $data[$h][3];
$text= $data[$h][4]; 
$url= $data[$h][5]; 
$title= $data[$h][6];
$author=$data[$h][7];
$created=$data[$h][8];

	if($type=="pic")
	{
	create_thumb_jpg_kl($url,$title);
	create_thumb_jpg_gr($url);
	$image_total++;
	}
}

require_once (JPATH_COMPONENT.DS.'includes'.DS.'js.php');
require_once (JPATH_COMPONENT.DS.'includes'.DS.'css.php');
?>
<div id="rp_main">
   
<div id="rp_wooden_frame">

<div id="rp_kork_frame">
  
<?php
$image_no=0;
for($h = 0; $h < $layers; $h++)
{
		
$id= $data[$h][0];
$type= $data[$h][3];
$text= $data[$h][4]; 
$url= $data[$h][5]; 
$title= $data[$h][6];
$author=$data[$h][7];
$created=$data[$h][8];
$author_id=$data[$h][9];
  
?>
 
<div id="rp_ly<?php echo $id;?>" class="rp_dragger <?php if($id==RPID){echo "rp_current";} ?>">

 <div class="rp_icons">
  
  <?php
   $title=remove_all_special($title);
   $text=remove_all_special($text);
   $author=remove_all_special($author);
   $url=remove_all_special($url);
	   
   //$created=JHTML::_('date',$created, JText::_('DATE_RP_FORMAT'));	
   
  if(version_compare(JVERSION,'1.6.0','ge')) {
  // Joomla! 1.6 code here
   $created=JHTML::_('date',$created, JText::_('DATE_RP_FORMAT'));	
  } else {
  // Joomla! 1.5 code here
   $created=JHTML::_('date',$created, JText::_('DATE_RP_FORMAT_J15'));	
  }  

   create_icons($image_no,$id,$type,$title,$text,$url,$created,$author)
  ?>   
          
  </div>

  <div class="rp_content" rp_author_id="<?php echo $author_id; ?>" rp_id="<?php echo $id; ?>">
  <?php
  if ($type=="pic")
    {
    create_pic($id,$text,$title,$url);
	$image_no++;
    } 

  if ($type=="postit")
    {	
    create_postit($id,$text,$title,$author);
    }
    
  if ($type=="youtube")
    {
    create_youtube($id,$title,$url,$text);
    }	
  ?>			
  </div>

</div>
<?php
}
?>

<!--shadows, dekoration etc.-->

<?php if (BORDER!="0" and (BORDER_BACKGROUND=="default" or BORDER_BACKGROUND=="")){ ?>
<div id="rp_inline1"></div>
<div id="rp_inline2"></div>
<?php }?>


<!-- new layout-->
 <div id="new_rp_item">
 
  <div id="new_blend" class="new_blend rp_tips" title="<?php echo JText::_('LANG_CLOSE'); ?>">
  <a href="javascript:void(0)"></a>
  </div> 
  
  <div id="new_info" class="new_info rp_tips" title="<?php echo JText::_('LANG_INFO'); ?>">
  <a id="new_info_window" class="new_info_fancybox" href="#new_info_window_content"></a>
  </div> 
      
  <div class="rp_help">
  <div id="new_info_window_content" class="new_info_window">
  <a href="<?php echo LOGO_SMALL_LINK; ?>" target="_blank"><img src="<?php echo SMALL_LOGO; ?>" /></a>
  <br />
  <br />
  <?php echo JText::_('LANG_NEW_HELP'); ?>
  <br />
  <a href="javascript:;" onclick="<?php echo $rpjs; ?>.fancybox.close();"><?php echo JText::_('LANG_CLOSE'); ?></a><br /><br /><br /><br />
  <div>&copy; 2021 - <a href="https://realpin.frumania.com" target="_blank"><span>RealPin, <?php  echo JText::_('LANG_VIRT'); ?></span></a>
 </div>
 </div></div>

  
  <div class="new_desc" id="new_desc_header">
  <?php echo JText::_('LANG_NEW_TITLE'); ?>
  </div>  
  
  <div id="new_rp_item_content">
  <?php
  if (NEW_ITEM == "2" or (NEW_ITEM=="1" and USER>=18) or (NEW_ITEM=="0" and USER>=24))
	{
  ?>
      <ul>
      
      <li>
      
      <?php 
	  if (ENABLE_POSTIT=="1")
	  {
	  $desc=JText::_('LANG_NEW_TITLE_POSTIT');
	  }
	  else
	  {
	  $desc=JText::_('LANG_NEW_ERROR_POSTIT');  
	  }
	  ?>
      <div id="new_postit" class="rp_tips" title="<?php echo $desc; ?>">
      <a href="javascript:void(0)"></a>
      </div>
      
      <div class="new_desc" id="new_desc_postit">
      <?php echo JText::_('LANG_NEW_OPT_POSTIT'); ?>
      </div> 
      
      </li>
      
      <li>
      
      <?php 
	  if (ENABLE_PIC=="1")
	  {
	  $desc=JText::_('LANG_NEW_TITLE_IMAGE');
	  }
	  else
	  {
	  $desc=JText::_('LANG_NEW_ERROR_IMAGE');  
	  }
	  ?>
      <div id="new_image" class="rp_tips" title="<?php echo $desc; ?>">
      <a href="javascript:void(0)"></a>   
      </div>
      
      <div class="new_desc" id="new_desc_image">
      <?php echo JText::_('LANG_NEW_OPT_IMAGE'); ?>
      </div> 
        
      </li>
      <li>
      
      <?php 
	  if (ENABLE_YT=="1")
	  {
	  $desc=JText::_('LANG_NEW_TITLE_VIDEO');
	  }
	  else
	  {
	  $desc=JText::_('LANG_NEW_ERROR_VIDEO');  
	  }
	  ?>		
      <div id="new_video" class="rp_tips" title="<?php echo $desc; ?>">
      <a href="javascript:void(0)"></a>   
      </div> 
     
      <div class="new_desc" id="new_desc_video">
      <?php echo JText::_('LANG_NEW_OPT_VIDEO'); ?>
      </div> 
      
      </li>
<!--      <li>
      
      <div id="new_twitter">
      <a href="#"></a>   
      </div> 
     
      <div class="new_desc" id="new_desc_twitter">
      Twitter
      </div> 
      
      </li>
      <li>
      
      <div id="new_poll">
      <a href="#"></a>   
      </div> 
     
      <div class="new_desc" id="new_desc_poll">
      Umfrage
      </div> 
      
      </li>
      <li>
      
      <div id="new_idea">
      <a href="#"></a>   
      </div> 
     
      <div class="new_desc" id="new_desc_idea">
      Was fehlt noch?
      </div> 
    
     </li>-->
     
     </ul>
    <?php
	}
	else
	{
	?>
    <br/>
    <br/>
    <div id="new_item_error">
    <img src="<?php echo SMALL_LOGO; ?>" name="error" border="0" />
    <br/><br/>
    <?php echo JText::_('LANG_NEW_ERROR'); ?>
    <br/><br/>
    <?php echo JText::_('LANG_NEW_ERROR_AD'); ?> <a href="https://realpin.frumania.com" target="_blank">https://realpin.frumania.com</a>.
    <br/><br/><br/>
    &copy; 2021 - <a href="https://realpin.frumania.com" target="_blank"><span>RealPin - <?php  echo JText::_('LANG_VIRT'); ?></span></a>
     </div>
    <?php
	}
	?>
  </div>
  

  </div>
<!--end new-->

<!-- new image -->
<div id="new_image_layer">

 <div id="new_image_input_upload">
  <?php echo JText::_('LANG_NEW_IMAGE3'); ?>
 </div>
 
 <div id="new_image_title">
  <input name="new_image_title" type="text" class="new_image_input_title" value="<?php echo JText::_('LANG_NEW_IMAGE5'); ?>" style="width:100%" />
 </div>
 
 <div id="new_image_blend" class="new_blend rp_tips" title="<?php echo JText::_('LANG_CLOSE'); ?>">
  <a href="javascript:void(0)"></a>
  </div>
  
   <div id="new_image_info" class="new_info rp_tips" title="<?php echo JText::_('LANG_INFO'); ?>">
   <a id="new_image_info_window" class="new_info_fancybox" href="#new_image_info_window_content"></a>
  </div> 
      
  <div class="rp_help">
  <div id="new_image_info_window_content" class="new_info_window">
  <a href="<?php echo LOGO_SMALL_LINK; ?>" target="_blank"><img src="<?php echo SMALL_LOGO; ?>" /></a>
  <br />
  <br />
  <?php echo JText::_('LANG_NEW_IMAGE_HELP'); ?>
  <br />
  <a href="javascript:;" onclick="<?php echo $rpjs; ?>.fancybox.close();"><?php echo JText::_('LANG_CLOSE'); ?></a><br /><br /><br /><br />
  <div>&copy; 2021  - <a href="https://realpin.frumania.com" target="_blank">
  <span>RealPin, <?php  echo JText::_('LANG_VIRT'); ?></span></a>
  </div> 
  </div></div>
  
   <div id="new_image_ok" class="new_ok rp_tips" title="<?php echo JText::_('LANG_OK'); ?>">
  <a href="javascript:void(0)"></a>
  </div>
  
  
  <div id="new_image_desc_header" class="new_desc">
  <?php echo JText::_('LANG_NEW_OPT_IMAGE2'); ?>
  </div>

  <div id="new_image_wrapper"><img src="<?php echo ROOT; ?>/css/no_pic.jpg" name="penguins" width="150" style="max-height:170px!important" border="0" id="penguins" />

  </div>
        
        <div class="new_inputs">
        
          <form id="new_image_form" name="new_image_form" method="post" action="">
          <br />
          <?php echo JText::_('LANG_NEW_IMAGE1'); ?><br />
          <input name="new_image_name" type="text" class="new_input_author" size="28" value="<?php $user = JFactory::getUser(); if (!$user->guest)
		  {
			  if(USERNAME=="1"){
			  echo $user->name;
		      }else{
			  echo $user->username;
			  }
		  
		  } ?>" <?php if (USEREDIT=="0" and !$user->guest) {echo "READONLY";} ?>/>
          <br />
          <br />
          <?php echo JText::_('LANG_NEW_IMAGE2'); ?><br />
          <textarea name="new_image_text" class="new_input_text" cols="25" rows="7"></textarea>
          <br />

          <?php if (USER >= 24){ ?>

          <div style="margin1-top:10px" class="rp_sticky" title="<?php echo JText::_('LANG_STICKY_ALT'); ?>">
            <input style="width:20px!important" type="checkbox" class="new_image_sticky" name="new_image_sticky"><?php echo JText::_('LANG_STICKY'); ?>
          </div>

          <?php } ?>

          <?php if (GDPR == "1"){ ?>

          <div style="margin1-top:10px" class="rp_gdpr" title="<?php echo JText::_('LANG_GDPR_ALT'); ?>">
            <input style="width:20px!important" type="checkbox" class="new_gdpr" name="new_gdpr"><?php echo JText::_('LANG_GDPR'); ?>
          </div>

          <?php } ?>

          </form>
		
		</div>

</div>
<!-- ende new image -->


<!-- new postit -->
<div id="new_postit_layer">
 
  <div id="new_postit_blend" class="new_blend rp_tips" title="<?php echo JText::_('LANG_CLOSE'); ?>">
  <a href="javascript:void(0)"></a>
  </div>
  
  <div id="new_postit_info" class="new_info rp_tips" title="<?php echo JText::_('LANG_INFO'); ?>">
  <a id="new_postit_info_window" class="new_info_fancybox" href="#new_postit_info_window_content"></a>
  </div> 
      
  <div class="rp_help">
  <div id="new_postit_info_window_content" class="new_info_window">
  <a href="<?php echo LOGO_SMALL_LINK; ?>" target="_blank"><img src="<?php echo SMALL_LOGO; ?>" /></a>
  <br /><br />
  <?php echo JText::_('LANG_NEW_POSTIT_HELP'); ?>
  <br />
  <a href="javascript:;" onclick="<?php echo $rpjs; ?>.fancybox.close();"><?php echo JText::_('LANG_CLOSE'); ?></a><br /><br /><br /><br />
  <div>&copy; 2021 - <a href="https://realpin.frumania.com" target="_blank">
  <span>RealPin, <?php  echo JText::_('LANG_VIRT'); ?></span></a>
  </div>
  </div></div>
  
   <div id="new_postit_ok" class="new_ok rp_tips" title="<?php echo JText::_('LANG_OK'); ?>">
  <a href="javascript:void(0)"></a>
  </div>
  
  
  <div id="new_postit_desc_header" class="new_desc">
  <?php echo JText::_('LANG_NEW_OPT_POSTIT2'); ?>
  </div>

  <div class="new_postit_wrapper">
       <div class="new_postit_wrapper_scrolldown rp_tips" title="<?php echo JText::_('LANG_SCROLL_DOWN'); ?>"><a href="javascript:void(0)"></a></div> 
       <div class="new_postit_wrapper_scrollup rp_tips" title="<?php echo JText::_('LANG_SCROLL_UP'); ?>"><a href="javascript:void(0)"></a></div> 
        <div class="new_postit_wrapper_text">
        <div class="new_postit_wrapper_text_content">
        <?php echo JText::_('LANG_NEW_POSTIT3'); ?>
        </div>
        </div>
  </div>
  
  <div class="new_postit_wrapper_edit" style="display:none">
  <div class="new_postit_wrapper_edit_item" id="postit1" style="background-color:#FC0;"><a style="display:block" href="javascript:void(0)">&nbsp;</a></div>
  <div class="new_postit_wrapper_edit_item" id="postit2" style="background-color:#093;"><a style="display:block" href="javascript:void(0)">&nbsp;</a></div>
  <div class="new_postit_wrapper_edit_item" id="postit3" style="background-color:#F30;"><a style="display:block" href="javascript:void(0)">&nbsp;</a></div>
  </div>
        
         <div class="new_inputs">
        
          <form id="new_postit_form" name="new_postit_form" method="post" action="">
          <br />
          <?php echo JText::_('LANG_NEW_POSTIT1'); ?>
          <br />
          <input name="new_postit_name" type="text" class="new_input_author" size="28" value="<?php $user = JFactory::getUser(); if (!$user->guest)
		  {	
			  if(USERNAME=="1"){
			  echo $user->name;
		      }else{
			  echo $user->username;
			  }
		  
		  } ?>" <?php if(USEREDIT=="0" and !$user->guest) {echo "READONLY";} ?>/>
          <br />
          <br />
          <?php echo JText::_('LANG_NEW_POSTIT2'); ?><br />
          <textarea name="new_postit_text" id="new_postit_text" class="new_input_text" cols="25" rows="6"></textarea>
          <br />

          <?php if (USER >= 24){ ?>
          
          <div style="margin1-top:10px" class="rp_sticky" title="<?php echo JText::_('LANG_STICKY_ALT'); ?>">
            <input style="width:20px!important" type="checkbox" class="new_image_sticky" name="new_image_sticky"><?php echo JText::_('LANG_STICKY'); ?>
          </div>

          <?php } ?>

          <?php if (GDPR == "1"){ ?>

          <div style="margin1-top:10px" class="rp_gdpr" title="<?php echo JText::_('LANG_GDPR_ALT'); ?>">
            <input style="width:20px!important" type="checkbox" class="new_gdpr" name="new_gdpr"><?php echo JText::_('LANG_GDPR'); ?>
          </div>

          <?php } ?>

          </form>
		
		</div>

</div>
<!-- ende new postit -->


<!-- new video -->
<div id="new_video_layer">

 
  <div id="new_video_blend" class="new_blend rp_tips" title="<?php echo JText::_('LANG_CLOSE'); ?>">
  <a href="javascript:void(0)"></a>
  </div>
  
  <div id="new_video_info" class="new_info rp_tips" title="<?php echo JText::_('LANG_INFO'); ?>">
  <a id="new_video_info_window" class="new_info_fancybox" href="#new_video_info_window_content"></a>
  </div> 
      
  <div class="rp_help">
  <div id="new_video_info_window_content" class="new_info_window">
  <a href="<?php echo LOGO_SMALL_LINK; ?>" target="_blank"><img src="<?php echo SMALL_LOGO; ?>" /></a>
  <br /><br />
   <?php echo JText::_('LANG_NEW_VIDEO_HELP'); ?>
  <br />
  <a href="javascript:;" onclick="<?php echo $rpjs; ?>.fancybox.close();">  <?php echo JText::_('LANG_CLOSE'); ?></a><br /><br /><br /><br />
  <div>&copy; 2021 - <a href="https://realpin.frumania.com" target="_blank">
  <span>RealPin, <?php  echo JText::_('LANG_VIRT'); ?></span></a>
  </div>
  </div></div>
  
  <div id="new_video_ok" class="new_ok rp_tips" title=" <?php echo JText::_('LANG_OK'); ?>">
  <a href="javascript:void(0)"></a>
  </div>
  
  
  <div id="new_video_desc_header" class="new_desc">
   <?php echo JText::_('LANG_NEW_OPT_VIDEO2'); ?>
  </div>

  <div class="new_video_wrapper">
         <form id="new_video_url_form" style="margin:0; text-align:left" name="new_video_url_form" method="post" action="">
         <br />
         <?php echo JText::_('LANG_NEW_VIDEO3'); ?>
         <input name="new_video_url" type="text" class="new_input_url" size="20" /><input type='button' value="check" style="margin-top:-10px" id="new_video_input_submit"/>
         </form>
         <div id="new_video_show_div"><img width="140px" src="<?php echo ROOT; ?>/css/new/video.jpg" /></div>
  </div>
        
        <div class="new_inputs">
        
          <form id="new_video_form" name="new_video_form" method="post" action="">
          <br />
          <?php echo JText::_('LANG_NEW_VIDEO1'); ?><br />
          <input name="new_video_name" type="text" class="new_input_author" size="28" value="<?php $user = JFactory::getUser(); if (!$user->guest)
		  {
			  if(USERNAME=="1"){
			  echo $user->name;
		      }else{
			  echo $user->username;
			  }
		  
		  } ?>" <?php if (USEREDIT=="0" and !$user->guest) {echo "READONLY";} ?>/>
          <br />
          <br />
          <?php echo JText::_('LANG_NEW_VIDEO2'); ?><br />
          <textarea name="new_video_text" id="new_video_text" class="new_input_text" cols="25" rows="7"></textarea>
          <br />
          
          <?php if (USER >= 24){ ?>

          <div style="margin1-top:10px" class="rp_sticky" title="<?php echo JText::_('LANG_STICKY_ALT'); ?>">
            <input style="width:20px!important" type="checkbox" class="new_image_sticky" name="new_image_sticky"><?php echo JText::_('LANG_STICKY'); ?>
          </div>

          <?php } ?>

          <?php if (GDPR == "1"){ ?>

          <div style="margin1-top:10px" class="rp_gdpr" title="<?php echo JText::_('LANG_GDPR_ALT'); ?>">
            <input style="width:20px!important" type="checkbox" class="new_gdpr" name="new_gdpr"><?php echo JText::_('LANG_GDPR'); ?>
          </div>

          <?php } ?>

          </form>
		
		</div>

</div>
<!-- ende new video -->

<!-- DO NOT REMOVE - RESPECT OPEN SOURCE -->
<div id="rp_info_author">
<?php 
if(LICENSED==false)
{
	$helptext= str_replace(array("\r\n", "\r", "\n"), "&lt;br /&gt;", "RealPin, ".JText::_('LANG_VIRT'));
	$helplink = "https://realpin.frumania.com";	
}
else
{
	$helptext= str_replace(array("\r\n", "\r", "\n"), "&lt;br /&gt;", HELPTEXT);
	$helplink= HELPLINK;
}
?>
<a href="<?php echo $helplink; ?>" target="_blank" class="rp_tips" title="<?php echo $helptext; ?>"></a>
</div>

<?php
$logourl="https://realpin.frumania.com";
if(LICENSED==true and LOGO_LINK!="")
{$logourl=LOGO_LINK;}
?>
<div id="rp_logo">
<a href="<?php echo $logourl; ?>" target="_blank" <?php if(LICENSED==false){?> class="rp_tips" title="<?php  echo JText::_('LANG_VIRT'); ?>" <?php }; ?>>
<?php if(LOGO=="default" or LICENSED!=true){$logo_url=ROOT."/css/logo_quer.png";}else{$logo_url=LOGO;}?>
<img src="<?php echo $logo_url; ?>" alt="logo" />
</a>
</div>

<div id="rp_new">
<a class="rp_tips" href="javascript:void(0)" title="<?php  echo JText::_('LANG_ICON_NEW'); ?>"></a>
</div>

<table id="rp_page_table">
<tr><td>
<div id="rp_page_backward" style="width:100px" page="0">
<a class="rp_tips" href="javascript:void(0)" title="<?php  echo JText::_('LANG_ICON_PAGE_BACKWARD'); ?>">
<?php if(SCROLLLEFT=="default"){$scrollleft_url=ROOT."/css/backward.png";}else{$scrollleft_url=SCROLLLEFT;}?>
<img src="<?php echo $scrollleft_url; ?>" alt="backward" />
</a>
</div>
</td><td></td><td>
<div id="rp_page_forward" style="width:100px" page="0">
<a class="rp_tips" href="javascript:void(0)" title="<?php  echo JText::_('LANG_ICON_PAGE_FORWARD'); ?>">
<?php if(SCROLLRIGHT=="default"){$scrollright_url=ROOT."/css/forward.png";}else{$scrollright_url=SCROLLRIGHT;}?>
<img src="<?php echo $scrollright_url; ?>" alt="forward" />
</a>
</div>
</tr>
<tr><td></td><td>
<div id="rp_page">
<span>1/<?php echo ceil($total_pin/ITEMSPERPAGE); ?></span>
</div>
</td><td></td>
</tr>
</table>

<?php $user = JFactory::getUser(); if (!$user->guest) { ?>
<?php if (USER >= 24 or (REMOVAL=="1" and USER>=18) or (COMMUNITY==1 and USERNAME==PINBOARD)){ ?>
<div id="rp_trash">
<a class="rp_tips" href="javascript:void(0)" title="<?php  echo JText::_('LANG_TRASH'); ?>"></a>
</div>
<?php } } ?>	

<div id="rp_version">
<?php if(LICENSED==true){?>
<span <?php if(DEBUG==0){ echo "style=\"display:none\"";} ?> >RealPin - <?php  echo JText::_('LANG_VIRT'); ?> - <?php  echo "v.".VERSION; ?> (P@r-o) </span>
<?php }else{ ?>
<span <?php if(DEBUG==0){ echo "style=\"display:none\"";} ?> >RealPin - <?php  echo JText::_('LANG_VIRT'); ?> - <?php  echo "v.".VERSION; ?></span>
<?php } ?>
</div>

<div id="rp_mobile_gallery"></div>

</div><!--end div kork-->

<?php if (BORDER!="0" and (BORDER_BACKGROUND=="default" or BORDER_BACKGROUND=="")){ ?>
<div id="rp_marker1"></div>
<div id="rp_marker2"></div>
<div id="rp_marker3"></div>
<div id="rp_marker4"></div>
<?php }?>

<input type="hidden" name="rp_author_id" id="rp_author_id" value="<?php $user = JFactory::getUser(); if (!$user->guest){ echo $user->id; } ?>">
<input type="hidden" name="rp_debug" id="rp_debug" value="<?php echo DEBUG; ?>">

</div><!--end div wooden-->

</div><!--end div realpin-->

<div id="rp_dialog-overlay"></div>
<div id="rp_dialog-box">
<div class="rp_dialog-content">
<div id="rp_dialog-message"></div>
<a href="javascript:wantmobile(true)" class="rp_dialog_button">View Mobile (current)</a>
<a href="javascript:wantmobile(false)" class="rp_dialog_button">View Classic</a>
</div>
</div>