<?php
/**
* @copyright 2021 - Marcel Törpe - All rights reserved
* @license http://www.gnu.org GNU/GPL
* @link https://frumania.com
* @author Marcel Toerpe
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

define('RP_VERSION', '1.6.0');

//Install 1.5
function com_install()
{

	return com_realpinInstallerScript::installme();

}

class com_realpinInstallerScript
{

	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
	    com_realpinInstallerScript::installme();
		// $parent is the class calling this method
		//$parent->getParent()->setRedirectURL('index.php?option=com_realpin');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// $parent is the class calling this method
		//echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		$db = JFactory::getDBO();
		// Obviously you may have to change the path and name if your installation SQL file ;)
		if(method_exists($parent, 'extension_root')) {
			$sqlfile = $parent->getPath('extension_root').DS.'install.sql';
		} else {
			$sqlfile = $parent->getParent()->getPath('extension_root').DS.'install.sql';
		}
		// Don't modify below this line
		$buffer = file_get_contents($sqlfile);
		if ($buffer !== false) {
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);
			if (count($queries) != 0) {
				foreach ($queries as $query)
				{
					$query = trim($query);
					if ($query != '' && $query{0} != '#') {
						$db->setQuery($query);
						if (!$db->query()) {
							JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
							return false;
						}
					}
				}
			}
		}

		com_realpinInstallerScript::installme();
		// $parent is the class calling this method
		//echo '<p>' . JText::_('INSTALL_UPGRADE_SUCCESS') . '</p>';
		//$parent->getParent()->setRedirectURL('index.php?option=com_realpin');
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	function installme()
	{

		$lang = JFactory::getLanguage();
		$lang->load('com_realpin');

		echo "<br/>";

		$db = JFactory::getDBO();

		//new columns for items/settings table
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_items", "author_link");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_items", "author_email");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_items", "author_id");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_items", "rating");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "useredit");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "postit_1_src");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "postit_2_src");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "postit_3_src");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "postit_1_active");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "postit_2_active");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "postit_3_active");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "postit_pinstyles");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "yt_pinstyles");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "pic_pinstyles");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "fullscreen");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "userauth");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "useraccesswrite");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "useraccessread");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "useraccessdelete");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "useraccesswritepw");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "useraccessreadpw");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "useraccessdeletepw");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "usermail");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "logo_new_enable");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "pic_check_server_memory","text NOT NULL","1");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "pic_no_restrict_size","text NOT NULL","1024000");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "css_file","text NOT NULL","1");
		com_realpinInstallerScript::add_column_if_not_exist("#__realpin_settings", "custom_css","text NOT NULL","");

		com_realpinInstallerScript::updateVersion();

			echo JHTML::_('image', 'components/com_realpin/includes/css/admin/logo_version.png', 'logo_version.png', array('width' => '392', 'height' => '109'));

			echo "<br/><br/><div style=\"margin-left:40px\">";

			if(!JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'))
			{
			$msg=str_replace("RP_VERSION", RP_VERSION, JText::_('INSTALL_SUCCESS'));
			}
			else
			{
			$msg=str_replace("RP_VERSION", RP_VERSION, JText::_('INSTALL_UPGRADE_SUCCESS'));
			echo "<br/><span style=\"color:#31a11b\"><b>".JText::_('INSTALL_UPGRADE')."</b></span><br/><br/>";
			}


			//create folder images/realpin
			if(!JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'))
			{
				if(JFolder::create(JPATH_SITE.DS.'images'.DS.'realpin', 0777))
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS1')."<br />";
				}
				else
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#db3f2f\">/images/realpin/ </span>".JText::_( 'INSTALL_FOLDER_ERROR')."<br />";
				}
			}
			else
			{
			echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS2')."<br />";
			}

			if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin')), 1)=="0777")
			{
			//    echo 'Ordnerrechte im Verzeichnis \'images/realpin\' erfolgreich gesetzt (777)!<br />';
			}
			else
			{
				echo JText::_( 'INSTALL_FOLDER_PERMISSIONS1').' \'images/realpin/\' '.JText::_( 'INSTALL_FOLDER_PERMISSIONS2')."<br />";
			}

			if(!JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs'))
			{
				if(JFolder::create(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs', 0777))
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/thumbs/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS1')."<br />";
				}
				else
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#db3f2f\">/images/realpin/thumbs/ </span>".JText::_( 'INSTALL_FOLDER_ERROR')."<br />";
				}
			}
			else
			{
			echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/thumbs/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS2')."<br />";
			}

			if(substr(decoct( fileperms(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs')), 1)=="0777")
			{
				//echo 'Ordnerrechte im Verzeichnis \'images/realpin/thumbs\' erfolgreich gesetzt (777)!<br />';
			}
			else
			{
				echo JText::_( 'INSTALL_FOLDER_PERMISSIONS1').' \'images/realpin/thumbs\' '.JText::_( 'INSTALL_FOLDER_PERMISSIONS2')."<br />";
			}

			com_realpinInstallerScript::unlinkRecursive(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'thumbs',false);

			if(!JFile::copy(JPATH_SITE.DS.'components'.DS.'com_realpin'.DS.'includes'.DS.'realpin.php', JPATH_SITE.DS.'templates'.DS.'system'.DS.'realpin.php'))
			{
				//echo JText::_('INSTALL_COPY_ERROR');
			}

			if(!JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'tmp'))
			{
				if(JFolder::create(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'tmp', 0777))
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/tmp/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS1')."<br />";
				}
				else
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#db3f2f\">/images/realpin/tmp/ </span>".JText::_( 'INSTALL_FOLDER_ERROR')."<br />";
				}
			}
			else
			{
			echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/tmp/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS2')."<br />";
			}

			if(!JFolder::exists(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'misc'))
			{
				if(JFolder::create(JPATH_SITE.DS.'images'.DS.'realpin'.DS.'misc', 0777))
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/misc/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS1')."<br />";
				}
				else
				{
					echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#db3f2f\">/images/realpin/misc/ </span>".JText::_( 'INSTALL_FOLDER_ERROR')."<br />";
				}
			}
			else
			{
			echo JText::_( 'INSTALL_FOLDER')." <span style=\"color:#31a11b\">/images/realpin/misc/ </span>".JText::_( 'INSTALL_FOLDER_SUCCESS2')."<br />";
			}


			echo $msg;
			echo "<br/><br/></div>";

	}

	function unlinkRecursive($dir, $deleteRootToo)
	{
		if(!$dh = @opendir($dir))
		{
			return;
		}
		while (false !== ($obj = readdir($dh)))
		{
			if($obj == '.' || $obj == '..')
			{
				continue;
			}

			if (!@unlink($dir . '/' . $obj))
			{
				unlinkRecursive($dir.'/'.$obj, true);
			}
		}

		closedir($dh);

		if ($deleteRootToo)
		{
			@rmdir($dir);
		}

		return;
	}

	function copyItems()
	{
	//#########copy items#############
		$db		=& JFactory::getDBO();
		$itemssourcetable = '#__realpin';
		$itemstargettable = '#__realpin_items';
		$sql="SELECT * from ".$itemssourcetable." ORDER BY created ASC";
		$db->setQuery($sql);
		$db->query();
		$result= $db->loadAssocList();

		for ($p=0, $c=count( $result ); $p < $c; $p++)
		{
		if($result[$p]['text']=="-"){$result[$p]['text']="";}
		if($result[$p]['url']=="-"){$result[$p]['url']="";}
		if($result[$p]['title']=="-"){$result[$p]['title']="";}
		if($result[$p]['pw']=="-"){$result[$p]['pw']="";}
		if($result[$p]['author']=="-"){$result[$p]['author']="";}
		$sql2="INSERT INTO ".$itemstargettable." (`id`, `xPos`, `yPos`, `type`, `text`, `url`, `pw`, `title`, `author`, `created`, `expires`, `published`, `sticky`, `pinboard`, `params`) VALUES
	('', '".$result[$p]['xPos']."', '".$result[$p]['yPos']."', '".$result[$p]['type']."', '".$result[$p]['text']."', '".$result[$p]['url']."', '".$result[$p]['pw']."', '".$result[$p]['title']."', '".$result[$p]['author']."', '".$result[$p]['created']."', '0000-00-00 00:00:00', '".$result[$p]['published']."', 0, '', '')";
		$db->setQuery($sql2);
		$db->query();
		}

		$sql="RENAME TABLE #__realpin TO #__realpin_old;";
		$db->setQuery($sql);
		$db->query();

	//#########end copy items#########
	}

	function copySettings()
	{
	//########copy settings###########
		$db		=& JFactory::getDBO();
		$sourcetable = '#__realpin_config';
		$sql="SELECT param, value from ".$sourcetable;
		$db->setQuery($sql);
		$db->query();
		$copyresult= $db->loadAssocList();

		$targettable = '#__realpin_settings';

		for ($z=0, $q=count( $copyresult ); $z < $q; $z++)
		{
		$val=$copyresult[$z]['value'];
		$key=strtolower($copyresult[$z]['param']);
		if($key=='interval'){$key='lifetime';}
		if($key=='delete'){$key='removal';}
		if($key=='limit'){$key='maxitems';}
		$sql="UPDATE ".$targettable." Set $key='$val' WHERE config_name='realpin_default'";
		$db->setQuery($sql);
		$db->query();
		$sql="UPDATE ".$targettable." Set $key='$val' WHERE config_name='default_private'";
		$db->setQuery($sql);
		$db->query();
		}

		$sql="RENAME TABLE #__realpin_config TO #__realpin_config_old;";
		$db->setQuery($sql);
		$db->query();

	//##########end copy settings#############
	}

	function updatePinboard()
	{
		$db		=& JFactory::getDBO();
		$sql="UPDATE #__realpin_items as m set pinboard = (SELECT config_id FROM #__realpin_settings WHERE config_name = 'realpin_default')";
		$db->setQuery($sql);
		$db->query();
	}

	function updateVersion()
	{
		$db = JFactory::getDBO();
		$sql="UPDATE `#__realpin_settings` SET `version` = '".RP_VERSION."' WHERE config_name!='default_private_global';";
		$db->setQuery($sql);
		$db->query();
	}

	function add_column_if_not_exist($table, $column, $column_attr = "text NOT NULL", $default = "" )
	{
		$db = JFactory::getDBO();

		$exists = false;

		$db->setQuery("show columns from $table");

				  $rows = $db->loadAssocList();
				  foreach( $rows as $row )
				  {

					  if($row['Field'] == $column)
					  {
						  $exists = true;
						  break;
					  }
				  }
				  if(!$exists)
				  {
					  $db->setQuery("ALTER TABLE `$table` ADD `$column` $column_attr");
					  $db->query();

					  $db->setQuery("UPDATE #__realpin_settings set `$column` = 'true' WHERE config_name = 'default_private_global'");
					  $db->query();

					  $db->setQuery("UPDATE #__realpin_settings set `$column` = '".$default."' WHERE config_name != 'default_private_global'");
					  $db->query();

				  }
	}

}
