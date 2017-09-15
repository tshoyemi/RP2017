<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.modellist');

/**
 * JUX_Real_Estate Component - Types JModelList
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelTypes extends JModelList {

    /**
     * Constructor.
     *
     * @param	array	An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 't.id',
                'title', 't.title',
                'alias', 't.alias',
                'types', 'count(r.id)',
                'icon', 't.icon',
                'published', 't.published',
                'ordering', 't.ordering',
                'access', 't.access',
                'language', 't.language'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication();
        $params = $app->getParams();

        $value = $app->getUserStateFromRequest('com_jux_real_estate.types.limit', 'limit',  $params->get('num_intro_types', 5));
        $this->setState('list.limit', $value);

        $limitstart = JRequest::getUInt('limitstart', 0);
        $this->setState('list.start', $limitstart);


        $orderCol = $app->getUserStateFromRequest('com_jux_real_estate.types.list.filter_order', 'filter_order', '', 'string');
        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = $this->_buildContentOrderBy();
            $listOrder = '';
        } else {
            $listOrder = $app->getUserStateFromRequest('com_jux_real_estate.types.list.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
            if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
                $listOrder = 'ASC';
            }
        }
        $this->setState('listOrder', $listOrder);

        $this->setState('orderCol', $orderCol);

        $this->setState('listOrder', $listOrder);

        $this->setState('filter.published', array(0, 1));

        $this->setState('filter.language', $app->getLanguageFilter());

        // filter by access
        $this->setState('filter.access', true);

        // Optional filter text
        $this->setState('list.filter', JRequest::getString('filter-search'));

        // Load the parameters.
        $this->setState('params', $params);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     *
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.access');
        $id .= ':' . $this->getState('filter.language');

        return parent::getStoreId($id);
    }

    /**
     * Method to build an SQL query to load the list datt.
     *
     * @return	string	An SQL query
     * @since	1.6
     */
    protected function getListQuery() {
        $user = JFactory::getUser();
        $groups = $user->getAuthorisedViewLevels();
        $db = JFactory::getDbo();
        // Filter by start and end dates.
        $nullDate = $db->Quote($db->getNullDate());
        $date = JFactory::getDate();
        $nowDate = $db->Quote($date->toSql());

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select($this->getState('list.select', 't.*'));
        $query->from($db->quoteName('#__re_types') . ' AS t');
        $query->select('count(r.id) AS `types`');
        $query->join('LEFT', '#__re_realties as r ON r.type_id = t.id AND r.published = 1 AND r.approved = 1 AND (r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ') AND (r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
        $query->where('t.published = 1');
        // Filter by access
        if (is_array($groups) && !empty($groups)) {
            $query->where('t.access IN (' . implode(",", $groups) . ')');
        }

        // Filter by client search. Title.
        if ($filter = $this->getState('list.filter')) {
            // clean filter variable
            $filter = JString::strtolower($filter);
            $filter = $db->Quote('%' . $db->escape($filter, true) . '%', false);

            // filter by Title
            $query->where('LOWER( t.title ) LIKE ' . $filter);
        }

        // Filter by language
        if ($this->getState('filter.language')) {
            $query->where('t.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
        }

        // Group by
        $query->group('t.id');

        // Ordering
        $query->order($this->getState('orderCol', 'r.ordering') . ' ' . $this->getState('listOrder'));
        return $query;
    }

    function _buildContentOrderBy() {
        // Get the page/component configuration
        $app = JFactory::getApplication();
        $params = $app->getParams();

        $order_by = '';

        if ($params->get('orderby_sec')) {
            $order = $params->def('orderby_sec', 'alpha');
        } else {
            $order = '';
        }

        switch ($order) {
            case 'alpha':
                $order_by .= 't.title';
                break;
            case 'ralpha':
                $order_by .= 't.title DESC';
                break;
            case 'order':
                $order_by .= 't.ordering';
                break;
            case 'rorder':
                $order_by .= 't.ordering DESC';
                break;
            default:
                $order_by .= 't.ordering DESC';
        }

        return $order_by;
    }

}