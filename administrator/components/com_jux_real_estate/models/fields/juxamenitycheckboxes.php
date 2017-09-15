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

defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldJUXAmenityCheckboxes extends JFormField {

    protected $type = 'JUXAmenityCheckboxes';
    protected $forceMultiple = true;

    protected function getInput() {
	// Initialize variables.
	$html = array();

	// Initialize some field attributes.
	$class = $this->element['class'] ? ' class="checkboxes ' . (string) $this->element['class'] . '"' : ' class="checkboxes"';

	// Start the checkbox field output.
	$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

	// Get the field options.
	$options = $this->getOptions($this->element['category']);

	// Build the checkbox field output.
	$html[] = '<div style="list-style: none;">';
	if (count($options)) {
	    foreach ($options as $i => $option) {

		// Initialize some option attributes.
		$checked = (in_array((string) $option->value, (array) $this->value) ? ' checked="checked"' : '');
		$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
		$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';

		// Initialize some JavaScript option attributes.
		$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

		$html[] = '<div>';
		$html[] = '<input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' .
			' value="' . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"'
			. $checked . $class . $onclick . $disabled . '/>';

		$html[] = '&nbsp;' . '<label for="' . $this->id . $i . '" style="margin-left: 2px;">' . JText::_($option->text) . '</label>';
		$html[] = '</div>';
	    }
	} else {
	    $html[] = '<div>' . JText::_('COM_JUX_REAL_ESTATE_NO_RESULTS') . '</div>';
	}
	$html[] = '</div>';

	// End the checkbox field output.
	$html[] = '</fieldset>';

	return implode($html);
    }

    protected function getOptions($cat = '') {
	// Initialize variables.
	$options = array();

	$db = JFactory::getDBO();
	$query = $db->getQuery(true);

	$query->select('id as value, title as text');
	$query->from('#__re_amenities');
	if ($cat)
	    $query->where('cat = ' . (int) $cat);
	$query->order('title ASC');

	$db->setQuery($query);
	$amen_array = $db->loadObjectList();

	foreach ($amen_array as $option) {
	    // Create a new option object based on the <option /> element.
	    $tmp = JHtml::_('select.option', (string) $option->value, trim((string) $option->text));

	    // Set some option attributes.
	    $tmp->class = 'inputbox';

	    // Add the option object to the result set.
	    $options[] = $tmp;
	}

	reset($options);

	return $options;
    }

}
