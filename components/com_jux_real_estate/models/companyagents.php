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
 * JUX_Real_Estate Component - Companycompanyagents Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelCompanyagents extends JModelList {

    /**
     * Model context string.
     *
     * @var        string
     */
    protected $_context = 'com_jux_real_estate.companyagents';

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
                'id', 'a.id', 'c.id',
                'first_name', 'a.first_name',
                'last_name', 'a.last_name',
                'ordering', 'a.ordering'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        $app = JFactory::getApplication('site');

        $configs = JUX_Real_EstateFactory::getConfiguration();
        $params = $app->getParams();
        // Load state from the request.
        $pk = JRequest::getInt('id');
        $this->setState('list.company_id', $pk);

        // Optional filter text
        $this->setState('filter.search', JRequest::getString('filter-search'));

        $orderCol = JRequest::getCmd('filter_order', 'c.ordering');
        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = 'c.ordering';
        }
        $this->setState('list.ordering', $orderCol);

        $listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');
        if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
            $listOrder = 'ASC';
        }
        $this->setState('list.direction', $listOrder);

        // Get limit
        $limit = (int) $params->get('num_intro_agents', $configs->get('num_intro_agents'));
        $value = $app->getUserStateFromRequest('global.compnayagents.limit', 'limit', $limit);
        $this->setState('list.limit', $value);

        $limitstart = JRequest::getUInt('limitstart', 0);
        $this->setState('list.start', $limitstart);


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
     * @param    string        $id    A prefix for the store id.
     *
     * @return    string        A store id.
     * @since    1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.company_id');

        return parent::getStoreId($id);
    }

    /**
     * @return    JDatabaseQuery
     */
    protected function getListQuery() {

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('Distinct a.*, c.id AS companyid, c.name AS companyname, CONCAT_WS(" ", first_name, last_name) AS name, c.alias AS co_alias');
        $query->from('#__re_agents AS a');
        $query->join('LEFT', '#__re_companies AS c ON c.id = a.company_id');
        $query->where('a.published = 1 AND c.published = 1');


        // Filter by search in title.
        $search = $this->getState('filter.search');
        $search = urldecode($search);
        $search = JString::strtolower($search);

        if (!empty($search)) {
            $where = array();
            $search = $db->Quote('%' . $db->escape($search, true) . '%');
            $where[] = '(LOWER( a.first_name ) LIKE ' . $search . ')';
            $where[] = '(LOWER( a.last_name ) LIKE ' . $search . ')';

            $query->where('(' . implode(' OR ', $where) . ')');
        }

        if ($this->getState('list.company_id')) {
            $query->where('a.company_id = ' . (int) $this->getState('list.company_id'));
        }

        // Ordering
        $query->order($this->getState('list.ordering', 'a.ordering') . ' ' . $this->getState('list.direction', 'ASC'));
        return $query;
    }

    function getFeatured() {
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $num_featured = $configs->get('agent_feat_num', 2);
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('a.*, c.id AS companyid, c.name AS companyname, CONCAT_WS(" ", first_name, last_name) AS name, c.alias AS co_alias');
        $query->from('#__re_agents AS a');
        $query->join('LEFT', '#__re_companies AS c ON c.id = a.company_id');
        $query->where('a.published = 1 AND c.published = 1');
        $query->where('a.featured = 1');
        $query->where('a.company_id = c.id');
        if ($this->getState('list.company_id')) {
            $query->where('a.company_id = ' . (int) $this->getState('list.company_id'));
        }
        $query->order('RAND()');
        //echo $query;
        $db->setQuery($query, 0, $num_featured);
        return $db->loadObjectList();
    }

    //get an Company object by id
    function getCompany() {
        $data = JUX_Real_EstateHelperQuery::getCompany($this->getState('list.company_id'));
        return $data;
    }

}