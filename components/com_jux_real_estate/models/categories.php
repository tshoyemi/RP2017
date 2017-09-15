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
 * JUX_Real_Estate Component - Categories JModelList
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelCategories extends JModelList {

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
                'id', 'c.id',
                'title', 'c.title',
                'alias', 'c.alias',
                'categories', 'count(r.id)',
                'icon', 'c.icon',
                'published', 'c.published',
                'access', 'c.access',
                'language', 'c.language',
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

//        $leading = (int)$params->get('num_leading_categories');
        $intro = (int) $params->get('num_intro_categories');


        $orderCol = $app->getUserStateFromRequest('com_jux_real_estate.categories.list.filter_order', 'filter_order', '', 'string');
        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = $this->_buildContentOrderBy();
            $listOrder = '';
        } else {
            $listOrder = $app->getUserStateFromRequest('com_jux_real_estate.categories.list.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
            if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
                $listOrder = 'ASC';
            }
        }
        $this->setState('orderCol', $orderCol);

        $this->setState('listOrder', $listOrder);

        $this->setState('filter.published', array(0, 1));

        $this->setState('filter.language', $app->getLanguageFilter());

        // filter by access
        $this->setState('filter.access', true);

        // Optional filter text
        $this->setState('list.filter', JRequest::getString('filter-search'));


        // Get the pagination request variables

        $value = $app->getUserStateFromRequest('com_jux_real_estate.categories.limit', 'limit', $params->get('num_intro_categories', 5));
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
     * Method to build an SQL query to load the list data.
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
        $extenstion = 'com_jux_real_estate';
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('c.*, count(r.id) AS `count_realty`')
                ->from('#__categories AS c')
                ->leftJoin('#__re_realties as r ON r.catid = c.id  AND r.published = 1 AND r.approved = 1 AND (r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
                ->where('c.published = 1 AND c.extension = "com_jux_real_estate"');
        if (is_array($groups) && !empty($groups)) {
            $query->where('c.access IN (' . implode(",", $groups) . ')');
        }

        // Filter by language

        $query->where('c.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');


        $query->group('c.id');

        // Ordering
        $query->order($this->getState('orderCol', 'r.ordering') . ' ' . $this->getState('listOrder', 'ASC'));
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
                $order_by .= 'c.title';
                break;
            case 'ralpha':
                $order_by .= 'c.title DESC';
                break;
            default:
                $order_by .= 'c.title DESC';
        }

        return $order_by;
    }

}