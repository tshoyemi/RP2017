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
 * JUX_Real_Estate Component - Category Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelCategory extends JModelList {

    /**
     * Model context string.
     *
     * @var        string
     */
    protected $_context = 'com_jux_real_estate.category';

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
                'cat_id', 'r.cat_id',
                'type_id', 'r.type_id',
                'locstate', 'r.locstate',
                'city', 'r.city',
                't.title', 't.title',
                'c.title', 'c.title',
                'date_created', 'r.date_created',
                'country_id', 'r.country_id',
                'beds', 'r.beds',
                'baths', 'r.baths',
                'sqft', 'r.sqft',
                'price', 'r.price',
                'date_created', 'r.date_created',
                'modified', 'r.modified',
                'ordering', 'r.ordering',  't.ordering',
                'language', 'r.language',
                'access', 'r.access', 'access_level'
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
        $this->setState('list.cat_id', $pk);

        $this->setState('filter.search', JRequest::getString('filter-search'));
        // filter
        $type_id = JRequest::getCmd('type_id', '');
        $this->setState('list.type_id', $type_id);
        //$cat_id = JRequest::getCmd('cat_id', '');

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
        $orderCol = JRequest::getCmd('filter_order', 'r.ordering');
//        if (!in_array($orderCol, $this->filter_fields)) {
        $orderCol = $this->_buildContentOrderBy();
//        }
        $this->setState('orderCol', $orderCol);

        $listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');
        if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', ''))) {
            $listOrder = 'ASC';
        }
        $this->setState('listOrder', $listOrder);

        // Get list limit
        if ($params->get('num_intro_realties')) {
            $limit = (int) $params->get('num_intro_realties');
        } else {
            $limit = (int) $params->get('display_num', $configs->get('num_intro_realties'));
        }

        $value = $app->getUserStateFromRequest('global.category.limit', 'limit', $limit);
        $this->setState('list.limit', $value);

        $limitstart = JRequest::getUInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        $this->setState('params', $params);

        $this->setState('filter.language', $app->getLanguageFilter());
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
        $id .= ':' . $this->getState('filter.access');
        $id .= ':' . $this->getState('filter.language');
        $id .= ':' . $this->getState('list.cat_id');
        $id .= ':' . $this->getState('list.type_id');

        return parent::getStoreId($id);
    }

    /**
     * @return    JDatabaseQuery
     */
    protected function getListQuery() {

        $user = JFactory::getUser();
        $groups = $user->getAuthorisedViewLevels();

        // Filter by start and end dates.
        $nullDate = $this->_db->Quote($this->_db->getNullDate());
        $date = JFactory::getDate();
        $nowDate = $this->_db->Quote($date->toSql());
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('r.*,c.title AS c_title, r.id AS id, r.title AS title, r.street_num, r.street, r.street2, r.description as description, r.date_created as date_created, co.name as company_name, r.alias as realty_alias, c.alias as cat_alias, a.alias as alias, co.alias as co_alias')
                ->from('#__re_realties AS r')
                //  ->leftJoin('#__re_realtyamid as ra ON ra.realty_id = r.id')
                //  ->leftJoin('#__re_agentrealty as ar ON ar.realty_id = r.id')
                ->leftJoin('#__categories as c ON c.id = r.catid')
                ->leftJoin('#__re_types as t ON t.id = r.type_id')
                ->leftJoin('#__re_companies as co ON co.id = r.company_id')
                ->leftJoin('#__re_agents as a on a.id = r.agent_id')
                ->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
                ->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')')
                ->where('r.published = 1 AND co.published = 1')
                ->where('r.approved = 1');
        if (is_array($groups) && !empty($groups)) {
            $query->where('r.access IN (' . implode(",", $groups) . ')')
                    ->where('c.access IN (' . implode(",", $groups) . ')');
        }

        if ($this->getState('list.cat_id')) {
            $query->where('r.catid = ' . (int) $this->getState('list.cat_id'));
        }

        // Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $where = array();
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
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

        // Filter by type

        if ($this->getState('list.type_id')) {
            $query->where('r.type_id = ' . (int) $this->getState('list.type_id'));
        }
        // Filter by category
//        if ($this->getState('list.cat_id')) {
//            $children = JUX_Real_EstateHelperQuery::getChildren($this->getState('list.cat_id'));
//            if ($children) {
//                $child_array = array();
//                foreach ($children as $c) {
//                    $child_array[] = $c->id;
//                }
//                $child_array = implode(',', $child_array);
//                $query->where('(ra.cat_id = ' . (int)$this->getState('list.cat_id') . ' OR ra.cat_id IN (' . $child_array . '))');
//            } else {
//                $query->where('ra.cat_id = ' . (int)$this->getState('list.cat_id'));
//            }
//
//        }
        // Filter by language
        if ($this->getState('filter.language')) {
            $query->where('r.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
        }

        $query->group('r.id');

        // Ordering
        $query->order($this->getState('orderCol', 'r.ordering') . ' ' . $this->getState('listOrder', 'ASC'));

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
                $items[$i]->street_address = JUX_Real_EstateHTML::getStreetAddress($configs, $items[$i]->title, $items[$i]->street_num, $items[$i]->street, $items[$i]->street2, $items[$i]->hide_address);
                $items[$i]->baths = (!$configs->get('baths_fraction')) ? round($items[$i]->baths) : $items[$i]->baths;
                $items[$i]->lat_pos = ($items[$i]->hide_address) ? round($items[$i]->latitude, $hide_round) : $items[$i]->latitude;
                $items[$i]->long_pos = ($items[$i]->hide_address) ? round($items[$i]->longitude, $hide_round) : $items[$i]->longitude;
                // get realty link
                $items[$i]->realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($items[$i]->id . ':' . $items[$i]->alias));
                # Get the thumbnail
                $items[$i]->thumb = JUX_Real_EstateHTML::getThumbnail($items[$i], $items[$i]->realtylink, $items[$i]->title, $configs->get('thumb_img_width'), $configs->get('thumb_img_height'), $items[$i]->title, $items[$i]->title);

                # Format Price and SQft output
                $items[$i]->formattedprice = JUX_Real_EstateHTML::getFormattedPrice($items[$i]->price, $items[$i]->type_freq, $items[$i]->currency_id, false, $items[$i]->call_for_price, $items[$i]->price2);
                $items[$i]->formattedsqft = number_format($items[$i]->sqft);

                # Check if new or updated
                $items[$i]->new = JUX_Real_EstateHTML::isNew($items[$i]->date_created, $configs->get('new_days'));
                $items[$i]->updated = JUX_Real_EstateHTML::isNew($items[$i]->modified, $configs->get('updated_days'));

                # Get last modified date if available
                $items[$i]->last_updated = ($items[$i]->modified != '0000-00-00 00:00:00') ? JHTML::_('date', htmlspecialchars($items[$i]->modified), JText::_('DATE_FORMAT_LC2'), $tzoffset) : '';


                if ($items[$i]->company_name) {
                    if ($items[$i]->date_created != '0000-00-00 00:00:00') {
                        $items[$i]->info_basic = JText::_('COM_JUX_REAL_ESTATE_LISTED_BY') . ' ' . $items[$i]->company_name . ' ' . JText::_('COM_JUX_REAL_ESTATE_ON') . ' ' . JHTML::_('date', htmlspecialchars($items[$i]->date_created), JText::_('DATE_FORMAT_LC1'), $tzoffset);
                    } else {
                        $items[$i]->info_basic = JText::_('COM_JUX_REAL_ESTATE_LISTED_BY') . ' ' . $items[$i]->company_name;
                    }
                } else {
                    $items[$i]->info_basic = '';
                }
                $last_updated = ($items[$i]->last_updated && $items[$i]->updated) ? JText::_('COM_JUX_REAL_ESTATE_LAST_MODIFIED') . ': ' . $items[$i]->last_updated : '';
                $items[$i]->listing_info = $items[$i]->info_basic . ' [' . JUX_Real_EstateHelperQuery::getType($items[$i]->type_id) . '] <br>' . $last_updated;
            }
        }
        
        return $items;
    }

    function getFeatured() {
        
        $configs = JUX_Real_EstateFactory::getConfiguration();

        // Create a new query object.
        // Create a new query object.
        $db = $this->getDbo();
        $user = JFactory::getUser();
        $groups = $user->getAuthorisedViewLevels();

        // Filter by start and end dates.
        $nullDate = $db->Quote($db->getNullDate());
        $date = JFactory::getDate();
        $nowDate = $db->Quote($date->toSql());

        $query = $db->getQuery(true);
        $query->select('r.*, r.id AS id, r.title AS title, r.street_num, r.street, r.street2, r.description as description, r.date_created as fcreated, co.name as company_name, r.alias as realty_alias, c.alias as cat_alias, a.alias as alias, co.alias as co_alias')
                ->from('#__re_realties AS r')
                ->leftJoin('#__re_realtyamid as ra ON ra.realty_id = r.id')
                ->leftJoin('#__re_agentrealty as ar ON ar.realty_id = r.id')
                ->leftJoin('#__categories as c ON c.id = r.catid')
                ->leftJoin('#__re_types as t ON t.id = r.type_id')
                ->leftJoin('#__re_companies as co ON co.id = r.company_id')
                ->leftJoin('#__re_agents as a on a.id = ar.agent_id')
                ->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
                ->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')')
                ->where('r.published = 1 AND c.published = 1 AND a.published = 1 AND co.published = 1')
                ->where('r.approved = 1 AND r.featured = 1');

        if (is_array($groups) && !empty($groups)) {
            $query->where('r.access IN (' . implode(",", $groups) . ')')
                    ->where('c.access IN (' . implode(",", $groups) . ')');
        }

        if ($this->getState('list.cat_id')) {
            $children = JUX_Real_EstateHelperQuery::getChildren($this->getState('list.cat_id'));
            if ($children) {
                $child_array = array();
                foreach ($children as $c) {
                    $child_array[] = $c->id;
                }
                $child_array = implode(',', $child_array);
                $query->where('(ra.cat_id = ' . (int) $this->getState('list.cat_id') . ' OR ra.cat_id IN (' . $child_array . '))');
            } else {
                $query->where('ra.cat_id = ' . (int) $this->getState('list.cat_id'));
            }
        }

        $query->group('r.id');
        // Ordering
        $query->order('RAND()');

        $db->setQuery($query, 0, $configs->get('feat_num', 5));

        $items = $db->loadObjectList();

        $config = JFactory::getConfig();
        $tzoffset = $config->get('config.offset');
        $hide_round = 3;

        if (count($items)) {
            for ($i = 0; $i < count($items); $i++) {

                $items[$i]->street_address = JUX_Real_EstateHTML::getStreetAddress($configs, $items[$i]->title, $items[$i]->street_num, $items[$i]->street, $items[$i]->address, $items[$i]->hide_address);
                $items[$i]->baths = (!$configs->get('baths_fraction')) ? round($items[$i]->baths) : $items[$i]->baths;
                $items[$i]->lat_pos = ($items[$i]->hide_address) ? round($items[$i]->latitude, $hide_round) : $items[$i]->latitude;
                $items[$i]->long_pos = ($items[$i]->hide_address) ? round($items[$i]->longitude, $hide_round) : $items[$i]->longitude;

                # Get the thumbnail
                $items[$i]->thumb = JUX_Real_EstateHTML::getThumbnail($items[$i], true, $items[$i]->title, $configs->get('thumb_img_width'), $configs->get('thumb_img_height'), $items[$i]->title, $items[$i]->title);

                # Format Price and SQft output
                $items[$i]->formattedprice = JUX_Real_EstateHTML::getFormattedPrice($items[$i]->price, $items[$i]->price_freq, false, $items[$i]->call_for_price, $items[$i]->price2);
                $items[$i]->formattedsqft = number_format($items[$i]->sqft);

                # Check if new or updated
                $items[$i]->new = JUX_Real_EstateHTML::isNew($items[$i]->date_created, $configs->get('new_days'));
                $items[$i]->updated = JUX_Real_EstateHTML::isNew($items[$i]->modified, $configs->get('updated_days'));

                # Get last modified date if available
                $items[$i]->last_updated = ($items[$i]->modified != '0000-00-00 00:00:00') ? JHTML::_('date', htmlspecialchars($items[$i]->modified), JText::_('DATE_FORMAT_LC2'), $tzoffset) : '';

                // get realty link
                $items[$i]->realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($items[$i]->id . ':' . $items[$i]->alias));
            }
        }
        return $items;
    }

    function _buildContentOrderBy() {
        // Get the page/component configuration
        $app = JFactory::getApplication();
        $params = $app->getParams();
        $orderby_type = $params->def('orderby_type', 'alpha');
        $orderby_category = $params->def('orderby_category', 'alpha');
        $orderby_pri = $params->def('orderby_pri', 'rdate');
        $orderbyType = JUX_Real_EstateHelperQuery::orderbyType($orderby_type);
        $orderbyCategoty = JUX_Real_EstateHelperQuery::orderbyCategory($orderby_category);
        $primary = JUX_Real_EstateHelperQuery::orderbyPrimary($orderby_pri);

        $orderby = "$primary $orderbyCategoty $orderbyType";
        return $orderby;
    }

    //get an Category object by id
    function getCategory() {

        $data = JUX_Real_EstateHelperQuery::getInfoCategory($this->getState('list.cat_id'));

        return $data;
    }

}