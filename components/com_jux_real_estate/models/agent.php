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

require_once dirname(__FILE__) . '/list.php';

/**
 * JUX_Real_Estate Component - Agent Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 * @since        3.0
 */
class JUX_Real_EstateModelAgent extends JUX_Real_EstateModelList {

    /**
     * Model context string.
     *
     * @var        string
     */
    protected $_context = 'com_jux_real_estate.agent';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState() {
	$app = JFactory::getApplication('site');

	parent::populateState();
	$params = $this->state->params;
	// Add archive properties
	// Load state from the request.
	$pk = JRequest::getInt('agentid');
	$this->setState('list.agent_id', $pk);
	// Get list limit

	$itemid = JRequest::getInt('Itemid', 0);
	$limit = $app->getUserStateFromRequest('com_jux_real_estate.agent.list.limit', 'limit', $app->getCfg('display_num', 8));
	$this->setState('list.limit', $limit);

	$offset = JRequest::getUInt('limitstart');
	$this->setState('list.offset', $offset);

	// Get the pagination request variables

	$limitstart = JRequest::getInt('limitstart', 0);
	// In case limit has been changed, adjust limitstart accordingly
	$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

	$this->setState('limitstart', $limitstart);
    }

    /**
     * @return    JDatabaseQuery
     */
    function getListQuery() {
	// Create a new query object.
	$query = parent::getListQuery();
	if ($this->getState('list.agent_id')) {
	    $query->where('a.agent_id = ' . (int) $this->getState('list.agent_id') . ' OR a.user_id=(SELECT user_id FROM #__re_agents WHERE id = ' . (int) $this->getState('list.agent_id') . ')');
	}
	return $query;
    }

    //get an Agent object by agentid
    function getAgent() {
	if (empty($data)) {
	    $db = $this->getDbo();
	    $query = $db->getQuery(true);
	    $query->select('a.*');
	    $query->from('#__re_agents AS a');
	    $query->where('published = 1 AND id = ' . $this->getState('list.agent_id'));
	    $db->setQuery($query);
	    $data = $db->loadObject();
	}
	return $data;
    }

}