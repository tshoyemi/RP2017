<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla
 * @subpackage		com_jux_real_estate
 * 
 * @copyright		Copyright (C) 2012 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, See LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class com_JUX_Real_EstateInstallerScript {
    /*
     * $parent is the class calling this method.
     * $type is the type of change (install, update or discover_install, not uninstall).
     * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
     * If preflight returns false, Joomla will abort the update and undo everything already done.
     */

    function preflight($type, $parent) {
	$jversion = new JVersion();

	// Installing component manifest file version
	$this->release = $parent->get("manifest")->version;

	// Manifest file minimum Joomla version
	$this->minimum_joomla_release = $parent->get("manifest")->attributes()->version;

	// Show the essential information at the install/update back-end
	echo '<p>Installing component manifest file version = ' . $this->release;
	echo '<br />Current manifest cache commponent version = ' . $this->getParam('version');
	echo '<br />Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release;
	echo '<br />Current Joomla version = ' . $jversion->getShortVersion();

	// abort if the current Joomla release is older
	if (version_compare($jversion->getShortVersion(), $this->minimum_joomla_release, 'lt')) {
	    Jerror::raiseWarning(null, 'Cannot install com_jux_real_estate in a Joomla release prior to ' . $this->minimum_joomla_release);
	    return false;
	}

	// abort if the component being installed is not newer than the currently installed version
	if ($type == 'update') {
	    $oldRelease = $this->getParam('version');
	    $rel = $oldRelease . ' to ' . $this->release;
	    if (version_compare($this->release, $oldRelease, 'le')) {
		Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
		return false;
	    }
	} else {
	    $rel = $this->release;
	}

	echo '<p>' . JText::_('JUX Real Estate ' . $type . ' ' . $rel) . '</p>';
    }

    /*
     * get a variable from the manifest file (actually, from the manifest cache).
     */
    function getParam($name) {
	$db = JFactory::getDbo();
	$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_jux_real_estate"');
	$manifest = json_decode($db->loadResult(), true);
	return $manifest[$name];
    }

    /*
     * sets parameter values in the component's row of the extension table
     */
    function setParams($param_array) {
	if (count($param_array) > 0) {
	    // read the existing component value(s)
	    $db = JFactory::getDbo();
	    $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_jux_real_estate"');
	    $params = json_decode($db->loadResult(), true);
	    // add the new variable(s) to the existing one(s)
	    foreach ($param_array as $name => $value) {
		$params[(string) $name] = (string) $value;
	    }
	    // store the combined new and existing values back as a JSON string
	    $paramsString = json_encode($params);
	    $db->setQuery('UPDATE #__extensions SET params = ' .
		    $db->quote($paramsString) .
		    ' WHERE name = "com_jux_real_estate"');
	    $db->query();
	}
    }

    function install($parent) {
	$db = JFactory::getDBO();

	$foldermedia = JPATH_SITE . '/' . 'images' . '/' . 'jux_real_estate';

	//copy folders
	$message = '';
	$src_img = JPATH_ADMINISTRATOR.'/components/com_jux_real_estate/sql/data_example/jux_real_estate';
	$dest_img = JPATH_SITE . '/images/jux_real_estate';	

	if (JFolder::exists($src_img)){
		if (JFolder::copy($src_img, $dest_img)) {
				JFolder::delete($src_img);
				$message .= '<p><b><span style="color:#009933">Folder</span> ' . $src_img
				. ' <span style="color:#009933">Copy successful!</span></b></p>';
		} else {
				$message .= '<p><b><span style="color:#009933">Folder</span> ' . $src_img
				. ' <span style="color:#009933">Can not copy!</span></b></p>';
		}
	} 
	
	
	$sql = 'SELECT count(*) FROM #__re_amenities';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' .'/'. 're_amenities.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}
	
	$sql = 'SELECT count(*) FROM #__re_configs';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_configs.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}
	
	$sql = 'SELECT count(*) FROM #__re_countries';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_countries.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}
	
	$sql = 'SELECT count(*) FROM #__re_currencies';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_currencies.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}
	
	$sql = 'SELECT count(*) FROM #__re_fields';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_fields.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}
	
	$sql = 'SELECT count(*) FROM #__re_states';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_states.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}
	
	$sql = 'SELECT count(*) FROM #__re_types';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_types.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}

	$sql = 'SELECT count(*) FROM #__re_agents';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_agents.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}	

	$sql = 'SELECT count(*) FROM #__re_plans';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total) {
	    $configSql = JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'sql' . '/' . 'data_example' . '/' . 're_plans.utf8.sql';
	    $sql = JFile::read($configSql);
	    $queries = $db->splitSql($sql);
	    if (count($queries)) {
		foreach ($queries as $query) {
		    if ($query != '' && $query{0} != '#') {
			$db->setQuery($query);
			$db->query();
		    }
		}
	    }
	}
		

	echo '<p>' . $message . '</p>';
    }

    function uninstall($parent) {
	echo '<p>' . JText::_('COM_JUX_REAL_ESTATE_UNINSTALL') . '</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
	
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
	// $parent is the class calling this method
	// $type is the type of change (install, update or discover_install)
    }

}

?>