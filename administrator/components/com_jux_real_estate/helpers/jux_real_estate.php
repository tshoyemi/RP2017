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
class JUX_Real_EstateHelper {

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     */
    public static function getActions() {
	$user = JFactory::getUser();
	$result = new JObject;

	$assetName = 'com_jux_real_estate';

	$actions = array(
	    'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.edit.country', 'core.delete'
	);

	foreach ($actions as $action) {
	    $result->set($action, $user->authorise($action, $assetName));
	}

	return $result;
    }

    /**
     * Configure the Linkbar.
     *
     * @param	string	$vName		The name of the active view.
     *
     * @return	void
     */
    public static function addSubmenu($vName) {
	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_DASHBOARD'), 'index.php?option=com_jux_real_estate&view=dashboard', $vName == 'dashboard');
	JHtmlSidebar::addEntry(JText::_(
			'COM_JUX_REAL_ESTATE_TYPES'), 'index.php?option=com_jux_real_estate&view=types', $vName == 'types');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_CONTRACT'), 'index.php?option=com_categories&extension=com_jux_real_estate', $vName == 'categories');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_REALTIES'), 'index.php?option=com_jux_real_estate&view=realties', $vName == 'realties');
	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_AGENTS'), 'index.php?option=com_jux_real_estate&view=agents', $vName == 'agents');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_PLANS'), 'index.php?option=com_jux_real_estate&view=plans', $vName == 'plans');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_FIELDS'), 'index.php?option=com_jux_real_estate&view=fields', $vName == 'fields');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_AMENITIES'), 'index.php?option=com_jux_real_estate&view=amenities', $vName == 'amenities');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_COUNTRIES'), 'index.php?option=com_jux_real_estate&view=countries', $vName == 'countries');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_STATES'), 'index.php?option=com_jux_real_estate&view=states', $vName == 'states');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_MESSAGES'), 'index.php?option=com_jux_real_estate&view=messages', $vName == 'messages');

	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_CURRENCIES'), 'index.php?option=com_jux_real_estate&view=currencies', $vName == 'currencies');
	JHtmlSidebar::addEntry(
		JText::_('COM_JUX_REAL_ESTATE_LANGUAGE'), 'index.php?option=com_jux_real_estate&view=language', $vName == 'languages');

	$canDo = JUX_Real_EstateHelper::getActions();

	if ($canDo->get('core.admin')) {
	    JHtmlSidebar::addEntry(
		    JText::_('COM_JUX_REAL_ESTATE_CONFIGURATION'), 'index.php?option=com_jux_real_estate&view=configs', $vName == 'configs');
	}
    }

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
     * Get a list of filter options for the activated state of a message.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    static function getStatusOptions() {
	// Build the filter options.
	$options = array();
	$options[] = JHtml::_('select.option', '0', JText::_('COM_JUX_REAL_ESTATE_NOT_READ'));
	$options[] = JHtml::_('select.option', '1', JText::_('COM_JUX_REAL_ESTATE_READ'));

	return $options;
    }

    /**
     * Get a list of filter options for the approved state of a realty.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    static function getApprovedOptions() {
	// Build the filter options.
	$options = array();
	$options[] = JHtml::_('select.option', '1', JText::_('COM_JUX_REAL_ESTATE_APPROVED'));
	$options[] = JHtml::_('select.option', '0', JText::_('COM_JUX_REAL_ESTATE_PEDDING'));
	$options[] = JHtml::_('select.option', '-1', JText::_('COM_JUX_REAL_ESTATE_REJECTED'));
	return $options;
    }

    /**
     * Get a list of filter options for the featured state of a realty.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    static function getFeaturedOptions() {
	// Build the filter options.
	$options = array();
	$options[] = JHtml::_('select.option', '1', JText::_('COM_JUX_REAL_ESTATE_FEATURED'));
	$options[] = JHtml::_('select.option', '0', JText::_('COM_JUX_REAL_ESTATE_UNFEATURED'));
	return $options;
    }

    /**
     * Get a list of filter options for the beds of a realty.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    static function getBedsOptions() {
	// Build the filter options.
	$options = array();
	$options[] = JHtml::_('select.option', 1, JText::_('1'));
	$options[] = JHtml::_('select.option', 2, JText::_('2'));
	$options[] = JHtml::_('select.option', 3, JText::_('3'));
	$options[] = JHtml::_('select.option', 4, JText::_('4'));
	$options[] = JHtml::_('select.option', 5, JText::_('5'));
	$options[] = JHtml::_('select.option', 6, JText::_('6'));
	$options[] = JHtml::_('select.option', 7, JText::_('7'));
	$options[] = JHtml::_('select.option', 8, JText::_('8'));
	$options[] = JHtml::_('select.option', 9, JText::_('9'));
	$options[] = JHtml::_('select.option', 10, JText::_('10'));
	return $options;
    }

    /**
     * Get a list of filter options for the baths of a realty.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    static function getBathsOptions() {
	// Build the filter options.
	$options = array();
	$options[] = JHtml::_('select.option', 1, JText::_('1'));
	$options[] = JHtml::_('select.option', 2, JText::_('2'));
	$options[] = JHtml::_('select.option', 3, JText::_('3'));
	$options[] = JHtml::_('select.option', 4, JText::_('4'));
	$options[] = JHtml::_('select.option', 5, JText::_('5'));
	$options[] = JHtml::_('select.option', 6, JText::_('6'));
	$options[] = JHtml::_('select.option', 7, JText::_('7'));
	$options[] = JHtml::_('select.option', 8, JText::_('8'));
	$options[] = JHtml::_('select.option', 9, JText::_('9'));
	$options[] = JHtml::_('select.option', 10, JText::_('10'));
	return $options;
    }

    public static function uploadImage($listimages = null, $data = array(), $configs = null) {

	$image_types = explode(',', $configs->get('image_exts'));
	$err = false;
	// upload images
	if (is_array($listimages['name'])) {
	    $imgnames = array();
	    $n = count($listimages['name']);

	    for ($i = 0; $i < $n; $i++) {
		$file = array();

		$file['name'] = $listimages['name'][$i];
		$file['ext'] = strtolower(end(explode('.', $file['name'])));
		if (!empty($file['name']) && in_array($file['ext'], $image_types)) {
		    $file['type'] = $listimages['type'][$i];
		    $file['tmp_name'] = $listimages['tmp_name'][$i];
		    $file['size'] = $listimages['size'][$i];
		    $file['error'] = $listimages['error'][$i];

		    $base_dir = JPATH_SITE . '/' . 'images' . '/' . 'jux_real_estate' . '/';
		    if (JUX_Real_EstateClassImage::check($file, $configs->get('images_zise'))) {
			// sanitize the image filename
			$filename = JUX_Real_EstateClassImage::sanitize($base_dir, $file['name']);
			$filepath = $base_dir . $filename;

			// upload the image
			if (JFile::upload($file['tmp_name'], $filepath)) {
			    // resize image
			    $imgnames[] = $filename;
			}
		    } else {
			$err = true;
		    }
		}
	    }
	}
	if (!$err) {
	    $imgname = '';
	    if (count($imgnames)) {
		if ($data['old_images'] != '') {
		    $imgname = implode(',', array_merge(explode(',', $data['old_images']), $imgnames));
		}
		else
		    $imgname = implode(',', $imgnames);
	    } else {
		$imgname = $data['old_images'];
	    }
	    $data['images'] = $imgname;
	}

	// delete image
	$dir = JPATH_SITE . '/' . 'images' . '/' . 'jux_real_estate' . '/';
	$del_images = explode(',', $data['del_images']);
	if (count($del_images)) {
	    foreach ($del_images as $image) {
		if (JFile::exists($dir . $image)) {
		    JFile::delete($dir . $image);
		}
	    }
	}

	return $data;
    }

    function getCatImage($cat_id, $width = '', $text_only = false) {
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);
	$query->select('id, image, title, alias')
		->from('#__categories')
		->where('id = ' . (int) $cat_id);

	$db->setQuery($query, 0, 1);
	$result = $db->loadObject();

	if ($text_only) {
	    $cat_image = $result->title;
	} else if ($result->image) {
	    $cat_image = '<img src="' . JURI::root(true) . '/images/jux_real_estate/categories/' . $result->image . '" alt="' . $result->title . '" width="' . $width . '" class="hasTip" title="' . $result->title . '" style="margin-right: 2px;" />';
	} else {
	    $cat_image = $result->title;
	}

	$catlink = JRoute::_('index.php?option=com_jux_real_estate&task=category.edit&id=' . (int) $cat_id);
	$cat_image = '<a href="' . $catlink . '">' . $cat_image . '</a>';

	return $cat_image;
    }

    function getCategoryList($tag, $attrib, $selected = null, $list = false) {
	$db = JFactory::getDbo();
	$companies = array();
	$companies[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_CATEGORY'), "value", "text");
	$query = $db->getQuery(true);
	$query->select('c.id, c.title');
	$query->from('#__categories as c');
	$query->where('published = 1');
	$query->where('c.extension ="com_jux_real_estate"');
	$query->order('c.title ASC');
	$db->setQuery($query);
	$result = $db->loadObjectList();

	echo $query;

	foreach ($result as $r) {
	    $companies[] = JHTML::_('select.option', $r->id, JText::_($r->title), "id", "title");
	}

	if ($list) {
	    return $companies;
	} else {
	    return JHTML::_('select.genericlist', $companies, $tag, $attrib, "id", "title", $selected);
	}
    }

}

?>
