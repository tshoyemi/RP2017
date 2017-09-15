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

jimport('joomla.application.component.modelitem');

/**
 * JUX_Real_Estate Component Realty Modelitem
 *
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @since 3.0
 */
class JUX_Real_EstateModelRealty extends JModelItem {

    /**
     * Model context string.
     *
     * @var		string
     */
    protected $_context = 'com_jux_real_estate.realty';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
	$app = JFactory::getApplication('site');
	$user = JFactory::getUser();

	// Load state from the request.
	$pk = JRequest::getInt('id');
	$this->setState('realty.id', $pk);

	if ((!$user->authorise('core.edit.state', 'com_jux_real_estate')) && (!$user->authorise('core.edit', 'com_jux_real_estate')) && (!$user->authorise('core.edit.own', 'com_jux_real_estate'))) {
	    // filter on published for those who do not have edit or edit.state or edit.own rights.
	    $this->setState('filter.published', 1);
	} else {
	    $this->setState('filter.published', array(0, 1));
	}

	// filter by language
	$this->setState('filter.language', $app->getLanguageFilter());
    }

    /**
     * Method to get realty data.
     *
     * @param	integer	The id of the realty.
     *
     * @return	mixed	Menu item data object on success, false on failure.
     */
    public function &getItem($pk = null) {

	// Initialise variables.
	$pk = (!empty($pk)) ? $pk : (int) $this->getState('realty.id');
	if ($this->_item === null) {
	    $this->_item = array();
	}

	if (!isset($this->_item[$pk])) {
	    $db = $this->getDbo();
	    $query = $db->getQuery(true);

	    $user = JFactory::getUser();
	    $groups = $user->getAuthorisedViewLevels();

	    // Filter by start and end dates.
	    $nullDate = $db->Quote($this->_db->getNullDate());
	    $timeZone = JFactory::getConfig()->get('offset');
	    $date = JFactory::getDate('now', $timeZone);
	    $nowDate = $db->Quote($date->toSql(true));
	    $query->select('r.*,r.alias as realty_alias, c.title AS category, t.title AS type, c.alias as cat_alias, a.alias as agent_alias')
		    ->from('#__re_realties AS r')
		    ->leftJoin('#__re_types as t ON t.id = r.type_id')
		    ->leftJoin('#__categories as c ON c.id = r.cat_id')
		    ->leftJoin('#__re_agents as a on a.id = r.agent_id')
		    ->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
		    ->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')')
		    ->where('r.published = 1 AND c.published = 1 AND a.published = 1')
		    ->where('r.approved = 1')
		    ->where('r.id = ' . $pk);
	    if (is_array($groups) && !empty($groups)) {
		$query->where('r.access IN (' . implode(",", $groups) . ')')
			->where('c.access IN (' . implode(",", $groups) . ')');
	    }
	    if ($this->getState('list.agent_id')) {
		$query->where('(r.agent_id = ' . (int) $this->getState('list.agent_id') . ')');
	    }
	    // Filter by language
	    if ($this->getState('filter.language')) {
		$query->where('r.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
	    }

	    $db->setQuery($query);

	    try {
		$data = $db->loadObject();

		// set count
		if (is_object($data)) {
		    $configs = JUX_Real_EstateFactory::getConfiguration();
		    $config = JFactory::getConfig();
		    $tzoffset = $config->get('config.offset');
		    $hide_round = 3;

		    $data->street_address = JUX_Real_EstateHTML::getStreetAddress($configs, $data->title, $data->address);
		    $data->baths = (!$configs->get('baths_fraction')) ? round($data->baths) : $data->baths;
		    // get realty link
		    $data->realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($data->id . ':' . $data->alias));
		    $data->formattedprice = JUX_Real_EstateHTML::getFormattedPrice($data->price, $data->price_freq, $data->currency_id, false, $data->call_for_price, $data->price2);
		    $data->formattedsqft = number_format($data->sqft);

		    # Check if new or updated
		    $data->new = JUX_Real_EstateHTML::isNew($data->date_created, $configs->get('new_days'));
		    $data->updated = JUX_Real_EstateHTML::isNew($data->modified, $configs->get('updated_days'));

		    # Get last modified date if available
		    $data->last_updated = ($data->modified != '0000-00-00 00:00:00') ? JHTML::_('date', htmlspecialchars($data->modified), JText::_('DATE_FORMAT_LC2'), $tzoffset) : '';
		    $last_updated = ($data->last_updated && $data->updated) ? JText::_('COM_JUX_REAL_ESTATE_LAST_MODIFIED') . ': ' . $data->last_updated : '';
		}

		if ($error = $db->getErrorMsg()) {
		    throw new Exception($error);
		}

		if (empty($data)) {
		    return JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_REALTY_NOT_FOUND'));
		}

		$this->_item[$pk] = $data;
	    } catch (JException $e) {
		if ($e->getCode() == 404) {
		    // Need to go thru the error handler to allow Redirect to work.
		    JError::raiseError(404, $e->getMessage());
		} else {
		    $this->setError($e);
		    $this->_item[$pk] = false;
		}
	    }
	}

	return $this->_item[$pk];
    }

    function getAmenities() {
	return JUX_Real_EstateHelperQuery::getRealtyAmenities($this->getState('realty.id'));
    }

    public static function getItemRelate($agent_id, $realty_id) {

	$user = JFactory::getUser();
	$groups = $user->getAuthorisedViewLevels();

	$db = JFactory::getDbo();
	$query = $db->getQuery(true);

	$nullDate = $db->Quote($db->getNullDate());
	$timeZone = JFactory::getConfig()->get('offset');
	$date = JFactory::getDate('now', $timeZone);
	$nowDate = $db->Quote($date->toSql(true));

	$query->select('r.*');
	$query->from('#__re_realties as r');
	$query
		->leftJoin('#__categories as c ON c.id = r.cat_id')
		->where('r.agent_id =' . $agent_id)
		->where('r.id !=' . $realty_id)
		->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')')
		->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')')
		->where('r.published = 1 AND c.published = 1 AND r.approved = 1');
	if (is_array($groups) && !empty($groups)) {
	    $query->where('r.access IN (' . implode(",", $groups) . ')')
		    ->where('c.access IN (' . implode(",", $groups) . ')');
	}

	$db->setQuery($query);
	$row = $db->loadObjectList();
	if ($row) {
	    foreach ($row as $key => $val) {
		$row[$key]->link = JUX_Real_EstateHelperRoute::getRealtyRoute($val->id . '-' . $val->alias);
	    }
	}
	$db->getQuery($query);
	return $row;
    }

    function GetExtraField() {
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	$query
		->select('f.title, fv.field_value')
		->from('#__re_fields as f')
		->join('LEFT', '#__re_field_value as fv ON f.id = fv.field_id')
		->where('fv.realty_id = ' . $_REQUEST['id']);
	$db->setQuery($query);
	$results = $db->loadObjectList();

	return $results;
    }

    // Increase hits when view detail realty
    function getJuxCount() {
	$db = $this->getDbo();
	$query = $db->getQuery(true);
	$query
		->update('#__re_realties')
		->set('count = count + 1')
		->where('id = ' . $_REQUEST['id']);
	$db->setQuery($query);
	$db->execute();
    }
    function getType(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('t.*');
        $query->from('#__re_types AS t');
        $query->join('LEFT','#__re_realties AS r ON t.id=r.type_id');
        $query->where('r.id = ' . $_REQUEST['id']);
        $query->where('t.published = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        
        return $results;

    }

}