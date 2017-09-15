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

// import Joomla formfield calendar
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('calendar');

/**
 * Form Field class for the Joomla Platform.
 *
 * Provides a pop up date picker linked to a button.
 * Optionally may be filtered to use user's or server's time zone.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJUXDonationCalendar extends JFormFieldCalendar {

    public function __construct($form = null) {
	parent::__construct($form);
    }

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    public $type = 'JUXDonationCalendar';

    /**
     * Method to get the field input markup.
     *
     * @return  string   The field input markup.
     *
     * @since   11.1
     */
    protected function getInput() {
	// Initialize some field attributes.
	$format = $this->element['format'] ? (string) $this->element['format'] : '%Y-%m-%d';

	// Specific now when default value is "now".
	if (strtoupper($this->value) == 'NOW') {
	    $this->value = '2012-01-01'; //strftime($format);
	}

	return parent::getInput();
    }

}
