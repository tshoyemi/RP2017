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
require_once (JPATH_SITE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'html.helper.php' );

/**
 * Form Field class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJUXCompany extends JFormField {

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'JUXCompany';

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
	$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '1';

	// Initialize JavaScript field attributes.
	$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

	$data = $this->getData();

	return JHTML::_('select.genericlist', $data, $this->name, $attr, 'value', 'text', $this->value, $this->id, true);

	return implode($html);
    }

    /**
     * Method to get list of companies.
     *
     * @return	array
     */
    protected function getData() {
	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, `name` AS text');
	$query->from('#__re_companies');
	$query->where('published = 1');
	$query->order('name');

	$db->setQuery($query);
	$sec = array();
	$sec[] = JHTML::_('select.option', '', '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_COMPANY') . ' -', 'value', 'text');
	try {
	    $companies = $db->loadObjectList();
	    if (count($companies)) {
		foreach ($companies as $company) {
		    $sec[] = JHTML::_('select.option', $company->value, $company->text, 'value', 'text');
		}
	    }
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}

	return $sec;
    }

    public function getOptions() {
	$options = array();
	$options = JUX_Real_EstateHelperQuery::getCompanyList('', '', '', true);
	return $options;
    }

}
