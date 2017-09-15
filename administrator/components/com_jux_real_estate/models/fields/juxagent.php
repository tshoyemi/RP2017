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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');
require_once (JPATH_SITE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'html.helper.php' );

/**
 * Form Field class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJUXAgent extends JFormField {

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'JUXAgent';

    /**
     * Method to get the field input markup.
     *
     * @return  string   The field input markup.
     *
     * @since   11.1
     */
    protected function getInput() {
	// Initialize variables.

	$attr = '';

	// Initialize some field attributes.
	$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
	$attr .= $this->element['multiple'] ? ' multiple="multiple"' : '';
	$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '1';

	// Initialize JavaScript field attributes.
	$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

	$data = $this->getData();

	return JHTML::_('select.genericlist', $data, $this->name, $attr, 'value', 'text', $this->value, $this->id);
    }

    /**
     * Method to get list of countries.
     *
     * @return	array
     */
    public function getData() {
	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);
	$user = JFactory::getUser();
	$user_id = $user->id;
	$isAdmin = $user->get('isRoot');
	$sec = array();
	$query->select('`id` AS value, CONCAT_WS(" ", username) AS text');
	$query->from('#__re_agents');
	$query->where('published = 1');


	if ($isAdmin) {
	    $sec[] = JHTML::_('select.option', '', '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_AN_AGENT') . ' -', 'value', 'text');
	}
	else
	    $query->where('user_id =' . (int) $user_id);
	//
	$query->order('username');
	$db->setQuery($query);

	try {
	    $agents = $db->loadObjectList();
	    if (count($agents)) {
		foreach ($agents as $agent) {
		    $sec[] = JHTML::_('select.option', $agent->value, $agent->text, 'value', 'text');
		}
	    }
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}

	return $sec;
    }

    public static function getOptions() {

	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, CONCAT_WS(" ", username) AS text');
	$query->from('#__re_agents');
	$query->where('published = 1');
	$query->order('username');

	$db->setQuery($query);

	$sec = array();

	try {
	    $agents = $db->loadObjectList();
	    if (count($agents)) {
		foreach ($agents as $agent) {
		    $sec[] = JHTML::_('select.option', $agent->value, $agent->text, 'value', 'text');
		}
	    }
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}

	return $sec;
    }

}
