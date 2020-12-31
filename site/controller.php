<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT.DS.'includes'.DS.'functions.php');
require_once(JPATH_COMPONENT.DS.'includes'.DS.'embed'.DS.'AutoEmbed.class.php');

$document	= JFactory::getDocument();
$lang = $document->getLanguage();
$user = JFactory::getUser();

$xml = simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR . '/realpin.xml');
define('RP_VERSION', $xml->version);

define('EMPTY_STRING', '');
define('LANG', strtolower($lang));
define('VIEW', JRequest::getVar( 'view','','GET'));
define('ROOT', JURI::base().'components/com_realpin/includes');
define('USERNAME_LOGIN', sanitize($user->username));
define('USERID', $user->id);
define('RPID', JRequest::getInt( 'rpitem',0,'GET'));

if(!empty($_GET['fullscreen']))
{
	define('FULLSCREEN', JRequest::getVar( 'fullscreen','0','GET'));
}
		
$language = JFactory::getLanguage();
$language->load('com_realpin');

$debug=JRequest::getVar( 'rp_debug','0','GET');
define('DEBUG', $debug);

if(version_compare(JVERSION,'1.6.0','ge'))
{
	// Joomla! 1.6 or greater, code here
	$usrid = $user->get('id');
	$getGroups = JAccess::getGroupsByUser($usrid);
	
	$usrgid=0;
	foreach($getGroups as $key => $value) 
	{
		if($value==7 or $value==8) {$usrgid=25;break;}
		if($value>=2 and $value <=6){$usrgid=18;}
		//echo $value;
	}
		
	define('USER', $usrgid);
	
	if(DEBUG=="1")
	{
		var_dump($getGroups);
		echo "User: ".USER;
	}
} 
else 
{
	// Joomla! 1.5 code here
	define('USER', $user->get('gid'));
}

class realpinController extends JControllerLegacy
{

	function display($cachable = false, $urlparams = Array())
	{

	$vars = new stdClass();

	$this->def();
		
	//define('RPTEMPLATE', strtolower(JRequest::getVar('rptemplate',rand(1,3),'GET')));
	define('SITE', strtolower(JRequest::getVar( 'user','','GET')));
	if(SITE!=null){$this->setRedirect('index.php?option=com_realpin_hosting&user='.SITE);}
	//JRequest::setVar( 'tmpl','realpin');
	
	switch (VIEW) {
    case 'rpdefault':
        	require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.html.php');
	        $view = new realpinViewrpdefault();	
	        $model = &$this->getModel('rpdefault');
			$view->setModel($model, true);
			if(COMMUNITY==1){JRequest::setVar( 'tmpl','realpin');}
        break;
    case 'rpparameter':
        	require_once (JPATH_COMPONENT.DS.'views'.DS.'rpparameter'.DS.'view.html.php');
	        $view = new realpinViewrpparameter();	
	        $model = $this->getModel('rpparameter');
			$view->setModel($model, true);
        break;
    default:
        	require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.html.php');
	        $view = new realpinViewrpdefault();	
	        $model = $this->getModel('rpdefault');
			$view->setModel($model, true);
			if(COMMUNITY==1){JRequest::setVar( 'tmpl','realpin');}
	}
    
	$view->assignRef('vars'  , $vars);
	$view->display();	
	}
	
	function email($type,$title,$author)
	{
	$db	= JFactory::getDBO();
		
	$recipient = "";
		
	if(COMMUNITY=="1")
    {
		$query = "SELECT name, email, sendEmail FROM #__users WHERE id = '".PINBOARDUSER."'";
		$db->setQuery( $query );
	    $rows = $db->loadObjectList();
		
		if(count($rows) > 0)
	    {
			$recipient = $rows[0]->email;
		}
		
	}
	else
	{
			$config = JFactory::getConfig();
		    $recipient = $config->get( 'mailfrom' );
			
			if(USERMAIL!="")
			{
				$recipient = USERMAIL;
			}
	}
	
	        if($recipient!="")
			{
				$siteURL      = JURI::base();
				$config = JFactory::getConfig();
				$mailer = JFactory::getMailer();
				$sender = array( $config->get( 'mailfrom' ), $config->get( 'fromname' ) );
				$mailer->setSender($sender);
				$mailer->addRecipient($recipient);
	
				$subject    = 'RealPin: '.JText::_('LANG_MAIL1').'!';
				$message =''.JText::_('LANG_MAIL0').
				' Administrator,<br/>
				<br/>
				'.JText::_('LANG_MAIL2').'.<br/><br/>
				'.JText::_('LANG_MAIL3').': '.$title.'<br/>
				'.JText::_('LANG_MAIL4').': '.$type.'<br/>
				'.JText::_('LANG_MAIL5').': '.$author.'<br/>
				<br/>';
				
				if (APPROVAL == "2" or (APPROVAL=="1" and USER<18))
				{
					if (COMMUNITY=="0")
					{
					$message.=JText::_('LANG_MAIL6').': <a href="'.$siteURL.'administrator/index.php?option=com_realpin&controller=management&task=display&pinboard='.PINBOARD.'&community='.COMMUNITY.'">'.JText::_('LANG_MAIL7').'</a><br/><br/>';
					}
				}
				
				$message.=JText::_('LANG_MAIL8').': <a href="'.$siteURL.'index.php?option=com_realpin&pinboard='.PINBOARD.'&Itemid='.JRequest::getInt( 'Itemid',0,'REQUEST').'">'.JText::_('LANG_MAIL7').'</a>';
				
			   $mailer->setSubject($subject);
			   $mailer->isHTML(true);
			   $mailer->Encoding = 'base64';
			   $mailer->setBody($message);

				$send = $mailer->Send();
				if ( $send !== true ) {
				    //echo 'Error sending email: ' . $send->message;
				}

		   }
	
	}
	
	function def($property = NULL, $default = NULL) 
	{
	
	require_once(JPATH_COMPONENT.DS.'includes'.DS.'ismobile.php');
    $detect = new MobileDetect();
	
	$rp_mobile = JRequest::getVar( 'rp_mobile','','GET');
	if($rp_mobile == "1")
	{define('RP_MOBILE', true);}
	elseif($rp_mobile == "0")
	{define('RP_MOBILE', false);}
	else
    {define('RP_MOBILE', $detect->isMobile());}
	
	if (RP_MOBILE) {
   		 define('FULLSCREEN', '1');
		 JRequest::setVar( 'tmpl','realpin');
		 define('BORDER_BACKGROUND', 'transparent');
    }
	
	if(DEBUG=="1"){define('JQUERY_COMPATIBILITY', '1');}
	if(DEBUG=="2")
	{
	define('JQUERY_COMPATIBILITY', '1');
	JRequest::setVar( 'tmpl','realpin');
	
	echo "DEBUG-INFO:";
	echo "<br/>";
	
		if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin')), 1)=="0755")
		{ echo "<span style='color:green;'>realpin: ".JText::_('writable')."</span>";} else
		{ echo "<span style='color:red;'>realpin: ".JText::_('unwritable')."</span>";}
		
		echo "<br/>";
		
		if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs')), 1)=="0755")
		{ echo "<span style='color:green;'>realpin/thumbs: ".JText::_('writable')."</span>";} else
		{ echo "<span style='color:red;'>realpin/thumbs: ".JText::_('unwritable')."</span>";}
		
		echo "<br/>";
		
		if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'tmp')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'tmp')), 1)=="0755")
		{ echo "<span style='color:green;'>realpin/tmp: ".JText::_('writable')."</span>";} else
		{ echo "<span style='color:red;'>realpin/tmp: ".JText::_('unwritable')."</span>";}
		
		echo "<br/>";
		
		if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'misc')), 1)=="0777" or substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'misc')), 1)=="0755")
		{ echo "<span style='color:green;'>realpin/misc: ".JText::_('writable')."</span>";} else
		{ echo "<span style='color:red;'>realpin/misc: ".JText::_('unwritable')."</span>";}
		
		echo "<br/>";
		echo "<br/>";
	}		
		
	$pinboard=strtolower(JRequest::getInt('pinboard',5,'REQUEST'));
	define('PINBOARD',$pinboard);
	
	$import = preg_replace ("/SITE/", PINBOARD, JText::_('LANG_NOPINBOARD'));
	
	$db   = JFactory::getDBO();
	
	//check if settings exists	
	$query = "SELECT config_user_id, config_community FROM #__realpin_settings WHERE config_id='".PINBOARD."' and published='1' and config_name!='default_private' and config_name!='default_private_global' and config_name!='default_community' and config_name!='default_community_global' ";
	$db->setQuery($query);
	$obj=$db->loadObject();
	$userid=0;
	$community=0;
	if($obj!=NULL){$community=$obj->config_community; $userid=$obj->config_user_id;}
	else{echo "<div align=\"center\"><br/><img src=\"".ROOT."/css/admin/logo_version.png\" /><br/><br/><br/><br/>";echo $import."</div>";exit;}

	define('COMMUNITY', $community);
	define('PINBOARDUSER', $userid);
	
	if($community=="1")
    {
	define('CHECK', "community");
	}
	else
	{
	define('CHECK', "private");
	}
	
	if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
	define('RP_SETTINGS_TABLE','#__realpin_settings');
	define('RP_TABLE','#__realpin_items');
    define('PIC_DIR',  JPATH_ROOT.DS.'images'.DS.'realpin'.DS);
	define('PIC_THUMBS_DIR', JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'thumbs'.DS);
	define('PIC_TMP_DIR', JPATH_ROOT.DS.'images'.DS.'realpin'.DS.'tmp'.DS);
	define('PIC_THUMBS_URL', JURI::root().'images/realpin/thumbs/');
	define('PIC_TMP_URL', JURI::root().'images/realpin/tmp/');
	
	$model = $this->getModel('rpdefault');
			
	$settings=$model->getSettingsList();
	$isglobal=$model->getGlobalSettingsList();
	$globalsettings=$model->getDefaultSettingsList();
	
	if(intval(get_cfg_var('allow_url_fopen'))==0)
	{define('YT_VALIDATE', '0');}
	
	foreach ($isglobal as $key=>$value )
	{
    if($value==""){$db	= JFactory::getDBO(); $db->setQuery("UPDATE `".RP_SETTINGS_TABLE."` SET `$key` = 'true' WHERE config_name = 'default_private_global' "); $db->query();}	
	if($value=="true"){if(!defined($key)){@define(strtoupper($key),  $globalsettings[$key]);}}
	}	
	foreach ( $settings as $key=>$value )
	{ 
	if(!defined($key)){@define(strtoupper($key),  $value);}
	}
	
	$message1=JURI::root();
	$message2=JPATH_ROOT;
	$message2=str_replace("\\","",$message2);
	//$message1=str_replace(array("http://www.","http://","www."),"",$message1);
	$message1=str_replace(array("http://www.","http://","www.","https://","https://www."),"",$message1);
	$encoded1 = str_replace(' ', "",rsa_encrypt ($message1,  7681,  60716087));
	$encoded2 = str_replace(' ', "",rsa_encrypt ($message2,  7681,  60716087));
	
	if($encoded1 == str_replace(' ', "",LICENSE) or $encoded2 == str_replace(' ', "",LICENSE)){$licensed=true;}else{$licensed=false;}
	
	define('LICENSED', $licensed);
	
	if(LOGO_SMALL=="default" or LOGO_SMALL==""){define('SMALL_LOGO', ROOT."/css/logo_small.png");}else{define('SMALL_LOGO', LOGO_SMALL);}
	
	if(MAXITEMS=='auto')
	{
	define('ITEMSPERPAGE',round((TOTAL_WIDTH*TOTAL_HEIGHT)/(220*220),0));
	}
	else
	{
	define('ITEMSPERPAGE',MAXITEMS);	
	}


	}
	
	function updatepinboard()
	{
	$db		= JFactory::getDBO();
	$dating=date("Y-m-d H:i:s");
	$query = "UPDATE #__realpin_settings Set config_updated = '$dating' WHERE config_id='".PINBOARD."'";
	$db->setQuery($query);		
	$db->query();
	}
		
	function loaddata()
	{	
    $this->def();
	global $mainframe;
	$db		= JFactory::getDBO();
	
	$start_ug_y=90;
	$start_ug_x=90;
	$size_ug_x=$start_ug_x;
	$size_ug_y=$start_ug_y;
	
	$start_g_y=230;
	$start_g_x=150;
	$size_g_x=$start_g_x;
	$size_g_y=$start_g_y;
	
	$page=JRequest::getInt( 'rp_page' , 0, 'POST');
	$itemsperpage=JRequest::getInt( 'rp_itemsperpage' , 10, 'POST');
	
    $db->setQuery("SELECT * FROM ".RP_TABLE." WHERE pinboard='".PINBOARD."' AND (sticky='1' OR created>=DATE_ADD(NOW(), INTERVAL -".LIFETIME." DAY)) AND published='1' ORDER BY sticky DESC, created DESC LIMIT ".$page*$itemsperpage.",".($itemsperpage).";");
	//$db->query();
	$data = array();
	$index=0;
	if( $rows = $db->loadObjectList() )
	{
	  foreach( $rows as $row )
	  {

	   $row->title=remove_all_special($row->title);
	   $row->text=remove_all_special($row->text);
	   $row->author=remove_all_special($row->author);
	   $row->url=remove_all_special($row->url);
	   
			if ($row->type=="pic")
			{
				if (!file_exists(PIC_THUMBS_DIR.$row->url) && file_exists(PIC_DIR.$row->url))       //thumbs erstellen
				{
				create_thumb_jpg_gr($row->url);
				create_thumb_jpg_kl($row->url,$row->title);
				}
				if(!file_exists(PIC_DIR.$row->url)){$row->url="no_pic.jpg";}
				$tmptitle=create_tooltip($row->title,$row->text,JText::_('LANG_OPEN_GALLERY'),30,"&lt;br /&gt;");  //tip
				$tmptext=create_tooltip($row->title,$row->text,JText::_('LANG_NO_TEXT'),100,"&lt;br /&gt;");  //fancytitle
				$row->title=$tmptitle;
				$row->text=$tmptext;				 
			}
			
			if ($row->type=="postit")
			{
			    $row->text=postit_text($row->text);
			}
			
			if ($row->type=="youtube")
			{
				$row->title=create_tooltip($row->title,$row->text,"",30,"&lt;br /&gt;");  //tip
				$row->embed="false";
				
				$AE = new AutoEmbed();
	
		        if ($AE->parseUrl($row->url)) {
			    //$imageURL = $AE->getImageURL();
			    $AE->setObjectAttrib('width',YOUTUBE_X);
			    $AE->setObjectAttrib('height',YOUTUBE_Y);
				$row->embed= $AE->getEmbedCode();
				}
			}
			
		if(POSITIONING=="0")
		{
				 
		  if($index % 2 == 0)
		  {// Zahl ist gerade
			  if($size_g_x<TOTAL_WIDTH-370)
			  {
			  $row->xPos=$size_g_x+rand(-40,40);
			  $row->yPos=$size_g_y+rand(-40,40);
			  $size_g_x=$size_g_x+200;
			  }
			  else
			  {
			  $row->xPos=$size_g_x+rand(-40,40);
			  $row->yPos=$size_g_y+rand(-40,40);
			  $size_g_x=$start_g_x;
			  $size_g_y=$size_g_y+260;
			  }
	  
		  }
		  else
		  {
			  if($size_ug_x<TOTAL_WIDTH-370)
			  {
			  $row->xPos=$size_ug_x+rand(-40,40);
			  $row->yPos=$size_ug_y+rand(-40,40);
			  $size_ug_x=$size_ug_x+200;
			  }
			  else
			  {
			  $row->xPos=$size_ug_x+rand(-40,40);
			  $row->yPos=$size_ug_y+rand(-40,40);
			  $size_ug_x=$start_ug_x;
			  $size_ug_y=$size_ug_y+260;
			  }
		  }
		  $index++;	
		  
		  if ($row->type=="pic")
		  {
			  if(file_exists(PIC_THUMBS_DIR.$row->url))
			  {
			  $pic=PIC_THUMBS_DIR.$row->url;
			  $imagesize = getimagesize($pic);
			  $x=round($imagesize[0]*PIC_SCALE,0);
			  $y=round($imagesize[1]*PIC_SCALE,0);
			  }
		  if($x+$row->xPos>TOTAL_WIDTH){$row->xPos=$row->xPos-50;}
		  if($y+$row->yPos>TOTAL_HEIGHT){$row->yPos=$row->yPos-50;}
		  }
		  
		}
		
		//zuf�llig
		if(POSITIONING=="2")
		{
		$row->yPos=rand(50,TOTAL_HEIGHT-200);
		$row->xPos=rand(50,TOTAL_WIDTH-250);
		}
		
		if(isURL($row->author)){if (strlen(strstr($row->author,"http://"))==0){$row->author=str_replace("www.", "http://www.", $row->author);}}
		
		  if(version_compare(JVERSION,'1.6.0','ge')) {
		  // Joomla! 1.6 code here
		   $row->created=JHTML::_('date',$row->created, JText::_('DATE_RP_FORMAT'));	
		  } else {
		  // Joomla! 1.5 code here
		   $row->created=JHTML::_('date',$row->created, JText::_('DATE_RP_FORMAT_J15'));	
		  }  
		
		
		$data[] = $row;
	  }
	}

	require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.json.php');
	$view = new realpinViewjson();
    $view->display($data);
	}
	
	function feed()
	{
	$this->def();
	global $mainframe;
	$db		= JFactory::getDBO();
	 
	require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.feed.php');
	$view = new realpinViewfeed();
	$model = &$this->getModel('rpdefault');
	$view->setModel($model, true);
    $view->display();
	
	}
	
	function save()
	{
	
        $this->def();
	    global $mainframe;
	    $db		= JFactory::getDBO();

		$x=JRequest::getInt( 'rp_x' , 0, 'POST');
		$y=JRequest::getInt( 'rp_y' , 0, 'POST');
		$id=JRequest::getInt( 'rp_id' , 0, 'POST');
		
		require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.ajax.php');
		$view = new realpinViewajax();
			
		if($x!='' && $y!='' && $id!='' && (SAVE == "2" or (SAVE=="1" and USER>=18) or (SAVE=="0" and USER>=24)))
		 {
				if($x<80 and $y<80){$x=120;$y=100;}
				$query = "UPDATE ".RP_TABLE." Set xPos = '$x',yPos = '$y' WHERE id='$id'";
				$db->setQuery($query);		
				$db->query();

		        $view->display(JText::_('LANG_NOT2'));
		 }
		 else
		 {
			    $view->display(JText::_('LANG_NOT3'));
	     }
		 
		 $this->updatepinboard();
		 
		 
		
	}
	
	function videoEmbed()
	{
	
	$url=stripslashes(JRequest::getVar( 'rp_video_url' , '', 'REQUEST'));
	$width=stripslashes(JRequest::getVar( 'rp_video_width' , '', 'REQUEST'));
	$height=stripslashes(JRequest::getVar( 'rp_video_height' , '', 'REQUEST'));
	
	$AE = new AutoEmbed();
	
		if (!$AE->parseUrl($url)) {
			echo "false";
		}
		else
		{
	
			//$imageURL = $AE->getImageURL();
			$AE->setObjectAttrib('width',$width);
			$AE->setObjectAttrib('height',$height);
			
			echo $AE->getEmbedCode();
		}
	
	}
		
    function isValidYoutubeURL($url) 
	{
	$id=getYTid($url);
    if (!$data = @file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$id)) return false;
    if ($data == "Video not found") return false;
    return true;
    }
	
	function checkyoutube() 
	{
	$url=stripslashes(JRequest::getVar( 'rp_url' , '', 'POST'));
	
		if($this->isValidYoutubeURL($url))
		{
			$msg="true";
		}
		else
		{
		    $msg="false";
		}
		require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.ajax.php');
		$view = new realpinViewajax();	
		$view->display($msg);		
    }

	function upload()
	{
	
        $this->def();
	    $db		= JFactory::getDBO();
		jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
		
		$img=$_FILES['rp_url']['name'];
		$path = JPath::clean(PIC_TMP_DIR.$img);
						
		$vars = new stdClass();		
		$vars->url=JFile::makeSafe($_FILES['rp_url']['name']);
		
		$max_upload_size = 1024000;
		if(PIC_NO_RESTRICT_SIZE!="")
		{$max_upload_size = PIC_NO_RESTRICT_SIZE;}
		
		if($max_upload_size == "" or $max_upload_size == 0)
		{$max_upload_size = 1024000;}
		
		$limit=(int) @ini_get('memory_limit')-round(memory_get_usage()/(1024*1024),0);
		
		if(DEBUG=="2")
	    {
		echo 'Here is some more debugging info:';
        print_r($_FILES);
		echo "Max Upload Size: ".round($max_upload_size/(1024*1024),2)." MB "; 
		echo "Max Memory Limit: ".$limit." MB ";
		
		exit;
		}
				
				if($vars->url!='' && ($_FILES["rp_url"]["type"] == "image/jpeg" or $_FILES["rp_url"]["type"] == "image/pjpeg") && $_FILES['rp_url']['size'] < $max_upload_size)
				{
					  
					  if(!JFile::upload($_FILES['rp_url']['tmp_name'], $path))
					  {
						  $msg=JText::_('LANG_CON4C');
					  }
					  else
					  {	
		                  $path = JPath::clean(PIC_TMP_DIR.$vars->url);
					      $size=getimagesize($path);
						  $imgsize=round(($size[0]*$size[1]*5)/(1024*1024),0);
						  
						  if($size[0]<200 or $size[1]<200)
						  {
						  $msg=JText::_('LANG_CON4D');	  //Error image too small
						  JFile::delete($path);
						  }
						  elseif($limit-$imgsize<2 and PIC_CHECK_SERVER_MEMORY!="0")
						  {
						  $msg=JText::_('LANG_CON4E');    //.$limit."-".$imgsize;	  //Error image too large
						  JFile::delete($path);
						  }
						  else
						  {
					      $msg=$vars->url;
						  }
					  };
				}
				else
				{
					  $msg=JText::_('LANG_CON4B');
				}
				
		   require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.ajax.php');
		   $view = new realpinViewajax();
		   $view->display($msg);
		
	}
	
	
	function delete()
	{

       $this->def();
	   $db		= JFactory::getDBO();
	   jimport('joomla.filesystem.file');

	   $id=JRequest::getInt( 'rp_id' , 0, 'POST');
	   $author_id=JRequest::getInt( 'rp_author_id' , 0, 'POST');
	   $user = JFactory::getUser();
	   
	   if (!$user->guest)
	   {
			if (USER >= 24 or (REMOVAL=="1" and (USERID==$author_id)) or (COMMUNITY==1 and USERID==PINBOARDUSER))
			{
				
				$obj=array();
				
				$query = "SELECT id,type,url,pw FROM ".RP_TABLE." WHERE id='$id'";
				$db->setQuery($query);
				$obj=$db->loadObject();  
				
				$type=$obj->type;
				$url=$obj->url;
				$src1= JPath::clean(PIC_TMP_DIR.$url);
				$src2= JPath::clean(PIC_THUMBS_DIR.$url);
				$src3= JPath::clean(PIC_THUMBS_DIR.'th_'.$url);
				
				if ($type=="pic")
				{
				if (file_exists($src1)){JFile::delete($src1);}
				if (file_exists($src2)){JFile::delete($src2);}
				if (file_exists($src3)){JFile::delete($src3);}
				}
				  
				$query = "DELETE FROM ".RP_TABLE." WHERE id='$id'";
				$db->setQuery( $query );
				if (!$db->query()) 
				{
				$msg=$db->getErrorMsg();
				$data= array ('success'=>false,'message'=>$msg);
				}
				else
				{
				$msg=JText::_('LANG_NOT4');
				$data= array ('success'=>true,'message'=>$msg);
				}
		   }
			else
		   {
			   $msg=JText::_('LANG_NOT7');
			   $data= array ('success'=>false,'message'=>$msg);
		   }
		}
		else
	    {
		   $msg=JText::_('LANG_NOT5');
		   $data= array ('success'=>false,'message'=>$msg);
	    }
		
		   require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.json.php');
		   $view = new realpinViewjson();
		   $view->display($data);

			
		
	}
	
	function newpic()
	{
		
        $this->def();
	    $db		= JFactory::getDBO();

		jimport('joomla.filesystem.file');
		
		if (NEW_ITEM == "2" or (NEW_ITEM=="1" and USER>=18) or (NEW_ITEM=="0" and USER>=24))
		{
				
				$vars = new stdClass();
				$vars->text=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_text' , '', 'POST')))));
				$vars->title=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_title' , '', 'POST')))));
				$vars->author=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_author' , '', 'POST')))));
				$vars->author_id=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_author_id' , '', 'POST')))));
				$vars->url=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_url' , '', 'POST')))));
				$vars->sticky=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_sticky' , '', 'POST')))));
				
				$xPos=rand(50,TOTAL_WIDTH-200);
				$yPos=rand(50,TOTAL_HEIGHT-200);
				//$xPos=150;
				//$yPos=150;
				
				$tmp=$vars->url; 
				
				if($vars->url!='' and file_exists(PIC_TMP_DIR.$tmp))
				{
					    $timestamp = time();
						$vars->url=JFile::makeSafe($vars->url); 
						
						$vars->urlnew=$timestamp."_".$vars->url;
						
					    $dating=date("Y-m-d H:i:s");
						
						if($vars->sticky=="true"){$sticky=1;}else{$sticky=0;}
						
						if (APPROVAL == "2" or (APPROVAL=="1" and USER<18)){$pub=0;}else{$pub=1;}
						$sqls = array("ALTER TABLE ".RP_TABLE." AUTO_INCREMENT = 1","INSERT INTO ".RP_TABLE." (xPos, yPos, type, text, url, pw, title, author, created, expires, published, sticky, pinboard, params, author_link, author_email, author_id) VALUES ('$xPos','$yPos', 'pic', '$vars->text', '$vars->urlnew','','$vars->title','$vars->author','$dating','','$pub','$sticky','".PINBOARD."','','','','$vars->author_id')");
						
						foreach($sqls as $sql) 
						{
							$db->setQuery($sql);
							$db->query();
						}
						
						$obj=array();
				
				        $query = "SELECT id FROM ".RP_TABLE." WHERE created='$dating'";	
				        $db->setQuery($query);
				        $obj=$db->loadObject();  
				
				        $vars->id=$obj->id;
						
		                $src= JPath::clean(PIC_TMP_DIR.$tmp);
						$dest= JPath::clean(PIC_DIR.$vars->urlnew);
						$thumb= JPath::clean(PIC_THUMBS_DIR.$vars->urlnew);	
				
						JFile::move($src,$dest);
						create_thumb_jpg_gr($vars->urlnew);
						create_thumb_jpg_kl($vars->urlnew,$vars->title);
						JFile::delete($src);
						
						$imagesize=getimagesize($thumb);
						$width=$imagesize[0]; 
						$height=$imagesize[1];
						
						if (EMAIL == "2" or (EMAIL=="1" and USER<18))
						{
						$this->email('Pic',$vars->title,$vars->author);
						}
						
						$tmptitle=create_tooltip($vars->title,$vars->text,JText::_('LANG_OPEN_GALLERY'),30,"<br/>");  //tip
						$tmptext= create_tooltip($vars->title,$vars->text,JText::_('LANG_NO_TEXT'),100,"<br/>");  //fancytitle
						$vars->title=$tmptitle;
						$vars->text=$tmptext;
						$msg="true";					
						$data= array ('success'=>true,'message'=>$msg,'approved'=>$pub,'id'=>$vars->id,'xPos'=>$xPos,'yPos'=>$yPos,'width'=>$width,'height'=>$height,'url'=>$vars->urlnew,'text'=>$vars->text,'title'=>$vars->title,'author'=>$vars->author,'author_id'=>$vars->author_id);
						$this->updatepinboard();

				}
				else
				{
				$msg="false";
				$data= array ('success'=>false,'message'=>$msg);
				}	
		
		}
		
	require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.json.php');
	$view = new realpinViewjson();
    $view->display($data);
			
	}
	
	function newpostit()
	{		
		
		$this->def();
	    $db		= JFactory::getDBO();
		
		if (NEW_ITEM == "2" or (NEW_ITEM=="1" and USER>=18) or (NEW_ITEM=="0" and USER>=24))
		{
	
	    $vars = new stdClass();
		$vars->text=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_text' , '', 'POST')))));
		$vars->author=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_author' , '', 'POST')))));
		$vars->author_id=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_author_id' , '', 'POST')))));
		$vars->sticky=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_sticky' , '', 'POST')))));
		
			$xPos=rand(50,TOTAL_WIDTH-250);
			$yPos=rand(50,TOTAL_HEIGHT-200);
		
			if($vars->text!='')
			{
			$dating= date("Y-m-d H:i:s");
			
			if($vars->sticky=="true"){$sticky=1;}else{$sticky=0;}
			
			if (APPROVAL == "2" or (APPROVAL=="1" and USER<18)){$pub=0;}else{$pub=1;}
			$sqls = array("ALTER TABLE ".RP_TABLE." AUTO_INCREMENT = 1","INSERT INTO ".RP_TABLE." (xPos, yPos, type, text, url, pw, title, author, created, expires, published, sticky, pinboard, params, author_link, author_email, author_id) VALUES ('$xPos','$yPos', 'postit', '$vars->text', '','','','$vars->author','$dating','','$pub','$sticky','".PINBOARD."','','','','$vars->author_id')");
					
			foreach($sqls as $sql) 
			{
				$db->setQuery($sql);
				$db->query();
			}
			
			$obj=array();
				
			$query = "SELECT id FROM ".RP_TABLE." WHERE created='$dating'";	
			$db->setQuery($query);
			$obj=$db->loadObject();  
	
			$vars->id=$obj->id;
			 
			$vars->text=postit_text($vars->text); 
			$msg="true";
            $data= array ('success'=>true,'message'=>$msg,'approved'=>$pub,'id'=>$vars->id,'xPos'=>$xPos,'yPos'=>$yPos,'text'=>$vars->text,'author'=>$vars->author,'author_id'=>$vars->author_id);
			$this->updatepinboard();
					
				if (EMAIL == "2" or (EMAIL=="1" and USER<18))
				{
				$this->email('Postit','',$vars->author);
				}
				
			}
			else
			{
			$msg="false";
		    $data= array ('success'=>false,'message'=>$msg);
			}
			
		   require_once(JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.json.php');
		   $view = new realpinViewjson();
		   $view->display($data);
		
		
		}


			
	}
	
	function newvideo()
	{
		$this->def();
	    $db		= JFactory::getDBO();

		$go=false;
		
		if (NEW_ITEM == "2" or (NEW_ITEM=="1" and USER>=18) or (NEW_ITEM=="0" and USER>=24))
		{
		
	    $vars = new stdClass();
		$vars->embed="false";
		$vars->text=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_text' , '', 'POST')))));
		$vars->title=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_title' , '', 'POST')))));
		$vars->author=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_author' , '', 'POST')))));
		$vars->author_id=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_author_id' , '', 'POST')))));
		$vars->url=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_url' , '', 'POST')))));
		$vars->sticky=strip_tags(addslashes(remove_all_special(stripslashes(JRequest::getVar( 'rp_sticky' , '', 'POST')))));
		
		$xPos=rand(50,TOTAL_WIDTH-200);
		$yPos=rand(50,TOTAL_HEIGHT-200);

		if($vars->url!='')
		{
		  $go=true;
		  //if(YT_VALIDATE=="0"){$go=true;}
		  if(YT_VALIDATE=="1")
		  {
			  if($this->isValidYoutubeURL($vars->url))
			  {
			  $go=true;
			  $vidID=getYTid($vars->url);
		      $urltmp = "https://gdata.youtube.com/feeds/api/videos/".$vidID;
			  $doc = new DOMDocument('1.0','UTF-8');
			  $doc->load($urltmp);
			  $vars->title = addslashes($doc->getElementsByTagName("title")->item(0)->nodeValue);
			  if($vars->text=='' or $vars->text=='-')
			  {$vars->text = addslashes($doc->getElementsByTagName("content")->item(0)->nodeValue);}
			  }
		  }
		  
			if($go==true)
			{
				$dating= date("Y-m-d H:i:s");
				
				if($vars->sticky=="true"){$sticky=1;}
				else{$sticky=0;}
				
				if (APPROVAL == "2" or (APPROVAL=="1" and USER<18)){$pub=0;}else{$pub=1;}
				$sqls = array("ALTER TABLE ".RP_TABLE." AUTO_INCREMENT = 1","INSERT INTO ".RP_TABLE." (xPos, yPos, type, text, url, pw, title, author, created, expires, published, sticky, pinboard, params, author_link, author_email, author_id) VALUES ('$xPos','$yPos', 'youtube', '$vars->text', '$vars->url','','$vars->title','$vars->author','$dating','','$pub','$sticky','".PINBOARD."','','','','$vars->author_id')");
					
					foreach($sqls as $sql) 
					{
						$db->setQuery($sql);
						$db->query();
					}
				
				$obj=array();
					
				$query = "SELECT id FROM ".RP_TABLE." WHERE created='$dating'";	
				$db->setQuery($query);
				$obj=$db->loadObject();  
		
				$vars->id=$obj->id;
	
				if (EMAIL == 2 or (EMAIL==1 and USER<18))
				{
				$this->email('Youtube Video',$vars->title,$vars->author);
				}
				
				$AE = new AutoEmbed();
	
				if ($AE->parseUrl($vars->url)) 
				{
					//$imageURL = $AE->getImageURL();
					$AE->setObjectAttrib('width',YOUTUBE_X);
					$AE->setObjectAttrib('height',YOUTUBE_Y);
					$vars->embed= $AE->getEmbedCode();
				}
	
	            $vars->title=create_tooltip($vars->title,$vars->text,"",30,"&lt;br /&gt;");
				$msg="true";
				$data= array ('success'=>true,'message'=>$msg,'approved'=>$pub,'id'=>$vars->id,'xPos'=>$xPos,'yPos'=>$yPos,'url'=>$vars->url,'text'=>$vars->text,'title'=>$vars->title,'author'=>$vars->author,'author_id'=>$vars->author_id,'embed'=>$vars->embed);
				$this->updatepinboard();
			}
			else  //not valid
			{
				  $msg=JText::_('LANG_NEW_VIDEO_ERROR2');
				  $data= array ('success'=>false,'message'=>$msg);	
			}
		}
		else  //internal error
		{
		$msg=JText::_('LANG_CON4a');
		$data= array ('success'=>false,'message'=>$msg);
		}
		
	   require_once (JPATH_COMPONENT.DS.'views'.DS.'rpdefault'.DS.'view.json.php');
	   $view = new realpinViewjson();
	   $view->display($data);
	
	}
	
	}
}
?>