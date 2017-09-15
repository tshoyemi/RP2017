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

require_once dirname(__FILE__) . '/allrealties.php';

/**
 * JUX_Real_Estate Component - List Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelList extends JUX_Real_EstateModelAllRealties {

    /**
     * Model context string.
     *
     * @var        string
     */
    protected $_context = 'com_jux_real_estate.list';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null) {
	parent::populateState();
    }

    /**
     * @return    JDatabaseQuery
     */
    protected function getListQuery() {
	$query = parent::getListQuery();
	return $query;
    }

    /**
     * Method to load type data if it doesn't exist.
     *
     * @access    public
     * @return    boolean    True on success
     */
    function getType() {
	// Lets get the information for the current section
	if ($this->getState('list.type_id')) {
	    $query = 'SELECT title FROM #__re_types'
		    . ' WHERE published = 1 AND id = ' . (int) $this->getState('list.type_id');
	    $this->_db->setQuery($query);
	    $title = $this->_db->loadResult();
	    if ($title != '')
		return $title;
	}
	return false;
    }

    /**
     * Method to load type data if it doesn't exist.
     *
     * @access    public
     * @return    boolean    True on success
     */
    function getCategory() {
	// Lets get the information for the current section
	if ($this->getState('list.cat_id')) {
	    $query = 'SELECT title FROM #__categories'
		    . ' WHERE published = 1 AND id = ' . (int) $this->getState('list.cat_id');
	    $this->_db->setQuery($query);
	    $title = $this->_db->loadResult();
	    if ($title != '')
		return $title;
	}
	return false;
    }

}