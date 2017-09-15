<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

/**
 * JUX_Real_Estate Component - Companies Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelCompanies extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'c.id',
                'name', 'c.name',
                'email', 'c.email',
                'phone', 'c.phone',
                'website', 'c.website',
                'ordering', 'c.ordering',
                'published', 'c.published',
				'featured', 'c.featured',
                'agent_count', 'realty_count'
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
    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.access');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.language');

        return parent::getStoreId($id);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param    string    An optional ordering field.
     * @param    string    An optional direction (asc|desc).
     *
     * @return    void
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {


        // Initialise variables.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
        $this->setState('filter.access', $access);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        // List state information.
        parent::populateState('c.ordering', 'asc');
    }


    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     * @since    1.6
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'c.id AS id,' .
                    'c.name AS name,' .
                    'c.email AS email,' .
                    'c.phone AS phone,' .
                    'c.featured AS featured,' .
                    'c.published AS published,' .
                    'c.ordering AS ordering,' .
                    'c.image AS image,' .
                    'c.website AS website,' .
                    'c.checked_out,' .
                    'c.checked_out_time,' .
                    'c.alias,' .
                    'c.access,' .
                    'count(DISTINCT(a.id)) AS agent_count,' .
                    'count(DISTINCT(r.id)) AS realty_count'
            )
        );
        $query->from('#__re_companies AS c');

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id=c.checked_out');

        // Join over agents to get agent count per company
        $query->join('LEFT', '#__re_agents AS a ON a.company_id = c.id');

        // Join over the properties
        $query->join('LEFT', '#__re_realties AS r ON r.company_id = c.id');

        // Join over the asset groups.
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = c.access');

        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('c.published = ' . (int)$published);
        } else if ($published === '') {
            $query->where('(c.published = 0 OR c.published = 1)');
        }

        // Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('c.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->quote('%' . $db->escape($search, true) . '%');
                $query->where('(c.name LIKE ' . $search . ')');
            }
        }

        // Filter by access level.
        if ($access = $this->getState('filter.access')) {
            $query->where('c.access = ' . (int)$access);
        }

        // Implement View Level Access
        if (!$user->authorise('core.admin')) {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $query->where('c.access IN (' . $groups . ')');
        }


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol == 'c.ordering') {
            $orderCol = 'c.ordering';
        }
        if ($orderCol == 'access_level')
            $orderCol = 'ag.title';

        $query->group('c.id');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

	//	echo $query;
        return $query;
    }
}
