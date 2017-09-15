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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldJUXAmenityCat extends JFormField {

    protected $type = 'JUXAmenityCat';

    protected function getInput() {
	$amenity_cats = array('' => JText::_('COM_JUX_REAL_ESTATE_SELECT'),
	    0 => JText::_('COM_JUX_REAL_ESTATE_GENERAL_AMENITIES'),
	    1 => JText::_('COM_JUX_REAL_ESTATE_INTERIOR_AMENITIES'),
	    2 => JText::_('COM_JUX_REAL_ESTATE_EXTERIOR_AMENITIES'));

	$options = array();
	foreach ($amenity_cats as $key => $value) {
	    $options[] = JHTML::_('select.option', $key, $value);
	}

	return JHtml::_('select.genericlist', $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
    }

    public static function getOptions() {
	$amenity_cats = array(0 => JText::_('COM_JUX_REAL_ESTATE_GENERAL_AMENITIES'),
	    1 => JText::_('COM_JUX_REAL_ESTATE_INTERIOR_AMENITIES'),
	    2 => JText::_('COM_JUX_REAL_ESTATE_EXTERIOR_AMENITIES'));

	$options = array();
	foreach ($amenity_cats as $key => $value) {
	    $options[] = JHTML::_('select.option', $key, $value);
	}

	return $options;
    }

}