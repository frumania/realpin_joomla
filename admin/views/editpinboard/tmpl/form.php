<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

defined('_JEXEC') or die('Restricted access');

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

$message1=JURI::root();
$message2=JPATH_ROOT;
$message2=str_replace("\\","",$message2);
$message1=str_replace(array("http://www.","http://","www.","https://","https://www."),"",$message1);
$encoded1 = str_replace(' ', "",rsa_encrypt ($message1,  7681,  60716087));
$encoded2 = str_replace(' ', "",rsa_encrypt ($message2,  7681,  60716087));

$db	= JFactory::getDBO();
$table="#__realpin_settings";
$query = "SELECT license FROM ".$table." WHERE config_id = '1'";
$db->setQuery($query);
//$db->query();
$license = $db->loadResult();
if($license==""){$license=="0";}

if($encoded1 == str_replace(' ', "",$license) or $encoded2 == str_replace(' ', "",$license)){$licensed=true;}else{$licensed=false;}

define('LICENSED', $licensed);

$query = "SELECT * FROM ".$table." WHERE config_community = '0'";
$db->setQuery($query);
$db->query();
$num = $db->getNumRows();


?>

<?php
$doc = JFactory::getDocument();

$style='
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
positon:absolute;
float:right;
margin-top:10px;
left:350px;	
}

#rpedittotal
{
width:700px;
}

fieldset label, fieldset span.faux-label
{
    clear: none!important;
    display: block;
    float: none!important;
}
';

$doc->addStyleDeclaration( $style );
?>


<script language="JavaScript" type="text/javascript">
<!--
function submitbutton(pressbutton)
{
  document.adminForm.task.value=pressbutton;

  if (document.adminForm.name.value == "" && pressbutton!="cancel") 
  {
    alert( "Please enter a name!" );
    document.adminForm.name.focus();
  }
  else
  {
    submitform(pressbutton);
  }

}
//-->
</script>
<?php 
$go=false;

if(LICENSED==true)
{
if($num<22){$go=true;}else{$go=false;$msg=JText::_( 'LANG_CON11' );$app =& JFactory::getApplication();$app->redirect( 'index.php?option=com_realpin&controller=pinboards&task=display',$msg);}
}
else
{
if($num<5){$go=true;}else{$go=false;$msg=JText::_( 'LANG_CON10' );$app =& JFactory::getApplication();$app->redirect( 'index.php?option=com_realpin&controller=pinboards&task=display',$msg);}
}

if($go){
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="rpedittotal">
<div id="rpeditform">
    <fieldset class="adminform">
    <legend><?php echo JText::_( 'LANG_EDIT4'); ?></legend>
    
    <table class="admintable">
    
         <tr>
            <td width="110" class="key">
            <label for="title">
            <?php echo JText::_( 'LANG_NAME' ); ?>:
            </label>
            </td>
            <td>
            <input class="inputbox" type="text" name="config_name" id="config_name" size="32" value="<?php echo $this->edit->config_name; ?>" />
            </td>
        </tr> 


        <tr>
            <td width="110" class="key">
            <label for="title">
            <?php echo JText::_( 'LANG_DESCRIPTION' ); ?>:
            </label>
            </td>
            <td>
            <input class="inputbox" type="text" name="config_desc" id="config_desc" size="32" value="<?php echo $this->edit->config_desc; ?>" />
            </td>
        </tr> 
          
         <tr style="display:none">
            <td width="120" class="key">
            <?php echo JText::_( 'Community' ); ?>:
            </td>
            <td>
            <?php echo JHTML::_( 'select.booleanlist',  'config_community', 'class="inputbox"', $this->edit->config_community ); ?>
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
        


</div>
</div>

<div class="clr"></div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_realpin" />
<input type="hidden" name="config_id" value="<?php echo $this->edit->config_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="editpinboard" />
</form>

<?php } ?>


