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

jimport('joomla.application.component.view');

/**
 * JUX_Real_Estate Component - Maprealty View
 * @package		JUX_Real_Estate
 * @subpackage	View
 * @since		1.0
 */
class JUX_Real_EstateViewMaprealty extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {

	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$document = JFactory::getDocument();
	//$tmpl = new JUX_Real_EstateTemplate();
	// get page layout
	$layout = $this->getLayout();

	if ($layout == 'map') {
	    $this->_displayMaps($tpl);
	    return;
	}
	// Get the parameters of the active menu item
	$menus = $app->getMenu();
	$menu = $menus->getActive();

	// Get the page/component configuration
	$params = $app->getParams();
	$types = $this->get('Types');

	//Get Configuration model
	$model = JModelLegacy::getInstance('configuration', 'JUX_Real_EstateModel');
	$config = $model->getData();

	if ($menu) {
	    $params->def('page_heading', $params->def('page_title', $menu->title));
	} else {
	    $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JOOM_PROPERTY'));
	}
	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself
	if (!$params->get('page_title')) {

	    $params->set('page_title', $menu->name);
	} else {
	    $params->set('page_title', $params->get('page_title'));
	}

	$document->setTitle($params->get('page_title'));
	$this->config = $config;
	$this->params = $params;
	$this->types = $types;

	parent::display($tpl);
    }

}

function _displayMaps($tpl = null) {
    $app = JFactory::getApplication();
    $rows = &$this->get('Data');
    $params = $app->getParams();

    $model = &JModelLegacy::getInstance('configuration', 'JUX_Real_EstateModel');

    $config = $model->getData();
    header('Content-Type: application/xml');
    $xml = '<?xml version="1.0" encoding="utf-8" ?><markers>';

    if (count($rows)) {
	foreach ($rows as $row) {
	    $files_image = JUX_Real_EstateHelperQuery::getFileImageList($row->id);
	    $images = explode(",", $row->images);
	    JFilterOutput::objectHTMLSafe($row, ENT_QUOTES, array('description'));
	    $url = JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=realty&id=' . $row->id);
	    $description = ($params->get('show_desc')) ? htmlspecialchars(substr(strip_tags($row->sub_desc), 0, $params->get('max_desc'))) . '...' : '';
	    $price = $this->getFormattedPrice($row->price, $row->price_freq, $row->currency_id, false, $row->call_for_price, $row->price2);
	    if (JVERSION >= '3.0.0') {
		if ($files_image) {
		    $img_thumb = JUri::root() . 'media/com_jux_real_estate/realty_image/' . $row->id . '/' . $files_image[0]->file_name;
		} else {
		    $img_thumb = JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg';
		}
	    }
	    if (JVERSION < '3.0.0') {
		if ($row->images) {
		    $img_thumb = JUX_REAL_ESTATE_IMG . "?width={$params->get('thumb_img_width')}&amp;height={$params->get('thumb_img_height')}&amp;image=/$images[0]";
		} else {
		    $img_thumb = JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg';
		}
	    }
	    $content = '<a href="' . $url . '"><img style="width: ' . $params->get('thumb_img_width') . 'px; height: ' . $params->get('thumb_img_height') . 'px" src="' . $img_thumb . '" align="right" /></a>';
	    $xml .= '<marker html="' . htmlspecialchars($content) . '" title="' . $row->title . '" url="' . $url . '" description="' . $description . '" typeid="' . $row->type_id . '" categoryid="' . $row->cat_id . '" id="' . $row->id . '" price="' . $price . '" icon="' . $row->icon . '" latitude="' . $row->latitude . '" longitude="' . $row->longitude . '" address ="' . $row->address . '"/>';
	}
    }


    $xml .= "</markers>";
    @ob_end_clean();
    ob_start();
    print $xml;
    exit();
    $this->row = $rows;
    $this->params = $params;
    $this->config = $config;
    parent::display($tpl);
}

function getFormattedPrice($price = '', $price_freq = '', $currency_id = 0, $advsearch = false, $call = false, $price2 = false, $newline = false) {
    if ($call == true) { //call for price flag
	$formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
    } else if ($price != 0 || $advsearch == true) { //if valid price & not using advanced search
	$configs = JUX_Real_EstateFactory::getConfiguration();

	$currency_digits = $configs->get('currency_digits', 2);
	$thousand_separator = $configs->get('thousand_separator');
	$db = &JFactory::getDBO();


	$query = 'SELECT a.*'
		. ' FROM #__re_currencies AS a'
		. ' WHERE a.id =' . $currency_id
	;
	$db->setQuery($query);
	$row = $db->loadObject();

	$before_curr = ($row->position == 0) ? $row->sign : ''; //currency before price
	$after_curr = ($row->position == 1) ? ' ' . $row->sign : ''; //currency after price

	$payments = ($price_freq) ? '/' . $price_freq : '';
	if ($thousand_separator == '.') {
	    $format = number_format($price, $currency_digits, ',', $thousand_separator);
	} else {
	    $format = number_format($price, $currency_digits, '.', $thousand_separator);
	}
	$formattedprice = $before_curr . $format . $after_curr . $payments;
    } else { //there was no price set
	$formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
    }
    return $formattedprice;
}

?>