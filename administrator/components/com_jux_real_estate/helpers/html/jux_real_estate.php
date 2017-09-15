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

/**
 * Utility class for creating HTML lists for JUX_Real_Estate component
 * @package		JUX_Real_Estate
 * @subpackage	Helper
 * @since		3.0
 */
abstract class JHtmlJUX_Real_Estate {

    // template folder
    function template($name = 'config[template]', $active = 'default', $javascript = '', $size = 1) {
	$folders = JFolder::folders(JPATH_ROOT . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'templates');

	$options = array();
	foreach ($folders as $folder) {
	    $options[] = JHTML::_('select.option', $folder, $folder);
	}

	return JHTML::_('select.genericlist', $options, $name, 'class="inputbox" size="' . $size . '" ' . $javascript, 'value', 'text', $active);
    }

    /**
     * @param	int $value	The requiredfield value
     * @param	int $i
     * @param	bool $canChange Whether the value can be changed or not
     *
     * @return	string	The anchor tag to toggle approve/unapprove agents.
     * @since	1.6
     */
    static function requiredfield($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('publish_x.png', 'fields.required', 'COM_JUX_REAL_ESTATE_UN_REQUIRE', 'COM_JUX_REAL_ESTATE_REQUIRED'),
	    1 => array('tick.png', 'fields.unrequired', 'COM_JUX_REAL_ESTATE_REQUIRE', 'COM_JUX_REAL_ESTATE_UN_REQUIRE')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);
	$html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}
	return $html;
    }

    /**
     * @param	int $value	The accessfield value
     * @param	int $i
     * @param	bool $canChange Whether the value can be changed or not
     *
     * @return	string	The anchor tag to toggle approve/unapprove agents.
     * @since	1.6
     */
    static function accessfield($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('publish_x.png', 'fields.access', 'COM_JUX_REAL_ESTATE_NOT_ALLOW', 'COM_JUX_REAL_ESTATE_ALLOW'),
	    1 => array('tick.png', 'fields.unaccess', 'COM_JUX_REAL_ESTATE_ALLOW', 'COM_JUX_REAL_ESTATE_NOT_ALLOW')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);
	$html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}
	return $html;
    }

    /**
     * @param	int $value	The searchfield value
     * @param	int $i
     * @param	bool $canChange Whether the value can be changed or not
     *
     * @return	string	The anchor tag to toggle approve/unapprove agents.
     * @since	1.6
     */
    static function searchfield($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('publish_x.png', 'fields.search', 'COM_JUX_REAL_ESTATE_NOT_SEARCH', 'COM_JUX_REAL_ESTATE_ALLOW'),
	    1 => array('tick.png', 'fields.unsearch', 'COM_JUX_REAL_ESTATE_SEARCH_FIELD', 'COM_JUX_REAL_ESTATE_NOT_SEARCH')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);
	$html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}
	return $html;
    }

    /**
     * Display the category list
     * @param $name Name of the list
     * @param $active Active list item
     * @param $javascript Javascript of the list
     * @param $size Size of the list
     * @return string
     */
    public static function category() {

	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, `title` AS text');
	$query->from('#__categories');
	$query->where('published = 1');
	$query->where('extension ="com_jux_real_estate"');
	$query->order('id');

	$db->setQuery($query);

	try {
	    $rows = $db->loadObjectList();
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}
	$sec = array();

	if (count($rows)) {

	    foreach ($rows as $row) {
		$sec[] = JHTML::_('select.option', $row->value, $row->text, 'value', 'text');
	    }
	}
	return $sec;
    }

    public static function type() {

	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, `title` AS text');
	$query->from('#__re_types');
	$query->where('published = 1');
	$query->order('id');

	$db->setQuery($query);

	try {
	    $rows = $db->loadObjectList();
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}

	$sec = array();
	if (count($rows)) {
	    foreach ($rows as $row) {
		$sec[] = JHTML::_('select.option', $row->value, $row->text, 'value', 'text');
	    }
	}

	return $sec;
    }

  

    function agent() {

	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, `title` AS text');
	$query->from('#__re_agents');
	$query->where('published = 1');
	$query->order('id');

	$db->setQuery($query);

	try {
	    $rows = $db->loadObjectList();
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}

	$sec = array();
	if (count($rows)) {
	    foreach ($rows as $row) {
		$sec[] = JHTML::_('select.option', $row->value, $row->text, 'value', 'text');
	    }
	}

	return $sec;
    }

    public static function country($name = 'country', $active = 0, $class = null, $javascript = '', $size = 1, $list = false) {

	$db = JFactory::getDBO();
	$query = 'SELECT `id` AS value, `name` AS text'
		. ' FROM #__re_countries AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.id'
	;
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	if ($list) {
	    return $rows;
	} else {
	    $options = array();
	    $options[] = JHTML::_('select.option', 0, '-- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_COUNTRY') . ' --', 'value', 'text');
	    foreach ($rows as $row) {
		$options[] = JHTML::_('select.option', $row->value, JText::_($row->text), 'value', 'text');
	    }

	    return JHTML::_('select.genericlist', $options, $name, 'class="inputbox ' . $class . '" size="' . $size . '" ' . $javascript, 'value', 'text', $active);
	}
    }

    /**
     * @param	int $value	The approved value
     * @param	int $i
     * @param	bool $canChange Whether the value can be changed or not
     *
     * @return	string	The anchor tag to toggle approved/unapproved agents.
     * @since	1.6
     */
    static function approved($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('pending.png', 'realties.approved', 'COM_JUX_REAL_ESTATE_PENDING', 'COM_JUX_REAL_ESTATE_APPROVE'),
	    1 => array('tick.png', 'realties.rejected', 'COM_JUX_REAL_ESTATE_APPROVED', 'COM_JUX_REAL_ESTATE_REJECT'),
	    -1 => array('publish_x.png', 'realties.approved', 'COM_JUX_REAL_ESTATE_REJECT', 'COM_JUX_REAL_ESTATE_APPROVE')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);

	$html = '';
	if ($value == 0) {
	    $html = '<img src="' . JURI::base() . 'components/com_jux_real_estate/assets/img/' . $state[0] . '" alt="' . JText::_($state[2]) . '"  />';
	} else {
	    $html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	}
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}

	return $html;
    }

    /**
     * @param	int $value	The approve value
     * @param	int $i
     * @param	bool $canChange Whether the value can be changed or not
     *
     * @return	string	The anchor tag to toggle approve/unapprove agents.
     * @since	1.6
     */
    static function approve($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('publish_x.png', 'agents.approve', 'COM_JUX_REAL_ESTATE_UNAPPROVE_ITEM', 'COM_JUX_REAL_ESTATE_APPROVE_ITEM'),
	    1 => array('tick.png', 'agents.unapprove', 'COM_JUX_REAL_ESTATE_APPROVED', 'COM_JUX_REAL_ESTATE_UNAPPROVE')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);
	$html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}
	return $html;
    }

    /**
     * @param	int $value	The approved value
     * @param	int $i
     * @param	bool $canChange Whether the value can be changed or not
     *
     * @return	string	The anchor tag to toggle approved/unapproved contacts.
     * @since	1.6
     */
    static function sale($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('publish_x.png', 'realties.sale', 'COM_JUX_REAL_ESTATE_SELLING', 'COM_JUX_REAL_ESTATE_SOLD'),
	    1 => array('tick.png', 'realties.sold', 'COM_JUX_REAL_ESTATE_SOLD', 'COM_JUX_REAL_ESTATE_SELLING')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);
	$html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}

	return $html;
    }

    function catSelectList($tag, $attrib, $sel = null, $listonly = false) {
	$db = JFactory::getDbo();
	$cats = array();
	$cats[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_CATEGORY'), "value", "text");

	$cats = array_merge($cats, JHtmlJUX_Real_Estate::subcatSelect(0, ""));
	$sel = explode(',', $sel);

	if ($listonly) {
	    return $cats;
	} else {
	    return JHTML::_('select.genericlist', $cats, $tag, $attrib, "value", "text", $sel);
	}
    }

    function subcatSelect($parent, $prefix) {
	$db = JFactory::getDbo();
	$options = array();

	$query = JUX_Real_EstateHelperQuery::getCategories($parent);
	$db->setQuery($query);

	$result = $db->loadObjectList();
	$total = count($result);

	for ($i = 0; $i < ($total - 1); $i++) {
	    $options[] = JHTML::_('select.option', $result[$i]->id, $prefix . "- " . $result[$i]->title, "value", "text");
	    $options = array_merge($options, JHtmlJUX_Real_Estate::subcatSelect($result[$i]->id, $prefix . "- "));
	}

	if ($total > 0) {
	    $options[] = JHTML::_('select.option', $result[$total - 1]->id, $prefix . "- " . $result[$total - 1]->title, "value", "text");
	    $options = array_merge($options, JHtmlJUX_Real_Estate::subcatSelect($result[$total - 1]->id, $prefix . "- "));
	}

	return $options;
    }

    /**
     * @param	int $value	The featured value
     * @param	int $i
     * @param	bool $canChange Whether the value can be changed or not
     *
     * @return	string	The anchor tag to toggle featured/unfeatured .
     * @since	1.6
     */
    static function featured($value = 0, $i, $canChange = true, $controller = 'realties') {


	// Array of image, task, title, action
	$states = array(
	    0 => array('disabled.png', $controller . '.featured', 'COM_JUX_REAL_ESTATE_UNFEATURED', 'COM_JUX_REAL_ESTATE_FEATURED'),
	    1 => array('featured.png', $controller . '.unfeatured', 'COM_JUX_REAL_ESTATE_FEATURED', 'COM_JUX_REAL_ESTATE_UNFEATURED')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);
	$html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}
	return $html;
    }

    static function showbanner($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('publish_x.png', 'types.showbanner', 'JHIDE', 'JSHOW'),
	    1 => array('tick.png', 'types.unshowbanner', 'JSHOW', 'JHIDE')
	);
	$state = JArrayHelper::getValue($states, (int) $value, $states[0]);
	$html = JHtml::_('image', 'admin/' . $state[0], JText::_($state[2]), NULL, true);
	if ($canChange) {
	    $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" title="' . JText::_($state[3]) . '">'
		    . $html . '</a>';
	}

	return $html;
    }

}

?>