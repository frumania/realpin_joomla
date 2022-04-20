<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');
?>

<style type="text/css">

#rp_dialog-overlay{width:100%;height:100%;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity:0.5;opacity:0.5;background:#000;position:absolute;top:0;left:0;z-index:3000;display:none;}#rp_dialog-box{-webkit-box-shadow:0px 0px 10px rgba(0,0,0,0.5);-moz-box-shadow:0px 0px 10px rgba(0,0,0,0.5);-moz-border-radius:5px;-webkit-border-radius:5px;background:#eee;width:328px;position:absolute;z-index:5000;display:none;}#rp_dialog-box .rp_dialog-content{text-align:left;padding:10px;margin:13px;color:#666;font-family:arial;font-size:11px;}a.rp_dialog_button{margin:10px auto 0 auto;text-align:center;background-color:#e33100;display:block;width:80px;height:20px;padding:5px 10px 6px;color:#fff;text-decoration:none;font-weight:bold;line-height:1;-moz-border-radius:5px;-webkit-border-radius:5px;-moz-box-shadow:0 1px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 1px 3px rgba(0,0,0,0.5);text-shadow:0 -1px 1px rgba(0,0,0,0.25);border-bottom:1px solid rgba(0,0,0,0.25);position:relative;cursor:pointer;}a.rp_dialog_button:hover{background-color:#c33100;}#rp_dialog-box .rp_dialog-content p{font-weight:700;margin:0;}#rp_dialog-box .rp_dialog-content ul{margin:10px 0 10px 20px;padding:0;height:50px;}</style>

<?php

if(TOTAL_WIDTH == "100%")
{
$dimension_x= "100%"; //TOTAL_HEIGHT+BORDER.'px';
$dimension_x_border= "100%"; // TOTAL_HEIGHT+BORDER.'px';
$dimension_y= TOTAL_HEIGHT.'px';
$dimension_y_border= TOTAL_HEIGHT+BORDER.'px';
}
else 
{
$dimension_x= TOTAL_WIDTH.'px';
$dimension_x_border= TOTAL_WIDTH+BORDER.'px';
$dimension_y= TOTAL_HEIGHT.'px';
$dimension_y_border= TOTAL_HEIGHT+BORDER.'px';	
}

?>


<?php
if (POSTIT_FONT_REPLACE=="1")
{
if(!file_exists(JPATH_COMPONENT.DS."includes".DS."fonts".DS.POSTIT_FONT)){$font="cartohothic-webfont";}else{$font=substr(POSTIT_FONT, 0, -4);}
?>

@font-face 
{
	font-family: '<?php echo $font; ?>';
	src: url('<?php echo ROOT;?>/fonts/<?php echo $font; ?>.eot?#iefix') format('embedded-opentype'), 
	     url('<?php echo ROOT;?>/fonts/<?php echo $font; ?>.woff') format('woff'), 
	     url('<?php echo ROOT;?>/fonts/<?php echo $font; ?>.ttf')  format('truetype'),
	     url('<?php echo ROOT;?>/fonts/<?php echo $font; ?>.svg#svgFontName') format('svg');
	font-weight: normal;
    font-style: normal;
}

<?php
}
?>

/*body
{
margin:-25px 0 0 0 !important;
padding:0 !important;
}*/
.rp_highlight
{
text-decoration:underline;
color:#F00;
}

.rp_postit_layer
{
font-family: <?php echo $font; ?>, Arial, sans-serif!important;
font-size:<?php echo POSTIT_FONT_SIZE; ?>px!important;
color:#<?php echo POSTIT_FONT_COLOR; ?>!important;
white-space:normal;
line-height:normal;
margin: 40px auto;
text-align:center!important;
}

.rpscrolldown
{
position:absolute;
bottom:10px;
left:165px;
}

.rpscrolldown a, .rpscrolldown a:hover, .rpscrolldown a:active, .rpscrolldown a:focus
{
display:block;	
width:10px;
height:12px;
background-image:url(<?php echo ROOT?>/css/scrolldown.png)!important;
background-color:transparent!important;
border-color:transparent;
outline:none;
}

.rpscrollup
{
position:absolute;
bottom:12px;
left:15px;
}

.rpscrollup a, .rpscrollup a:hover, .rpscrollup a:active, .rpscrollup a:focus
{
display:block;	
width:10px;
height:12px;
background-image:url(<?php echo ROOT?>/css/scrollup.png)!important;
background-color:transparent!important;
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

.rp_postit_layer a, .rp_postit_layer a:visited
{
font-family: <?php echo $font; ?>, Arial, sans-serif!important;
font-size:<?php echo POSTIT_FONT_SIZE; ?>px!important;
color:#09F!important;
padding:0px!important;
line-height:normal;
font-weight:normal!important;
outline:none;
text-decoration:none;
text-align:center!important;
}

<?php
if(strval(TOTAL_WIDTH)<620)
{
$window_left=80;
$window_top=170;	
}
else
{
$window_left=120;
$window_top=110;
}
?>
/*-----------------Neuer Beitrag-Styling-----------------*/
#new_rp_item 
{
position:absolute;
left: <?php echo $window_left; ?>px;
top: <?php echo $window_top; ?>px;
width:500px;
height:250px;
background-color:#FFF!important;
display:none;
z-index:200;
}

#new_rp_item_content 
{
position:absolute;
background-color: #FFF;
width:470px;
height:210px;
left:20px;
top:20px;
overflow:hidden;
}

#new_rp_item_content ul
{
width: 500px;
height: 210px;
left:0px;
background-color: white;
list-style:none outside none;
list-style-image:none;
}

#new_rp_item_content li
{
color: #888;
width: 150px;
font-size: 15px;
list-style-type: none;
background-color: white;
padding:0;
margin:0;
background-image:none;
list-style-image:none;
}

#new_postit 
{
position:absolute;
left:5px;
top:30px;
width:127px;
height:140px;
z-index:50;
}

<?php 
if(ENABLE_POSTIT=="1")
{ 
$background="/css/new/postit.jpg";
}
else
{
$background="/css/new/postit_bw.jpg";	
}
?>

#new_postit a, #new_postit a:hover, #new_postit a:active, #new_postit a:focus
{
display:block;		
width:127px;
height:140px;
background-image:url(<?php echo ROOT.$background;?>)!important;
background-color:transparent!important;
border-color:transparent;
outline:none;
}


#new_image 
{
position:absolute;
left:145px;
top:30px;
width:146px;
height:140px;
z-index:50;
}

<?php 
if(ENABLE_PIC=="1")
{ 
$background="/css/new/bild.jpg";
}
else
{
$background="/css/new/bild_bw.jpg";	
}
?>

#new_image a, #new_image a:hover, #new_image a:active, #new_image a:focus
{
display:block;	
width:146px;
height:140px;
background-image:url(<?php echo ROOT.$background;?>)!important;
background-color:transparent!important;
border-color:transparent;
outline:none;
}

#new_video  
{
position:absolute;
left:315px;
top:30px;
width:143px;
height:140px;
z-index:50;
}

<?php 
if(ENABLE_YT=="1")
{ 
$background="/css/new/video.jpg";
}
else
{
$background="/css/new/video_bw.jpg";	
}
?>

#new_video a, #new_video a:hover, #new_video a:active, #new_video a:focus
{
display:block;	
width:143px;
height:140px;
background-image:url(<?php echo ROOT.$background;?>)!important;
background-color:transparent!important;
border-color:transparent;
outline:none;
}

#new_desc_header
{
position:absolute;
background-color: #FFF;
padding-left:5px;
width:400px;
height:20px;
left:25px;
top:5px;
z-index:2;
border-bottom-style: solid;
border-bottom-color: #CCC;
border-bottom-width: 1px;
}

#new_desc_postit
{
position:absolute;
background-color: #FFF;
width:38px;
height:18px;
left:50px;
top:175px;
}

#new_desc_image
{
position:absolute;
background-color: #FFF;
width:50px;
height:18px;
left:200px;
top:175px;
}

#new_desc_video
{
position:absolute;
background-color: #FFF;
width:38px;
height:18px;
left:375px;
top:175px;
}

/*#new_desc_twitter
{
position:absolute;
background-color: #FFF;
width:38px;
height:18px;
left:540px;
top:160px;
}

#new_desc_poll
{
position:absolute;
background-color: #FFF;
width:38px;
height:18px;
left:690px;
top:160px;
}

#new_desc_idea
{
position:absolute;
background-color: #FFF;
width:100px;
height:18px;
left:825px;
top:160px;
}
*/
/*#new_twitter 
{
position:absolute;
left:495px;
top:20px;
width:143px;
height:143px;
z-index:9999;
}

#new_twitter a
{
display:block;	
width:143px;
height:143px;
background-image:url(<?php echo ROOT;?>/css/new/twitter.jpg);
border-color:transparent;
}

#new_poll
{
position:absolute;
left:650px;
top:20px;
width:143px;
height:124px;
z-index:9999;
}

#new_poll a
{
display:block;		
width:143px;
height:124px;
background-image:url(<?php echo ROOT;?>/css/new/umfrage.jpg);
border-color:transparent;
}

#new_idea
{
position:absolute;
left:800px;
top:20px;
width:143px;
height:124px;
z-index:9999;
}

#new_idea a
{
display:block;	
width:143px;
height:124px;
background-image:url(<?php echo ROOT;?>/css/new/idee.jpg);
border-color:transparent;
}
*/

.new_desc 
{
font-size: 12px;
font-family: Arial, Helvetica, sans-serif ;
font-size: 12px;
color:#000;
}

#new_item_error
{
color:#000;
font-family: Arial, Helvetica, sans-serif ;
font-size: 12px;	
}

#new_item_error a {
color:#0099FF !important;
font-family: Arial, Helvetica, sans-serif  !important;
font-size:12px !important;
font-weight:normal !important;
text-decoration:none !important;
}

.new_info_window
{
width:550px;
height:auto;
text-align:left!important;
background-color: #FFF!important;
color:#000!important;
font-family: Arial, Helvetica, sans-serif !important;
font-size: 12px!important;
line-height:normal;
padding-left:10px!important;
padding-right:10px!important;
}

.new_info_window ul
{
list-style:inside!important;
background-color: #FFF!important;
color:#000!important;
text-align:left!important;
}

.new_info_window li
{
padding-left:10px!important;
list-style:inside!important;
background-color: #FFF!important;
color:#000!important;
text-align:left!important;
}

.new_info_window a
{
font-weight:normal!important;
font-family: Arial, Helvetica, sans-serif !important;
font-size: 12px!important;
color:#09F!important;
text-decoration:none!important;
background:none!important;
}

.new_info_window a img
{
border:none!important;
}

.new_info_window ul li
{
background:none;
line-height:normal;
padding-left:0;
padding-right:0;
padding-top:0;
padding-bottom:0;
margin:0;
}

#new_image_form label,#new_postit_form label,#new_video_form label
{
border-style: none!important;
color:#000!important;
background-color:transparent!important;
font-family: Arial, Helvetica, sans-serif !important;
font-size:10px!important;
outline:none!important;	
font-style:italic!important;
}

.new_input_author, .new_input_text, .new_input_url
{
font-size:10px!important;
border-style: none!important;
background-color:#fdefc5!important;
background-image:none!important;
font-family: Arial, Helvetica, sans-serif !important;
outline:none!important;
color:#000!important;
line-height:normal!important;
text-align:left!important;
padding:0!important;

-moz-box-sizing: none !important;
border: 0 !important;
box-shadow: none !important;
height: 18px !important;
vertical-align: middle !important;

}

.new_input_author:focus, .new_input_text:focus, .new_input_url:focus,.new_input_author:hover,.new_input_text:focus,.new_input_url:hover
{
font-size:10px!important;
border-style: none!important;
background-color:#fdefc5!important; 
font-family: Arial, Helvetica, sans-serif !important;
outline:none!important;
color:#000!important;
line-height:normal!important;
text-align:left!important;
padding:0!important;
}

.new_inputs 
{
position:absolute;
top:10px;
width: 155px;
height: 188px;
margin:20px;
right: 5px;
font-size:10px;
text-align:left;
}

.new_inputs input
{
font-size:10px!important;
border-style: none!important;
background-color:#fdefc5;
font-family: Arial, Helvetica, sans-serif !important;
outline:none!important;
color:#000!important;
line-height:normal!important;
text-align:left!important;
padding:0!important;
background-image:none;
width: 140px!important;
}

.new_inputs label
{
font-size:10px!important;
border-style: none!important;
background-color:#fdefc5!important; 
font-family: Arial, Helvetica, sans-serif !important;
outline:none!important;
color:#000!important;
line-height:normal!important;
text-align:left!important;
padding:0!important;
}

.new_inputs textarea
{
font-size:10px!important;
border-style: none!important;
background-color:#fdefc5!important; 
font-family: Arial, Helvetica, sans-serif !important;
outline:none!important;
color:#000!important;
line-height:normal!important;
text-align:left!important;
padding:0!important;
background-image:none!important;
width: 140px!important;
height: 80px !important;
}

.new_info
{
position:absolute;
right:40px;
top:5px;
z-index:3;
width: 20px;
height: 20px;
}

.new_info a, .new_info a:hover, .new_info a:active, .new_info a:focus
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -120px 0px!important;
outline:none!important;
border:none!important;
}

.new_blend
{
position:absolute;
right:10px;
top:5px;
z-index:3;
width: 20px;
height: 20px;
}

.new_blend a, .new_blend  a:hover, .new_blend  a:active, .new_blend  a:focus
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -143px 0px!important;
outline:none!important;
border:none!important;
}

.new_ok
{
position:absolute;
right:10px;
bottom:5px;
z-index:3;
width: 20px;
height: 20px;
}

.new_ok a, .new_ok  a:hover, .new_ok  a:active, .new_ok  a:focus
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -165px 0px!important;
outline:none!important;
border:none!important;
}

/*start new_postit*/

#new_postit_layer
{
position:absolute;
width:440px;
height:260px;
background-color: #FFF;
color:#000;
font-family: Arial, Helvetica, sans-serif ;
font-size: 12px;
text-align:center;
left: <?php echo $window_left; ?>px;
top: <?php echo $window_top; ?>px;
display:none;
z-index:200;
}

.new_postit_wrapper div{
text-align:center!important;
}

#new_postit_desc_header
{
position:absolute;
background-color: #FFF;
padding-left:5px;
width:340px;
height:18px;
left:21px;
top:5px;
z-index:2;
text-align:left;
border-bottom-style: solid;
border-bottom-color: #CCC;
border-bottom-width: 1px;
}

.new_postit_wrapper_edit
{
position:absolute;
margin-top:125px;
left:230px;	
}

.new_postit_wrapper_edit_item
{
width:12px;
height:12px;
-moz-border-radius: 10px;
border-radius: 10px; 
}

.new_postit_wrapper 
{
position:absolute;
margin-top:30px;
left:30px;
width: 200px;
height: 207px;
background: url(<?php echo ROOT;?>/css/postit_advanced.png);
}

.new_postit_wrapper_text
{
width: 170px;
height: 150px;
overflow:hidden;
margin: 40px auto!important;
font-family: <?php echo $font; ?>, Arial, sans-serif!important;
font-size:<?php echo POSTIT_FONT_SIZE; ?>px!important;
color:#<?php echo POSTIT_FONT_COLOR; ?>!important;
white-space:normal!important;
line-height:normal!important;
text-align:center!important;
}

.new_postit_wrapper_text_content
{	
width:150px;
height:150px;
overflow:hidden;
text-align:center!important;
margin: 0 auto;
}


.new_postit_wrapper_scrolldown
{
position:absolute;
bottom:20px;
left:165px;
}

.new_postit_wrapper_scrolldown a, .new_postit_wrapper_scrolldown a:hover, .new_postit_wrapper_scrolldown a:active, .new_postit_wrapper_scrolldown a:focus
{
display:block;	
width:10px;
height:12px;
background-image:url(<?php echo ROOT?>/css/scrolldown.png)!important;
background-color:transparent!important;
border-color:transparent;
outline:none;
}

.new_postit_wrapper_scrollup
{
position:absolute;
bottom:20px;
left:15px;
}

.new_postit_wrapper_scrollup a, .new_postit_wrapper_scrollup a:hover, .new_postit_wrapper_scrollup a:active, .new_postit_wrapper_scrollup a:focus
{
display:block;	
width:10px;
height:12px;
background-image:url(<?php echo ROOT?>/css/scrollup.png)!important;
background-color:transparent!important;
border-color:transparent;
outline:none;
}

/*end new_postit*/

/*start new_image*/

#new_image_layer
{
position:absolute;
width:480px;
height:260px;
background-color: #FFF;
font-family: Arial, Helvetica, sans-serif ;
font-size: 12px;
text-align:center;
left: <?php echo $window_left; ?>px;
top: <?php echo $window_top; ?>px;
display:none;
z-index:200;
}

#new_image_desc_header
{
position:absolute;
background-color: #FFF;
padding-left:5px;
width:380px;
height:18px;
left:21px;
top:5px;
z-index:2;
text-align:left;
border-bottom-style: solid;
border-bottom-color: #CCC;
border-bottom-width: 1px;
}

#new_image_wrapper 
{
position:absolute;
top:27px;
width: 226px;
height: 170px;
margin:20px;
font-size:10px;
left: 2px;
}

#new_image_wrapper img
{
padding:0px!important;
}

#new_image_input_upload 
{
position:absolute;
top:104px;
padding: 5px;
font-size:12px;
font-weight:bold;
background-color:#FDEFC5 !important;
opacity:0.8;
color:#F00;
left: 82px;
outline:none;
z-index:60;
}

#new_image_title
{
position:absolute;
top:193px;
padding: 5px;
font-size:12px;
font-weight:bold;
background-color:#FDEFC5 !important;
opacity:0.8;
color:#F00;
left: 22px;
z-index:60;
width: 216px;
text-align:left;
outline:none;
}

.new_image_input_title, .new_image_input_title:hover
{
border-style: none!important;
background-color:transparent!important;
background-image:none!important;
color:#F00!important;
font-size:12px!important;
font-weight:bold!important;
font-family: Arial, Helvetica, sans-serif !important;
}

/*end new image*/

/*start new_video*/

#new_video_layer
{
position:absolute;
width:480px;
height:270px;
background-color: #FFF;
font-family: Arial, Helvetica, sans-serif ;
font-size: 12px;
text-align:center;
left: <?php echo $window_left; ?>px;
top: <?php echo $window_top; ?>px;
display:none;
z-index:200;
}

#new_video_desc_header
{
position:absolute;
background-color: #FFF;
padding-left:5px;
width:380px;
height:18px;
left:21px;
top:5px;
z-index:2;
text-align:left;
border-bottom-style: solid;
border-bottom-color: #CCC;
border-bottom-width: 1px;
}

.new_video_loading 
{
background: url(<?php echo ROOT;?>/css/ajax-loader.gif) no-repeat center center;
}

.new_video_wrapper 
{
position:absolute;
top:8px;
width: 260px;
height: 170px;
margin:20px;
font-size:10px;
left: 2px;
text-align:left;
}

#new_video_input_submit
{
color:#000!important;
background-color:#fdefc5!important; 
border:1px solid!important; 
font-family: Arial, Helvetica, sans-serif !important;
font-size:12px!important;
outline:none!important;	
}

/*end new video*/


/*-----------------Tip-Styling---------------------*/

#mytips
{
background-color:#000;
font-family: Arial, Helvetica, sans-serif ;
font-size:12px;
color:#FFF;
opacity: 0.8;
padding: 10px 10px 10px 10px;
margin:0;
text-align:left;
line-height:normal;
-moz-border-radius:10px 10px 10px 10px;
border-radius:10px 10px 10px 10px;
-webkit-border-radius:10px 10px 10px 10px;
/*filter: alpha(opacity = 50);*/
z-index:999;
outline:0;
}

/*-----------------allg. Styling-----------------*/

#rp_info_author
{
position:absolute;
right:5px;	
top:10px;
z-index:502;
}

#rp_info_author a, #rp_info_author a:active, #rp_info_author a:focus
{
<?php if(LICENSED==true and HELPTEXT_ENABLE=="0"){ echo "display:none;\n";}else{echo "display:block;\n";} ?>
height:28px;
width:30px;
background-image: url(<?php echo ROOT;?>/css/icon_info.png)!important;
background-color:transparent!important;
background-repeat: no-repeat;
outline:none;
}

#rp_logo
{
position:absolute;	
top:0px;
text-align:<?php echo LOGO_POSITION; ?>!important;
width:100%;
z-index:100;
}

#rp_logo a, #rp_logo a:hover, #rp_logo a:active, #rp_logo a:focus
{
<?php if(LICENSED==true and LOGO_ENABLE=="0"){ ?>
display:none;
<?php }?>
background-color:transparent!important;
border-color:transparent;
outline:none;
}

#rp_page_forward
{
display:none;
}

#rp_page_table
{
position:absolute;
right:25px;
bottom:20px;
border:0!important;
text-align:center;
z-index:500;
}

#rp_page_table tr, #rp_page_table td
{
border:0!important;
}

#rp_page_backward
{
display:none;
}

#rp_page
{
width:30px;
height:20px;
color:#000000;
font-family: Arial, Helvetica, sans-serif ;
font-size:15px;
z-index:50;
display:none;
}

.rp_logo_fancybox
{
<?php if(PIC_FANCYBOX_LOGO=="0"){ ?>
display:none;
<?php }?>
position:absolute;
top:-60px; /* 10px*/
right:20px;
}

.rp_logo_fancybox a img
{
background-color:transparent!important;
border:none!important;
}

.rp_page_fancybox
{
position:absolute;
bottom:10px;
right:30px;
}

#rp_trash
{
position:absolute;	
left:5px;
top: <?php echo TOTAL_HEIGHT-190; ?>px;
width:200px;
height:200px;
z-index:50;
}

#rp_trash a, #rp_trash a:hover, #rp_trash a:active, #rp_trash a:focus
{
display:block;	
background-image: url(<?php echo ROOT;?>/css/Recycle-Bin-Empty-icon.png)!important;
background-color:transparent!important;
width:128px;
height:128px;
border-color:transparent;
outline:none;
margin-top:50px;
}

#rp_new
{
position:absolute;
<?php if(LICENSED==true and (LOGO_ENABLE=="0" or LOGO_POSITION!="left")){ ?>
top:5px;
left:5px;
<?php }else{ ?>
top:80px;
left:4px;	
<?php }; ?>
width:116px;
height:94px;
z-index:100;
<?php if(LICENSED!=true or (LOGO_NEW_ENABLE=="1" or (NEW_ITEM == "2" or (NEW_ITEM=="1" and USER>=18) or (NEW_ITEM=="0" and USER>=24)))){ ?>
<?php }else{ ?>
display:none;
<?php } ?>
}

<?php
if (file_exists('components/com_realpin/includes/css/new_'.LANG.'.png'))
{
$image='new_'.LANG.'.png'; 
} 
else
{
$image='new_en-gb.png';
}
?>

#rp_new a, #rp_new a:hover, #rp_new a:active, #rp_new a:focus
{
display:block;	
background-image: url(<?php echo ROOT;?>/css/<?php echo $image; ?>)!important;
background-color:transparent!important;
width:116px;
height:100px;
border-color:transparent;
outline:none;
}

#rp_main
{
border:0;
padding:0;
margin:0;
<?php
if (FULLSCREEN=="1")
{
?>
    position: absolute;
    top: 0;
    left: 0;
    min-height: <?php $dimension_y ?>;
    min-width: 1024px;
    height: 100%;
    width: 100%;
<?php
}
?>
}

.rp_current
{
z-index:900;
border:2px dotted red;
}

#rp_main img
{
border:0;
border-color:transparent;
}

/*
#rp_marker1
{
position:absolute;
width: <?php echo BORDER/2; ?>px;
height: <?php echo BORDER/2; ?>px;
left: <?php echo TOTAL_WIDTH+BORDER/2; ?>px;
top: <?php echo TOTAL_HEIGHT+BORDER/2; ?>px;
background-image: url(<?php echo ROOT;?>/css/marker.png);
background-repeat:no-repeat;
background-position: -50px 0px;
}

#rp_marker2 
{
position:absolute;
width:<?php echo BORDER/2; ?>px;
height: <?php echo BORDER/2; ?>px;
left: <?php echo TOTAL_WIDTH+BORDER/2; ?>px;
top: 0px;
background-image: url(<?php echo ROOT;?>/css/marker.png);
background-repeat:no-repeat;
background-position: 0 100%;
}

#rp_marker3
{
position:absolute;
width:<?php echo BORDER/2; ?>px;
height: <?php echo BORDER/2; ?>px;
left: 0px;
top: <?php echo TOTAL_HEIGHT+BORDER/2; ?>px;
background-image: url(<?php echo ROOT;?>/css/marker.png);
background-repeat:no-repeat;
background-position: 0 100%;
}

#rp_marker4
{
position:absolute;
width:<?php echo BORDER/2; ?>px;
height: <?php echo BORDER/2; ?>px;
left: 0px;
top: 0px;
background-image: url(<?php echo ROOT;?>/css/marker.png);
background-repeat:no-repeat;
background-position: -50px 0px;
}

#rp_inline1
{
position:absolute;
width:<?php echo TOTAL_WIDTH+1; ?>px;
height: 4px;
left: 0px;
top:<?php echo $dimension_y; ?>px;
background-image: url(<?php echo ROOT;?>/css/inline.png);
}

#rp_inline2
{
position:absolute;
width: 3px;
height:<?php echo $dimension_x; ?>;
left:<?php echo $dimension_y; ?>;
top: 0px;
background-image: url(<?php echo ROOT;?>/css/inline.png);
}

#rp_shadow1
{
position:absolute;
width: 20px;
height:<?php echo $dimension_y; ?>;
left: 0px;
top: 0px;
background-image: url(<?php echo ROOT;?>/css/shadow1.png);
}

#rp_shadow2
{
position:absolute;
width:<?php echo $dimension_x; ?>;
height: 20px;
left: 0px;
top: 0px;
background-image: url(<?php echo ROOT;?>/css/shadow2.png);
}
*/

<?php

if(BACKGROUND=="default" or BACKGROUND=="")
{
$url="background-image: url(".ROOT."/css/kork_low.jpg);\n";	
}
elseif(BACKGROUND=="transparent")
{
$url="background-image:none;\n";
}
else
{
$url="background-image: url(".BACKGROUND.");\n";
}

?>

<?php if (FULLSCREEN=="1"){ ?>
  	#rp_kork_frame
	{
	position:absolute;
	<?php echo $url;?>
	background-repeat: repeat;
	left:0px;
	top:0px;
	height:100%;
	width:100%;
	<?php if (BORDER!="0" and BORDER_BACKGROUND!="transparent"){ ?>
	box-shadow: inset 10px 10px 10px rgba(0, 0, 0, .7);
	-webkit-box-shadow: inset 10px 10px 10px rgba(0, 0, 0, .7);
	-moz-box-shadow: inset 10px 10px 10px rgba(0, 0, 0, .7);
	<?php }?>
	}
<?php }else{ ?>
	#rp_kork_frame
	{
	position:relative;
	<?php echo $url;?>
	background-repeat: repeat;
	left:<?php echo BORDER/2; ?>px;
	top:<?php echo BORDER/2; ?>px;
	height:<?php echo $dimension_y; ?>;
	width:<?php echo $dimension_x; ?>;
	<?php if (BORDER!="0" and BORDER_BACKGROUND!="transparent"){ ?>
	box-shadow: inset 10px 10px 10px rgba(0, 0, 0, .7);
	-webkit-box-shadow: inset 10px 10px 10px rgba(0, 0, 0, .7);
	-moz-box-shadow: inset 10px 10px 10px rgba(0, 0, 0, .7);
	<?php }?>
	}
<?php } ?>

#rp_kork_frame label
{
width:100%;	
}

<?php
if(BORDER_BACKGROUND=="default" or BORDER_BACKGROUND=="")
{
$border_background="background-image: url(".ROOT."/css/wood_low.jpg);\n background-repeat: repeat;\n";	
}
elseif(BORDER_BACKGROUND=="transparent")
{
$border_background='background-color:transparent;\n';
}
else
{
$border_background='background-color:#'.BORDER_BACKGROUND.";\n";
}
?>

<?php if (FULLSCREEN=="1"){ ?>
	#rp_wooden_frame
	{
	<?php echo $border_background; ?>
	top:0px;
	left:0px;
	line-height:normal;
	outline:none;
	font-family: Arial, Helvetica, sans-serif ;
	font-size:12px;
	color:#000;
	}
<?php }else{ ?>
	#rp_wooden_frame
	{
	position:relative;
	<?php echo $border_background; ?>
	top:0px;
	left:0px;
	height:<?php echo $dimension_y_border; ?>;
	width:<?php echo $dimension_x_border; ?>;
	line-height:normal;
	outline:none;
	font-family: Arial, Helvetica, sans-serif ;
	font-size:12px;
	color:#000;
	}
<?php } ?>

#rp_wooden_frame a:hover,#rp_wooden_frame a:active,#rp_wooden_frame a:focus
{
background-color:transparent!important;
line-height:normal;
outline:none;
color:#00F;
text-decoration:none;
}

.rp_help
{
display:none;
}


/*-----------------Icons-Styling-----------------*/

/*.rp_new_postit
{
position:absolute;
left:20px;
top:20px;
}

.rp_new_postit a
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png);
background-repeat: no-repeat;
background-position: 0px 0px;
outline:none;
}*/

.rp_save_postit
{
position:absolute;
left:20px;
top:20px;
}

.rp_save_postit a, .rp_save_postit a:hover, .rp_save_postit a:active, .rp_save_postit a:focus
{
<?php if(POSITIONING=="1"){echo "display:block;";}else{echo "display:none;";}?>
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -25px 0px!important;
outline:none;
}

/*.rp_edit_postit
{
position:absolute;
right:50px;	
top:20px;
}

.rp_edit_postit a
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png);
background-repeat: no-repeat;
background-position: -48px 0px;
}*/

.rp_info_postit
{
position:absolute;
right:50px;
top:20px;
}

.rp_info_postit a, .rp_info_postit a:hover, .rp_info_postit a:active, .rp_info_postit a:focus
{
display:block;	
height:20px;
width:22px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -118px 0px!important;
outline:none;
}

.rp_blend_postit
{
position:absolute;
right:20px;
top:20px;
}

.rp_blend_postit a, .rp_blend_postit a:hover, .rp_blend_postit a:active, .rp_blend_postit a:focus
{
height:20px;
width:20px;
display:block;	
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -143px 0px!important;
outline:none;
}

/*.rp_new_youtube
{
position:absolute;
left:30px;
top:0px;
}

.rp_new_youtube a
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png);
background-repeat: no-repeat;
background-position: 0px 0px;
outline:none;
}*/

.rp_save_youtube
{
position:absolute;
left:10px;
top:0px;
}

.rp_save_youtube a, .rp_save_youtube a:hover, .rp_save_youtube a:active, .rp_save_youtube a:focus
{
<?php if(POSITIONING=="1"){echo "display:block;";}else{echo "display:none;";}?>
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -25px 0px!important;
outline:none;
}

/*.rp_edit_youtube
{
position:absolute;
right:30px;	
top:0px;
}

.rp_edit_youtube a
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png);
background-repeat: no-repeat;
background-position: -48px 0px;
}*/

.rp_info_youtube
{
position:absolute;
right:40px;
top:0px;
}

.rp_info_youtube a, .rp_info_youtube a:hover, .rp_info_youtube a:active, .rp_info_youtube a:focus
{
display:block;
height:20px;
width:22px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -118px 0px!important;
outline:none;
}

.rp_blend_youtube
{
position:absolute;
right:10px;
top:0px;
}

.rp_blend_youtube a, .rp_blend_youtube a:hover, .rp_blend_youtube a:active, .rp_blend_youtube a:focus
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -143px 0px!important;
outline:none;
}

/*.rp_new_pic
{
position:absolute;
left:10px;
top:10px;
}

.rp_new_pic a
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png);
background-repeat: no-repeat;
background-position: 0px 0px;
outline:none;
}*/

.rp_save_pic
{
position:absolute;
left:10px;
top:10px;
}

.rp_save_pic a, .rp_save_pic a:hover, .rp_save_pic a:active, .rp_save_pic a:focus
{
<?php if(POSITIONING=="1"){echo "display:block;";}else{echo "display:none;";}?>
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -25px 0px!important;
outline:none;
}

.rp_zoom_pic
{
position:absolute;
left:40px;
top:10px;
}

.rp_zoom_pic a, .rp_zoom_pic a:hover, .rp_zoom_pic a:active, .rp_zoom_pic a:focus
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -70px 0px!important;
outline:none;
}

/*.rp_edit_pic
{
position:absolute;
right:70px;	
top:10px;
}

.rp_edit_pic a
{
display:block;
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png);
background-repeat: no-repeat;
background-position: -48px 0px;
}*/

.rp_info_pic
{
position:absolute;
right:40px;	
top:10px;
}

.rp_info_pic a, .rp_info_pic a:hover, .rp_info_pic a:active, .rp_info_pic a:focus
{
display:block;	
height:20px;
width:22px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -118px 0px!important;
outline:none;
}

.rp_blend_pic 
{
position:absolute;
right:10px;		
top:10px;
}

.rp_blend_pic a , .rp_blend_pic a :hover, .rp_blend_pic a :active, .rp_blend_pic a :focus
{
display:block;	
height:20px;
width:20px;
background-image: url(<?php echo ROOT;?>/css/icons_small.png)!important;
background-color:transparent!important;
background-repeat: no-repeat!important;
background-position: -143px 0px!important;
outline:none;
}

.rp_icons
{
position:absolute;
width:100%;
height:20px;
margin:0 auto;
z-index:999;
<?php if (RP_MOBILE) { ?>
display:block;
<?php }else{ ?>
display:none;
<?php } ?>
}

/*-----------------Ende Icons-Styling-----------------*/

.rp_youtube
{
width:<?php echo YOUTUBE_X;?>px;
height:<?php echo YOUTUBE_Y;?>px;
margin-left: auto;
margin-right: auto; 
z-index:1;
}

.rp_youtube_preview
{
position:absolute;
width:<?php echo YOUTUBE_X;?>px;
/*height:<?php echo YOUTUBE_Y;?>px;*/
background-color:#FFF;
margin-left: auto;
margin-right: auto; 
z-index:1;
}

.rp_youtube_play
{
position:absolute;
width:99px;
height:59px;
left:<?php echo YOUTUBE_X/2-50;?>px;
top:<?php echo YOUTUBE_Y/2-30;?>px;
margin-left: auto;
margin-right: auto; 
z-index:10;
}

.rp_content
{
margin:0 auto;
}

.rp_pin
{
position:relative;
width:38px;
height:37px;
top:-17px;
left:10px;
margin: 0 auto;
background-image: url(<?php echo ROOT;?>/css/pin_yellow.png);
background-repeat:no-repeat;
border-color:transparent;
z-index:2;
}

.rp_pin_video_l
{
position:absolute;
width:73px;
height:65px;
left:-35px;
top:-5px;
margin: 0 auto;
background-image: url(<?php echo ROOT;?>/css/tesa_l.png);
background-repeat:no-repeat;
border-color:transparent;
z-index:100;
}

.rp_pin_video_r
{
position:absolute;
width:73px;
height:65px;
right:-35px;
top:-5px;
margin: 0 auto;
background-image: url(<?php echo ROOT;?>/css/tesa_r.png);
background-repeat:no-repeat;
border-color:transparent;
z-index:100;
}



#rp_version
{
position:absolute;
right:10px;	
bottom:5px;
font-size:8px;
font-family: Arial, Helvetica, sans-serif ;
}

/*socialize module*/

#socializethis{
  position:absolute;
   /*background:#CCC;*/
  background-color:#<?php echo MODULE_BACKGROUND_COLOR; ?>;
  /*border:solid 1px #666;
  border-width: 1px 0 0 1px;*/
  height:165px;
  width:40px;
  bottom:0;
  /*right:0;*/
  left:0px;
  <?php if(MODULE_SOCIAL_POSITION=="top"){ $social_position="180"; }?>
  <?php if(MODULE_SOCIAL_POSITION=="center" or MODULE_SOCIAL_POSITION==""){ $social_position=TOTAL_HEIGHT/2-100; }?>
  <?php if(MODULE_SOCIAL_POSITION=="bottom"){ $social_position=TOTAL_HEIGHT-230; }?>
  top:<?php echo $social_position; ?>px;	
  /*bottom:0px;*/
  padding:2px 5px;
  overflow:hidden; 
  /* CSS3 */
  -webkit-border-top-right-radius: 12px;
  -webkit-border-bottom-right-radius: 12px;
  -moz-border-radius-topright: 12px;
  -moz-border-radius-bottomright: 12px;
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
  -moz-box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
  -webkit-box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
  box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
  z-index:500;
  }
 
#socializethis a{
  float:left; 
  width:32px;
  margin:3px 2px 2px 2px; 
  padding:0;
  background-color:transparent!important;
}
 
#socializethis span{ 
  float:left; 
  margin:2px 3px; 
  /*text-shadow: 1px 1px 1px #FFF;*/
  color:#<?php echo MODULE_FOREGROUND_COLOR; ?>;
  font-size:12px;
}  

/*search module*/

#rp_search {
  position:absolute;
  top:0px;
  <?php if(MODULE_SEARCH_POSITION=="left"){ $search_position="180"; }?>
  <?php if(MODULE_SEARCH_POSITION=="center" or MODULE_SEARCH_POSITION==""){ $search_position=TOTAL_WIDTH/2-100; }?>
  <?php if(MODULE_SEARCH_POSITION=="right"){ $search_position=TOTAL_WIDTH-230; }?>
  left:<?php echo $search_position; ?>px;	
  /*background:#CCC;*/
  background-color:#<?php echo MODULE_BACKGROUND_COLOR; ?>;
  height:30px;
  /*width:180px;*/
  padding:2px 5px;
  overflow:hidden; 
  /* CSS3 */
  -webkit-border-bottom-left-radius: 12px;
  -webkit-border-bottom-right-radius: 12px;
  -moz-border-radius-bottomright: 12px;
  -moz-border-radius-bottomleft: 12px;
  border-bottom-right-radius: 12px;
  border-bottom-left-radius: 12px;
  -moz-box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
  -webkit-box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
  box-shadow: 5px 5px 5px rgba(0,0,0,0.7);
  z-index:500;
  }
 

#rp_search span{ 
  float:left; 
  margin:6px 3px; 
  /*text-shadow: 1px 1px 1px #FFF;*/
  color:#<?php echo MODULE_FOREGROUND_COLOR; ?>;
  font-size:12px;
}  

#rp_search input{ 
  float:left!important;
  margin:3px 5px;!important;
  color:#F00!important;
  font-size:12px!important;
  font-family: Arial, Helvetica, sans-serif !important;
  font-style:normal!important;
  font-weight:normal !important;
  padding:0 !important;
  width:100px !important;
  
  -moz-box-sizing: none !important;
  background-color: #FFF !important;
  border: 0 !important;
  box-shadow: none !important;
  height: 18px !important;
  outline: none !important;
  vertical-align: middle !important;
}  

#rp_loading
{
  position:fixed;
  background-color:#000;
  font-family: Arial, Helvetica, sans-serif ;
  font-size:30px;
  color:#FFF;
  opacity: 0.8;
  padding: 10px 10px 10px 10px;
  left:50%;
  top:40%;
  width:200px;
  height:70px;
  margin-top: -35;
  margin-left: -100px;
  text-align:center!important;
  line-height:normal;
  -moz-border-radius:10px 10px 10px 10px;
  border-radius:10px 10px 10px 10px;
  -webkit-border-radius:10px 10px 10px 10px;
  /*filter: alpha(opacity = 50);*/
  z-index:999;
  outline:0;	
}

.rp_loading 
{
  position:absolute;
  width:32px;
  height:32px;
  left:50%;
  top:50%;
  opacity: 0.8;
  text-align:center;
  padding: 10px 10px 10px 10px;
  -moz-border-radius:10px 10px 10px 10px;
  border-radius:10px 10px 10px 10px;
  -webkit-border-radius:10px 10px 10px 10px;
  z-index:999;
  background-color:#000;
}

/*CUSTOM OVERRIDES*/
<?php echo CUSTOM_CSS; ?>

</style>
