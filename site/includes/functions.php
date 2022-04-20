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

function sanitize($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "—", "–", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}

function json_new_encode( $data ) {           
    if( is_array($data) || is_object($data) ) {
        $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );
       
        if( $islist ) {
            $json = '[' . implode(',', array_map('json_new_encode', $data) ) . ']';
        } else {
            $items = Array();
            foreach( $data as $key => $value ) {
                $items[] = json_new_encode("$key") . ':' . json_new_encode($value);
            }
            $json = '{' . implode(',', $items) . '}';
        }
    } elseif( is_string($data) ) {
        # Escape non-printable or Non-ASCII characters.
        $string = '"' . addcslashes($data, "\"\\\n\r\t\f/" . chr(8)) . '"';
        $json    = '';
        $len    = strlen($string);
        # Convert UTF-8 to Hexadecimal Codepoints.
        for( $i = 0; $i < $len; $i++ ) {
           
            $char = $string[$i];
            $c1 = ord($char);
           
            # Single byte;
            if( $c1 <128 ) {
                $json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
                continue;
            }
           
            # Double byte
            $c2 = ord($string[++$i]);
            if ( ($c1 & 32) === 0 ) {
                $json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
                continue;
            }
           
            # Triple
            $c3 = ord($string[++$i]);
            if( ($c1 & 16) === 0 ) {
                $json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
                continue;
            }
               
            # Quadruple
            $c4 = ord($string[++$i]);
            if( ($c1 & 8 ) === 0 ) {
                $u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;
           
                $w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
                $w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
                $json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
            }
        }
    } else {
        # int, floats, bools, null
        $json = strtolower(var_export( $data, true ));
    }
    return $json;
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


function stripper($stringvar){
    $stringvar = stripslashes($stringvar);
    return $stringvar;
}

function remove_all_special($string)
{
$string=str_replace("'", "", $string);
$string=str_replace('"', '', $string);
return $string;
}

function foxy_utf8_to_nce($utf = EMPTY_STRING) 
{
	
  if($utf == EMPTY_STRING) return($utf);

  $max_count = 5; // flag-bits in $max_mark ( 1111 1000 == 5 times 1)
  $max_mark = 248; // marker for a (theoretical ;-)) 5-byte-char and mask for a 4-byte-char;

  $html = EMPTY_STRING;
  for($str_pos = 0; $str_pos < strlen($utf); $str_pos++) {
    $old_chr = $utf[$str_pos];
    $old_val = ord( $utf[$str_pos] );
    $new_val = 0;

    $utf8_marker = 0;

    // skip non-utf-8-chars
    if( $old_val > 127 ) {
      $mark = $max_mark;
      for($byte_ctr = $max_count; $byte_ctr > 2; $byte_ctr--) {
        // actual byte is utf-8-marker?
        if( ( $old_val & $mark  ) == ( ($mark << 1) & 255 ) ) {
          $utf8_marker = $byte_ctr - 1;
          break;
        }
        $mark = ($mark << 1) & 255;
      }
    }

    // marker found: collect following bytes
    if($utf8_marker > 1 and isset( $utf[$str_pos + 1] ) ) {
      $str_off = 0;
      $new_val = $old_val & (127 >> $utf8_marker);
      for($byte_ctr = $utf8_marker; $byte_ctr > 1; $byte_ctr--) {

        // check if following chars are UTF8 additional data blocks
        // UTF8 and ord() > 127
        if( (ord($utf[$str_pos + 1]) & 192) == 128 ) {
          $new_val = $new_val << 6;
          $str_off++;
          // no need for Addition, bitwise OR is sufficient
          // 63: more UTF8-bytes; 0011 1111
          $new_val = $new_val | ( ord( $utf[$str_pos + $str_off] ) & 63 );
        }
        // no UTF8, but ord() > 127
        // nevertheless convert first char to NCE
        else {
          $new_val = $old_val;
        }
      }
      // build NCE-Code
      $html .= '&#'.$new_val.';';
      // Skip additional UTF-8-Bytes
      $str_pos = $str_pos + $str_off;
    }
    else {
      $html .= chr($old_val);
      $new_val = $old_val;
    }
  }
  return($html);
  
}

//Function will return TRUE if email address is valid and FALSE if not.
function isEmail($email)
{
	if(function_exists('filter_var'))
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
		{
		return true;
		}
		else
		{
		return false;
		}
	}
	else
	{
	return false;
	}
}

//Function will return TRUE if url address is valid and FALSE if not.
function isURL($url)
{
	$url= str_replace("www.", "http://www.", $url);
	if(function_exists('filter_var'))
	{
		if(filter_var($url, FILTER_VALIDATE_URL) !== false)
		{
		return true;
		}
		else
		{
		return false;
		}
	
	}
	else
	{
	return false;
	}

}



// Retrieve the video ID from a YouTube video URL
function getYTid($ytURL)
{
  $ytURL = str_replace("v/", "watch?v=", $ytURL);
  $ytURL = str_replace("de.", "www.", $ytURL);
  
  $ytvIDlen = 11;	// This is the length of YouTube's video IDs
  
  // The ID string starts after "v=", which is usually right after 
  // "youtube.com/watch?" in the URL
  $idStarts = strpos($ytURL, "?v=");
  
  // In case the "v=" is NOT right after the "?" (not likely, but I like to keep my 
  // bases covered), it will be after an "&":
  if($idStarts === FALSE)
	  $idStarts = strpos($ytURL, "&v=");
  // If still FALSE, URL doesn't have a vid ID
  if($idStarts === FALSE)
	  return("0");
  
  // Offset the start location to match the beginning of the ID string
  $idStarts +=3;
  
  // Get the ID string and return it
  $ytvID = substr($ytURL, $idStarts, $ytvIDlen);
  
  return $ytvID;
  
}

function format_tooltip($string,$wrap,$sep)
{
$string=strip_tags($string);
$string=str_replace(array("\r\n", "\r", "\n"), $sep, $string);
$string=wordwrap($string, $wrap, $sep);	
if(strlen($string)>300){$string=substr($string, 0, 300 )."...";}
return $string;
}

function create_tooltip($title,$text,$default,$wrap,$sep)
{
	$result="";
	if($title=="" and $text=="")
	{
	$result=$default;
	}
	if($title=="" and $text!="")
	{
	$result=format_tooltip($text,$wrap,$sep);
	}
	if($title!="" and $text=="")
	{
	$result=format_tooltip($title,$wrap,$sep);
	}
	if($title!="" and $text!="")
	{
	$result=format_tooltip($title,$wrap,$sep).$sep."<i>".format_tooltip($text,$wrap,$sep)."</i>";
	}

	return $result;
}

function wordwrapURI($str, $width = 25, $break = "\n", $cut = false)
{
    $newText = array();
    $words = explode(' ', str_replace("\n", "\n ", $str));
    foreach($words as $word) {
        if(strpos($word, 'http://') === false && strpos($word, 'www.') === false) {
            $word = wordwrap($word, $width, $break, $cut);
        }
        $newText[] = $word;
    }
    return implode(' ', $newText);
}

function unicode_wordwrap($str, $len=50, $break=" ", $cut=false){
    if(empty($str)) return "";
   
    $pattern="";
    if(!$cut)
        $pattern="/(\S{".$len."})/u";
    else
        $pattern="/(.{".$len."})/u";
   
    return preg_replace($pattern, "\${1}".$break, $str);
} 
	
function postit_text($text)
{
$text=strip_tags($text);
$text=str_replace(array("\r\n", "\r", "\n"), " <br /> ", $text);
$words= explode(' ', $text); // string to array
$result="";

foreach ($words as $word)
{
if(isURL($word)){if (strlen(strstr($word,"http://"))==0){$word=str_replace("www.", "http://www.", $word);} 
$tmptext="<a href=\"".$word."\" target=\"_blank\">Link</a>";}
else
{
	
	if(!function_exists('mb_strlen'))
	{ 
		if(strlen($word)>20){$tmptext=unicode_wordwrap($word,20,"<br />",true);}else{$tmptext=$word;};
	}
	else
	{
		if(mb_strlen($word, 'utf-8')>20){$tmptext=unicode_wordwrap($word,20,"<br />",true);}else{$tmptext=$word;};
	}
	
}
$result.=' '.$tmptext;
}		
return $result;	
}


function create_youtube($id,$title,$url,$text)
{
	//FIX HTTPS
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') 
	{
    // SSL connection
    if(strpos($url, "https") === false)
    $url = str_replace("http", "https", $url);
	}
	
	//begin yt url
	$urlid=getYTid($url);
	//end yt url

	$tooltip=create_tooltip($title,$text,"",30,"&lt;br /&gt;");
	
	$AE = new AutoEmbed();
	
	if (!$AE->parseUrl($url)) {
    // No embeddable video found (or supported)
	}

	$imageURL = $AE->getImageURL();
	
	?>
	<div class="rp_youtube">
    
<div class="rp_pin_video_l"></div>
<div class="rp_pin_video_r"></div>

<div style="position:relative;height:25px;"></div>
        
        	<?php if($imageURL=="")
			{
			$AE->setObjectAttrib('width',YOUTUBE_X);
			$AE->setObjectAttrib('height',YOUTUBE_Y);
			echo $AE->getEmbedCode();
			}
			else
			{
			?>

        
        <div class="rp_youtube_preview rp_tips" title="<?php echo $tooltip; ?>">
        <img id="<?php echo $urlid; ?>" src="<?php echo $imageURL; ?>" width="<?php echo YOUTUBE_X; ?>" height="<?php echo YOUTUBE_Y; ?>" />
        </div>
        
        <div class="rp_youtube_play rp_tips" title="<?php echo JText::_('LANG_ICON_PLAY'); ?>">
        <img id="<?php echo $urlid; ?>" rpvideourl="<?php echo $url;?>" src="<?php echo ROOT;?>/css/playbutton.png" width="99" height="59" />
        </div>
        	
             <?php } ?>
        
        <div class="rp_video_search" style="display:none"><?php echo $title; ?> - <?php echo $text; ?></div>

	</div>
	<?php
}   

function create_postit($id,$text,$title,$author)
{				
      $text=postit_text($text);

	  $isurl=false; $info=""; $url="javascript:void(0)";
	  if(isURL($author)){if (strlen(strstr($author,"http://"))==0){$author=str_replace("www.", "http://www.", $author);}
	  $url=$author; $info=JText::_('LANG_POSTIT_INFO_URL');  $isurl=true;}
	  if(isEMAIL($author)){$url="mailto:".$author; $info=JText::_('LANG_POSTIT_INFO_EMAIL'); $isurl=true;}
?>
 
       <?php $avar = explode("<br />", $text); $len = count($avar); if($len>7 or strlen(strip_tags($text))>100){ ?>
       <div class="rpscrolldown rp_tips" title="<?php echo JText::_('LANG_SCROLL_DOWN'); ?>"><a href="javascript:void(0)"></a></div> 
       <div class="rpscrollup rp_tips" title="<?php echo JText::_('LANG_SCROLL_UP'); ?>"><a href="javascript:void(0)"></a></div> 
       <?php }; ?>
    
	   <div class="rp_postit_layer rp_tips" title="<?php echo $info; ?>">
       
      
       <div class="rp_postit_content"> 
       <?php if($isurl==true){?><a href="<?php echo $url; ?>" target="_blank"><?php }; ?>
       <?php echo $text;?>
       <?php if($isurl==true){?></a><?php }; ?> 
       </div>

        
       </div>   
<?php

}

function create_pic($id,$text,$title,$url)
{

$tooltip=create_tooltip($title,$text,JText::_('LANG_OPEN_GALLERY'),30,"&lt;br /&gt;");

//start pic	
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
$x=round(200*PIC_SCALE,0);
$y=round(200*PIC_SCALE,0);
$tooltip="Bild nicht mehr verfügbar!";
}
//end pic
?>	
	
<div class="rp_pin"></div>

<div id="rp_pic<?php echo $id;?>" class="rp_tips" title="<?php echo $tooltip; ?>">

<img src="<?php echo $imgurl;?>" class="rp_rotate" alt="<?php echo $url;?>" id="image<?php echo $id;?>" width="<?php echo $x;?>" height="<?php echo $y;?>" />	

<div class="rp_img_search" style="display:none"><?php echo $title; ?> - <?php echo $text; ?></div>													
</div>

<?php
}

function format_size($size) 
{
  if ($size < 1024) {
    return $size . ' bytes';
  }
  else {
    $size = round($size / 1024, 2);
    $suffix = 'KB';
    if ($size >= 1024) {
      $size = round($size / 1024, 2);
      $suffix = 'MB';
    }
    return $size . ' ' . $suffix;
  }
}

function create_thumb_jpg_kl($url,$title)
{

  $title=str_replace('\\', '', $title);
  
  if(!file_exists(PIC_THUMBS_DIR.$url) && file_exists(PIC_DIR.$url))       //thumbs erstellen
  {
	    ini_set('memory_limit', "256M");

		try 
		{
    		//echo 'Usage vorher: ' . format_size(memory_get_usage()) . "\n";
			$pic=PIC_DIR.$url;
			if(!file_exists(JPATH_COMPONENT.DS."includes".DS."fonts".DS.PIC_FONT)){$font="cartohothic-webfont.ttf";}else{$font=PIC_FONT;}
			$imagesize=getimagesize($pic);
			$breite=$imagesize[0]; 
			$hoehe=$imagesize[1]; 
			//ini_set( 'memory_limit', '75M' );
			//echo "limit: ". ((int) @ini_get('memory_limit')-round(memory_get_usage()/(1024*1024),0));
			//echo "imgsize: ".round(($breite*$hoehe*5)/(1024*1024),0);
			$title=foxy_utf8_to_nce($title);	 
			
			  if ($breite<$hoehe) //Hochformat
			  { 
			  $neueBreite=PIC_SIZE*$breite/$hoehe*PIC_SCALE*1.4; 
			  $neueHoehe=PIC_SIZE*PIC_SCALE*1.4;
			  } 
			  else  //Querformat
			  {
			  $neueHoehe=PIC_SIZE*PIC_SCALE; 
			  $neueBreite=PIC_SIZE*$breite/$hoehe*PIC_SCALE;
			  }
		
			$border=PIC_BORDER;
		
			$img_adj_width=$neueBreite+(2*$border);
			$img_adj_height=$neueHoehe+(2*$border);
			$neuesBild=imagecreatetruecolor($img_adj_width,$img_adj_height);
		
			$altesBild=imagecreatefromjpeg($pic);
			$border_color = PIC_BORDER_COLOR;
			$bordercolor = imagecolorallocate($neuesBild, hexdec('0x' . $border_color[0] . $border_color[1]), hexdec('0x' . $border_color[2] . $border_color[3]), hexdec('0x' . $border_color[4] . $border_color[5]));
			imagefilledrectangle($neuesBild,0,0,$img_adj_width,$img_adj_height,$bordercolor);
			imagecopyresampled($neuesBild,$altesBild,$border,$border,0,0,$neueBreite,$neueHoehe,$breite,$hoehe);
			$title=stripper($title);
			$color = PIC_FONT_COLOR;
			$textcolor = imagecolorallocate($neuesBild, hexdec('0x' . $color[0] . $color[1]), hexdec('0x' . $color[2] . $color[3]), hexdec('0x' . $color[4] . $color[5]));
			
			if($title!="" and $title!="-")
			{
				imagettftext($neuesBild, PIC_FONT_SIZE, 0, 30, $neueHoehe, $textcolor, JPATH_COMPONENT.DS.'includes'.DS.'fonts'.DS.$font, $title);
			}
				  
			imagejpeg($neuesBild, PIC_THUMBS_DIR.$url, 90); 
			//echo 'Usage nachher: ' . format_size(memory_get_usage()) . "\n";
			imagedestroy($neuesBild);
			imagedestroy($altesBild);
		}
		catch (Exception $e)
		{
			echo "Error: Unable to generate small thumbnail! Image: ". $url." Error: ".$e->getMessage();
		}
		
		ini_restore('memory_limit');

		  
  }
		
}


function create_thumb_jpg_gr($url)
{
  if (file_exists(PIC_DIR.$url))    
  {	
  $pic=PIC_DIR.$url;
  $imagesize=getimagesize($pic);
  $breite=$imagesize[0]; 
  $hoehe=$imagesize[1];

  if($hoehe>=500)
  {
  $neueBreite=PIC_THUMBS_SIZE*$breite/$hoehe; 
  $neueHoehe=PIC_THUMBS_SIZE;
  }
  else
  {
  $neueBreite=$breite; 
  $neueHoehe=$hoehe;  
  }
  
  if (!file_exists(PIC_THUMBS_DIR."th_".$url))      
  {
	if($imagesize[2]==2)
	{ 
	
	    ini_set('memory_limit', "256M");

		try 
		{
		$altesBild=imagecreatefromjpeg($pic); 
		$neuesBild=imagecreatetruecolor($neueBreite,$neueHoehe); 
		imagecopyresampled($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe);
		imagejpeg($neuesBild, PIC_THUMBS_DIR."th_".$url, 90);
		imagedestroy($neuesBild);
		imagedestroy($altesBild);
		}
		catch (Exception $e)
		{
			echo "Error: Unable to generate large thumbnail! Image: ". $url." Error: ".$e->getMessage();
		}
		
		ini_restore('memory_limit');
	}
  }
  
}
  
  
}  

function create_icons($image_no,$id,$type,$title,$text,$url,$created,$author)
{
?>

<?php if (SAVE == "2" or (SAVE=="1" and USER>=18) or (SAVE=="0" and USER>=24)) { ?>
<div class="rp_save rp_save_<?php echo $type;?>">
<a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_SAVE'); ?>"></a>
</div>
<?php } ?>

<?php

if ($type=="pic")
{

//$fancytitle=create_tooltip($title,$text,JText::_('LANG_NO_TEXT'),100,"&lt;br /&gt;");
//echo "images/realpin/thumbs/th_".$url; alt=echo $fancytitle; 
?>
<div class="rp_zoom_<?php echo $type; ?>">
<a href="<?php echo PIC_THUMBS_URL."th_".$url; ?>" class="rp_zoomimage rp_tips" title="<?php echo JText::_('LANG_ICON_ZOOM'); ?>"></a>
</div>

<?php 
}
?>		

<?php
$isurl=false; $info=$author; $url="javascript:void(0)";
if(isURL($author)){if (strlen(strstr($author,"http://"))==0){$author=str_replace("www.", "http://www.", $author);} $url=$author; $info=JText::_('LANG_ICON_INFO_URL'); $isurl=true;}
if(isEMAIL($author)){$url="mailto:".$author; $info=JText::_('LANG_ICON_INFO_EMAIL'); $isurl=true;}
?>

<div class="rp_info_<?php echo $type; ?>">
<a href="<?php echo $url ?>" <?php if($isurl==true){ echo 'target="_blank"';} ?> class="rp_tips" title="<?php echo JText::_('LANG_ICON_INFO1')." ".$info."&lt;br /&gt;".JText::_('LANG_ICON_INFO2')." ".$created; ?>"></a>
</div>

<div class="rp_blend_<?php echo $type; ?> rp_icon_blend">
<a href="javascript:void(0)" class="rp_tips" title="<?php echo JText::_('LANG_ICON_BLEND'); ?>"></a>
</div>	
 		
<?php
}
//end function create icons	
?>