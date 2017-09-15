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

class JFormFieldJUXGmap extends JFormField {

    protected $type = 'JUXGmap';

    protected function getInput() {
	$document = JFactory::getDocument();

	$frontend = (isset($this->element['frontend']) && $this->element['frontend']) ? true : false;
	if ($frontend) {
	    $configs = JUX_Real_EstateFactory::getConfiguration();
	} else {
	    $configs = JUX_Real_EstateFactory::getConfigs();
	}

	if ($configs->get('gmapapikey')) {
	    $document->addScript("https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=" . $configs->get('gmapapikey') . "&amp;sensor=false");
	} else {
	    $document->addScript("https://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false");
	}

	$document->addScript(JURI::root() . "administrator/components/com_jux_real_estate/assets/js/realtysmap.js");

	$width = ($this->element['width']) ? $this->element['width'] : '100%';
	$height = ($this->element['width']) ? $this->element['height'] : '300px';
	$border = ($this->element['border']) ? $this->element['border'] : '#666';

	echo '<div id="map_canvas" style="width: 100%; height: 400px">&nbsp;</div><br>';
	echo '<div id="search_type">
			<fieldset class="radio btn-group" id="jform_search_google_map">
			<input id="searchTextField" type="text" size="60"
			placeholder="' . JText::_('COM_JUX_REAL_ESTATE_ENTER_A_LOCATION') . '" /><br/>
						</fieldset>
             </div>';
    }

}