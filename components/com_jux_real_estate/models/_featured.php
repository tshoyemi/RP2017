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
 * JUX_Real_Estate Component - Allrealties Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelFeatured extends JUX_Real_EstateModelAllRealties {

    /**
     * Model context string.
     *
     * @var        string
     */
    protected $_context = 'com_jux_real_estate.featured';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState() {
	parent::populateState();
    }

    /**
     * @return    JDatabaseQuery
     */
    function getListQuery() {

	$query = parent::getListQuery();
	$query->where('r.featured = 1');
	return $query;
    }

}