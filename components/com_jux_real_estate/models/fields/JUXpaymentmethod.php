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
class JFormFieldJUXPaymentMethod extends JFormField {

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'JUXPaymentMethod';

    /**
     * Method to get the radio button field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput() {
	// Initialize variables.
	$html = array();
	// Initialize some field attributes.
	$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
	$disabled = !empty($payment_method->disable) ? ' disabled="disabled"' : '';
	// To avoid user's confusion, readonly="true" should imply disabled="true".
	if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
	    $disabled = ' disabled="disabled"';
	}

	// Initialize some JavaScript option attributes.
	$onclick = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
	// Get the field options.
	if ($payment_methods = $this->getPayments()) {
	    // Initialize some field attributes.
	    $class = $this->element['class'] ? ' class="radio ' . (string) $this->element['class'] . '"' : ' class="radio"';

	    // Start the radio field output.
	    $html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

	    // Build the radio field output.
	    foreach ($payment_methods as $i => $payment_method) {
		// Initialize some option attributes.
		$checked = ((string) $payment_method['code'] == (string) $this->value) ? ' checked="checked"' : '';
		if (!$i && !$checked) {
		    $checked = ' checked="checked"';
		}

		$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
			. htmlspecialchars($payment_method['code'], ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>';

		$html[] = '<label for="' . $this->id . $i . '"' . $class . '>'
			. JText::alt($payment_method['name'], preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</label>';
	    }

	    // End the radio field output.
	    $html[] = '</fieldset>';
	}

	return implode($html);
    }

    /**
     * Get all payment plugins
     */
    protected function getPayments() {
	$jspayments = JPluginHelper::getPlugin('jspayment');
	$dispatcher = JDispatcher::getInstance();
	JPluginHelper::importPlugin('jspayment');
	if (count($jspayments)) {
	    $data = $dispatcher->trigger('onPaymentInfo');

	    return $data;
	}
	return false;
    }

}
