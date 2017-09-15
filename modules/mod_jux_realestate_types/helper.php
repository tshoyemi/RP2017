<?php
/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * JUX Real Estate Categories Helper Class.
 * @package		JUX Real Estate
 * @subpackage	Helper
 */
class ModJUXRealtyEstate_TypesHelper {

    function getTypes($params) {

        $db =  JFactory::getDBO();
        $orderby = $this->_buildContentOrderBy($params);

        // Get all categories
        $query	= 'SELECT DISTINCT a.*, count(r.id) AS `count`'
                . ' FROM #__re_types AS a'
                . ' LEFT JOIN #__re_realties AS r ON (r.type_id = a.id AND r.published =1 AND r.approved = 1)'
                . ' WHERE a.published = 1'
                . ' GROUP BY a.id'
                . $orderby;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function _buildContentOrderBy($params) {
        $order_by	= ' ORDER BY ';
        if ($params->get('orderby_sec') ) {
            $order		= $params->def('orderby_sec', 'alpha');
        } else {
            $order = '';
        }
        switch ($order) {
            case 'alpha':
            $order_by .= 'a.title';
            break;
            case 'ralpha':
            $order_by .= 'a.title DESC';
            break;
            case 'order':
            $order_by .= 'a.ordering';
            break;
            case 'rorder':
            $order_by .= 'a.ordering DESC';
            break;
            default:
            $order_by .= 'a.title DESC';
        }
        return $order_by;
    }
}