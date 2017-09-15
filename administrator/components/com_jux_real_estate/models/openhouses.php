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
 * JUX_Real_Estate Component - Openhouses Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelOpenhouses extends JModelList
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
                'id', 'a.id',
                'name', 'a.name',
                'street', 'r.street',
                'title', 'r.title',
                'publish_down', 'a.publish_down',
                'publish_up', 'a.publish_up',
                'company_id', 'r.company_id',
                'published', 'a.published'
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
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.company_id');
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

        $state = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $state);

        $company = $this->getUserStateFromRequest($this->context . '.filter.company_id', 'filter_company_id', '');
        $this->setState('filter.company_id', $company);

        // List state information.
        parent::populateState('a.publish_down', 'asc');
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
		$query2= $db->getQuery(true);
        $user = JFactory::getUser();
        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.*'
            )
        );
        $query->from('#__re_openhouses AS a');

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the properties
        $query->select('r.title, r.street, CONCAT_WS(" ", r.street_num, r.street, r.street2) AS `street_address`, r.company_id AS `company_id`');
        $query->join('LEFT', '#__re_realties AS r ON r.id = a.realty_id');

        $state = $this->getState('filter.published');
        if (is_numeric($state)) {
            $query->where('a.published = ' . (int)$state);
        } else if ($state === '') {
            $query->where('(a.published = 0 OR a.published = 1)');
        }

        // Filter by company.
        $companyId = $this->getState('filter.company_id');
        if ($companyId && is_numeric($companyId)) {
            $query->where('r.company_id = ' . (int)$companyId);
        }

        // Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->quote('%' . $db->escape($search, true) . '%', false);

                $searchwhere[] = 'LOWER(a.name) LIKE ' . $search;
//                $searchwhere[] = 'LOWER(r.street) LIKE ' . $search;
//                $searchwhere[] = 'LOWER(r.title) LIKE ' . $search;

                $query->where('(' . implode(' OR ', $searchwhere) . ')');

            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query->group('a.id');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
//            echo $query;
        return $query;
    }
}
