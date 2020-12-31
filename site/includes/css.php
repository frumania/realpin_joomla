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
$doc->addStyleSheet(ROOT.'/js/gritter/jquery.gritter.css');
$doc->addStyleSheet(ROOT.'/js/fancybox/jquery.fancybox-1.3.4.css');

if (RP_MOBILE) {
$doc->addStyleSheet(ROOT.'/js/photoswipe/photoswipe.css');
}

ob_start();  //startet Buffer
//include(JPATH_COMPONENT.DS.'includes'.DS.'js'.DS.'gritter'.DS.'jquery.gritter.css');
//include(JPATH_COMPONENT.DS.'includes'.DS.'js'.DS.'fancybox'.DS.'fancybox-1.3.1.css');
include(JPATH_COMPONENT.DS.'includes'.DS.'static_css.php');  //datei ist jetzt im Buffer

//if(RPTEMPLATE!="0")
//{
//include(JPATH_COMPONENT.DS.'templates'.DS.'default'.RPTEMPLATE.'.css'); 
//}

$style=ob_get_contents();  //Buffer wird in $var geschrieben
ob_end_clean();  //Buffer wird gel�scht
$style = str_replace("<style type=\"text/css\">", "", $style);
$style = str_replace("</style>", "", $style);



//start--------------layer
$start_ug_y=100;
$start_ug_x=100;
$size_ug_x=$start_ug_x;
$size_ug_y=$start_ug_y;

$start_g_y=230;
$start_g_x=150;
$size_g_x=$start_g_x;
$size_g_y=$start_g_y;

for($h = 0; $h < $layers; $h++)
{

$id = $data[$h][0];		
$xPos = $data[$h][1];
$yPos = $data[$h][2];
$type= $data[$h][3];
$url= $data[$h][5];
$created=$data[$h][8];

$date1=JHTML::_('date',$created, JText::_('DATE_RP_FORMAT'));
$date0=JHTML::_('date',date("m.d.y"), JText::_('DATE_RP_FORMAT'));

$zindex=200-$h;

$array = array(); 

//auto
if(POSITIONING=="0")
{
 
    if($h % 2 == 0)
	{// Zahl ist gerade
		if($size_g_x<TOTAL_WIDTH-370)
		{
		$xPosition=$size_g_x+rand(-40,40);
		$yPosition=$size_g_y+rand(-40,40);
		$size_g_x=$size_g_x+200;
		}
		else
		{
		$xPosition=$size_g_x+rand(-40,40);
		$yPosition=$size_g_y+rand(-40,40);
		$size_g_x=$start_g_x;
		$size_g_y=$size_g_y+260;
		}

	}
	else
	{
	   	if($size_ug_x<TOTAL_WIDTH-370)
		{
		$xPosition=$size_ug_x+rand(-40,40);
		$yPosition=$size_ug_y+rand(-40,40);
		$size_ug_x=$size_ug_x+200;
		}
		else
		{
		$xPosition=$size_ug_x+rand(-40,40);
		$yPosition=$size_ug_y+rand(-40,40);
		$size_ug_x=$start_ug_x;
		$size_ug_y=$size_ug_y+260;
		}
	}
	
	if ($type=="pic")
	{
		$x=0;
		$y=0;
		if(file_exists(PIC_THUMBS_DIR.$url))
		{
		$pic=PIC_THUMBS_DIR.$url;
		$imagesize = getimagesize($pic);
		$x=round($imagesize[0]*PIC_SCALE,0);
		$y=round($imagesize[1]*PIC_SCALE,0);
		}
	if($x+$xPosition>TOTAL_WIDTH){$xPosition=$xPosition-50;}
	if($y+$yPosition>TOTAL_HEIGHT){$yPosition=$yPosition-50;}
	}
 
}

//manuell
if(POSITIONING=="1")
{
$yPosition=$yPos;
$xPosition=$xPos;	
}

//zufällig
if(POSITIONING=="2")
{
$yPosition=rand(50,TOTAL_HEIGHT-200);
$xPosition=rand(50,TOTAL_WIDTH-250);
}

if($date1==$date0){$zindex=500;}

$style.= '
#rp_ly'.$id.'
{
position:absolute; 
top: '.$yPosition.'px;
left: '.$xPosition.'px;
z-index:'.$zindex.';';

//--------------------------------------------->postit
if ($type=="postit")
{
	
$style.='
width: '.POSTIT_X.'px;
height: '.POSTIT_Y.'px;';

if($date1!=$date0)
{$style.='
background-image: url('.ROOT.'/css/postit_advanced.png);';}
else{$style.='
background-image: url('.ROOT.'/css/postit_advanced.png);';}
$style.='
background-repeat:no-repeat;';
}
//--------------------------------------------->ende postit

//--------------------------------------------->pic
if($type=="pic")
{

if(file_exists(PIC_THUMBS_DIR.$url))
{
$pic=PIC_THUMBS_DIR.$url;
$imgurl=PIC_THUMBS_URL.$url;
$imagesize = getimagesize($pic);
$x=round($imagesize[0]*PIC_SCALE,0);
$y=round($imagesize[1]*PIC_SCALE,0);
}
else
{
$imgurl=ROOT."/css/no_pic.jpg";
$x=200*PIC_SCALE;
$y=200*PIC_SCALE;
}

	
$style.='
width: '.($x).'px;
height: '.($y).'px;';
}
//--------------------------------------------->ende pic

//--------------------------------------------->youtube
if ($type=="youtube")
{
$style.='
width: '.(YOUTUBE_X).'px;
height: '.(YOUTUBE_Y).'px;';
}
//--------------------------------------------->ende youtube

$style.='
}
';

}//ende--------------layer


//start--------------pic

for($h = 0; $h < $layers; $h++)
{

$id = $data[$h][0];		
$xPos = $data[$h][1];
$yPos = $data[$h][2];
$type= $data[$h][3];
$url= $data[$h][5];
$created=$data[$h][8];



if($type=="pic")
{
$zufall=rand(-10,10);

	$style.= '
	#rp_pic'.$id.'
	{
	position:absolute;
	top:0px;
	margin: 0 auto;
	z-index:1;';

	if (PIC_ROTATE=="1")
    {
    $style.= '
	-moz-transform: rotate('.$zufall.'deg);
	-webkit-transform:rotate('.$zufall.'deg);
	-o-transform:rotate('.$zufall.'deg);';
	}
	
	$style.='
	}
	';

}//ende--------------pic
}


//-moz-transform: rotate('+rotation+'deg);-webkit-transform:rotate('+rotation+'deg);-o-transform:rotate('+rotation+'deg);
if(CSS_FILE!="0")
{
	$myFile = JPATH_COMPONENT.DS."includes".DS."css".DS."realpin".PINBOARD.".css";
	
	if(@file_put_contents($myFile, $style))
	{
		//$stylelink= '<link rel="stylesheet" class="cssSandpaper-Index" href="'.ROOT.'/css/realpin'.PINBOARD.'.css?'.time().'" />' ."\n";
		$stylelink= '<link rel="stylesheet" href="'.ROOT.'/css/realpin'.PINBOARD.'.css?'.time().'" />' ."\n";
		$doctype = $doc->getType();
		// Only render for HTML output
		if ($doctype == 'html'){$doc->addCustomTag($stylelink);}
	}
	else	//Write Failed
	{
		$doc->addStyleDeclaration( $style );
	}
}
else
{
$doc->addStyleDeclaration( $style );
}
?>
