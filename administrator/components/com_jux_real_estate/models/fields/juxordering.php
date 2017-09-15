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

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of categories
 *
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @since		3.0
 */
class JFormFieldJUXOrdering extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'JUXOrdering';

    /**
     * Method to get the field input markup.
     *
     * @return	string	The field input markup.
     * @since	1.6
     */
    protected function getInput() {
	// Initialize variables.
	$html = array();
	$attr = '';

	// Initialize some field attributes.
	$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
	$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
	$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

	// Initialize JavaScript field attributes.
	$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

	// Build the query for the ordering list.
	$tablename = $this->element['table'] ? $this->element['table'] : JRequest::getVar('view');
	if ($tablename == 'plans') {
	    $query = 'SELECT ordering AS value, name AS text' .
		    ' FROM #__re_' . $tablename;
	    ' ORDER BY ordering';
	} else if ($tablename == 'agents') {
	    $query = 'SELECT ordering AS value, CONCAT_WS(" ", username) AS text' .
		    ' FROM #__re_' . $tablename;
	    ' ORDER BY ordering';
	} else if ($tablename == 'companies') {
	    $query = 'SELECT ordering AS value, name AS text' .
		    ' FROM #__re_' . $tablename;
	    ' ORDER BY ordering';
	} else {
	    $query = 'SELECT ordering AS value, title AS text' .
		    ' FROM #__re_' . $tablename;
	    ' ORDER BY ordering';
	}

	// Create a read-only list (no name) with a hidden input to store the value.
	if ((string) $this->element['readonly'] == 'true') {
	    $html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $this->value ? 0 : 1);
	    $html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
	}
	
	// Create a regular list.
	else {
	    $html[] = JHtml::_('list.ordering', $this->name, $query, trim($attr), $this->value, $this->value ? 0 : 1);
	}

	return implode($html);
    }

}
