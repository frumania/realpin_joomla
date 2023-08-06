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

function listfiles($dir)
{
$read_ordner_dir = opendir($dir);
$z=0;
while($datei = readdir ($read_ordner_dir))
{
       $str = explode(".",$datei);          // Liest die Dateiendung aus
       $ext = end($str);
	   if($ext=="php" or $ext=="ttf")
		  {
				  if ($datei != "." && $datei != ".." && $datei != "thumbs" && $datei != "thumbs.db")
				  {
						  if (is_dir($datei))                           // �berpr�ft ob es sich um ein Verzeichnis oder eine Datei handelt
						  {
						  }
						  else
						  {  
						  $list[$z]=$datei;          // Dateien in Array schreiben
						  $z++;
						  }
				 }
		 }
}
closedir ($read_ordner_dir);
sort($list);            // Dateien alphabetisch sortieren
foreach ($list as $newlist);

return $list;
}

	  
define("STATE_FALSE_IMG", "icon-cancel");
define("STATE_TRUE_IMG", "icon-save");


function getglobal($state)
{
$result=false;
		if($state=="false")
		$result = STATE_FALSE_IMG;
		if($state=="true")
		$result = STATE_TRUE_IMG;
	
return $result;
}

define("PINBOARD", $this->pinboard);
define("ISGLOBAL", $this->isglobal);

function visible($state)
{
$result=false;	
	
if($state=="false")
$result=true;
if($state=="true")
$result=false;

if(ISGLOBAL=="true"){$result=true;}

return $result;
}

//Thanks to Ilya Rudev <www <at> polar-lights <dot> com> (Polar Lights Labs)
function rsa_encrypt ($m,  $e,  $n) {
    $asci = array ();
    for ($i=0; $i<strlen($m); $i+=3) {
        $tmpasci="1";
        for ($h=0; $h<3; $h++) {
            if ($i+$h <strlen($m)) {
                $tmpstr = ord (substr ($m,  $i+$h,  1)) - 30;
                if (strlen($tmpstr) < 2) {
                    $tmpstr ="0".$tmpstr;
                }
            } else {
                break;
            }
            $tmpasci .=$tmpstr;
        }
        array_push($asci,  $tmpasci."1");
    }

	$coded="";
    for ($k=0; $k< count ($asci); $k++) {
        $resultmod = powmod($asci[$k],  $e,  $n);
        $coded .= $resultmod." ";
    }
    return trim($coded);
}

function powmod ($base,  $exp,  $modulus) {
    $accum = 1;
    $i = 0;
    $basepow2 = $base;
    while (($exp >> $i)>0) {
        if ((($exp >> $i) & 1) == 1) {
            $accum = mo(($accum * $basepow2) ,  $modulus);
        }
        $basepow2 = mo(($basepow2 * $basepow2) ,  $modulus);
        $i++;
    }
    return $accum;
}

function mo ($g,  $l) {
    return $g - ($l * floor ($g/$l));
}


$doc = JFactory::getDocument();

$style='
.icon-cancel
{
	color:#BD362F!important;
}
div.current 
{
    background: #FFFFFF;
}
.txt
{
color:#666;
text-align:left;
}
.sub
{
font-weight:bold;	
}
textarea, input, #HELPTEXT
{
font-size:10px;
font-family:Arial,Helvetica,sans-serif;
}

.admintable
{
border-spacing:8px;	
}

div.current input, div.current textarea, div.current select
{
    clear: none!important;
	float:none!important;
}
';

$doc->addStyleDeclaration( $style);

foreach ( $this->items as $key=>$value )
{ 
define(strtoupper($key),  $value);
}

foreach ( $this->globalitems as $key=>$value )
{ 
define(strtoupper($key)."_GLOBAL",  $value);
}

$db	= JFactory::getDBO();
$table="#__realpin_settings";
if($this->community==0){$query = "SELECT license FROM ".$table." WHERE config_id = '1'";}
if($this->community==1){$query = "SELECT license FROM ".$table." WHERE config_id = '".$this->pinboard."'";}
$db->setQuery($query);
//$db->execute();
//var_dump($db->loadResultArray());
$license = $db->loadResult();

//echo "License DB: ".$license;

if($license==""){$license=="0";}

$message1=JURI::root();
$message2=JPATH_ROOT;
$message2=str_replace("\\","",$message2);
$message1=str_replace(array("http://www.","http://","www.","https://","https://www."),"",$message1);
$encoded1 = str_replace(' ', "",rsa_encrypt ($message1,  7681,  60716087));
$encoded2 = str_replace(' ', "",rsa_encrypt ($message2,  7681,  60716087));

//echo "LicenseTheory: ".$encoded1." oder ".$encoded2;

if($encoded1 == str_replace(' ', "",$license) or $encoded2 == str_replace(' ', "",$license)){$licensed=true;}else{$licensed=false;}

define('LICENSED', $licensed);


$doc = JFactory::getDocument();
$doc->addScript(JURI::root().'components/com_realpin/includes/js/jquery.min.js');
//$doc->addScript(JURI::root().'components/com_realpin/includes/js/jquery-ui-1.8.5.custom.min.js');
$doc->addScript(JURI::root().'administrator/components/com_realpin/includes/color_picker/js/colorpicker.js');
$doc->addScript(JURI::root().'administrator/components/com_realpin/includes/ajax_upload/ajaxupload.3.6.js');
$doc->addStyleSheet(JURI::root().'administrator/components/com_realpin/includes/color_picker/css/colorpicker.css');

function render_header($tab,$globalvar,$stringvar)
{?>
<tr>
<td class="key">
<?php if(ISGLOBAL=="true" and $stringvar!="LICENSE"){?><a style="float:left;outline:none" title="Global ?" class="rp_global" value="<?php echo $globalvar; ?>" key="<?php echo $stringvar; ?>" href="javascript:void(0);">
<span class="<?php echo getglobal($globalvar);?>"></span></a><?php }; ?>
&nbsp;<?php echo JText::_('LANG_'.$tab.'_'.$stringvar.'_TITLE'); ?>:</td>
<td>
<?php
}

//if(ISGLOBAL=="true" and $stringvar!="LICENSE" and $stringvar!="LOGO" and $stringvar!="LOGO_ENABLE" and $stringvar!="LOGO_LINK" and $stringvar!="LOGO_SMALL" and $stringvar!="LOGO_SMALL_LINK" and $stringvar!="HELPTEXT_ENABLE" and $stringvar!="HELPLINK" and $stringvar!="HELPTEXT" and $stringvar!="LOGO_POSITION"){

function render_footer($tab,$stringvar)
{?>
</td>
<td width="600" class="txt"><?php echo JText::_('LANG_'.$tab.'_'.$stringvar.'_DESC'); ?></td>
</tr>
<?php
}

function render_check($tab,$stringvar)
{?>
<?php if(visible(ENABLE_POSTIT_GLOBAL) or visible(ENABLE_YT_GLOBAL) or visible(ENABLE_PIC_GLOBAL))
    { ?> 
        
            <tr><td class="key">
            <?php if(visible(ENABLE_POSTIT_GLOBAL) and ISGLOBAL=="true"){ ?> 
              <a style="float:left;outline:none" title="Global ?" class="rp_global" value="<?php echo ENABLE_POSTIT_GLOBAL; ?>" key="ENABLE_POSTIT" href="javascript:void(0);">
              <span class="<?php echo getglobal(ENABLE_POSTIT_GLOBAL);?>"</span></a>
            <?php } if(visible(ENABLE_YT_GLOBAL) and ISGLOBAL=="true"){ ?> 
              <a style="float:left;outline:none" title="Global ?" class="rp_global" value="<?php echo ENABLE_YT_GLOBAL; ?>" key="ENABLE_YT" href="javascript:void(0);">
              <span class="<?php echo getglobal(ENABLE_YT_GLOBAL);?>"</span></a>
            <?php } if(visible(ENABLE_PIC_GLOBAL) and ISGLOBAL=="true"){ ?> 
              <a style="float:left;outline:none" title="Global ?" class="rp_global" value="<?php echo ENABLE_PIC_GLOBAL; ?>" key="ENABLE_PIC" href="javascript:void(0);">
              <span class="<?php echo getglobal(ENABLE_PIC_GLOBAL);?>"</span></a>
			<?php } echo JText::_('LANG_'.$tab.'_'.$stringvar.'_TITLE'); ?>:
			</td><td>
            
			<?php if(visible(ENABLE_POSTIT_GLOBAL)){ ?>     
            <?php echo JText::_('LANG_OPT4'); ?>:
            <input type="checkbox" name="ENABLE[0]" value="1" <?php if(ENABLE_POSTIT=="1"){echo "checked=\"checked\"";} ?>>
            <?php } if(visible(ENABLE_YT_GLOBAL)){ ?> 
            <?php echo JText::_('LANG_OPT5'); ?>: 
            <input type="checkbox" name="ENABLE[1]" value="1" <?php if(ENABLE_YT=="1"){echo "checked=\"checked\"";} ?>>
            <?php } if(visible(ENABLE_PIC_GLOBAL)){ ?> 
            <?php echo JText::_('LANG_OPT6'); ?>:
            <input type="checkbox" name="ENABLE[2]" value="1" <?php if(ENABLE_PIC=="1"){echo "checked=\"checked\"";} ?>>
            <?php } ?>
                
			<?php render_footer($tab,$stringvar);
	}
}

function render_input($tab,$var,$globalvar,$stringvar,$size,$upload=false,$class="")
{
	if(visible($globalvar))
	{
	render_header($tab,$globalvar,$stringvar);?> 
	
	<input class="<?php echo $class; ?>" type="text" name="<?php echo $stringvar; ?>" id="<?php echo $stringvar; ?>" size="<?php echo $size; ?>" value="<?php echo $var; ?>" />
	<?php if($upload){?> <a style="margin-left:5px" id="upload_<?php echo strtolower($stringvar); ?>" href="javascript:void(0)"><?php echo JText::_('Upload'); ?></a> <?php } ?>
	<?php render_footer($tab,$stringvar);
	}
}

function render_textarea($tab,$var,$globalvar,$stringvar,$cols,$rows)
{
	if(visible($globalvar))
	{
	render_header($tab,$globalvar,$stringvar);?>
	
	<?php $helptext= str_replace("<br />", "\r\n", $var); ?>
	<textarea class="inputbox" name="<?php echo $stringvar; ?>" id="<?php echo $stringvar; ?>" cols="<?php echo $cols; ?>" rows="<?php echo $rows; ?>"><?php echo $helptext; ?></textarea> 
	
	<?php render_footer($tab,$stringvar);
	}
	
}

function render_empty($number)
{
	for($count = 0; $count < $number; $count++)
	{
	?>
	<tr><td></td></tr> 
	<?php
	}
}

function render_radio($tab,$var,$globalvar,$stringvar,$array,$class="")
{
	if(visible($globalvar))
	{
	render_header($tab,$globalvar,$stringvar);?>
	
	<?php if($var==""){$var="1";} ?>
	
		<?php for($count = 0; $count < count($array); $count++) {?>                       
		<input type="radio" name="<?php echo $stringvar; ?>" id="<?php echo $stringvar.$count; ?>" value="<?php echo $count; ?>" <?php if($var==$count){echo "checked=\"checked\"";} ?> class="<?php echo $class; ?>" />
		<?php echo $array[$count]; if($count<=(count($array)-1)){ echo "<br />"; }
		}?>
		
	<?php render_footer($tab,$stringvar);
	}
}

function render_select($tab,$var,$globalvar,$stringvar,$array,$class="")
{
	if(visible($globalvar))
	{
	render_header($tab,$globalvar,$stringvar);?>
							  
	<select id="<?php echo $stringvar; ?>" name="<?php echo $stringvar; ?>" class="<?php echo $class; ?>">
	<?php foreach ($array as $value) {?>
	<option value="<?php echo $value; ?>" <?php if($var==$value){echo "selected=\"selected\" ";} ?>><?php echo JText::_($value); ?></option>
	<?php }?>
	</select>
	
	<?php render_footer($tab,$stringvar);
	}
}
?>

<script language="javascript" type="text/javascript">
function GetRandom( min, max )
{
	if( min > max ) {
		return( -1 );
	}
	if( min == max ) {
		return( min );
	}
 
    return( min + parseInt( Math.random() * ( max-min+1 ) ) );
}

jQuery.noConflict();
jQuery(document).ready
(
		   
	function()
	{
		
	  jQuery('.rp_global').click(function()
	  {
	  var key=jQuery(this).attr("key");
	  var val=jQuery(this).attr("value");
	  var valtmp="false";	  
	  
	  if(val=="false"){valtmp="true";jQuery(this).find("span").attr("class", "<?php echo STATE_TRUE_IMG; ?>");}
	  if(val=="true"){valtmp="false";jQuery(this).find("span").attr("class", "<?php echo STATE_FALSE_IMG; ?>");}
	  jQuery(this).attr("value",valtmp);
	  
	  jQuery.ajax
		({
		  url: "index.php?option=com_realpin&controller=global&task=makeglobal&community=<?php echo $this->community; ?>&format=raw",
		  global: false,
		  type: "POST",
		  data:{key: key,val: valtmp},
		  dataType: "html",
		  success: function(msg)
		  {			 
				//gritter_message(msg,false,2000); //saved
				//alert(msg);
		  }
	   });
	  
	  });
	
	/*jQuery('#MODULE_FOREGROUND_COLOR,#MODULE_BACKGROUND_COLOR,#POSTIT_FONT_COLOR,#BORDER_BACKGROUND').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			jQuery(this).ColorPickerSetColor(this.value);
		});	
		
		jQuery('#PIC_FONT_COLOR,#PIC_BORDER_COLOR').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
				jQuery('#adminForm').find('#picchange').val('1');
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			jQuery(this).ColorPickerSetColor(this.value);
		});		
		
    jQuery('.pic').change(function() {
	jQuery('#adminForm').find('#picchange').val('1');
    });*/

<?php if((LOGO_GLOBAL=="false" or ISGLOBAL=="true") and LICENSED==true)
{
?>

	/* button1 */
	var button1 = jQuery('#upload_logo'), interval;
	new AjaxUpload(button1,{
		action: 'index.php?option=com_realpin&controller=global&task=upload&pinboard=<?php echo PINBOARD; ?>&format=raw', 
		name: 'myfile',
		onSubmit : function(file, ext){
			button1.text('Uploading');
			this.disable();
			interval = window.setInterval(function(){
				var text = button1.text();
				if (text.length < 13){
					button1.text(text + '.');					
				} else {
					button1.text('Uploading');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button1.text('<?php echo JText::_('Upload'); ?>');					
			window.clearInterval(interval);
			if(response=="success"){jQuery("#LOGO").val('<?php echo JURI::root()."images/realpin/misc/".PINBOARD."_";?>'+file);}else{alert('<?php echo JText::_('LANG_UPLOAD_FAILURE'); ?>');}
			this.enable();		
		}
	});

<?php } ?>

<?php if((LOGO_SMALL_GLOBAL=="false" or ISGLOBAL=="true") and LICENSED==true)
{
?>

/* button2 */
	var button2 = jQuery('#upload_logo_small'), interval;
	new AjaxUpload(button2,{
		action: 'index.php?option=com_realpin&controller=global&task=upload&pinboard=<?php echo PINBOARD; ?>&format=raw', 
		name: 'myfile',
		onSubmit : function(file, ext){			
			button2.text('Uploading');
			this.disable();
			interval = window.setInterval(function(){
				var text = button2.text();
				if (text.length < 13){
					button2.text(text + '.');					
				} else {
					button2.text('Uploading');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button2.text('<?php echo JText::_('Upload'); ?>');					
			window.clearInterval(interval);
			if(response=="success"){jQuery("#LOGO_SMALL").val('<?php echo JURI::root()."images/realpin/misc/".PINBOARD."_";?>'+file);}else{alert('<?php echo JText::_('LANG_UPLOAD_FAILURE'); ?>');}
			this.enable();					
		}
	});

<?php } ?>

<?php if(BACKGROUND_GLOBAL=="false" or ISGLOBAL=="true")
{
?>
 
			/* button3 */
	var button3 = jQuery('#upload_background'), interval;
	new AjaxUpload(button3,{
		action: 'index.php?option=com_realpin&controller=global&task=upload&pinboard=<?php echo PINBOARD; ?>&format=raw', 
		name: 'myfile',
		onSubmit : function(file, ext){			
			button3.text('Uploading');
			this.disable();
			interval = window.setInterval(function(){
				var text = button3.text();
				if (text.length < 13){
					button3.text(text + '.');					
				} else {
					button3.text('Uploading');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button3.text('<?php echo JText::_('Upload'); ?>');					
			window.clearInterval(interval);
			if(response=="success"){jQuery("#BACKGROUND").val('<?php echo JURI::root()."images/realpin/misc/".PINBOARD."_";?>'+file);}else{alert('<?php echo JText::_('LANG_UPLOAD_FAILURE'); ?>');}
			this.enable();					
		}
	});

<?php } ?>

<?php if(SCROLLLEFT_GLOBAL=="false" or ISGLOBAL=="true")
{
?>

		/* button4 */
	var button4 = jQuery('#upload_scrollleft'), interval;
	new AjaxUpload(button4,{
		action: 'index.php?option=com_realpin&controller=global&task=upload&pinboard=<?php echo PINBOARD; ?>&format=raw', 
		name: 'myfile',
		onSubmit : function(file, ext){			
			button4.text('Uploading');
			this.disable();
			interval = window.setInterval(function(){
				var text = button4.text();
				if (text.length < 13){
					button4.text(text + '.');					
				} else {
					button4.text('Uploading');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button4.text('<?php echo JText::_('Upload'); ?>');					
			window.clearInterval(interval);
			if(response=="success"){jQuery("#SCROLLLEFT").val('<?php echo JURI::root()."images/realpin/misc/".PINBOARD."_";?>'+file);}else{alert('<?php echo JText::_('LANG_UPLOAD_FAILURE'); ?>');}
			this.enable();					
		}
	});

<?php } ?>

<?php if(SCROLLRIGHT_GLOBAL=="false" or ISGLOBAL=="true")
{
?>

			/* button5 */
	var button5 = jQuery('#upload_scrollright'), interval;
	new AjaxUpload(button5,{
		action: 'index.php?option=com_realpin&controller=global&task=upload&pinboard=<?php echo PINBOARD; ?>&format=raw', 
		name: 'myfile',
		onSubmit : function(file, ext){			
			button5.text('Uploading');
			this.disable();
			interval = window.setInterval(function(){
				var text = button5.text();
				if (text.length < 13){
					button5.text(text + '.');					
				} else {
					button5.text('Uploading');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button5.text('<?php echo JText::_('Upload'); ?>');					
			window.clearInterval(interval);
			if(response=="success"){jQuery("#SCROLLRIGHT").val('<?php echo JURI::root()."images/realpin/misc/".PINBOARD."_";?>'+file);}else{alert('<?php echo JText::_('LANG_UPLOAD_FAILURE'); ?>');}
			this.enable();					
		}
	});

<?php } ?>

	jQuery("#DESIGN").change(function() 
    { 
        var index 
 
        index = jQuery("#DESIGN").val(); 
 
        if (index == "kork") 
        {
			jQuery("#BACKGROUND").val("default");
			jQuery("#BORDER_BACKGROUND").val("default");
			jQuery("#BORDER").val("40");
		}
		if (index == "wooden") 
        {
		    jQuery("#BACKGROUND").val("<?php echo JURI::root(); ?>components/com_realpin/includes/css/wooden.jpg");
			jQuery("#BORDER_BACKGROUND").val("000000");
			jQuery("#BORDER").val("20");
		}
		if (index == "transparent") 
        {
			jQuery("#BACKGROUND").val("transparent");
			jQuery("#BORDER_BACKGROUND").val("transparent");
			jQuery("#BORDER").val("0");
		}
		if (index == "custom1") 
        {
		    jQuery("#BACKGROUND").val("http://www.wallpaperbase.com/wallpapers/3d/3dspace/3d_space_32.jpg");
			jQuery("#BORDER_BACKGROUND").val("CCCCCC");
			jQuery("#SCROLLLEFT").val("http://www.gettyicons.com/free-icons/112/must-have/png/48/next_48.png");
			jQuery("#SCROLLRIGHT").val("http://www.gettyicons.com/free-icons/112/must-have/png/48/previous_48.png");
		}
		if (index == "custom2") 
        {
		    jQuery("#BACKGROUND").val("http://fotomagazin.de/images/galerie/wueste_ausgangsbild.jpg");
			jQuery("#BORDER_BACKGROUND").val("000000");	
		}
	
		
	});	
	}
);	

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>

<?php
jimport ('joomla.html.html.bootstrap');


$this->tabsOptionsJ31['active'] = "tab1_j31_id";
if(ISGLOBAL=="false")
$this->tabsOptionsJ31['active'] = "tab2_j31_id";

echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-J31-Group', $this->tabsOptionsJ31);
?>

<!--LICENSE SETTINGS-->

<?PHP $show_tab="true"; if(LOGO_GLOBAL=="true" && LOGO_ENABLE_GLOBAL=="true" && LOGO_POSITION_GLOBAL=="true" && LOGO_LINK_GLOBAL=="true" && LOGO_SMALL_GLOBAL=="true" && LOGO_SMALL_LINK_GLOBAL=="true" && HELPTEXT_ENABLE_GLOBAL=="true" && HELPLINK_GLOBAL=="true" && HELPTEXT_GLOBAL=="true"){$show_tab="false";}?>

<?php if(LICENSED==false and ISGLOBAL=="false"){$show_tab="false";} ?>

<?php if(LICENSED==false and ISGLOBAL=="false" and $this->community==1){$show_tab="true";} ?>

<?php if(($show_tab=="true" or ISGLOBAL=="true")){echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab1_j31_id', JText::_('LANG_PART0')); echo "<table cellpadding=\"4\" class=\"admintable\">"; ?>

		<?php render_empty(1); ?>                  
        <tr><td class="sub"><?php echo JText::_('LANG_PART0').": ";?></td>
        <?php if(LICENSED==true){echo "<td><span style=\"color:#00BA00;\"><b>PRO-VERSION</b></span>";}else{echo "<td style=\"background-color:#FF6\"><span style=\"color:#FF0000;\">STANDARD-VERSION </span><a href=\"https://realpin.frumania.com/buy.html\" target=\"_blank\">".JText::_('LANG_LICENSE_GET')."</a>";}?>
        </td><td class="txt">(Installation:&nbsp;<?php echo JPATH_ROOT; ?>)</td></tr>
        <?php render_empty(1); ?> 
        
        <?php render_input("LICENSE",LICENSE,LICENSE_GLOBAL,"LICENSE",40);  ?>    
        
	<?php if(LICENSED==true){ ?>      
             
        <?php render_radio("LICENSE",LOGO_ENABLE,LOGO_ENABLE_GLOBAL,"LOGO_ENABLE",array(JText::_('LANG_HIDE'),JText::_('LANG_SHOW'))); ?>                      
        <?php render_input("LICENSE",LOGO,LOGO_GLOBAL,"LOGO",40, true); ?>       
        <?php render_input("LICENSE",LOGO_LINK,LOGO_LINK_GLOBAL,"LOGO_LINK",40); ?>      
        <?php render_select("LICENSE",LOGO_POSITION,LOGO_POSITION_GLOBAL,"LOGO_POSITION",array(JText::_('LANG_LEFT'),JText::_('LANG_CENTER'),JText::_('LANG_RIGHT'))); ?>
        <?php render_input("LICENSE",LOGO_SMALL,LOGO_SMALL_GLOBAL,"LOGO_SMALL",40,true); ?>      
        <?php render_input("LICENSE",LOGO_SMALL_LINK,LOGO_SMALL_LINK_GLOBAL,"LOGO_SMALL_LINK",40); ?>       
        <?php render_radio("LICENSE",LOGO_NEW_ENABLE,LOGO_NEW_ENABLE_GLOBAL,"LOGO_NEW_ENABLE",array(JText::_('LANG_HIDE'),JText::_('LANG_SHOW'))); ?>         
        <?php render_radio("LICENSE",HELPTEXT_ENABLE,HELPTEXT_ENABLE_GLOBAL,"HELPTEXT_ENABLE",array(JText::_('LANG_HIDE'),JText::_('LANG_SHOW'))); ?>       
        <?php render_textarea("LICENSE",HELPTEXT,HELPTEXT_GLOBAL,"HELPTEXT",36,2); ?>       
        <?php render_input("LICENSE",HELPLINK,HELPLINK_GLOBAL,"HELPLINK",40); ?>   
        <?php render_textarea("LICENSE",CUSTOM_CSS,CUSTOM_CSS_GLOBAL,"CUSTOM_CSS",36,6); ?>

	<?php } ?>      
        
<?php echo "</table>"; echo JHtml::_('bootstrap.endTab');  } ?>

<!--END LICENSE SETTINGS--> 



<!--GENERAL SETTINGS--> 
   		
<?PHP $show_tab="true"; if(LIFETIME_GLOBAL=="true" && POSITIONING_GLOBAL=="true" && MAXITEMS_GLOBAL=="true" && JQUERY_COMPATIBILITY_GLOBAL=="true" && ENABLE_JQUERY_GLOBAL=="true" && ENABLE_JQUERYUI_GLOBAL=="true" && ENABLE_YT_GLOBAL=="true" && REMOVAL_GLOBAL=="true" && SAVE_GLOBAL=="true" && APPROVAL_GLOBAL=="true" && DRAG_GLOBAL=="true" && NEW_ITEM_GLOBAL=="true" && EMAIL_GLOBAL=="true" && USERNAME_GLOBAL=="true"){$show_tab="false";}?>

<?php if($show_tab=="true" or ISGLOBAL=="true"){echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab2_j31_id', JText::_('LANG_PART2')); echo "<table cellpadding=\"4\" class=\"admintable\">"; ?>
                 
    <?php render_empty(1); ?>         
    <tr><td width="150" class="sub"><?php echo JText::_( 'LANG_PART2'); ?>:
    </td><td><?php //echo JHTML::_('image', 'administrator/templates/khepri/images/header/icon-48-frontpage.png', 'icon-48-frontpage.png', array('width' => '48', 'height' => '48')); ?></td></tr>       
    <?php render_empty(1); ?> 
         
    <?php render_input("MAIN",LIFETIME,LIFETIME_GLOBAL,"LIFETIME",40); ?>      
    <?php render_radio("MAIN",POSITIONING,POSITIONING_GLOBAL,"POSITIONING",array(JText::_('LANG_MAIN_POSITIONING_AUTO'),JText::_('LANG_MAIN_POSITIONING_MANUELL'),JText::_('LANG_MAIN_POSITIONING_RANDOM'))); ?>         
    <?php render_select("MAIN",MAXITEMS,MAXITEMS_GLOBAL,"MAXITEMS",array("auto","6","8","10","12","15","18","20","25","30","40","50")); ?>          
    <?php render_radio("MAIN",JQUERY_COMPATIBILITY,JQUERY_COMPATIBILITY_GLOBAL,"JQUERY_COMPATIBILITY",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?>       
    <?php render_radio("MAIN",ENABLE_JQUERY,ENABLE_JQUERY_GLOBAL,"ENABLE_JQUERY",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?>    
    <?php render_radio("MAIN",ENABLE_JQUERYUI,ENABLE_JQUERYUI_GLOBAL,"ENABLE_JQUERYUI",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?> 
    <?php render_check("MAIN","ENABLE"); ?> 	
    <?php render_radio("MAIN",REMOVAL,REMOVAL_GLOBAL,"REMOVAL",array(JText::_('LANG_MAIN_OPT1A'),JText::_('LANG_MAIN_OPT2C'))); ?>    
    <?php render_radio("MAIN",DRAG,DRAG_GLOBAL,"DRAG",array(JText::_('LANG_MAIN_OPT1A'),JText::_('LANG_MAIN_OPT2C'),JText::_('LANG_MAIN_OPT3'))); ?>
    <?php render_radio("MAIN",SAVE,SAVE_GLOBAL,"SAVE",array(JText::_('LANG_MAIN_OPT1A'),JText::_('LANG_MAIN_OPT2C'),JText::_('LANG_MAIN_OPT3'))); ?>   
	<?php render_radio("MAIN",APPROVAL,APPROVAL_GLOBAL,"APPROVAL",array(JText::_('LANG_MAIN_OPT1'),JText::_('LANG_MAIN_OPT2B'),JText::_('LANG_MAIN_OPT4'))); ?>   
	<?php render_radio("MAIN",NEW_ITEM,NEW_ITEM_GLOBAL,"NEW_ITEM",array(JText::_('LANG_MAIN_OPT1A'),JText::_('LANG_MAIN_OPT2C'),JText::_('LANG_MAIN_OPT3'))); ?> 
    <?php render_radio("MAIN",EMAIL,EMAIL_GLOBAL,"EMAIL",array(JText::_('LANG_MAIN_OPT1'),JText::_('LANG_MAIN_OPT2B'),JText::_('LANG_MAIN_OPT4'))); ?>   
    <?php render_input("MAIN",USERMAIL,USERMAIL_GLOBAL,"USERMAIL",40); ?>
	<?php render_radio("MAIN",USERNAME,USERNAME_GLOBAL,"USERNAME",array(JText::_('LANG_MAIN_USERNAME_A'),JText::_('LANG_MAIN_USERNAME_B'))); ?>
    <?php render_radio("MAIN",USEREDIT,USEREDIT_GLOBAL,"USEREDIT",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?> 
    <?php render_radio("MAIN",CSS_FILE,CSS_FILE_GLOBAL,"CSS_FILE",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?>
	<?php render_radio("MAIN",GDPR,GDPR_GLOBAL,"GDPR",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?>
        
<?php echo "</table>"; echo JHtml::_('bootstrap.endTab');  } ?>

<!--END GENERAL SETTINGS-->


 

<!--DESIGN SETTINGS--> 

<?PHP $show_tab="true"; if(BACKGROUND_GLOBAL=="true" && TOTAL_WIDTH_GLOBAL=="true" && TOTAL_HEIGHT_GLOBAL=="true" && BORDER_GLOBAL=="true" && BORDER_BACKGROUND_GLOBAL=="true" && SCROLLLEFT_GLOBAL=="true" && SCROLLRIGHT_GLOBAL=="true"){$show_tab="false";}?>

<?php if($show_tab=="true" or ISGLOBAL=="true"){echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab3_j31_id', JText::_('LANG_PART1')); echo "<table cellpadding=\"4\" class=\"admintable\">"; ?>   
		
	<?php render_empty(1); ?>           
    <tr><td class="sub"><?php echo JText::_('LANG_PART1'); ?>:
    </td><td><?php //echo JHTML::_('image', 'administrator/templates/khepri/images/header/icon-48-media.png', 'icon-48-media.png', array('width' => '48', 'height' => '48')); ?></td>  
    <td class="txt"><?php echo JText::_('LANG_DESIGN_PREDEF'); ?>&nbsp;&nbsp;
    <select id="DESIGN" name="DESIGN">
    <option value="" ><?php echo JText::_('LANG_DESIGN_SELECT'); ?></option>
    <option value="kork">kork</option>
    <option value="wooden">wooden</option>
    <option value="transparent">transparent</option>
    <option value="custom1">custom1</option>
    <option value="custom2">custom2</option>
    </select></td></td></tr>
    <?php render_empty(1); ?> 

	<?php render_input("DESIGN",BACKGROUND,BACKGROUND_GLOBAL,"BACKGROUND",40,true); ?>
	<?php render_input("DESIGN",TOTAL_WIDTH,TOTAL_WIDTH_GLOBAL,"TOTAL_WIDTH",40); ?>   
    <?php render_input("DESIGN",TOTAL_HEIGHT,TOTAL_HEIGHT_GLOBAL,"TOTAL_HEIGHT",40); ?>
	<?php render_input("DESIGN",BORDER,BORDER_GLOBAL,"BORDER",40); ?>   
    <?php render_input("DESIGN",BORDER_BACKGROUND,BORDER_BACKGROUND_GLOBAL,"BORDER_BACKGROUND",40); ?>
	<?php render_input("DESIGN",SCROLLLEFT,SCROLLLEFT_GLOBAL,"SCROLLLEFT",40,true); ?>    
    <?php render_input("DESIGN",SCROLLRIGHT,SCROLLRIGHT_GLOBAL,"SCROLLRIGHT",40,true); ?>           
            
<?php echo "</table>"; echo JHtml::_('bootstrap.endTab');  } ?>

<!--END DESIGN SETTINGS-->


        
<!--MODUL SETTINGS-->
 
<?PHP $show_tab="true"; if(MODULE_SEARCH_GLOBAL=="true" && MODULE_SOCIAL_GLOBAL=="true" && MODULE_FOREGROUND_COLOR_GLOBAL=="true" && MODULE_BACKGROUND_COLOR_GLOBAL=="true"){$show_tab="false";}?>

<?php if($show_tab=="true" or ISGLOBAL=="true"){echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab4_j31_id', JText::_('LANG_PART6')); echo "<table cellpadding=\"4\" class=\"admintable\">"; ?>
          
	<?php render_empty(1); ?>               
    <tr><td class="sub"><?php echo JText::_('LANG_PART6'); ?>:</td>
    </td><td><?php //echo JHTML::_('image', 'administrator/templates/khepri/images/header/icon-48-module.png', 'icon-48-module.png', array('width' => '48', 'height' => '48')); ?></td></tr>              
    <?php render_empty(1); ?>  
         
    <?php render_radio("MODULE",MODULE_SEARCH,MODULE_SEARCH_GLOBAL,"MODULE_SEARCH",array(JText::_('LANG_HIDE'),JText::_('LANG_SHOW'))); ?> 
    <?php render_select("MODULE",MODULE_SEARCH_POSITION,MODULE_SEARCH_POSITION_GLOBAL,"MODULE_SEARCH_POSITION",array("left","center","right")); ?>   
    <?php render_radio("MODULE",MODULE_SOCIAL,MODULE_SOCIAL_GLOBAL,"MODULE_SOCIAL",array(JText::_('LANG_HIDE'),JText::_('LANG_SHOW'))); ?>
    <?php render_select("MODULE",MODULE_SOCIAL_POSITION,MODULE_SOCIAL_POSITION_GLOBAL,"MODULE_SOCIAL_POSITION",array("top","center","bottom")); ?>        
    <?php render_input("MODULE",MODULE_FOREGROUND_COLOR,MODULE_FOREGROUND_COLOR_GLOBAL,"MODULE_FOREGROUND_COLOR",40); ?>   
    <?php render_input("MODULE",MODULE_BACKGROUND_COLOR,MODULE_BACKGROUND_COLOR_GLOBAL,"MODULE_BACKGROUND_COLOR",40); ?>      

<?php echo "</table>"; echo JHtml::_('bootstrap.endTab');  } ?>

<!--END MODULE SETTINGS--> 

   
 
<!--POSTIT SETTINGS--> 

<?PHP $show_tab="true"; if(POSTIT_FONT_GLOBAL=="true" && POSTIT_FONT_SIZE_GLOBAL=="true" && POSTIT_FONT_COLOR_GLOBAL=="true" && POSTIT_FONT_REPLACE_GLOBAL=="true"){$show_tab="false";}?>

<?php if($show_tab=="true" or ISGLOBAL=="true"){echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab5_j31_id', JText::_('LANG_PART3')); echo "<table cellpadding=\"4\" class=\"admintable\">"; ?>             
           
	<?php render_empty(1); ?>                     
    <tr><td class="sub"><?php echo JText::_('LANG_PART3'); ?>:</td>
    <td width="150"><?php echo JHTML::_('image', 'components/com_realpin/includes/css/new/postit.jpg', 'postit.jpg', array('width' => '25%', 'height' => '25%')); ?></td></tr>             
    <?php render_empty(1); ?>       
        
    <?php if(POSTIT_FONT_REPLACE=="0"){
	render_select("ITEMS",POSTIT_FONT,POSTIT_FONT_GLOBAL,"POSTIT_FONT",array("Arial, Helvetica, sans-serif","Verdana, Geneva, sans-serif","Palatino Linotype, Book Antiqua, Palatino, serif"));} 
    else{
	render_select("ITEMS",POSTIT_FONT,POSTIT_FONT_GLOBAL,"POSTIT_FONT",listfiles(JPATH_ROOT.'/components/com_realpin/includes/fonts/'));} ?>                     
    <?php render_input("ITEMS",POSTIT_FONT_SIZE,POSTIT_FONT_SIZE_GLOBAL,"POSTIT_FONT_SIZE",40); ?>      
    <?php render_input("ITEMS",POSTIT_FONT_COLOR,POSTIT_FONT_COLOR_GLOBAL,"POSTIT_FONT_COLOR",40); ?>     
    <?php render_radio("ITEMS",POSTIT_FONT_REPLACE,POSTIT_FONT_REPLACE_GLOBAL,"POSTIT_FONT_REPLACE",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?>        
 
<?php echo "</table>"; echo JHtml::_('bootstrap.endTab');  } ?>

<!--END POSTIT SETTINGS-->


 
<!--VIDEO SETTINGS--> 

<?PHP $show_tab="true"; if(YOUTUBE_X_GLOBAL=="true" && YOUTUBE_Y_GLOBAL=="true" && YT_VALIDATE_GLOBAL=="true"){$show_tab="false";}?>

<?php if($show_tab=="true" or ISGLOBAL=="true"){echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab6_j31_id', JText::_('LANG_PART4')); echo "<table cellpadding=\"4\" class=\"admintable\">"; ?>
        
	<?php render_empty(1); ?>           
    <tr><td class="sub"><?php echo JText::_('LANG_PART4'); ?>:</td>
    <td><?php echo JHTML::_('image', 'components/com_realpin/includes/css/new/video.jpg', 'video.jpg', array('width' => '25%', 'height' => '25%')); ?></td></tr>          
    <?php render_empty(1); ?> 

    <?php render_input("ITEMS",YOUTUBE_X,YOUTUBE_X_GLOBAL,"YOUTUBE_X",40); ?>      
    <?php render_input("ITEMS",YOUTUBE_Y,YOUTUBE_Y_GLOBAL,"YOUTUBE_Y",40); ?>     
    <?php render_radio("ITEMS",YT_VALIDATE,YT_VALIDATE_GLOBAL,"YT_VALIDATE",array(JText::_('LANG_NO'),JText::_('LANG_YES'))); ?>               

<?php echo "</table>"; echo JHtml::_('bootstrap.endTab');  } ?>
     
<!--END VIDEO SETTINGS-->  

  
            
<!--IMAGE SETTINGS-->

<?PHP $show_tab="true"; if(PIC_SCALE_GLOBAL=="true" && PIC_FONT_GLOBAL=="true" && PIC_FONT_SIZE_GLOBAL=="true" && PIC_FONT_COLOR_GLOBAL=="true" && PIC_BORDER_GLOBAL=="true" && PIC_BORDER_COLOR_GLOBAL=="true" && PIC_ROTATE_GLOBAL=="true"){$show_tab="false";}?>

<?php if($show_tab=="true" or ISGLOBAL=="true"){echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab7_j31_id', JText::_('LANG_PART5')); echo "<table cellpadding=\"4\" class=\"admintable\">"; ?>        
        
	<?php render_empty(1); ?>                     
    <tr><td class="sub"><?php echo JText::_('LANG_PART5'); ?>:</td>
    <td><?php echo JHTML::_('image', 'components/com_realpin/includes/css/new/bild.jpg', 'bild.jpg', array('width' => '25%', 'height' => '25%')); ?></td></tr>
    <?php render_empty(1); ?>       
            
    <?php render_select("ITEMS",PIC_SCALE,PIC_SCALE_GLOBAL,"PIC_SCALE",array("0.7","0.8","0.9","1.1","1.2","1.3"),'pic'); ?>  
    <?php render_select("ITEMS",PIC_FONT,PIC_FONT_GLOBAL,"PIC_FONT",listfiles(JPATH_ROOT.'/components/com_realpin/includes/fonts/'),'pic'); ?>           
    <?php render_input("ITEMS",PIC_FONT_SIZE,PIC_FONT_SIZE_GLOBAL,"PIC_FONT_SIZE",40,false,'pic'); ?>   
    <?php render_input("ITEMS",PIC_FONT_COLOR,PIC_FONT_COLOR_GLOBAL,"PIC_FONT_COLOR",40,false,'pic'); ?> 
    <?php render_input("ITEMS",PIC_BORDER,PIC_BORDER_GLOBAL,"PIC_BORDER",40,false,'pic'); ?>      
    <?php render_input("ITEMS",PIC_BORDER_COLOR,PIC_BORDER_COLOR_GLOBAL,"PIC_BORDER_COLOR",40,false,'pic'); ?>     
    <?php render_radio("ITEMS",PIC_ROTATE,PIC_ROTATE_GLOBAL,"PIC_ROTATE",array(JText::_('LANG_NO'),JText::_('LANG_YES')),false,'pic'); ?>
    <?php render_radio("ITEMS",PIC_FANCYBOX_INFO,PIC_FANCYBOX_INFO_GLOBAL,"PIC_FANCYBOX_INFO",array(JText::_('LANG_HIDE'),JText::_('LANG_SHOW')),false,'pic'); ?> 
    <?php render_radio("ITEMS",PIC_FANCYBOX_LOGO,PIC_FANCYBOX_LOGO_GLOBAL,"PIC_FANCYBOX_LOGO",array(JText::_('LANG_HIDE'),JText::_('LANG_SHOW')),false,'pic'); ?>
    <?php render_radio("ITEMS",PIC_CHECK_SERVER_MEMORY,PIC_CHECK_SERVER_MEMORY_GLOBAL,"PIC_CHECK_SERVER_MEMORY",array(JText::_('LANG_NO'),JText::_('LANG_YES')),false,'pic'); ?>
    <?php render_input("ITEMS",PIC_NO_RESTRICT_SIZE,PIC_NO_RESTRICT_SIZE_GLOBAL,"PIC_NO_RESTRICT_SIZE",20,false,'pic'); ?>    
       
<?php echo "</table>"; echo JHtml::_('bootstrap.endTab');  } ?>

<!--END IMAGE SETTINGS-->

<?php echo JHtml::_('bootstrap.endTabSet');?>   
   
</div>
<div class="clr"></div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_realpin" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="community" value="<?php echo $this->community; ?>" />
<input type="hidden" name="global" value="<?php echo ISGLOBAL; ?>" />
<input type="hidden" name="pinboard" value="<?php echo PINBOARD; ?>" />
<input type="hidden" name="picchange" id="picchange" value="0" />
<input type="hidden" name="controller" value="global" />
</form>
