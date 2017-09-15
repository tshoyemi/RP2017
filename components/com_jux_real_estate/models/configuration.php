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

jimport('joomla.application.component.model');

/**
 * JUX_Real_Estate Component - Configuration Model
 * @package		JUX_Real_Estate
 * @subpackage	Model
 * @since		1.0
 */
class JUX_Real_EstateModelConfiguration extends JModelLegacy {

    /**
     * Containing all config data,  store in an object with key, value
     *
     * @var object
     */
    var $_data = null;

    /**
     * Constructor
     *
     */
    function __construct() {
	parent::__construct();
    }

    /**
     * Get config data.
     */
    function getData() {
	if (empty($this->_data)) {
	    $this->_db = JFactory::getDBO();
	    $query = 'SELECT *'
		    . ' FROM #__re_configs';
	    $this->_db->setQuery($query);
	    $rows = $this->_db->loadObjectList();

	    $config = new JObject();

	    for ($i = 0, $n = count($rows); $i < $n; $i++) {
		$row = $rows[$i];
		$key = $row->key;
		$value = $row->value;

		$config->set($key, $value);
	    }
	    // get currency config
	    $query = 'SELECT a.*'
		    . ' FROM #__re_currencies AS a'
		    . ' WHERE a.id = ' . (int) $config->get('currency')
		    . ' UNION (SELECT a.*'
		    . ' FROM #__re_currencies AS a'
		    . ' ORDER BY a.ordering'
		    . ' LIMIT 1)'
	    ;
	    $this->_db->setQuery($query);
	    $row = $this->_db->loadObject();

	    $config->set('currency_code', $row->code);
	    $config->set('currency_sign', $row->sign);
	    $config->set('currency_position', $row->position);
	    //
	    $this->_data = $config;
	}

	return $this->_data;
    }

    /**
     *  Get site languages
     *
     */
    function getSiteLanguages() {
	jimport('joomla.filesystem.folder');
	$path = JPATH_ROOT . DS . 'language';
	$folders = JFolder::folders($path);
	$rets = array();
	foreach ($folders as $folder)
	    if ($folder != 'pdf_fonts')
		$rets[] = $folder;
	return $rets;
    }

}