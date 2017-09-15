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
 * JUX_Real_EstateHTML Helper
 *
 * @static
 * @package        Joomla
 * @subpackage    com_jux_real_estateHTML
 * @since 3.0
 */
class JUX_Real_EstateHTML {
        
	

    function displayBanners($type = '', $new = '', $path = '', $configs = '', $updated = '') {

	if ($configs->get('banner_display') == 1) {
	    //image banners
	    $banner_display = '';
	    if ($new == 1 && $configs->get('new_days')) {
		$banner_display.= '
                <div class="realty_bannerleft">
                    <img src="' . $path . '/components/com_jux_real_estate/templates/default/images/banners/banner_new.png" alt="' . JText::_('COM_JUX_REAL_ESTATE_NEW') . '" title="' . JText::_('COM_JUX_REAL_ESTATE_NEW') . '" />
                </div>';
	    } else if ($updated == 1 && $configs->get('updated_days')) {
		$banner_display.= '
                <div class="realty_bannerleft">
                    <img src="' . $path . '/components/com_jux_real_estate/templates/default/images/banners/banner_updated.png" alt="' . JText::_('COM_JUX_REAL_ESTATE_UPDATED') . '" title="' . JText::_('COM_JUX_REAL_ESTATE_UPDATED') . '" />
                </div>';
	    }

	    $banner_display.= JUX_Real_EstateHTML::displayTypeBanner($type, 1, $path);
	} else if ($configs->get('banner_display', 2) == 2) {
	    //css banners
	    $banner_display = '';
	    if ($new == 1 && $configs->get('new_days')) {
		$banner_display.= '
                <div class="realty_bannercsstop bannernew">
                    ' . JText::_('COM_JUX_REAL_ESTATE_NEW') . '
                </div>';
	    } else if ($updated == 1 && $configs->get('updated_days')) {
		$banner_display.= '
                <div class="realty_bannercsstop bannerupdated">
                    ' . JText::_('COM_JUX_REAL_ESTATE_UPDATED') . '
                </div>';
	    }

	    $banner_display.= JUX_Real_EstateHTML::displayTypeBanner($type, 2);
	} else {
	    //no banners
	    $banner_display = '';
	}
	return $banner_display;
    }

    function displayTypeBanner($type, $banner_type, $path = null) {

	// load stype object from db
	jimport('joomla.filesystem.file');
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('*')->from('#__re_types')->where('id = ' . (int) $type);

	$db->setQuery($query, 0, 1);
	$result = $db->loadObject();

	$type_banner = '';
	if ($result->show_banner && $result->published) {

	    // banner image
	    if ($banner_type == 1 && $result->banner_image) {
		$type_banner = '<div class="realty_bannerright"><img src="' . $path . '/' . $result->banner_image . '" alt="' . JText::_($result->title) . '" /></div>';
	    } elseif ($banner_type == 2 && $result->banner_color) {
		$type_banner = '<div class="realty_bannercssbot" style="background: ' . $result->banner_color . '; font-weight: bold; color: #fff;">' . JText::_($result->title) . '</div>';
	    }
	}
	return $type_banner;
    }

    public static function isNew($created, $days = 7) {
	$stamp = strtotime("-$days days");
	$created = strtotime($created);
	$new = ($created >= $stamp) ? true : false;
	return $new;
    }

    public static function buildToolbar($view = '', $extras = '') {

	$configs = JUX_Real_EstateFactory::getConfiguration();
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	
	$html = "<div>";
	switch ($view) {
	    case 'realty':
		if ($this->permissions->canEditRealty($this->item->id)) {
		    $html.= JHtml::_('jux_real_estate.edit', $this->item, 'realty', true);
		}
		break;

	    case 'agent':
		if ($this->permissions->canEditAgent($this->item->id)) {
		    $html.= JHtml::_('jux_real_estate.edit', $this->item, 'agent', true);
		}
		break;

	    
	}

	$html.= '</div>';
	$html.= '<div class="clearfix"></div>';

	return $html;
    }

    public static function buildRealtySortList($tag = false, $filter_order, $attrib = '') {
	$configs = JUX_Real_EstateFactory::getConfiguration();

	$tag = ($tag) ? $tag : 'filter_order';
	$munits = (!$configs->get('measurement_units', 1)) ? JText::_('COM_JUX_REAL_ESTATE_SQFTDD') : JText::_('COM_JUX_REAL_ESTATE_SQMDD');
	$sortbys = array();
	$sortbys[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SORTBY'));
	$sortbys[] = JHTML::_('select.option', 'r.title', JText::_('COM_JUX_REAL_ESTATE_TITLE'));
	$sortbys[] = JHTML::_('select.option', 'r.price', JText::_('COM_JUX_REAL_ESTATE_PRICE'));
	$sortbys[] = JHTML::_('select.option', 'r.date_created', JText::_('COM_JUX_REAL_ESTATE_DATE_CREATED'));
	$sortbys[] = JHTML::_('select.option', 'r.beds', JText::_('COM_JUX_REAL_ESTATE_BEDS'));
	$sortbys[] = JHTML::_('select.option', 'r.baths', JText::_('COM_JUX_REAL_ESTATE_BATHS'));
	$sortbys[] = JHTML::_('select.option', 'r.sqft', $munits);
	return JHTML::_('select.genericlist', $sortbys, $tag, $attrib, 'value', 'text', $filter_order);
    }

    public static function buildAgentSortList($tag = false, $filter_order, $attrib = '', $list = false) {
	$sortbys = array();
	$sortbys[] = JHTML::_('select.option', 'a.ordering', JText::_('COM_JUX_REAL_ESTATE_SORTBY'));
	$sortbys[] = JHTML::_('select.option', 'a.username', JText::_('COM_JUX_REAL_ESTATE_AGENT_USERNAME'));
	$sortbys[] = JHTML::_('select.option', 'a.date_created', JText::_('COM_JUX_REAL_ESTATE_AGENT_DATE_CREATED'));

	$tag = ($tag) ? $tag : 'filter_order';

	if ($list) {
	    return $sortbys;
	} else {
	    return JHTML::_('select.genericlist', $sortbys, $tag, $attrib, 'value', 'text', $filter_order);
	}
    }

    public static function buildOrderList($tag = false, $filter_order_dir, $attrib = '') {
	$orderbys = array();
	$orderbys[] = JHTML::_('select.option', 'ASC', JText::_('COM_JUX_REAL_ESTATE_ASCENDING'));
	$orderbys[] = JHTML::_('select.option', 'DESC', JText::_('COM_JUX_REAL_ESTATE_DESCENDING'));

	$tag = ($tag) ? $tag : 'filter_order_Dir';

	return JHTML::_('select.genericlist', $orderbys, $tag, $attrib, 'value', 'text', $filter_order_dir);
    }

    public static function getFormattedPrice($price = '', $price_freq = '', $currency_id = 0, $advsearch = false, $call = false, $price2 = false, $newline = false) {
	if ($call == true) {
	    //call for price flag
	    $formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
	} else if ($price != 0 || $advsearch == true) {
	    //if valid price & not using advanced search
	    $configs = JUX_Real_EstateFactory::getConfiguration();

	    $currency_digits = $configs->get('currency_digits', 2);
	    $thousand_separator = $configs->get('thousand_separator');
	    $db = JFactory::getDBO();

	    $query = 'SELECT a.*' . ' FROM #__re_currencies AS a' . ' WHERE a.id =' . $currency_id;
	    $db->setQuery($query);
	    $row = $db->loadObject();

	    $before_curr = ($row->position == 0) ? $row->sign : '';
	    //currency before price
	    
	    $after_curr = ($row->position == 1) ? ' ' . $row->sign : '';
	    //currency after price

	    $payments = ($price_freq) ? '/' . $price_freq : '';
	    if ($thousand_separator == '.') {
		$format = number_format($price, $currency_digits, ',', $thousand_separator);
	    } else {
		$format = number_format($price, $currency_digits, '.', $thousand_separator);
	    }
	    $formattedprice = $before_curr . $format . $after_curr . $payments;

	    if ($price2 && $price2 != '0.00') {
		$p2format = number_format($price2, $currency_digits, ',', '.');
		$oldprice = '<span class="slashprice" style="text-decoration: line-through;">' . $before_curr . $p2format . $after_curr . '</span>';
		$oldprice.= ($newline) ? '<br />' : ' ';
		$formattedprice = $oldprice . '<span class="jp_newprice">' . $formattedprice . '</span>';
	    }
	} else {
	    //there was no price set
	    $formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
	}
	return $formattedprice;
    }

    public static function getFormattedPriceModule($price = '', $price_freq = '', $currency_id = 0, $advsearch = false, $call = false, $price2 = false, $newline = false) {
	$db = JFactory::getDbo();

	$query = "SELECT c.*" . " FROM #__re_configs as c" . " WHERE c.key = 'thousand_separator'  or c.key =  'currency_digits'  ";
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	$config = new JObject();

	for ($i = 0, $n = count($rows); $i < $n; $i++) {
	    $row = $rows[$i];
	    $key = $row->key;
	    $value = $row->value;

	    $config->set($key, $value);
	}

	if ($call == true) {

	    //call for price flag
	    $formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
	} else if ($price != 0 || $advsearch == true) {

	    //if valid price & not using advanced search
	    $currency_digits = $config->currency_digits;
	    $thousand_separator = $config->get('thousand_separator');
	    $db = JFactory::getDBO();
	    $query = 'SELECT a.*' . ' FROM #__re_currencies AS a' . ' WHERE a.id =' . $currency_id;
	    $db->setQuery($query);
	    $row = $db->loadObject();
	    $before_curr = ($row->position == 0) ? $row->sign : '';
	    //currency before price
	    
	    $after_curr = ($row->position == 1) ? ' ' . $row->sign : '';
	    //currency after price

	    $payments = ($price_freq) ? '/' . $price_freq : '';
	    if ($thousand_separator == '.') {
		$format = number_format($price, $currency_digits, ',', $thousand_separator);
	    } else if ($thousand_separator == ',') {
		$format = number_format($price, $currency_digits, '.', $thousand_separator);
	    }
	    $formattedprice = $before_curr . $format . $after_curr . $payments;

	    if ($price2 && $price2 != '0.00') {
		$p2format = number_format($price2, $currency_digits, ',', '.');
		$oldprice = '<span class="slashprice" style="text-decoration: line-through;">' . $before_curr . $p2format . $after_curr . '</span>';
		$oldprice.= ($newline) ? '<br />' : ' ';
		$formattedprice = $oldprice . '<span class="jp_newprice">' . $formattedprice . '</span>';
	    }
	} else {
	    //there was no price set
	    $formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
	}
	return $formattedprice;
    }

    public static function getStreetAddress($configs, $title = null, $street_num = null, $street = null, $hide = null) {
	$street_address = '';
	if ($configs->get('showtitle') && $title) {
	    $street_address = $title;
	} else {

	    if ($hide) {
		$street_address = JText::_('COM_JUX_REAL_ESTATE_ADDRESS_HIDDEN');
	    } else {

		if (!$configs->get('street_num_pos')) {
		    //street number before street
		    $street_address.= $street_num . ' ' . $street;
		} else {
		    //street number after street
		    $street_address.= $street;
		    $street_address.= ($street_num) ? ' ' . $street_num : '';
		}
	    }
	}
	return $street_address;
    }

    public static function getCategoryTitle($cat_id) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query = 'SELECT title FROM #__categories' . ' WHERE published = 1 AND id = ' . (int) $cat_id;
	$db->setQuery($query);
	$title = $db->loadResult();
	if ($title != '')
	    return $title;
	return false;
    }

    public static function getTypeTitle($type_id) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query = 'SELECT title FROM #__re_types' . ' WHERE published = 1 AND id = ' . (int) $type_id;
	$db->setQuery($query);
	$title = $db->loadResult();
	if ($title != '')
	    return $title;
	return false;
    }

    public static function getCategoryName($cat) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('id, title')
		->from('#__categories')
		->where('id=' . (int) $cat);
	$db->setQuery($query);

	$result = $db->loadObject();
	$row = $db->loadObject();
	return $result->title;
    }

}
