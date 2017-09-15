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
 * JUX_Real_Estate Component - Openhouses Modellist
 * @package        JUX_Real_Estate
 * @subpackage    Modellist
 * @since        3.0
 */
class JUX_Real_EstateModelOpenhouses extends JModelList {

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
                'id', 'r.id',
                'title', 'r.title',
                'catid', 'r.catid',
                'type_id', 'r.type_id',
                'locstate', 'r.locstate',
                'city', 'r.city',
                'country_id', 'r.country_id',
                'company_id', 'r.company_id',
                'beds', 'r.beds',
                'baths', 'r.baths',
                'sqft', 'r.sqft',
                'price', 'r.price',
                'date_created', 'r.date_created',
                'modified', 'r.modified',
                'ordering', 'r.ordering', 't.ordering',
                'language', 'r.language',
                'access', 'r.access', 'access_level'
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
// Compile the store id.
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.company_id');
        $id .= ':' . $this->getState('filter.state_id');
        $id .= ':' . $this->getState('filter.country_id');

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
        $itemsost = JRequest::get('post');
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $params = $app->getParams();

        // Get limit
        $limit = (int) $params->get('num_intro_openhouses', $configs->get('num_intro_realties'));
        $value = $app->getUserStateFromRequest('com_jux_real_estate.openhouses.limit', 'limit', $limit);
        $this->setState('list.limit', $value);

        $limitstart = JRequest::getUInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        $startDate = JRequest::getCmd('startDate', '');
        $this->setState('list.startDate', $startDate);

        $endDate = JRequest::getCmd('endDate', '');
        $this->setState('list.endDate', $endDate);




        $this->setState('filter.search', JRequest::getString('filter-search'));
//filter

        $type_id = JRequest::getCmd('type_id', '');
        $this->setState('list.type_id', $type_id);
        $cat_id = JRequest::getCmd('catid', '');
        $this->setState('list.cat_id', $cat_id);

        $company_id = JRequest::getCmd('company_id');
        $this->setState('list.company_id', $company_id);

        $city = JRequest::getString('city', '');
        $this->setState('list.city', $city);
        $curstate = JRequest::getCmd('locstate', '');
        $this->setState('list.locstate', $curstate);
        $province = JRequest::getCmd('province', '');
        $this->setState('list.province', $province);
        $county = JRequest::getCmd('county', '');
        $this->setState('list.county', $county);
        $region = JRequest::getCmd('region', '');
        $this->setState('list.region', $region);
        $country_id = JRequest::getCmd('country_id', '');
        $this->setState('list.country_id', $country_id);
        $beds = JRequest::getCmd('beds', 0);
        $this->setState('list.beds', $beds);
        $baths = JRequest::getCmd('baths', 0);
        $this->setState('list.baths', $baths);
        $price_low = JRequest::getCmd('price_low', 0);
        $this->setState('list.price_low', $price_low);
        $price_high = JRequest::getCmd('price_high', 0);
        $this->setState('list.price_high', $price_high);

// order filter
        $orderCol = JRequest::getCmd('filter_order', 'a.ordering');
        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = 'a.ordering';
        }
        $this->setState('list.ordering', $orderCol);

        $listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');
        if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
            $listOrder = 'ASC';
        }
        $this->setState('list.direction', $listOrder);


        $this->setState('list.ordering', $orderCol);

        $this->setState('list.direction', $listOrder);


// Load the parameters.
        $this->setState('filter.language', $app->getLanguageFilter());
        $this->setState('params', $itemsarams);
    }

    function convertDate($date) {
        $sql = 'SELECT `value` FROM #__re_configs WHERE `key`="date_format"';
        $this->_db->setQuery($sql);
        $dateFormat = $this->_db->loadResult();
        if (empty($dateFormat))
            $dateFormat = "d-m-Y H:i:s";
        if ($date) {
            $date = strtotime(str_replace('-', '/', $date));
            return date($dateFormat, $date);
        } else {
            return null;
        }
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return    string    An SQL query
     * @since    1.6
     */
    protected function getListQuery() {
        $user = JFactory::getUser();
        $groups = $user->getAuthorisedViewLevels();
// Create a new query object.
        $db = $this->getDbo();
// Filter by start and end dates.
        $nullDate = $db->Quote($db->getNullDate());
        $date = JFactory::getDate();
        $nowDate = $db->Quote($date->toSql());
        $query = $db->getQuery(true);

        $query->select('r.*, r.id AS id, r.title AS title, r.street_num, r.street , r.description as description, oh.name as ohname, oh.publish_up as ohstart, oh.publish_down as ohend, oh.comments as comments, r.date_created as fcreated')
                ->from('#__re_realties AS r')
                ->leftJoin('#__re_openhouses as oh ON oh.realty_id = r.id')
                ->leftJoin('#__categories as c on c.id = r.catid')
                ->leftJoin('#__re_companies as co on co.id = r.company_id')
                ->leftJoin('#__re_agents as a on a.id = r.agent_id')
                ->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
                ->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')')
                ->where('r.published = 1 AND c.published = 1 AND a.published = 1 AND co.published = 1 AND oh.published = 1')
                ->where('oh.publish_down >= ' . $nowDate)
                ->where('r.approved = 1');

        if (is_array($groups) && !empty($groups)) {
            $query->where('r.access IN (' . implode(",", $groups) . ')')
                    ->where('c.access IN (' . implode(",", $groups) . ')');
        }

// Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $where = array();
            if (stripos($search, 'id:') === 0) {
                $query->where('r.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $where[] = '(r.title LIKE ' . $search . ' OR r.alias LIKE ' . $search . ')';
                $where[] = '(LOWER( r.street ) LIKE ' . $search . ')';
                $where[] = '(LOWER( r.street2 ) LIKE ' . $search . ')';
                $where[] = '(LOWER( r.description ) LIKE ' . $search . ')';
                $where[] = '(LOWER( r.city ) LIKE ' . $search . ')';

                $query->where('(' . implode(' OR ', $where) . ')');
            }
        }
        if ($this->getState('list.city'))
            $query->where('LOWER(r.city) = ' . $db->Quote(JString::strtolower($this->getState('list.city'))));
        if ($this->getState('list.locstate'))
            $query->where('r.locstate = ' . (int) $this->getState('list.locstate'));
        if ($this->getState('list.province'))
            $query->where('LOWER(r.province) = ' . $db->Quote(JString::strtolower($this->getState('list.province'))));
        if ($this->getState('list.county'))
            $query->where('LOWER(r.county) = ' . $db->Quote(JString::strtolower($this->getState('list.county'))));
        if ($this->getState('list.region'))
            $query->where('LOWER(r.region) = ' . $db->Quote(JString::strtolower($this->getState('list.region'))));
        if ($this->getState('list.country_id'))
            $query->where('r.country_id = ' . (int) $this->getState('list.country_id'));
        if ($this->getState('list.beds'))
            $query->where('r.beds >= ' . (int) $this->getState('list.beds'));
        if ($this->getState('list.baths'))
            $query->where('r.baths >= ' . (int) $this->getState('list.baths'));

//price
        if ($this->getState('list.price_low') && $this->getState('list.price_high'))
            $query->where('(r.price BETWEEN ' . (int) $this->getState('list.price_low') . ' AND ' . $this->getState('list.price_high') . ')');
        if ($this->getState('list.price_low') && !$this->getState('list.price_high'))
            $query->where('r.price >= ' . (int) $this->getState('list.price_low'));
        if ($this->getState('list.price_high') && !$this->getState('list.price_low'))
            $query->where('r.price <= ' . (int) $this->getState('list.price_high'));

// Filter by date
        $startDate = $this->getState('list.startDate');
        $startDate = $this->convertDate($startDate);
//        var_dump($startDate);
        $endDate = $this->getState('list.endDate');
        $endDate = $this->convertDate($endDate);

        if ($startDate && $endDate) {
            $startDate = $startDate . ' 00:00:00';
            $endDate = $endDate . ' 23:59:59';
            $query->where(' ((oh.publish_up BETWEEN ' . $this->_db->Quote($startDate) . ' AND ' . $this->_db->Quote($endDate) . ')
                            OR (oh.publish_down < ' . $this->_db->Quote($startDate) . ' AND oh.publish_up >= ' . $this->_db->Quote($startDate) . '))
                            ');
        } else if ($startDate && $endDate == null) {
            $startDate = $startDate . ' 00:00:00';

            $query->where('((oh.publish_up >= ' . $db->quote($startDate) . ' OR oh.publish_down >= ' . $db->quote($startDate) . '))');
        } else if ($startDate == null && $endDate) {
            $endDate = $endDate . ' 23:59:59';
            $query->where('((oh.publish_up <= ' . $db->quote($endDate) . ' AND oh.publish_down <= ' . $db->quote($endDate) . '))');
        }
// Filter by type

        if ($this->getState('list.type_id')) {
            $query->where('r.type_id = ' . (int) $this->getState('list.type_id'));
        }

// Filter by company

        if ($this->getState('list.company_id')) {
            $query->where('r.company_id= ' . (int) $this->getState('list.company_id'));
        }


        $query->group('r.id, oh.publish_down');
// Ordering
        $query->order($db->escape($this->getState('list.ordering', 'oh.publish_down')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $config = JFactory::getConfig();
        $tzoffset = $config->get('config.offset');
        $hide_round = 3;

        if (count($items)) {
            for ($i = 0; $i < count($items); $i++) {

                $items[$i]->street_address = JUX_Real_EstateHTML::getStreetAddress($configs, $items[$i]->title, $items[$i]->street_num, $items[$i]->street, $items[$i]->address, $items[$i]->hide_address);
                $items[$i]->baths = (!$configs->get('baths_fraction')) ? round($items[$i]->baths) : $items[$i]->baths;
                $items[$i]->lat_pos = ($items[$i]->hide_address) ? round($items[$i]->latitude, $hide_round) : $items[$i]->latitude;
                $items[$i]->long_pos = ($items[$i]->hide_address) ? round($items[$i]->longitude, $hide_round) : $items[$i]->longitude;
// get realty link
                $items[$i]->realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($items[$i]->id . ':' . $items[$i]->alias));
# Get the thumbnail
                $items[$i]->thumb = JUX_Real_EstateHTML::getThumbnail($items[$i], $items[$i]->realtylink, $items[$i]->title, $configs->get('thumb_img_width'), $configs->get('thumb_img_height'), $items[$i]->title, $items[$i]->title);

# Format Price and SQft output
                $items[$i]->formattedprice = JUX_Real_EstateHTML::getFormattedPrice($items[$i]->price, $items[$i]->price_freq, $items[$i]->currency_id, false, $items[$i]->call_for_price, $items[$i]->price2);
                $items[$i]->formattedsqft = number_format($items[$i]->sqft);

# Check if new or updated
                $items[$i]->new = JUX_Real_EstateHTML::isNew($items[$i]->date_created, $configs->get('new_days'));
                $items[$i]->updated = JUX_Real_EstateHTML::isNew($items[$i]->modified, $configs->get('updated_days'));

# Get last modified date if available
                $items[$i]->last_updated = ($items[$i]->modified != '0000-00-00 00:00:00') ? JHTML::_('date', htmlspecialchars($items[$i]->modified), JText::_('DATE_FORMAT_LC2'), $tzoffset) : '';
            }
        }

        return $items;
    }

}