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
require_once (JPATH_SITE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'html.helper.php' );

class JFormFieldJUXRealty extends JFormField {

    protected $type = 'JUXRealty';

    protected function getInput() {
	$isagent = ($this->element['isagent']) ? true : false;

	$rows = JUX_Real_EstateHelperQuery::getRealtiesList('', '', '', true, $isagent);
	return JHtml::_('select.genericlist', $rows, $this->name, 'class="inputbox" style="width: 250px;"', 'value', 'text', $this->value, $this->id);
    }

    public function getOptions() {

	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, title AS text');
	$query->from('#__re_realties');
	$query->where('published = 1');
	$query->order('title');

	$db->setQuery($query);

	$sec = array();

	try {
	    $agents = $db->loadObjectList();
	    if (count($agents)) {
		foreach ($agents as $agent) {
		    $sec[] = JHTML::_('select.option', $agent->value, $agent->text, 'value', 'text');
		}
	    }
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}

	return $sec;
    }

}