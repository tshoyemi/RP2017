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

jimport('joomla.application.component.controllerform');

/**
 * @package     Joomla.Administrator
 * @subpackage  com_jux_real_estate
 * @since       1.6
 */
class JUX_Real_EstateControllerAgent extends JControllerForm {

    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_AGENT';

    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowAdd($data = array()) {
	$user = JFactory::getUser();
	$allow = null;
	$allow = $user->authorise('core.create', 'com_jux_real_estate.agent');
	if ($allow === null) {
	    return parent::allowAdd($data);
	} else {
	    return $allow;
	}
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowEdit($data = array(), $key = 'id') {
	$user = JFactory::getUser();
	// Initialise variables.
	$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

	// Check general edit permission first.
	if ($user->authorise('core.edit', 'com_jux_real_estate.agent.' . $recordId)) {
	    return true;
	}

	// Since there is no asset tracking, revert to the component permissions.
	return parent::allowEdit($data, $key);
    }

    public function checkUserAgent() {
	// Check for request forgeries
	JRequest::checkToken() or die('Invalid Token');
	$user_id = JRequest::getInt('user_id');
	$agent_id = JRequest::getInt('agent_id', 0);
	$user = &JFactory::getUser($user_id);
	if (!$user_id)
	    return false;

	$db = JFactory::getDbo();
	$query = $db->getQuery(true);

	$query->select('id');
	$query->from('#__re_agents');
	$query->where('user_id = ' . (int) $user_id);
	$query->where('id != ' . (int) $agent_id);
	$db->setQuery($query);
	@ob_end_clean();
	ob_start();
	$result = $db->loadResult();
	if ($result)
	    echo $result;
	else
	    echo $user->email;
	exit();
    }

}