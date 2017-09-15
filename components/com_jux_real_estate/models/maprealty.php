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
 * JUX_Real_Estate Component - Maprealty Model
 * @package		JUX_Real_Estate
 * @subpackage	Model
 * @since		1.0
 */
class JUX_Real_EstateModelMaprealty extends JModelLegacy {

    var $_data = null;

    /**
     * Constructor
     *
     */
    function __construct() {
	parent::__construct();
    }

    /**
     * Method to get realty item data for the type or category
     *
     * @param	int	$state	The realty state to pull from for the current
     * section
     * @since 1.5
     */
    function getData() {
	// $configs = JUX_Real_EstateFactory::getConfiguration();
	// Load the Category data
	if (empty($this->_data)) {
	    $query = $this->_buildQuery();
	    $this->_db->setQuery($query);
	    $this->_data = $this->_db->loadObjectList();
	    if (count($this->_data)) {
		for ($i = 0; $i < count($this->_data); $i++) {
		    // get realty link
		    $this->_data[$i]->realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($this->_data[$i]->id . ':' . $this->_data[$i]->alias));

		    # Format Price and SQft output
		    $this->_data[$i]->formattedprice = JUX_Real_EstateHTML::getFormattedPrice($this->_data[$i]->price, $this->_data[$i]->price_freq, $this->_data[$i]->currency_id, false, $this->_data[$i]->call_for_price, $this->_data[$i]->price2);
		}
	    }
	}
	return $this->_data;
    }

    function _buildQuery() {

	// Get the WHERE and ORDER BY clauses for the query
	$where = $this->_buildContentWhere();
	$query = 'SELECT DISTINCT a.*, t.title AS `type`, t.icon AS `icon`, c.title AS `category`'
		. ' FROM #__re_realties AS a'
		. ' INNER JOIN #__re_types AS t ON t.id = a.type_id'
		. ' INNER JOIN #__categories AS c on c.id = a.cat_id'
		. ' WHERE ' . $where
		. ' ORDER BY a.title';
	
	return $query;
    }

    function _buildContentWhere() {

	$app = JFactory::getApplication();
	$params = $app->getParams();

	$cat_id = $params->get('cat_id');
	$type_id = $params->get('typeid');

	$where = "a.published = 1 AND a.approved = 1 AND a.sold = 0";
	$where .= " AND t.published = 1";
	$where .= " AND c.published = 1";


	$types = array();
	if (is_array($type_id))
	    $types = $type_id;
	else
	    $types[] = (int) $type_id;
	if ($types[0] != 0) {
	    $types = implode(',', $types);
	    $where .= " AND t.id IN($types)";
	}

	$categories = array();
	if (is_array($cat_id))
	    $categories = $cat_id;
	else
	    $categories[] = (int) $cat_id;
	if ($categories[0] != 0) {
	    $categories = implode(',', $categories);
	    $where .= " AND c.id IN($categories)";
	}

	return $where;
    }

    function getTypes() {
	$app = JFactory::getApplication();
	$params = $app->getParams();
	$db = $this->getDbo();
	$query = $db->getQuery(true);
	$type_id = $params->get('typeid');
	if (!empty($query)) {
	    $query->select('a.*');
	    $query->from('#__re_types AS a');
	    $query->where('a.published = 1');

	    $types = array();
	    if (is_array($type_id))
		$types = $type_id;
	    else
		$types[] = (int) $type_id;
	    if ($types[0] != 0) {
		$types = implode(',', $types);
		$query->where('a.id IN(' . $types . ')');
	    }
	    $this->_db->setQuery($query);
	    return $this->_db->loadObjectList();
	}
	else
	    return false;
    }

}