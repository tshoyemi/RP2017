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
class JFormFieldJUXBaths extends JFormField {

    /**
     * The form field Baths.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'JUXBaths';

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
     * Method to get list of countries.
     *
     * @return	array
     */
    protected function getData() {

	$sec = array();
	$sec[] = JHTML::_('select.option', '', '- ' . JText::_('COM_JUX_REAL_ESTATE_BATHS') . ' -', 'value', 'text');
	$sec[] = JHtml::_('select.options', JUX_Real_EstateHelper::getBathsOptions(), 'value', 'text');
	return $sec;
    }

}
