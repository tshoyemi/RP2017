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
if (!class_exists('JFormFieldFile')) {
    require_once JPATH_ROOT . '/libraries/joomla/form/fields/file.php';
}

/**
 * Extend the field for files.
 *
 */
class JFormFieldJUXFile extends JFormFieldFile {

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    public $type = 'JUXFile';

    /**
     * Override getInput to change the input name. Because we changed the name here,
     * you'll have to manually get the field value later (on the save method of controller).
     *
     */
    protected function getInput() {
	// Initialize some field attributes.
	$accept = $this->element['accept'] ? ' accept="' . (string) $this->element['accept'] . '"' : '';
	$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
	$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
	$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

	// Initialize JavaScript field attributes.
	$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

	$html = '';
	if (!empty($this->value)) {
//			$html	= '<img width="48" height="48" src="'.JURI::base().$this->value.'" />';
	    $html .= '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '" />';
	}
	$html .= '<input type="file" name="' . $this->getAltName($this->element['name']) . '" id="' . $this->id . '"' . ' value=""' . $accept . $disabled . $class . $size
		. $onchange . ' />';

	return $html;
    }

    /**
     * Get the name with no [] because for unknow reason, input file type doesn't like it.
     *
     */
    protected function getAltName($fieldName = 'jux_file') {
	$name = '';

	// If there is a form control set for the attached form add it first.
	if ($this->formControl) {
	    $name .= $this->formControl;
	}

	// If the field is in a group add the group control to the field name.
	if ($this->group) {
	    // If we already have a name segment add the group control as another level.
	    $groups = explode('.', $this->group);
	    if ($name) {
		foreach ($groups as $group) {
		    $name .= '_' . $group;
		}
	    } else {
		$name .= array_shift($groups);
		foreach ($groups as $group) {
		    $name .= '_' . $group;
		}
	    }
	}

	// If we already have a name segment add the field name as another level.
	if ($name) {
	    $name .= '_' . $fieldName;
	} else {
	    $name .= $fieldName;
	}

	// If the field should support multiple values add the final array segment.
	if ($this->multiple) {
	    $name .= '[]';
	}

	return $name;
    }

}
