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
 * @package        JUX_Real_Estate
 * @subpackage    Helper
 * @since        3.0
 */
abstract class JHtmlJUX_Real_Estate {

    // template folder
    function template($name = 'config[template]', $active = 'default', $javascript = '', $size = 1) {
	$folders = JFolder::folders(JPATH_ROOT . DS . 'components' . DS . 'com_jux_real_estate' . DS . 'assets');

	$options = array();
	foreach ($folders as $folder) {
	    $options[] = JHTML::_('select.option', $folder, $folder);
	}

	return JHTML::_('select.genericlist', $options, $name, 'class="inputbox" size="' . $size . '" ' . $javascript, 'value', 'text', $active);
    }

    /**
     * @param    int $value    The requiredfield value
     * @param    int $i
     * @param    bool $canChange Whether the value can be changed or not
     *
     * @return    string    The anchor tag to toggle approve/unapprove agents.
     * @since    1.6
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
     * @param    int $value    The accessfield value
     * @param    int $i
     * @param    bool $canChange Whether the value can be changed or not
     *
     * @return    string    The anchor tag to toggle approve/unapprove agents.
     * @since    1.6
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
     * @param    int $value    The searchfield value
     * @param    int $i
     * @param    bool $canChange Whether the value can be changed or not
     *
     * @return    string    The anchor tag to toggle approve/unapprove agents.
     * @since    1.6
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
    function category() {

	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, `title` AS text');
	$query->from('#__categories');
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

    function type() {

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

    function country($name = 'country', $active = 0, $class = null, $javascript = '', $size = 1) {

	$db = &JFactory::getDBO();
	$query = 'SELECT `name` AS value, `name` AS text'
		. ' FROM #__re_countries AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.id';
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	$options = array();
	$options[] = JHTML::_('select.option', 0, '-- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_COUNTRY') . ' --', 'value', 'text');
	foreach ($rows as $row) {
	    $options[] = JHTML::_('select.option', $row->value, JText::_($row->text), 'value', 'text');
	}

	return JHTML::_('select.genericlist', $options, $name, 'class="inputbox ' . $class . '" size="' . $size . '" ' . $javascript, 'value', 'text', $active);
    }

    /**
     * @param    int $value    The approved value
     * @param    int $i
     * @param    bool $canChange Whether the value can be changed or not
     *
     * @return    string    The anchor tag to toggle approved/unapproved agents.
     * @since    1.6
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
     * @param    int $value    The approve value
     * @param    int $i
     * @param    bool $canChange Whether the value can be changed or not
     *
     * @return    string    The anchor tag to toggle approve/unapprove agents.
     * @since    1.6
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
     * @param    int $value    The approved value
     * @param    int $i
     * @param    bool $canChange Whether the value can be changed or not
     *
     * @return    string    The anchor tag to toggle approved/unapproved contacts.
     * @since    1.6
     */
    static function sold($value = 0, $i, $canChange = true) {
	// Array of image, task, title, action
	$states = array(
	    0 => array('publish_x.png', 'realties.sold', 'COM_JUX_REAL_ESTATE_SELLING', 'COM_JUX_REAL_ESTATE_SOLD'),
	    1 => array('tick.png', 'realties.unsold', 'COM_JUX_REAL_ESTATE_SOLD', 'COM_JUX_REAL_ESTATE_SELLING')
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

	return $options;
    }

    function displayBanners($type = '', $new = '', $path = '', $settings = '', $updated = '') {
	if ($settings->banner_display == 1) { //image banners
	    $banner_display = '';
	    if ($new == 1 && $settings->new_days) {
		$banner_display .= '
                    <div class="realty_bannerleft">
                        <img src="' . $path . '/components/com_jux_real_estate/assets/images/banners/banner_new.png" alt="' . JText::_('COM_JUX_REAL_ESTATE_NEW') . '" title="' . JText::_('COM_JUX_REAL_ESTATE_NEW') . '" />
                    </div>';
	    } else if ($updated == 1 && $settings->updated_days) {
		$banner_display .= '
                    <div class="realty_bannerbotleft">
                        <img src="' . $path . '/components/com_jux_real_estate/assets/images/banners/banner_updated.png" alt="' . JText::_('COM_JUX_REAL_ESTATE_UPDATED') . '" title="' . JText::_('COM_JUX_REAL_ESTATE_UPDATED') . '" />
                    </div>';
	    }
	    // dynamic sale type banners v1.5.5
	    $banner_display .= JUX_Real_EstateHTML::displayStypeBanner($type, 1, $path);
	} else if ($settings->banner_display == 2) { //css banners
	    $banner_display = '';
	    if ($new == 1 && $settings->new_days) {
		$banner_display .= '
                    <div class="realty_bannercsstop bannernew">
                        ' . JText::_('COM_JUX_REAL_ESTATE_NEW') . '
                    </div>';
	    } else if ($updated == 1 && $settings->updated_days) {
		$banner_display .= '
                    <div class="realty_bannercsstop bannerupdated">
                        ' . JText::_('COM_JUX_REAL_ESTATE_UPDATED') . '
                    </div>';
	    }
	    // dynamic sale type banners v1.5.5
	    $banner_display .= JUX_Real_EstateHTML::displayStypeBanner($type, 2);
	} else { //no banners
	    $banner_display = '';
	}
	return $banner_display;
    }

    function displayStypeBanner($type, $type, $path = null) {
	// load stype object from db
	jimport('joomla.filesystem.file');
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('*')
		->from('#__re_types')
		->where('id = ' . (int) $type);

	$db->setQuery($query, 0, 1);
	$result = $db->loadObject();

	$type_banner = '';
	if ($result->show_banner && $result->published) {
	    // banner image
	    if ($type == 1 && $result->banner_image) {
		$type_banner = '<div class="realty_bannerright"><img src="' . $path . '/' . $result->banner_image . '" alt="' . JText::_($result->name) . '" /></div>';
	    } elseif ($type == 2 && $result->banner_color) {
		$type_banner = '<div class="realty_bannercssbot" style="background: ' . $result->banner_color . '; font-weight: bold; color: #fff;">' . JText::_($result->name) . '</div>';
	    }
	}
	return $type_banner;
    }

    static function edit($item, $type, $text_only = false, $show_over = true) {
	// Initialise variables.
	$user = JFactory::getUser();
	$userId = $user->get('id');
	$uri = JFactory::getURI();

	if ($item->published < 0) {
	    return;
	}

	switch ($type) {
	    case 'realty':
		$controller = 'realty';
		$checkin = 'checkinRealty';
		break;
	    case 'agent':
		$controller = 'agentform';
		$checkin = 'checkinAgent';
		break;
	   
	}

	JHtml::_('behavior.tooltip');

	// Show checked_out icon if the article is checked out by a different user
	if (property_exists($item, 'checked_out') && property_exists($item, 'checked_out_time') && $item->checked_out > 0 && $item->checked_out != $user->get('id')) {
	    $checkoutUser = JFactory::getUser($item->checked_out);
	    $url = 'index.php?option=com_jux_real_estate&view=' . ($controller == 'realty') ? 'form' : $controller . '&task=' . $controller . '.' . $checkin . '&id=' . $item->id . '&return=' . base64_encode($uri) . '&' . JUtility::getToken() . '=1';

	    if ($text_only) {
		$button = JHtml::_('link', JRoute::_($url), JText::_('JLIB_HTML_CHECKED_OUT'));
	    } else {
		$button = JHtml::_('link', JRoute::_($url), JHtml::_('image', 'system/checked_out.png', NULL, NULL, true));
	    }

	    $date = addslashes(htmlspecialchars(JHtml::_('date', $item->checked_out_time, JText::_('DATE_FORMAT_LC')), ENT_COMPAT, 'UTF-8'));
	    $time = addslashes(htmlspecialchars(JHtml::_('date', $item->checked_out_time, 'H:i'), ENT_COMPAT, 'UTF-8'));

	    $tooltip = JText::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . JText::sprintf('COM_JUX_REAL_ESTATE_CHECKED_OUT_BY', $checkoutUser->name) . '<br />' . $date . '<br />' . $time;
	    return '<span class="hasTip" title="' . htmlspecialchars($tooltip, ENT_COMPAT, 'UTF-8') . '">' . $button . '</span>';
	}

	$url = 'index.php?option=com_jux_real_estate&view=' . $controller . '&task=' . $controller . '.edit&id=' . $item->id . '&return=' . base64_encode($uri);

	if ($text_only) {
	    $text = JText::_('JGLOBAL_EDIT');
	} else {
	    $icon = $item->published ? 'edit.png' : 'edit_unpublished.png';
	    $text = JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), NULL, true);
	}

	if ($item->published == 0) {
	    $overlib = JText::_('JUNPUBLISHED');
	} else {
	    $overlib = JText::_('JPUBLISHED');
	}

	if ($type == 'realty' && $show_over) {
	    $date = JHtml::_('date', $item->date_created);
	    $author = $item->user_id ? $item->user_id : 'Admin';

	    $overlib .= '&lt;br /&gt;';
	    $overlib .= $date;
	    $overlib .= '&lt;br /&gt;';
	    $overlib .= JText::sprintf('COM_JUX_REAL_ESTATE_CREATED_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));
	}

	$button = JHtml::_('link', JRoute::_($url), $text);

	if ($show_over) {
	    $output = '<span class="hasTip" title="' . JText::_('JGLOBAL_EDIT') . ' :: ' . $overlib . '">' . $button . '</span>';
	} else {
	    $output = $button;
	}
	return $output;
    }

}

?>