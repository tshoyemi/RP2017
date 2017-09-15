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

class JFormFieldJUXFields extends JFormField {

    protected $type = 'JUXFields';

    protected function getInput() {

	$document = JFactory::getDocument();
	$JUXFields = new JUX_Real_EstateFields();

	if ($JUXFields->getTotal()) {
	    $customField = true;
	    if ($this->form->getValue('id'))
		$fields = $JUXFields->renderCustomFieldsEdit($this->form->getValue('id'));
	    else
		$fields = $JUXFields->renderCustomFields();
	} else {
	    $customField = false;
	}
	if ($customField) {
	    echo $fields;
	}
    }

}

