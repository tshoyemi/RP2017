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

// no direct access
defined('_JEXEC') or die('Restricted access');

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldJUXRealty extends JFormFieldList {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    public $type = 'jux_real_estate';

    /**
     * fetch Element
     */
    function getInput() {
	$db = JFactory::getDBO();

	$realty = array();
        $realty[] = new stdClass();
	$realty[0]->id = '0';
	$realty[0]->title = JText::_('COM_JUX_REAL_ESTATE_SELECT_A_REALTY');

	$query = 'SELECT a.id, a.title FROM #__re_realties AS a'
		. ' WHERE a.published = 1 ORDER BY a.ordering';
	$db->setQuery($query);
	$lists = $db->loadObjectList();

	if (count($lists)) {
	    foreach ($lists as $list) {
		array_push($realty, $list);
	    }
	}

	$out = JHTML::_('select.genericlist', $realty, $this->name, 'class="inputbox"', 'id', 'title', $this->value, $this->id);
	return $out;
    }

}

?>
