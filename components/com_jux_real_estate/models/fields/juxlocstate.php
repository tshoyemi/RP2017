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

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJUXLocstate extends JFormField {

    /**
     * The form field JUXLocstate.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'JUXLocstate';

    /**
     * Method to get the field input markup.
     *
     * @return  string   The field input markup.
     *
     * @since   11.1
     */
    protected function getInput() {
	// Initialize variables.
	$html = array();
	$attr = '';

	// Initialize some field attributes.
	$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
//	$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '1';

	$country_id = $this->form->getValue('country_id');
	// Initialize JavaScript field attributes.
	$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

	if ($country_id) {
	    $data = $this->getData($country_id);
	} else {
	    $data = $this->getData();
	}


	return '<span id="ajax-container">' . JHTML::_('select.genericlist', $data, $this->name, $attr, 'value', 'text', $this->value, $this->id, true) . '</span>';

	return implode($html);
    }

    /**
     * Method to get list of countries.
     *
     * @return	array
     */
    protected function getData($country_id = 0) {
	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, `state_name` AS text');
	$query->from('#__re_states');
	if ($country_id) {
	    $query->where('country_id = ' . $country_id);
	}
	$query->order('id');
	$db->setQuery($query);
	$sec = array();
	$sec[] = JHTML::_('select.option', '', '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_STATE') . ' -', 'value', 'text');
	try {
	    $countries = $db->loadObjectList();
	    if (count($countries)) {
		foreach ($countries as $country) {
		    $sec[] = JHTML::_('select.option', $country->value, $country->text, 'value', 'text');
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
