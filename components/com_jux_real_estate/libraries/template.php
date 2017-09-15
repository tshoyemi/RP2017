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

/**
 * JUX_Real_Estate Component - Templating System Library.
 * @package		JUX_Real_Estate
 * @subpackage	Library
 */
class JUX_Real_EstateTemplate extends JObject {

    /** @var array Variables of template */
    var $_vars = null;

    /** @var string Template file */
    var $_file = null;

    /** @var string Template layout */
    var $_layout = null;

    /**
     * Constructor
     */
    function __construct($file = null, $layout = null) {
	$this->_file = $file;
	$this->_layout = $layout;
    }

    /**
     * Method to get template full path name.
     */
    function _getTemplateFullPath($file, $layout = null) {
	$mainframe = JFactory::getApplication();
	$config = JUX_Real_EstateFactory::getConfiguration();

	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.folder');

	$file_name = $file;

	if (isset($layout) && $layout != 'default') {
	    $arr = explode('.', $file_name);
	    $arr[0] .= '_' . $layout;
	    $file_name = implode('.', $arr);
	}
	// test if template override exists in joomla's template folder
	$override_path = JPATH_ROOT . '/' . 'templates' . '/' . $mainframe->getTemplate() . '/' . 'html';
	$override_exists = JFolder::exists($override_path . '/' . 'com_jux_real_estate');
	$template = JPATH_COMPONENT . '/' . 'templates' . '/' . $config->get('template') . '/' . $file_name . '.php';

	// test override path first
	if (JFile::exists($override_path . '/' . 'com_jux_real_estate' . '/' . $file_name . '.php')) {
	    // load the override template
	    $file = $override_path . '/' . 'com_jux_real_estate' . '/' . $file_name . '.php';
	} else if (JFile::exists($template) && !$override_exists) {
	    // if override fails, try the template set in config
	    $file = $template;
	} else {
	    // we assume to use the default template
	    $file = JPATH_COMPONENT . '/' . 'templates' . '/' . 'default' . '/' . $file_name . '.php';
	}
	return $file;
    }

    /**
     * Method to set template variable.
     */
    function set($name, $value = null) {
	$this->_vars[$name] = $value;
    }

    /**
     * Method to set template variable by reference.
     */
    function setRef($name, &$value) {
	$this->_vars[$name] = &$value;
    }

    /**
     * Method to add template style sheet.
     */
    public static function addStyleSheet($file) {
	$mainframe = JFactory::getApplication();
	$config = JUX_Real_EstateFactory::getConfiguration();

	if (!JString::strpos($file, '.css')) {
	    jimport('joomla.filesystem.file');
	    jimport('joomla.filesystem.folder');

	    $file_name = $file;
	    // test if template override exists in joomla's template folder
	    $override_path = JPATH_ROOT . '/' . 'assets' . '/' . $mainframe->getTemplate() . '/' . 'html';
	    $override_exists = JFolder::exists($override_path . '/' . 'com_jux_real_estate');
	    $template = JPATH_COMPONENT . '/' . 'assets' . '/' . $config->get('template') . '/' . 'css' . '/' . $file_name . '.css';

	    // test override path first
	    if (JFile::exists($override_path . '/' . 'com_jux_real_estate' . '/' . $file_name . '.css')) {
		// load the override template
		$file = '/assets/' . $mainframe->getTemplate() . '/html/com_jux_real_estate/css/' . $file_name . '.css';
	    } else if (JFile::exists($template) && !$override_exists) {
		// if override fails, try the template set in config
		$file = '/components/com_jux_real_estate/assets/' . $config->get('template') . '/css/' . $file_name . '.css';
	    } else {
		// we assume to use the default template
		$file = '/components/com_jux_real_estate/assets/css/' . $file_name . '.css';
	    }
	}

	JUX_Real_EstateTemplate::attachAssets($file, 'css', rtrim(JURI::root(), '/'));
    }

    /**
     * Method to add template style sheet.
     */
    public static function addScript($file) {
	$mainframe = JFactory::getApplication();
	$config = JUX_Real_EstateFactory::getConfiguration();

	if (!JString::strpos($file, '.js')) {
	    jimport('joomla.filesystem.file');
	    jimport('joomla.filesystem.folder');

	    $file_name = $file;
	    // test if template override exists in joomla's template folder
	    $override_path = JPATH_ROOT . '/' . 'assets' . '/' . $mainframe->getTemplate() . '/' . 'html';
	    $override_exists = JFolder::exists($override_path . '/' . 'com_jux_real_estate');
	    $template = JPATH_COMPONENT . '/' . 'assets' . '/' . $config->get('template') . '/' . 'js' . '/' . $file_name . '.js';

	    // test override path first
	    if (JFile::exists($override_path . '/' . 'com_jux_real_estate' . '/' . $file_name . '.js')) {
		// load the override template
		$file = '/assets/' . $mainframe->getTemplate() . '/html/com_jux_real_estate/js/' . $file_name . '.js';
	    } else if (JFile::exists($template) && !$override_exists) {
		// if override fails, try the template set in config
		$file = '/components/com_jux_real_estate/assets/' . $config->get('template') . '/js/' . $file_name . '.js';
	    } else {
		// we assume to use the default template
		$file = '/components/com_jux_real_estate/assets/js/' . $file_name . '.js';
	    }
	}

	JUX_Real_EstateTemplate::attachAssets($file, 'js', rtrim(JURI::root(), '/'));
    }

    /**
     * Method to attach component assets
     */
    public static function attachAssets($path, $type, $asset_path = '') {
	$document = JFactory::getDocument();

	if ($document->getType() != 'html') {
	    return;
	}

	if (!empty($asset_path)) {
	    $path = $asset_path . $path;
	} else {
	    $path = JURI::root() . 'components/com_jux_real_estate' . $path;
	}

	if (!defined('JOOM_PROPERTY_ASSET_' . md5($path))) {
	    define('JOOM_PROPERTY_ASSET_' . md5($path), 1);

	    switch ($type) {
		case 'js':
		    $document->addScript($path);
		    break;
		case 'css':
		    $document->addStyleSheet($path);
		    break;
	    }
	}
    }

    /**
     * Method to allow a temmplate include other template and inherit all the variables.
     */
    function load($file, $layout = null) {
	if ($this->_vars) {
	    extract($this->_vars, EXTR_REFS);
	}

	$file = $this->_getTemplateFullPath($file, $layout);
	include($file);

	return $this;
    }

    /**
     * Method to open, parse, and return the template file.
     */
    function fetch($file = null, $layout = null) {
	$file_layout = $this->_getTemplateFullPath($file, $layout);

	if (!$file_layout || !JFile::exists($file_layout)) {
	    $file = $this->_getTemplateFullPath($file);
	} else {
	    $file = $file_layout;
	}

	if (!$file) {
	    $file = $this->_file;
	}

	if (!isset($this->_vars['config']) || empty($this->_vars['config'])) {
	    $this->_vars['config'] = JUX_Real_EstateFactory::getConfiguration();
	}

	if (!isset($this->_vars['user']) || empty($this->_vars['user'])) {
	    $this->_vars['user'] = JFactory::getUser();
	}

	if (!isset($this->_vars['live_site']) || empty($this->_vars['live_site'])) {
	    $this->_vars['live_site'] = JURI::root();
	}

	if ($this->_vars) {
	    // extract the variables to local namespace
	    extract($this->_vars, EXTR_REFS);
	}

	ob_start();
	require($file);
	$contents = ob_get_contents();
	ob_end_clean();

	return $contents;
    }

}