<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">

<?php
/*if(LICENSED!=true)
{
	if(filesize(JPATH_COMPONENT.DS.'includes'.DS.'css'.DS.'logo_quer.png')!=12394)
	{?>
	alert('<?php echo JText::_('LANG_NO_COPY'); ?>');
	<?php }

}*/
?>

//Check if jquery is loaded
if (typeof jQuery == 'undefined') {
    // jQuery is not loaded
	alert('<?php echo JText::_('LANG_JQUERY_ERROR1'); ?>');
}

<?php if(JQUERY_COMPATIBILITY=="1"){?>
<?php $rpjs="jQueryrp";?>
var jQueryrp=$.noConflict(true);
<?php }else{?>
<?php $rpjs="jQuery";?>
jQuery.noConflict();
<?php } ?>

//Check if jquery for realpin has been initialized
if (typeof <?php echo $rpjs; ?> == 'undefined') {
    // jQuery is not loaded
	alert('<?php echo JText::_('LANG_JQUERY_ERROR2'); ?>');
}

var image_set = [
<?php

for($h = 0; $h < $total_pin; $h++)
{

$id= $data[$h][0];
$type= $data[$h][3];
$text= $data[$h][4];
$url= $data[$h][5];
$title= $data[$h][6];
$fancytitle=create_tooltip($title,$text,JText::_('LANG_NO_TEXT'),100,"&lt;br /&gt;");
$fancytitle=remove_all_special($fancytitle);
$fancytitle=str_replace("&lt;br /&gt;", "<br/>", $fancytitle);

	if($type=="pic")
	{
	echo "{ 'href': '".PIC_THUMBS_URL."th_".$url."', 'title': '".$fancytitle."' }";
	if($h<$total_pin-1){echo ",\n";}
	}
}


?>
];

<?php echo $rpjs; ?>.expr[':'].contains = function(a, i, m) {
  return <?php echo $rpjs; ?>(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

<?php echo $rpjs; ?>.fn.extend({

	highlight: function(search, insensitive, klass){
		var regex = new RegExp('(<[^>]*>)|('+ search.replace(/([-.*+?^${}()|[\]\/\\])/g,"\\$1") +')', insensitive ? 'ig' : 'g');
		return this.html(this.html().replace(regex, function(a, b, c){
			return (a.charAt(0) == '<') ? a : '<span class="'+ klass +'">' + c + '</span>';
		}));
	}

});

var isHandlerActive = false;
var isHandlerAddingActive = false;
var isloading = false;
var isNewWindowShown = false;
var page=0;

//MOBILE FIX
var hitEvent = 'click'; // 'ontouchstart' in document.documentElement ? 'touchstart' : 'click';

<?php echo $rpjs; ?>(document).ready
(

	function()
	{
		<?php if (RP_MOBILE) { ?>

		var message = '<?php echo JText::_('LANG_MOBILE'); ?>';
		// get the screen height and width
		var maskHeight = <?php echo $rpjs; ?>(document).height();
		var maskWidth = <?php echo $rpjs; ?>(window).width();

		// calculate the values for center alignment
		var dialogTop =  200;//(maskHeight/2) - (<?php echo $rpjs; ?>('#rp_dialog-box').height()/2);
		var dialogLeft = (maskWidth/2) - (<?php echo $rpjs; ?>('#rp_dialog-box').width()/2);

		// assign values to the overlay and dialog box
		<?php echo $rpjs; ?>('#rp_dialog-overlay').css({height:maskHeight, width:maskWidth+50}).show();
		<?php echo $rpjs; ?>('#rp_dialog-box').css({top:dialogTop, left:dialogLeft}).show();

		// display the message
		<?php echo $rpjs; ?>('#rp_dialog-message').html(message);



		<?php echo $rpjs; ?>.each(image_set, function(intIndex, objValue ) {
		<?php echo $rpjs; ?>('#rp_mobile_gallery').append('<a href="'+objValue.href+'" class="rp_zoomimage rp_tips" style="display:none" title="<?php echo JText::_('LANG_ICON_ZOOM'); ?>"></a>');
		});
		<?php }; ?>


		 <?php echo $rpjs; ?>('object').each(function(){
		if(<?php echo $rpjs; ?>(this).parent()[0]['tagName'] !='OBJECT'){
		  <?php echo $rpjs; ?>(this).attr("wmode", "opaque").wrap('<div>');
		}
	  });

		<?php echo $rpjs; ?>("iframe").each(function(){
		  var ifr_source = <?php echo $rpjs; ?>(this).attr('src');
		  var wmode = "?wmode=transparent";
		 <?php echo $rpjs; ?>(this).attr('src',ifr_source+wmode);
		});

    //iOS Touch support
    var ua = navigator.userAgent,
    //event = (ua.match(/iPad/i)) ? "touchstart" : "click";
	event = "click";

	//********************************************Begin social widget module*******************************************************
	<?php if(MODULE_SOCIAL=="1"){ ?>

	<?php if(LICENSED==true){ ?>
	var url = window.location.href;
    var host =  window.location.hostname;
    var title = <?php echo $rpjs; ?>('title').text();
	<?php }else{ ?>
	var url="https://realpin.frumania.com";
	var host="https://realpin.frumania.com";
	var title = "<?php echo utf8_encode("Realpin - ".JText::_('LANG_VIRT')); ?>";
	<?php } ?>

    title = escape(title); //clean up unusual characters

 	var addthis_share = {
    url: url,
	title: title,
	}

	var addthis_config = addthis_config||{};
	addthis_config.data_track_addressbar = false;
	addthis_config.data_track_clickback = false;

  var addthis_url = "";
  if (location.protocol != 'https:')
  addthis_url = "http://s7.addthis.com/js/250/addthis_widget.js";
  else
  addthis_url = "https://s7.addthis.com/js/250/addthis_widget.js";

    var tbar = '<!-- AddThis Button BEGIN --><div id="socializethis" class="addthis_toolbox addthis_default_style addthis_32x32_style"><div class="vertical"><a class="addthis_button_favorites"></a><a addthis:data_track_clickback="false" addthis:data_track_addressbar="false" class="addthis_button_facebook"></a><a class="addthis_button_twitter"></a><a class="addthis_button_email"></a></div></div><script type="text/javascript" src="'+addthis_url+'"></script><!-- AddThis Button END -->';

    // Add the share tool bar
    <?php echo $rpjs; ?>('#rp_kork_frame').append(tbar);
    <?php echo $rpjs; ?>('#socializethis').css({opacity: .7});
    // hover
    <?php echo $rpjs; ?>('#socializethis').bind('mouseenter',function(){
    <?php echo $rpjs; ?>(this).animate({opacity: 1}, 300);
      <?php echo $rpjs; ?>('#socializethis img').css('display', 'inline');
    });
    //leave
    <?php echo $rpjs; ?>('#socializethis').bind('mouseleave',function(){
      <?php echo $rpjs; ?>(this).animate({ opacity: .7}, 300);
    });

	<?php } ?>
	//********************************************End social widget module*******************************************************

	//********************************************Begin search module************************************************************
	<?php if(MODULE_SEARCH=="1"){ ?>

	var searchbar='<div id="rp_search"><span><?php echo JText::_('LANG_SEARCH'); ?></span>&nbsp;&nbsp;<input id="rp_search_string" name="rp_search_string" type="text" size="18" class="rp_tips" title="<?php echo JText::_('LANG_SEARCH_STRING'); ?>" tmp="<?php echo JText::_('LANG_SEARCH_STRING'); ?>" /></div>';

	<?php echo $rpjs; ?>('#rp_kork_frame').append(searchbar);

	<?php echo $rpjs; ?>('#rp_search').css({opacity: .7});

    <?php echo $rpjs; ?>('#rp_search').bind('mouseenter',function(){
    <?php echo $rpjs; ?>(this).animate({ opacity: 1}, 300);

	    if (<?php echo $rpjs; ?>(this).find('#rp_search_string').val() == <?php echo $rpjs; ?>(this).find('#rp_search_string').attr("tmp"))
        {
            <?php echo $rpjs; ?>(this).find('#rp_search_string').val("");
        }
    });

    <?php echo $rpjs; ?>('#rp_search').bind('mouseleave',function(){
      <?php echo $rpjs; ?>(this).animate({ opacity: .7}, 300);
	  if (<?php echo $rpjs; ?>(this).find('#rp_search_string').val() == "")
        {
            <?php echo $rpjs; ?>(this).find('#rp_search_string').val(<?php echo $rpjs; ?>(this).find('#rp_search_string').attr("tmp"));
        }
    });

	<?php echo $rpjs; ?>("#rp_search_string").val(<?php echo $rpjs; ?>(this).find("#rp_search_string").attr("tmp"));


	<?php echo $rpjs; ?>("#rp_search_string").bind('blur keyup',function()
	{
		term=<?php echo $rpjs; ?>("#rp_search_string").val().toLowerCase();

		<?php echo $rpjs; ?>('.rp_dragger').each
		(
		  function()
		  {
		  <?php echo $rpjs; ?>(this).show();
		  <?php echo $rpjs; ?>(this).find('.rp_content img').css('border','none');
		  }
		);
		<?php echo $rpjs; ?>("span.rp_highlight").each(function()
		{
		text=<?php echo $rpjs; ?>(this).contents();
		<?php echo $rpjs; ?>(this).replaceWith(text);
		});

		if(term != "")
		{
		<?php echo $rpjs; ?>("#rp_search_string").css('color','#F00');

			<?php echo $rpjs; ?>('div.rp_postit_content:contains("'+term+'")').each(function()
			{

			<?php echo $rpjs; ?>(this).highlight(term,true,'rp_highlight');

								   <?php echo $rpjs; ?>('.rp_dragger').each
									(
									  function()
									  {
									  <?php echo $rpjs; ?>(this).hide();
									  }
									);

									<?php echo $rpjs; ?>("span.rp_highlight").each(function()
									{
									<?php echo $rpjs; ?>(this).parent().parent().parent().parent().parent().show();
									<?php echo $rpjs; ?>(this).parent().parent().parent().parent().show();
									});

			});


			<?php echo $rpjs; ?>('div.rp_img_search:contains("'+term+'")').each(function()
			{

			<?php echo $rpjs; ?>(this).highlight(term,true,'rp_highlight');
			<?php echo $rpjs; ?>(this).parent().find('img').css('border','2px solid red');

								   <?php echo $rpjs; ?>('.rp_dragger').each
									(
									  function()
									  {
									  <?php echo $rpjs; ?>(this).hide();
									  }
									);

									<?php echo $rpjs; ?>("span.rp_highlight").each(function()
									{
									<?php echo $rpjs; ?>(this).parent().parent().parent().parent().show();
									});

			});

			<?php echo $rpjs; ?>('div.rp_video_search:contains("'+term+'")').each(function()
			{

			<?php echo $rpjs; ?>(this).highlight(term,true,'rp_highlight');

								   <?php echo $rpjs; ?>('.rp_dragger').each
									(
									  function()
									  {
									  <?php echo $rpjs; ?>(this).hide();
									  }
									);

									<?php echo $rpjs; ?>("span.rp_highlight").each(function()
									{
									<?php echo $rpjs; ?>(this).parent().parent().parent().parent().show();
									});

			});

		}

   });
	<?php } ?>
	//*********************************************End search module********************************************************************
	<?php echo $rpjs; ?>('div.rpscrollup').hide();
	<?php echo $rpjs; ?>('div.rpscrolldown').hide();
	<?php echo $rpjs; ?>('div.new_postit_wrapper_scrollup').hide();
	<?php echo $rpjs; ?>('div.new_postit_wrapper_scrolldown').hide();

	rebind();

    <?php echo $rpjs; ?>('#rp_page_forward').bind(event, function(e){

		if(isloading!=true){
		isloading=true;

		<?php echo $rpjs; ?>('.rp_dragger').each
		(
		  function()
		  {
		  <?php echo $rpjs; ?>(this).unbind();
		  <?php echo $rpjs; ?>(this).empty();
		  <?php echo $rpjs; ?>(this).remove();
		  }
		);

        page=parseInt(<?php echo $rpjs; ?>('#rp_page_forward').attr('page'))+1;

		<?php echo $rpjs; ?>.ajax
		({
			  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=loaddata&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=json",
			  global: false,
			  type: "POST",
			  async: true,
			  data: ({rp_itemsperpage: <?php echo ITEMSPERPAGE; ?>,rp_page: page}),
			  dataType: "json",
			  beforeSend : function()
			  {

			loadingdiv='<div id="rp_loading"><?php echo JText::_('LANG_NEW_UPLOADING_DATA'); ?><br /><img src="<?php echo ROOT;?>/css/ajax-loader2.gif" width="128" height="15"></div>';
			<?php echo $rpjs; ?>('body').append(loadingdiv);

		     },
			  success: function(data)
			  {
				//window.clearInterval(interval);
			    <?php echo $rpjs; ?>('#rp_loading').remove();

				if(typeof data != "object")
		  		data = jQuery.parseJSON(data);

				if(data!=null)
				{

					<?php echo $rpjs; ?>.each(data, function(i,item)
					{
						if(item.type=='postit'){append_new_postit(item.id,item.xPos,item.yPos,item.text,item.author,item.author_id,item.created,false,false);}
						if(item.type=='youtube'){append_new_video(item.id,item.xPos,item.yPos,item.url,item.text,item.title,item.author,item.author_id,item.created,item.embed,false,false);}
						if(item.type=='pic'){append_new_image(item.id,item.xPos,item.yPos,'','',item.url,item.text,item.title,item.author,item.author_id,item.created,false,false);}

						if(data.length-1==i){setTimeout( function() { rebind();isloading=false;}, 1000 );}

						//link_check(item.type,item.id,item.author,item.created);

					});
					<?php echo $rpjs; ?>('#rp_page_forward').attr('page',(page)+'');

					if(page+1 >= <?php echo ceil($total_pin/ITEMSPERPAGE);?>){<?php echo $rpjs; ?>('#rp_page_forward').hide();};

					<?php echo $rpjs; ?>('#rp_page_backward').show();
					<?php echo $rpjs; ?>('#rp_page').text(page+1+'/<?php echo ceil($total_pin/ITEMSPERPAGE); ?>');
				}
				else
				{
					<?php echo $rpjs; ?>('#rp_page_forward').attr('page',(page)+'');
					<?php echo $rpjs; ?>('#rp_page_forward').hide();
					<?php echo $rpjs; ?>('#rp_page_backward').show();
				}

			 }

		});
		}//end isloading
	});

	<?php if($this->pin_total>ITEMSPERPAGE){?>
	<?php echo $rpjs; ?>('#rp_page_forward').show();
	<?php echo $rpjs; ?>('#rp_page').show();
	<?php } ?>

	<?php echo $rpjs; ?>('#rp_page_backward').hide();

	   <?php echo $rpjs; ?>('#rp_page_backward').bind(event, function(e){

		if(isloading!=true){
		isloading=true;

		<?php echo $rpjs; ?>('.rp_dragger').each
		(
		  function()
		  {
		  <?php echo $rpjs; ?>(this).unbind();
		  <?php echo $rpjs; ?>(this).empty();
		  <?php echo $rpjs; ?>(this).remove();
		  }
		);

       page=parseInt(<?php echo $rpjs; ?>('#rp_page_forward').attr('page'))-1;
	   <?php echo $rpjs; ?>('#rp_page_forward').attr('page',(page)+'')

		<?php echo $rpjs; ?>.ajax
		({
			  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=loaddata&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=json",
			  global: false,
			  type: "POST",
			  async: true,
			  data: ({rp_itemsperpage: <?php echo ITEMSPERPAGE; ?>,rp_page: page}),
			  dataType: "json",
			  beforeSend : function()
			  {

			loadingdiv='<div id="rp_loading"><?php echo JText::_('LANG_NEW_UPLOADING_DATA'); ?><br /><img src="<?php echo ROOT;?>/css/ajax-loader2.gif" width="128" height="15"></div>';
			<?php echo $rpjs; ?>('#rp_kork_frame').append(loadingdiv);

			//<?php echo $rpjs; ?>('#rp_loading').css({top:'40%',left:'50%',margin:'-'+(<?php echo $rpjs; ?>('#rp_loading').outerHeight() / 2)+'px 0 0 -'+(<?php echo $rpjs; ?>('#rp_loading').outerWidth() / 2)+'px'});

		      },
			  success: function(data)
			  {
				<?php echo $rpjs; ?>('#rp_loading').remove();

				if(typeof data != "object")
		  		data = jQuery.parseJSON(data);

				if(data!=null)
				{

					<?php echo $rpjs; ?>.each(data, function(i,item)
					{
						if(item.type=='postit'){append_new_postit(item.id,item.xPos,item.yPos,item.text,item.author,item.author_id,item.created,false,false);}
						if(item.type=='youtube'){append_new_video(item.id,item.xPos,item.yPos,item.url,item.text,item.title,item.author,item.author_id,item.created,item.embed,false,false);}
						if(item.type=='pic'){append_new_image(item.id,item.xPos,item.yPos,'','',item.url,item.text,item.title,item.author,item.author_id,item.created,false,false);}
						if(data.length-1==i){setTimeout( function() { rebind();isloading=false;}, 1000 );}

						//link_check(item.type,item.id,item.author,item.created);
					});

					if(<?php echo $rpjs; ?>('#rp_page_forward').attr('page')=="0"){<?php echo $rpjs; ?>('#rp_page_backward').hide();}
					<?php echo $rpjs; ?>('#rp_page').text(page+1+'/<?php echo ceil($total_pin/ITEMSPERPAGE); ?>');
					<?php echo $rpjs; ?>('#rp_page_forward').show();
				}
				else
				{
					<?php echo $rpjs; ?>('#rp_page_forward').attr('page',(page)+'');
					<?php echo $rpjs; ?>('#rp_page_forward').hide();
					<?php echo $rpjs; ?>('#rp_page_backward').show();
				}
			 }

		});
		}//end is loading
	});

	<?php if(RPID!=''){ ?>
	<?php echo $rpjs; ?>('.rp_dragger').each
	(
	  function()
	  {
	  //<?php echo $rpjs; ?>(this).hide();
	  var tmpid=<?php echo $rpjs; ?>(this).find('div.rp_content').attr("rp_id");
	  if(tmpid!='<?php echo RPID; ?>'){}else{<?php echo $rpjs; ?>(this).css('z-index','201');}
	  }
	);
	<?php }; ?>

		<?php echo $rpjs; ?>('.new_postit_wrapper').bind({
	mouseenter: function()
	    {
		elem=<?php echo $rpjs; ?>(this).find('div.new_postit_wrapper_text_content');
		if(elem.scrollTop()>0){<?php echo $rpjs; ?>(this).find('div.new_postit_wrapper_scrollup').show();};
		if(elem[0].scrollHeight - elem.scrollTop() > elem.outerHeight()){	<?php echo $rpjs; ?>(this).find('div.new_postit_wrapper_scrolldown').show();};
		},
	mouseleave: function()
	   {
		<?php echo $rpjs; ?>(this).find('div.new_postit_wrapper_scrollup').hide();
		<?php echo $rpjs; ?>(this).find('div.new_postit_wrapper_scrolldown').hide();
		}
	});


	<?php $user = JFactory::getUser(); if (!$user->guest) { ?>
    <?php if (USER >= 24 or (REMOVAL=="1" and USER>=18) or (COMMUNITY==1 and USERNAME_LOGIN==PINBOARD)){ ?>
	var $trash = <?php echo $rpjs; ?>('#rp_trash');
	$trash.droppable({
				accept: '.rp_dragger',
				activeClass: 'ui-state-highlight',
				drop: function(ev, ui) {
					deleteItem(ui.draggable);
				}
			});
	<?php } } ?>

	/*<?php echo $rpjs; ?>('#postit1').bind({
	mousedown: function()
		{
		        <?php echo $rpjs; ?>(this).parent().parent().find('div.new_postit_wrapper').css("background", "url(http://localhost/toerpe/realpin/live/components/com_realpin/includes/css/postit_advanced.png)");
		}
	});
		<?php echo $rpjs; ?>('#postit2').bind({
	mousedown: function()
		{
		        <?php echo $rpjs; ?>(this).parent().parent().find('div.new_postit_wrapper').css("background", "url(http://localhost/toerpe/realpin/live/components/com_realpin/includes/css/postit_advanced2.png)");
		}
	});
			<?php echo $rpjs; ?>('#postit3').bind({
	mousedown: function()
		{
		        <?php echo $rpjs; ?>(this).parent().parent().find('div.new_postit_wrapper').css("background", "url(http://localhost/toerpe/realpin/live/components/com_realpin/includes/css/postit_advanced3.png)");
		}
	});*/


	<?php echo $rpjs; ?>('.new_postit_wrapper_scrolldown').bind({
	mousedown: function()
		{
		        <?php echo $rpjs; ?>(this).parent().find('div.new_postit_wrapper_text_content').stop().scrollTo( '+=120', 1000, { axis:'y',onAfter:
				function()
				{
				elem=<?php echo $rpjs; ?>(this).parent().find('div.new_postit_wrapper_text_content');
				if(elem.scrollTop()>0){<?php echo $rpjs; ?>(this).parent().parent().find('.new_postit_wrapper_scrollup').show();};
				if(elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()){<?php echo $rpjs; ?>(this).parent().parent().find('.new_postit_wrapper_scrolldown').hide();};
				}
				});
		}
	});

	<?php echo $rpjs; ?>('.new_postit_wrapper_scrollup').bind({
	mousedown: function()
	{

                <?php echo $rpjs; ?>(this).parent().find('div.new_postit_wrapper_text_content').stop().scrollTo( '-=120', 1000, { axis:'y',onAfter:
				function()
				{
				elem=<?php echo $rpjs; ?>(this).parent().find('div.new_postit_wrapper_text_content');
				if(elem.scrollTop()==0){<?php echo $rpjs; ?>(this).parent().parent().find('.new_postit_wrapper_scrollup').hide();};
				if(elem[0].scrollHeight - elem.scrollTop() > elem.outerHeight()){<?php echo $rpjs; ?>(this).parent().parent().find('.new_postit_wrapper_scrolldown').show();};
				}
				});

	}
	});

/*    <?php $cal=strtolower(str_replace("%","",JText::_('DATE_RP_FORMAT'))); ?>
    <?php $cal=strtolower(str_replace("d","dd",$cal)); ?>
    <?php $cal=strtolower(str_replace("m","mm",$cal)); ?>
    <?php $cal=strtolower(str_replace("y","yy",$cal)); ?>
	<?php echo $rpjs; ?>("#datepicker" ).datepicker({minDate: +1, format: '<?php echo $cal; ?>'});*/

	<?php
	if (ENABLE_PIC=="1")
    {
	?>
	<?php echo $rpjs; ?>('#new_image').bind(event, function(e)
	{
			 if(!isHandlerActive)
			 {
				 isHandlerActive = true;
				 /*<?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow", function()
				 {
						 <?php echo $rpjs; ?>('#new_image_layer').slideToggle("slow");
				 });*/

				  <?php echo $rpjs; ?>('#new_rp_item').toggle();
				  <?php echo $rpjs; ?>('#new_image_layer').toggle();

				 			<?php echo $rpjs; ?>('.rp_dragger').each
							(
							  function()
							  {
							  <?php echo $rpjs; ?>(this).hide();
							  //<?php echo $rpjs; ?>(this).css('opacity','0.3');
							  }
							);
			 }
	});
	<?php
	}
	?>

	<?php
	if (ENABLE_POSTIT=="1")
    {
	?>
	<?php echo $rpjs; ?>('#new_postit').bind(event, function(e)
	{
				if(!isHandlerActive)
				{
				isHandlerActive = true;

					 /*<?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow", function()
					  {
							 <?php echo $rpjs; ?>('#new_postit_layer').slideToggle("slow");
					  });*/

					 <?php echo $rpjs; ?>('#new_rp_item').toggle();
				     <?php echo $rpjs; ?>('#new_postit_layer').toggle();

					 		<?php echo $rpjs; ?>('.rp_dragger').each
							(
							  function()
							  {
							  <?php echo $rpjs; ?>(this).hide();
							  //<?php echo $rpjs; ?>(this).css('opacity','0.3');
							  }
							);
				}
	});
	<?php
	}
	?>

	<?php
	if (ENABLE_YT=="1")
    {
	?>
	<?php echo $rpjs; ?>('#new_video').bind(event, function(e)
	{
			     if(isHandlerActive==false)
		         {
				 isHandlerActive = true;

					 /*<?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow", function()
					 {
							 <?php echo $rpjs; ?>('#new_video_layer').slideToggle("slow");
					 });*/

					 <?php echo $rpjs; ?>('#new_rp_item').toggle();
				     <?php echo $rpjs; ?>('#new_video_layer').toggle();

							<?php echo $rpjs; ?>('.rp_dragger').each
							(
							  function()
							  {
							  <?php echo $rpjs; ?>(this).hide();
							  //<?php echo $rpjs; ?>(this).css('opacity','0.3');
							  }
							);
			     }
	});
	<?php
	}
	?>


	//textarea bind
	 <?php echo $rpjs; ?>('#new_postit_form textarea').bind('blur keyup',function()
	 {

		 if(<?php echo $rpjs; ?>('#new_postit_text').val().length>0)
		 {
		 <?php echo $rpjs; ?>('.new_postit_wrapper_text_content').text(<?php echo $rpjs; ?>('#new_postit_text').val());
		 <?php echo $rpjs; ?>('.new_postit_wrapper_text_content').html(<?php echo $rpjs; ?>('.new_postit_wrapper_text_content').html().replace(/\n/g,'<br />'));
		 }
		 else
		 {
         var texttmp=removeHTMLTags('<?php echo JText::_('LANG_NEW_POSTIT3'); ?>');
	     <?php echo $rpjs; ?>('.new_postit_wrapper_text_content').text(texttmp);
		 }


	 });

	 <?php
	  if(YT_VALIDATE=="1")
	  {
	  ?>
		 <?php echo $rpjs; ?>('#new_video_input_submit').click(function()
			 {
		     var result=getEmbed(<?php echo $rpjs; ?>('input[name=new_video_url]').val(), 200, 150);

				if(result!="false")
				{

				 var vid_id=<?php echo $rpjs; ?>.jYoutube(<?php echo $rpjs; ?>('input[name=new_video_url]').val());
				 <?php echo $rpjs; ?>("#new_video_show_div").empty();
			     <?php echo $rpjs; ?>("#new_video_show_div").html(result);


				 <?php echo $rpjs; ?>(".new_video_wrapper img").bind
				 ({
				 mouseup: function(){ <?php echo $rpjs; ?>(this).css('cursor', 'pointer'); },
				 mouseenter: function(){ <?php echo $rpjs; ?>(this).css('cursor', 'pointer');}
				 });

				}
				else
				{
				<?php echo $rpjs; ?>("#new_video_show_div").empty();
				<?php echo $rpjs; ?>("#new_video_show_div").append("<img id=\""+vid_id+"\" src=\"<?php echo ROOT; ?>/css/youtube-logo.jpg\" width=\"200\" height=\"150\" />");
				gritter_message('<?php echo JText::_('LANG_NEW_VIDEO_ERROR2'); ?>',true,5000); //not valid
				}

			 });//end #new_video_input_submit').click(function()
	  <?php
	  }
	  else
	  {
	  ?>
	  <?php echo $rpjs; ?>('#new_video_input_submit').hide();
	  <?php
	  }
	  ?>

	<?php echo $rpjs; ?>('body').on(hitEvent, '#rp_new', function(e)
	{
		e.preventDefault();

		if(isHandlerActive==false)
		{
					if(isNewWindowShown==false)
					{
						/*<?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow");*/

						<?php echo $rpjs; ?>('#new_rp_item').toggle();

						var texttmp=removeHTMLTags('<?php echo JText::_('LANG_NEW_POSTIT3'); ?>');
						<?php echo $rpjs; ?>('.new_postit_wrapper_text_content').text(texttmp);

						<?php echo $rpjs; ?>('.rp_dragger').each
						(
						  function()
						  {
						  <?php echo $rpjs; ?>(this).hide();
						  //<?php echo $rpjs; ?>(this).css('opacity','0.3');
						  }
						);
						isNewWindowShown = true;
					}
					else
					{
						 <?php echo $rpjs; ?>('#new_rp_item').toggle();

						 /*<?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow", function()
						  { */
								<?php echo $rpjs; ?>('.rp_dragger').each
								(
								  function()
								  {
								  <?php echo $rpjs; ?>(this).show();
								  //<?php echo $rpjs; ?>(this).css('opacity','1');
								  }
								);
								isHandlerActive = false;
								isNewWindowShown = false;
									// Animation complete.
						  /*});*/
					}
			}

	});


	<?php echo $rpjs; ?>('#new_blend').bind(event, function(e)
	{
		 /*<?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow", function()
		  { */

		 	<?php echo $rpjs; ?>('#new_rp_item').toggle();

				<?php echo $rpjs; ?>('.rp_dragger').each
				(
				  function()
				  {
				  <?php echo $rpjs; ?>(this).show();
				  //<?php echo $rpjs; ?>(this).css('opacity','1');
				  }
				);
				isHandlerActive = false;
				isNewWindowShown = false;
					// Animation complete.
		 /* });*/
	});


	<?php echo $rpjs; ?>('#new_image_blend').bind(event, function(e)
	{
		<?php echo $rpjs; ?>('#new_image_layer').toggle();
		<?php echo $rpjs; ?>('#new_rp_item').toggle();

		 /*<?php echo $rpjs; ?>('#new_image_layer').slideToggle("slow", function()
		 {
				  <?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow");
		 });*/
		 isHandlerActive = false;
	});

	<?php echo $rpjs; ?>('#new_postit_blend').bind(event, function(e)
	{

		<?php echo $rpjs; ?>('#new_postit_layer').toggle();
		<?php echo $rpjs; ?>('#new_rp_item').toggle();

	 /*<?php echo $rpjs; ?>('#new_postit_layer').slideToggle("slow", function()
	  {
		     <?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow");
	  });*/
	 isHandlerActive = false;
	});

	<?php echo $rpjs; ?>('#new_video_blend').bind(event, function(e)
	{
		<?php echo $rpjs; ?>('#new_video_layer').toggle();
		<?php echo $rpjs; ?>('#new_rp_item').toggle();

		 /*<?php echo $rpjs; ?>('#new_video_layer').slideToggle("slow", function()
		 {
		  <?php echo $rpjs; ?>('#new_rp_item').slideToggle("slow");
		 });*/
		 isHandlerActive = false;
	});

	<?php echo $rpjs; ?>('#new_image_ok').bind(event, function(e)
	{
	 if(!isHandlerAddingActive)
	 {
	 rpnewimage();
	 }
	 isHandlerActive = false;
	});

	<?php echo $rpjs; ?>('#new_postit_ok').bind(event, function(e)
	{
	 if(!isHandlerAddingActive)
	 {
	 rpnewpostit();
	 }
	 isHandlerActive = false;
	});

	<?php echo $rpjs; ?>('#new_video_ok').bind(event, function(e)
	{
	 if(!isHandlerAddingActive)
	 {
	 rpnewvideo();
	 }
	 isHandlerActive = false;
	});

	<?php echo $rpjs; ?>(".new_info_fancybox").fancybox({
		'titlePosition'		: 'inside',
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'overlayOpacity' : '0.8',
		'overlayColor' : '#000'
	});


	var button = <?php echo $rpjs; ?>('#new_image_input_upload'), interval;
	new AjaxUpload(button,{
		//action: 'upload-test.php', // I disabled uploads in this example for security reasons
		action: '<?php echo JURI::base(); ?>index.php?option=com_realpin&task=upload&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&rp_debug=<?php echo DEBUG; ?>&format=ajax',
		name: 'rp_url',
		onSubmit : function(file, ext){
			// change button text, when user selects file
			button.text('<?php echo JText::_('LANG_NEW_UPLOADING'); ?>');

			// If you want to allow uploading only 1 file at time,
			// you can disable upload button
			this.disable();

			// Uploding -> Uploading. -> Uploading...
			interval = window.setInterval(function(){
				var starttext = "<?php echo JText::_('LANG_NEW_UPLOADING'); ?>";
				var text=button.text();
				if (text.length < starttext.length+3){
					button.text(text + '.');
				} else {
					button.text(starttext);
				}
			}, 200);
		},
		onComplete: function(file, response){
			//button.text('Upload');

			window.clearInterval(interval);

			// enable upload button
			this.enable();

			<?php echo $rpjs; ?>('#new_image_input_upload').hide();

			var img = new Image();
            <?php echo $rpjs; ?>(img).load(function ()
			{
				<?php echo $rpjs; ?>(this).hide();
                ratio=img.width/img.height;
				width=ratio*170;
				if(width>280){width=280;}
				height=170;
				<?php echo $rpjs; ?>(this).css('width',width+'px');
				<?php echo $rpjs; ?>(this).css('height', height+'px');
				<?php echo $rpjs; ?>('#new_image_wrapper').remove();
				<?php echo $rpjs; ?>('#new_image_layer').append('<div id="new_image_wrapper"></div>');
				<?php echo $rpjs; ?>('#new_image_wrapper').css('width', width+'px');
				<?php echo $rpjs; ?>('#new_image_wrapper').css('height',height+'px');
				<?php echo $rpjs; ?>('#new_image_desc_header').css('width',width+150+'px');

				<?php echo $rpjs; ?>('#new_image_input_upload').css('left',width/2-20+'px');
				<?php echo $rpjs; ?>('#new_image_title').css('left',22+'px');
				<?php echo $rpjs; ?>('#new_image_title').css('width',width-10+'px');
				<?php echo $rpjs; ?>('#new_image_title').css('top',height+20+'px');
				<?php echo $rpjs; ?>('#new_image_layer').css('width',width+30+200+'px');
				<?php echo $rpjs; ?>('#new_image_wrapper').append(this);
				<?php echo $rpjs; ?>(this).fadeIn();
				button.text('<?php echo JText::_('LANG_NEW_IMAGE4'); ?>');
				<?php echo $rpjs; ?>('#new_image_input_upload').show();
				image_src=file;
            }).error(function ()
			{
				<?php echo $rpjs; ?>('#new_image_input_upload').show();
				gritter_message(response,true,5000); //upload failed
				button.text('<?php echo JText::_('LANG_NEW_IMAGE3'); ?>');

            }).attr('src', '<?php echo PIC_TMP_URL; ?>'+file);


		}
	});

//ende new



});



/*------------------------ende domready------------------------------------------*/

function search_img_id(url)
{
	imgid=0;
	for (i=0;i<image_set.length;i++)
    {
		if(image_set[i])
		{
	    if(image_set[i]['href']==url){imgid=i;}
		}
	}
	return imgid;
}

//start rebind
function rebind()
{
	//alert('rebind');

	<?php
	if (DRAG == "2" or (DRAG=="1" and USER>=18) or (DRAG=="0" and USER>=24))
	{
	?>
	<?php echo $rpjs; ?>('.rp_dragger').draggable({ containment: <?php echo $rpjs; ?>('#rp_kork_frame'),cursor: 'move'});
	<?php
	}
	?>

    <?php
	if (SAVE == "2" or (SAVE=="1" and USER>=18) or (SAVE=="0" and USER>=24))
	{
	?>

      <?php echo $rpjs; ?>('.rp_save').unbind();
	  <?php echo $rpjs; ?>('.rp_save').click(function()
	  {
	  var rpid=<?php echo $rpjs; ?>(this).parent().parent().find(".rp_content").attr("rp_id");
	  var obj = document.getElementById("rp_ly"+rpid);
	  var rpx=obj.offsetLeft;
	  var rpy=obj.offsetTop;

	  <?php echo $rpjs; ?>.ajax
		({
		  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=save&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=ajax",
		  global: false,
		  type: "POST",
		  data:{rp_id: rpid,rp_x: rpx,rp_y: rpy},
		  dataType: "html",
		  success: function(msg)
		  {
				gritter_message(msg,false,2000); //saved
		  }
	   });

	  });

	<?php
	}
	?>


	<?php echo $rpjs; ?>('.rp_dragger').bind({
	mouseup: function(){ <?php echo $rpjs; ?>(this).css('cursor', 'pointer'); },
	mousedown: function(){ <?php echo $rpjs; ?>(this).css('cursor', 'move'); },
	mouseenter: function()
	    {
		<?php echo $rpjs; ?>(this).css('cursor', 'pointer');
		<?php echo $rpjs; ?>(this).css('zIndex', '201');
		<?php echo $rpjs; ?>(this).find('div.rp_icons').show();
		elem=<?php echo $rpjs; ?>(this).find('div.rp_postit_content');
		if(elem.scrollTop()>0){<?php echo $rpjs; ?>(this).find('div.rpscrollup').show();};
		    if(elem[0]!=undefined)
			{
			if(elem[0].scrollHeight - elem.scrollTop() > elem.outerHeight()){	<?php echo $rpjs; ?>(this).find('div.rpscrolldown').show();};
			}
		},
	mouseleave: function()
	    {
			<?php if (RP_MOBILE) { ?>

			<?php }else{ ?>
					<?php echo $rpjs; ?>(this).css('zIndex', '50');
					<?php echo $rpjs; ?>(this).find('div.rp_icons').hide();
					<?php echo $rpjs; ?>(this).find('div.rpscrollup').hide();
					<?php echo $rpjs; ?>(this).find('div.rpscrolldown').hide();
			<?php } ?>

		}
	});

	<?php echo $rpjs; ?>('.rpscrolldown').bind({
	mousedown: function()
		{
		        <?php echo $rpjs; ?>(this).parent().find('div.rp_postit_content').stop().scrollTo( '+=120', 1000, { axis:'y',onAfter:
				function()
				{
				elem=<?php echo $rpjs; ?>(this).parent().find('div.rp_postit_content');
				if(elem.scrollTop()>0){<?php echo $rpjs; ?>(this).parent().parent().find('.rpscrollup').show();};
				if(elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()){<?php echo $rpjs; ?>(this).parent().parent().find('.rpscrolldown').hide();};
				}
				});
		}
	});

	<?php echo $rpjs; ?>('.rpscrollup').bind({
	mousedown: function()
	{

                <?php echo $rpjs; ?>(this).parent().find('div.rp_postit_content').stop().scrollTo( '-=120', 1000, { axis:'y',onAfter:
				function()
				{
				elem=<?php echo $rpjs; ?>(this).parent().find('div.rp_postit_content');
				if(elem.scrollTop()==0){<?php echo $rpjs; ?>(this).parent().parent().find('.rpscrollup').hide();};
				if(elem[0].scrollHeight - elem.scrollTop() > elem.outerHeight()){<?php echo $rpjs; ?>(this).parent().parent().find('.rpscrolldown').show();};
				}
				});

	}
	});

	 <?php echo $rpjs; ?>('.rp_youtube_play').find('img').click(function()
	 {
		     var rpvideourl=<?php echo $rpjs; ?>(this).attr("rpvideourl");
		     var result = getEmbed(rpvideourl,'<?php echo YOUTUBE_X ?>','<?php echo YOUTUBE_Y ?>');
			 if(result!="false")
			 {
			 //var youtubeID=<?php echo $rpjs; ?>(this).attr("id");
			 var current=<?php echo $rpjs; ?>(this).parent().parent();
			 current.empty();
			 current.html(result);
			 //current.append("<object width=\"<?php echo YOUTUBE_X; ?>\" height=\"<?php echo YOUTUBE_Y; ?>\"><param name=\"movie\" value=\"http://www.youtube.com/v/"+youtubeID+"?autoplay=1&fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowScriptAccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/"+youtubeID+"?autoplay=1&fs=1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"<?php echo YOUTUBE_X; ?>\" height=\"<?php echo YOUTUBE_Y; ?>\"></embed></object>");
			 }

	 });

	 <?php echo $rpjs; ?>('.rp_youtube_play').find('img').bind
	 ({
			 mouseup: function(){ <?php echo $rpjs; ?>(this).css('cursor', 'pointer'); },
			 mouseenter: function(){ <?php echo $rpjs; ?>(this).css('cursor', 'pointer');}
	 });

	<?php echo $rpjs; ?>('.rp_icon_blend').bind
	({
	mousedown: function()
	{
		<?php echo $rpjs; ?>(this).parent().parent().hide();
	}
	});

	<?php echo $rpjs; ?>("#mytips").each(function() {
    <?php echo $rpjs; ?>(this).remove();
    });


	<?php echo $rpjs; ?>(".rp_tips").wTooltip
	({
    id: "mytips",
    style: false
    });


	if (!<?php echo $rpjs; ?>.browser.msie)
	{
	<?php echo $rpjs; ?>('#new_rp_item').corner();
	<?php echo $rpjs; ?>('#mytips').corner();
	<?php echo $rpjs; ?>('#new_image_layer').corner();
	<?php echo $rpjs; ?>('#new_postit_layer').corner();
	<?php echo $rpjs; ?>('#new_video_layer').corner();
	}

	<?php if (RP_MOBILE) { ?>

	    var nimages = <?php echo $rpjs; ?>( ".rp_zoomimage" ).length;

		if(nimages > 0)
		{
		var myPhotoSwipe = <?php echo $rpjs; ?>(".rp_zoomimage").photoSwipe({ enableMouseWheel: false , enableKeyboard: false });
		}

	<?php }else{ ?>

	<?php echo $rpjs; ?>(".rp_zoomimage").click(function(event) {
        event.preventDefault();
		<?php echo $rpjs; ?>.fancybox(image_set, {
			'titlePosition' : 'inside',
		    'titleFormat'	: formatTitle,
			index : search_img_id(<?php echo $rpjs; ?>(this).attr('href'))
		});
	});

	<?php }; ?>



}
	//ende rebind

function clear()
{
	//zero image
	image_src=undefined;
	<?php echo $rpjs; ?>('#new_image_wrapper').remove();
	<?php echo $rpjs; ?>('#new_image_layer').append('<div id="new_image_wrapper"></div>');
	<?php echo $rpjs; ?>('#new_image_wrapper').css('width','226px');
	<?php echo $rpjs; ?>('#new_image_wrapper').css('height','170px');
	<?php echo $rpjs; ?>('#new_image_desc_header').css('width','380px');
	<?php echo $rpjs; ?>('#new_image_input_upload').css('left','95px');
	<?php echo $rpjs; ?>('#new_image_title').css('left','22px');
	<?php echo $rpjs; ?>('#new_image_title').css('width','216px');
	<?php echo $rpjs; ?>('#new_image_title').css('top','190px');
	<?php echo $rpjs; ?>('#new_image_layer').css('width','480px');
	<?php echo $rpjs; ?>('#new_image_wrapper').append("<img id=\"penguins\" src=\"<?php echo ROOT; ?>/css/no_pic.jpg\" border=\"0\" width=\"226\" height=\"170\" />");
    <?php echo $rpjs; ?>('textarea[name=new_image_text]').val('');
	<?php echo $rpjs; ?>('input[name=new_image_title]').val('<?php echo JText::_('LANG_NEW_IMAGE5'); ?>');

	//zero postit
	<?php echo $rpjs; ?>('textarea[name=new_postit_text]').val('');
	var texttmp=removeHTMLTags('<?php echo JText::_('LANG_NEW_POSTIT3'); ?>');
	<?php echo $rpjs; ?>('.new_postit_wrapper_text_content').text(texttmp);
    //end zero

	//zero video
	<?php echo $rpjs; ?>('input[name=new_video_url]').val('');
	<?php echo $rpjs; ?>('textarea[name=new_video_text]').val('');
	<?php echo $rpjs; ?>("#new_video_show_div").empty();
	<?php echo $rpjs; ?>("#new_video_show_div").append("<img src=\"<?php echo ROOT; ?>/css/new/video.jpg\" width=\"200\" height=\"150\" />");
	//end zero

}

function link_check(type,id,author,created)
{
var newtype=type;

	if (isValidUrl(author))
	{

		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_postit_content').wrapInner("<a href='"+author+"' target=\"_blank\"\>");
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_postit_layer').attr('title','<?php echo str_replace('&lt;br /&gt;','<br>',JText::_('LANG_POSTIT_INFO_URL')) ?>');
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_info_'+type+' a').attr('title','<?php echo JText::_('LANG_ICON_INFO1');?> <?php echo JText::_('LANG_ICON_INFO_URL'); ?><br><?php echo JText::_('LANG_ICON_INFO2'); ?> '+created);
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_info_'+type+' a').attr('href',author);
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_info_'+type+' a').attr('target','_blank');

	}
	if (isValidEmailAddress(author))
	{
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_postit_content').wrapInner("<a href='mailto:"+author+"' target=\"_blank\"\>");
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_postit_layer').attr('title','<?php echo str_replace('&lt;br /&gt;','<br>',JText::_('LANG_POSTIT_INFO_EMAIL')) ?>');
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_info_'+type+' a').attr('title','<?php echo JText::_('LANG_ICON_INFO1');?> <?php echo JText::_('LANG_ICON_INFO_EMAIL'); ?><br><?php echo JText::_('LANG_ICON_INFO2'); ?> '+created);
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_info_'+type+' a').attr('href','mailto:'+author);
		<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_info_'+type+' a').attr('target','_blank');

	}


}



function formatTitle(title, currentArray, currentIndex, currentOpts)
{
<?php if (PIC_FANCYBOX_INFO == "1"){ ?>
    return '<div class="rp_logo_fancybox"><a href="<?php echo LOGO_SMALL_LINK; ?>" target="_blank"><img src="<?php echo SMALL_LOGO; ?>" /></a>' + '<br/></div><div><br/>'+title+'<br/><br/></div><div class="rp_page_fancybox"><?php echo JText::_('LANG_IMAGE_GALLERY1'); ?> ' + (currentIndex + 1) + ' <?php echo JText::_('LANG_IMAGE_GALLERY2'); ?> ' + currentArray.length + '</div>';
<?php }else{ ?>
 return '<div class="rp_logo_fancybox"><a href="<?php echo LOGO_SMALL_LINK; ?>" target="_blank"><img src="<?php echo SMALL_LOGO; ?>" /></a></div>';
<?php } ?>
}

function isValidUrl(url)
{
 var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
return RegExp.test(url);
}

function isValidEmailAddress(emailAddress)
{
var emailregex = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
return emailregex.test(emailAddress);
}

function append_new_image(id,xpos,ypos,width,height,url,text,title,author,author_id,created,rb,st)
{
    if(url!="no_pic.jpg"){var url_full='<?php echo PIC_THUMBS_URL; ?>';}else{var url_full='<?php echo ROOT; ?>/css/';}

	var img = new Image();
	var rotation=GetRandom(1,10);

	<?php echo $rpjs; ?>('#rp_kork_frame').append('<div id="rp_ly'+id+'" class="rp_dragger"><div class="rp_loading"><img src="<?php echo ROOT;?>/css/ajax-loader.gif" width="32" height="32"></div></div>');

	<?php echo $rpjs; ?>('#rp_ly'+id).css('position','absolute');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('top',ypos+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('left',xpos+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('z-index','201');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('width','200px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('height','200px');

	var searchtitle=removeHTMLTags(ReplaceAll(title,"&lt;br /&gt;"," "));

    <?php echo $rpjs; ?>(img).load(function ()
	{

	width=img.width;
	height=img.height;

	<?php echo $rpjs; ?>('#rp_ly'+id).append('<div class="rp_icons"><div class="rp_save rp_save_pic" <?php if (SAVE == "2" or (SAVE=="1" and USER>=18) or (SAVE=="0" and USER>=24)){ echo 'style="display:block;"';}else{echo 'style="display:none;"';} ?>><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_SAVE'); ?>"></a></div><div class="rp_zoom_pic"><a href="'+url_full+'th_'+url+'" class="rp_zoomimage rp_tips" title="<?php echo JText::_('LANG_ICON_ZOOM'); ?>"></a></div><div class="rp_blend_pic rp_icon_blend"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_BLEND'); ?>"></a></div><div class="rp_info_pic"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_INFO1'); ?> '+author+'&lt;br /&gt;<?php echo JText::_('LANG_ICON_INFO2'); ?> '+created+'"></a></div></div><div class="rp_content" style="display:none;" rp_author_id="'+author_id+'" rp_id="'+id+'"><div class="rp_pin"></div><div class="rp_pic rp_tips" style="-moz-transform: rotate('+rotation+'deg);-webkit-transform:rotate('+rotation+'deg);-o-transform:rotate('+rotation+'deg);position:absolute;top:0px;margin: 0 auto;z-index:1;" title="'+title+'"><img src="'+url_full+url+'" id="image'+id+'" width="'+(width*<?php echo PIC_SCALE; ?>)+'" heigth="'+(height*<?php echo PIC_SCALE; ?>)+'" /><div class="rp_img_search" style="display:none">'+searchtitle+'</div></div></div>');

	<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_loading').remove();
	<?php echo $rpjs; ?>('#rp_ly'+id).css('width',(width*<?php echo PIC_SCALE; ?>)+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('height',(height*<?php echo PIC_SCALE; ?>)+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_content').fadeIn();
	//setTimeout( function() { <?php echo $rpjs; ?>('#rp_ly'+id).find('.rp_content').fadeIn();<?php echo $rpjs; ?>('#rp_ly'+id).removeClass('loading'); }, 2000 );

	isHandlerAddingActive = false;
	if(rb==true){rebind();}
	if(st==true){<?php echo $rpjs; ?>.scrollTo('#rp_ly'+id+'', 1000, {offset:-200, onAfter:function(){<?php echo $rpjs; ?>('#rp_ly'+id).css('z-index','201');}});}

    link_check("pic",id,author,created);

	}).error(function ()
	{
	}).attr('src', url_full+url);


}

function append_new_postit(id,xpos,ypos,text,author,author_id,created,rb,st)
{

	<?php echo $rpjs; ?>('#rp_kork_frame').append('<div id="rp_ly'+id+'" class="rp_dragger"><div class="rp_icons"><div class="rp_save rp_save_postit" <?php if (SAVE == "2" or (SAVE=="1" and USER>=18) or (SAVE=="0" and USER>=24)){ echo 'style="display:block;"';}else{echo 'style="display:none;"';} ?>><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_SAVE'); ?>"></a></div><div class="rp_blend_postit rp_icon_blend"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_BLEND'); ?>"></a></div><div class="rp_info_postit"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_INFO1'); ?> '+author+'&lt;br /&gt;<?php echo JText::_('LANG_ICON_INFO2'); ?> '+created+'"></a></div></div><div class="rp_content" rp_author_id="'+author_id+'" rp_id="'+id+'"><div class="rpscrolldown rp_tips" title="<?php echo JText::_('LANG_SCROLL_DOWN'); ?>"><a href="javascript:void(0)"></a></div><div class="rpscrollup rp_tips" title="<?php echo JText::_('LANG_SCROLL_UP'); ?>"><a href="javascript:void(0)"></a></div> <div class="rp_postit_layer rp_tips"><div class="rp_postit_content">'+text+'</div></div></div>');

	<?php echo $rpjs; ?>('#rp_ly'+id).css('position','absolute');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('top', ypos+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('left',xpos+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('z-index','201');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('width','200px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('height','200px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('background-image','url("<?php echo ROOT;?>/css/postit_advanced.png")');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('background-repeat','no-repeat');
	<?php echo $rpjs; ?>('div.rpscrollup').hide();
	<?php echo $rpjs; ?>('div.rpscrolldown').hide();

	isHandlerAddingActive = false;
	if(rb==true){rebind();}
	if(st==true){<?php echo $rpjs; ?>.scrollTo('#rp_ly'+id+'', 1000, {offset:-200, onAfter:function(){<?php echo $rpjs; ?>('#rp_ly'+id).css('z-index','201');}});}

	link_check("postit",id,author,created);

}


function append_new_video(id,xpos,ypos,url,text,title,author,author_id,created,embed,rb,st)
{

	var vidid=<?php echo $rpjs; ?>.jYoutube(url);
	var fancytitle=title;

	var searchtitle=removeHTMLTags(ReplaceAll(fancytitle,"&lt;br /&gt;"," "));

	/*<?php echo $rpjs; ?>('#rp_kork_frame').append('<div id="rp_ly'+id+'" class="rp_dragger"><div class="rp_icons"><div class="rp_save rp_save_youtube" <?php if (SAVE == "2" or (SAVE=="1" and USER>=18) or (SAVE=="0" and USER>=24)){ echo 'style="display:block;"';}else{echo 'style="display:none;"';} ?>><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_SAVE'); ?>"></a></div><div class="rp_blend_youtube rp_icon_blend"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_BLEND'); ?>"></a></div><div class="rp_info_youtube"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_INFO1'); ?> '+author+'&lt;br /&gt;<?php echo JText::_('LANG_ICON_INFO2'); ?> '+created+'"></a></div></div><div class="rp_content" rp_author_id="'+author_id+'" rp_id="'+id+'"><div class="rp_youtube"><div class="rp_pin_video_l"></div><div class="rp_pin_video_r"></div><div style="position:relative;height:25px;"></div><div class="rp_youtube_preview rp_tips" title="'+fancytitle+'"><img id="'+vidid+'" src="http://img.youtube.com/vi/'+vidid+'/0.jpg" width="<?php echo YOUTUBE_X; ?>" height="<?php echo YOUTUBE_Y; ?>" /></div><div class="rp_youtube_play rp_tips" title="<?php echo JText::_('LANG_ICON_PLAY'); ?>"><img id="'+vidid+'" rpvideourl="'+url+'" src="<?php echo ROOT;?>/css/playbutton.png" width="99" height="59" /></div><div class="rp_video_search" style="display:none">'+searchtitle+'</div></div></div>');	*/

	<?php echo $rpjs; ?>('#rp_kork_frame').append('<div id="rp_ly'+id+'" class="rp_dragger"><div class="rp_icons"><div class="rp_save rp_save_youtube" <?php if (SAVE == "2" or (SAVE=="1" and USER>=18) or (SAVE=="0" and USER>=24)){ echo 'style="display:block;"';}else{echo 'style="display:none;"';} ?>><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_SAVE'); ?>"></a></div><div class="rp_blend_youtube rp_icon_blend"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_BLEND'); ?>"></a></div><div class="rp_info_youtube"><a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_INFO1'); ?> '+author+'&lt;br /&gt;<?php echo JText::_('LANG_ICON_INFO2'); ?> '+created+'"></a></div></div><div class="rp_content" rp_author_id="'+author_id+'" rp_id="'+id+'"><div class="rp_youtube"><div class="rp_pin_video_l"></div><div class="rp_pin_video_r"></div><div style="position:relative;height:25px;"></div>'+embed+'<div class="rp_video_search" style="display:none">'+searchtitle+'</div></div></div>');

	<?php echo $rpjs; ?>('#rp_ly'+id).css('position','absolute');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('top',ypos+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('left',xpos+'px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('z-index','201');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('width','<?php echo (YOUTUBE_X); ?>px');
	<?php echo $rpjs; ?>('#rp_ly'+id).css('height','<?php echo (YOUTUBE_Y); ?>px');

    isHandlerAddingActive = false;

	if(rb==true){rebind();}
	if(st==true){<?php echo $rpjs; ?>.scrollTo('#rp_ly'+id+'', 1000, {offset:-200, onAfter:function(){<?php echo $rpjs; ?>('#rp_ly'+id).css('z-index','201');}});}

	link_check("youtube",id,author,created);

}

function ReplaceAll(Source,stringToFind,stringToReplace){

  var temp = Source;

    var index = temp.indexOf(stringToFind);

        while(index != -1){

            temp = temp.replace(stringToFind,stringToReplace);

            index = temp.indexOf(stringToFind);

        }

        return temp;

}

function removeHTMLTags(oldString)
{
	     Stringtmp=""+oldString;
		 return Stringtmp.replace(/(<([^>]+)>)/ig,"");
}

function rpcheckyt(rpurl)
{
	    var tmpmsg;

		<?php echo $rpjs; ?>.ajax
		({
		  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=checkyoutube&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=ajax",
		  global: false,
		  type: "POST",
		  data:{rp_url: rpurl},
		  async: false,
		  dataType: "html",
		  success: function(msg)
		  {
			  tmpmsg=msg;

		  }
		  ,error: function()
		  {
			  tmpmsg="false";
		  }

	   });

	   return tmpmsg;

};

function getEmbed(rpvideourl, width, height)
{
	    var tmpmsg;

		<?php echo $rpjs; ?>.ajax
		({
		  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=videoEmbed&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=ajax",
		  global: false,
		  type: "POST",
		  data:{rp_video_url: rpvideourl,rp_video_width: width, rp_video_height: height},
		  async: false,
		  dataType: "html",
		  success: function(msg)
		  {
			  tmpmsg=msg;

		  }
		  ,error: function()
		  {
			  tmpmsg="false";
		  }

	   });

	   return tmpmsg;

};


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

function gritter_message(message,sticky,time)
{
	  <?php echo $rpjs; ?>.gritter.add
	  ({
		  title: '<?php echo JText::_('LANG_NOT1'); ?>:',
		  text:  message,
		  image: '<?php echo SMALL_LOGO; ?>',
		  sticky: sticky,
		  time: time,
		  class_name: 'my-class'
	  });
}

function rpnewimage()
{
isHandlerAddingActive = true;

var rpauthor = <?php echo $rpjs; ?>('input[name=new_image_name]').val();
var rpauthor_id = <?php echo $rpjs; ?>('input[name=rp_author_id]').val();
var rptext = <?php echo $rpjs; ?>('textarea[name=new_image_text]').val();
var rptitle = <?php echo $rpjs; ?>('input[name=new_image_title]').val();
var rpsticky = <?php echo $rpjs; ?>('input[name=new_image_sticky]').is(':checked');

var rpgdpr_elem = <?php echo $rpjs; ?>('input[name=new_gdpr]');

if(rpgdpr_elem.length && !rpgdpr_elem.is(':checked'))
{
	gritter_message('<?php echo JText::_('LANG_GDPR_ERROR'); ?>'); //Error
	isHandlerAddingActive = false;
	<?php echo $rpjs; ?>('input[name=new_gdpr]').parent().css("color", "#FF0000");
	return false;
}

if(typeof image_src!='undefined')
{
var rpurl= image_src;

	if(rpauthor=="")
	{
	rpauthor="User";
	}
	if(rptitle=="<?php echo JText::_('LANG_NEW_IMAGE5'); ?>")
	{
	rptitle="";
	}

		<?php echo $rpjs; ?>.ajax
		({
		  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=newpic&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=json",
		  global: false,
		  type: "POST",
		  datatype: "json",
		  data: ({rp_text: rptext,rp_author: rpauthor,rp_url: rpurl,rp_title: rptitle, rp_sticky: rpsticky, rp_author_id: rpauthor_id}),
		  success: function(data)
		  {
				if(typeof data != "object")
		  		data = <?php echo $rpjs; ?>.parseJSON(data);

				if(data.success==true)
				{
					<?php echo $rpjs; ?>('#new_image_layer').toggle();

					/*<?php echo $rpjs; ?>('#new_image_layer').slideToggle("slow", function()
					{ */
						<?php echo $rpjs; ?>('.rp_dragger').each
						(
						  function()
						  {
						  <?php echo $rpjs; ?>(this).show();
						  }
						);
					/*});*/

				   if(data.approved==1)
				   {
				   append_new_image(data.id,data.xPos,data.yPos,data.width,data.height,data.url,data.text,data.title,data.author,data.author_id,'<?php echo JText::_('LANG_ICON_INFO_NEW'); ?>',true,true);
				   image_set=<?php echo $rpjs; ?>.merge([{'href': '<?php echo PIC_THUMBS_URL."th_"; ?>'+data.url, 'title': data.text}],<?php echo $rpjs; ?>.merge([],image_set));
				   clear();
				   //link_check("pic",data.id,data.author,'<?php echo JText::_('LANG_ICON_INFO_NEW'); ?>');
				   gritter_message('<?php echo JText::_('LANG_NEW_IMAGE_SUCCESS'); ?> '+data.url,false,4000); //added
				   }
				   else
				   {
				   gritter_message('<?php echo JText::_('LANG_NEW_APPROVAL_ERROR'); ?>',true,5000); //no approval
				   isHandlerAddingActive = false;
				   }

				}
				else
				{
				gritter_message('<?php echo JText::_('LANG_CON4a'); ?>',true,5000); //error
				isHandlerAddingActive = false;
	            }


		  },
		  error: function(XMLHttpRequest, textStatus, errorThrown)
		  {
		  gritter_message(textStatus+': '+errorThrown,true,5000); //Error
		  isHandlerAddingActive = false;
		  }
	   });

}
else
{
		  gritter_message('<?php echo JText::_('LANG_NEW_IMAGE_ERROR1'); ?>',true,5000); //no image
		  isHandlerAddingActive = false;
}


};

function rpnewpostit()
{
isHandlerAddingActive = true;

var rpauthor = <?php echo $rpjs; ?>('input[name=new_postit_name]').val();
var rpauthor_id = <?php echo $rpjs; ?>('input[name=rp_author_id]').val();
var rptext = <?php echo $rpjs; ?>('textarea[name=new_postit_text]').val();
var rpsticky = <?php echo $rpjs; ?>('input[name=new_postit_sticky]').is(':checked');

var rpgdpr_elem = <?php echo $rpjs; ?>('input[name=new_gdpr]');

if(rpgdpr_elem.length && !rpgdpr_elem.is(':checked'))
{
	gritter_message('<?php echo JText::_('LANG_GDPR_ERROR'); ?>'); //Error
	isHandlerAddingActive = false;
	<?php echo $rpjs; ?>('input[name=new_gdpr]').parent().css("color", "#FF0000");
	return false;
}

if(rptext!='')
{
	if(rpauthor=="")
	{
	rpauthor="User";
	}

		<?php echo $rpjs; ?>.ajax
		({
		  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=newpostit&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=json",
          global: false,
		  type: "POST",
		  data:({rp_text: rptext, rp_author: rpauthor, rp_sticky: rpsticky, rp_author_id: rpauthor_id}),
		  dataType: "json",
		  success: function(data)
		  {
		  		if(typeof data != "object")
		  		data = jQuery.parseJSON(data);

				if(data.success==true)
				{
					<?php echo $rpjs; ?>('#new_postit_layer').toggle();

					/*<?php echo $rpjs; ?>('#new_postit_layer').slideToggle("slow", function()
					{ */
						<?php echo $rpjs; ?>('.rp_dragger').each
						(
						  function()
						  {
						  <?php echo $rpjs; ?>(this).show();
						  }
						);
					/*});*/

				   if(data.approved==1)
				   {
				   append_new_postit(data.id,data.xPos,data.yPos,data.text,data.author,data.author_id,'<?php echo JText::_('LANG_ICON_INFO_NEW'); ?>',true,true);
				   clear();
				   //link_check("postit",data.id,data.author,'<?php echo JText::_('LANG_ICON_INFO_NEW'); ?>');
				   gritter_message('<?php echo JText::_('LANG_NEW_POSTIT_SUCCESS'); ?>',false,4000); // added
				   }
				   else
				   {
				   gritter_message('<?php echo JText::_('LANG_NEW_APPROVAL_ERROR'); ?>',true,5000); //Approval
				   isHandlerAddingActive = false;
				   }

				}
				else
				{
				gritter_message('<?php echo JText::_('LANG_CON4a'); ?>',true,5000); //Error
				isHandlerAddingActive = false;
	            }

		  },
		  error: function(XMLHttpRequest, textStatus, errorThrown)
		  {
		  gritter_message(textStatus+': '+errorThrown,true,5000); //Error
		  isHandlerAddingActive = false;
		  }
	   });

}
else
{//No Text
gritter_message('<?php echo JText::_('LANG_NEW_POSTIT_ERROR1'); ?>',true,5000);
isHandlerAddingActive = false;
}


};//end new postit


function rpnewvideo()
{
isHandlerAddingActive = true;

var rpauthor = <?php echo $rpjs; ?>('input[name=new_video_name]').val();
var rpauthor_id = <?php echo $rpjs; ?>('input[name=rp_author_id]').val();
var rptext = <?php echo $rpjs; ?>('textarea[name=new_video_text]').val();
var rpurl = <?php echo $rpjs; ?>('input[name=new_video_url]').val();
var rpsticky = <?php echo $rpjs; ?>('input[name=new_video_sticky]').is(':checked');
var rptitle ="";

var rpgdpr_elem = <?php echo $rpjs; ?>('input[name=new_gdpr]');

if(rpgdpr_elem.length && !rpgdpr_elem.is(':checked'))
{
	gritter_message('<?php echo JText::_('LANG_GDPR_ERROR'); ?>'); //Error
	isHandlerAddingActive = false;
	<?php echo $rpjs; ?>('input[name=new_gdpr]').parent().css("color", "#FF0000");
	return false;
}

if(rpurl!='')
{

	if(rpauthor=="")
	{
	rpauthor="User";
	}

				<?php echo $rpjs; ?>.ajax
				({
				  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=newvideo&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=json",
				  global: false,
				  type: "POST",
				  data:({rp_text: rptext,rp_author: rpauthor,rp_title: rptitle,rp_url: rpurl, rp_sticky: rpsticky, rp_author_id: rpauthor_id}),
				  dataType: "json",
				  success: function(data)
				  {

				  	if(typeof data != "object")
		  			data = jQuery.parseJSON(data);

					if(data.success==true)
					{
						<?php echo $rpjs; ?>('#new_video_layer').toggle();

						/*<?php echo $rpjs; ?>('#new_video_layer').slideToggle("slow", function()
						{ 	*/
							<?php echo $rpjs; ?>('.rp_dragger').each
							(
							  function()
							  {
							  <?php echo $rpjs; ?>(this).show();
							  }
							);
						/*});*/

					   if(data.approved==1)
					   {
					   append_new_video(data.id,data.xPos,data.yPos,data.url,data.text,data.title,data.author,data.author_id,'<?php echo JText::_('LANG_ICON_INFO_NEW'); ?>',data.embed,true,true);
					   clear();
					   //link_check("youtube",data.id,data.author,'<?php echo JText::_('LANG_ICON_INFO_NEW'); ?>');
					   gritter_message('<?php echo JText::_('LANG_NEW_VIDEO_SUCCESS'); ?>',false,4000); //added
					   }
					   else
					   {
					   gritter_message('<?php echo JText::_('LANG_NEW_APPROVAL_ERROR'); ?>',true,5000);
					   isHandlerAddingActive = false;
					   }


					}
					else
					{
					gritter_message(data.message,true,5000);
					isHandlerAddingActive = false;
					}


				},
				  error: function(XMLHttpRequest, textStatus, errorThrown)
				  {
				  gritter_message(textStatus+': '+errorThrown,true,5000); //Error
				  isHandlerAddingActive = false;
				  }

			   });//end request


}
else
{
		gritter_message('<?php echo JText::_('LANG_NEW_VIDEO_ERROR1'); ?>',true,5000); //no video url
		isHandlerAddingActive = false;
}



};//end new video



function rpdel(rpid, rpauthor_id)
{
	var success;
	<?php echo $rpjs; ?>.ajax
	({
		  url: "<?php echo JURI::base(); ?>index.php?option=com_realpin&task=delete&view=rpdefault&pinboard=<?php echo PINBOARD; ?>&format=json",
		  global: false,
		  type: "POST",
		  async: false,
		  data:({rp_id: rpid, rp_author_id: rpauthor_id}),
		  dataType: "json",
		  success: function(data)
		  {

		 		if(typeof data != "object")
		  		data = jQuery.parseJSON(data);

					if(data.success==true)
					{
						gritter_message(data.message,false,4000); //Removal successful
						success=true;
					}
					else
					{
						gritter_message(data.message,true,5000); //Removal failed
						success=false;
					}
		  }
	});

    return success;

};

function wantmobile(mybool)
{
	if(mybool)
	{
		<?php echo $rpjs; ?>('#rp_dialog-overlay, #rp_dialog-box').hide();
	}
	else
	{
		<?php echo $rpjs; ?>('#rp_dialog-overlay, #rp_dialog-box').hide();
		var pathname = addParameterToURL("rp_mobile=0");
		window.location = pathname;
	}
}

function addParameterToURL(param){
    _url =  window.location.href;
    _url += (_url.split('?')[1] ? '&':'?') + param;
    return _url;
}


//<![CDATA[
function deleteItem($item)
{
  confirmMsg="<?php echo JText::_('LANG_NOT6'); ?> ?"
  if(confirm(confirmMsg)==true)
  {
		$item.fadeOut(function()
		{
			<?php echo $rpjs; ?>('#rp_trash a').css('background-image', 'url(<?php echo ROOT;?>/css/Recycle-Bin-Full-icon.png)');
			var id=$item.find('div.rp_content').attr("rp_id");
			var author_id=$item.find('div.rp_content').attr("rp_author_id");
			var result=rpdel(id,author_id);
			if(result==true){$item.remove();}else{$item.show();<?php echo $rpjs; ?>('#rp_trash a').css('background-image', 'url(<?php echo ROOT;?>/css/Recycle-Bin-Empty-icon.png)');$item.css('left','200px');};
		});
  }
  else
  {
  $item.css('left','200px');
  }
};//]]>

</script>
