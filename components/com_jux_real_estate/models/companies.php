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
 * JUX_Real_Estate Component - Companies Modellist
 * @package        JUX_Real_Estate
 * @subpackage    Modellist
 * @since        3.0
 */
class JUX_Real_EstateModelCompanies extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'c.id',
                'name', 'c.name',
                'city', 'c.city',
                'ordering', 'c.ordering'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param    string        $id    A prefix for the store id.
     *
     * @return    string        A store id.
     * @since    1.6
     */
    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication();
        $itemsarams = $app->getParams();
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $params = $app->getParams();

        // Get limit
        $limit = (int) $params->get('num_intro_companies', $configs->get('num_intro_companies'));
        $value = $app->getUserStateFromRequest('global.companies.limit', 'limit', $limit);
        $this->setState('list.limit', $value);

        $limitstart = JRequest::getUInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        // Optional filter text
        $this->setState('filter.search', JRequest::getString('filter-search'));

        $orderCol = JRequest::getCmd('filter_order', 'c.ordering');
        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = 'c.ordering';
            $listOrder = '';
        } else {
            $listOrder = $app->getUserStateFromRequest('com_jux_real_estate.companies.list.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
            if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
                $listOrder = 'ASC';
            }
        }

        $this->setState('list.ordering', $orderCol);
        $this->setState('list.direction', $listOrder);

        // Load the parameters.
        $this->setState('params', $itemsarams);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return    string    An SQL query
     * @since    1.6
     */
    protected function getListQuery() {
        $user = JFactory::getUser();

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select($this->getState('list.select', 'c.id'));
        $query->from('#__re_companies AS c');
        $query->where('c.published = 1');

        // Filter by search in title.
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('c.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(c.name LIKE ' . $search . ' OR c.alias LIKE ' . $search . ')');
            }
        }

        // Ordering
        $query->order($db->escape($this->getState('list.ordering', 'c.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

        return $query;
    }

    function getFeatured() {
        $config = JUX_Real_EstateFactory::getConfiguration();
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select($this->getState('list.select', 'c.id'));
        $query->from('#__re_companies AS c');
        $query->where('c.published = 1');
        $query->where('c.featured = 1');
        // Ordering
        $query->order('RAND()');
        $db->setQuery($query, 0, $config->get('com_feat_num', 5));

        return $db->loadObjectList();
    }

}