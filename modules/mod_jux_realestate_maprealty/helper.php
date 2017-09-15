<?php
/**
 * @version     $Id$
 * @author      JoomlaUX Admin
 * @package     Joomla!
 * @subpackage  JUX Real Estate
 * @copyright   Copyright (C) 2012 - 2015 by JoomlaUX Solutions. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted acscess');

class mod_JUX_RealEstate_MapRealtyHelper {

	public static function javascript($params) {
		$document = JFactory::getDocument();
	}

	public static function css() {
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_maprealty/assets/css/style.css');
	}

	public static function getTypes($params) {
		$type_id = $params->get('type_id');
		$types = array();
		if (is_array($type_id))
			$types = $type_id;
		else
			$types[] = (int) $type_id;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT t.*');
		$query->from('#__re_types AS t');
		$query->where('t.published = 1');
		if ($types[0] != 0) {
			$types = implode(',', $types);
			$query->where('t.id IN(' . $types . ')');
		}
		$db->setQuery($query);

		$data = $db->loadObjectList();
		return $data;
	}

	public static function getData($params) {
		$db = JFactory::getDbo();
		$type_id = $params->get('type_id');

		$query = $db->getQuery(true);
		$query->select('DISTINCT a.*, t.title AS `type`, t.icon AS `icon`, c.title as `category`');
		$query->from('#__re_realties AS a');
		$query->leftJoin('#__re_types AS t ON t.id = a.type_id');
		$query->leftJoin('#__categories AS c ON c.id = a.cat_id');
		$query->where('a.published = 1 AND a.approved = 1');
		$query->where('t.published = 1');
		$types = array();
		if (is_array($type_id))
			$types = $type_id;
		else
			$types[] = (int) $type_id;

		if ($types[0] != 0) {
			$types = implode(',', $types);
			$query->where('t.id IN(' . $types . ')');
		}

		$db->setQuery($query);

		$data = $db->loadObjectList();

		if (count($data)) {
			for ($i = 0; $i < count($data); $i++) {
		// Format Price and SQft output
				$data[$i]->formattedprice = mod_JUX_RealEstate_MapRealtyHelper::getFormattedPrice($data[$i]->price, $data[$i]->price_freq, $data[$i]->currency_id, false, $data[$i]->call_for_price, $data[$i]->price2);
			}
		}

		return $data;
	}

	public static function getFormattedPrice($price = '', $price_freq = '', $currency_id = 0, $advsearch = false, $call = false, $price2 = false, $newline = false) {
		if ($call == true) {
	    	//call for price flag
			$formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
		} else if ($price != 0 || $advsearch == true) {
	    	//if valid price & not using advanced search
			$configs = mod_JUX_RealEstate_MapRealtyHelper::getConfiguration();

			$currency_digits = $configs->get('currency_digits', 2);
			$thousand_separator = $configs->get('thousand_separator');
			$db = JFactory::getDBO();

			$query = 'SELECT a.*' . ' FROM #__re_currencies AS a' . ' WHERE a.id =' . $currency_id;
			$db->setQuery($query);
			$row = $db->loadObject();

			$before_curr = ($row->position == 0) ? $row->sign : ' ';
	    	//currency before price
			$after_curr = ($row->position == 1) ? ' ' . $row->sign : ' ';
	    	//currency after price

			$payments = ($price_freq) ? ' / ' . $price_freq : '';
			if ($thousand_separator == '.') {
				$format = number_format($price, $currency_digits, ',', $thousand_separator);
			} else {
				$format = number_format($price, $currency_digits, '.', $thousand_separator);
			}
			$formattedprice = $before_curr . $format . $after_curr . $payments;
		} else {
	    	//there was no price set
			$formattedprice = JText::_('COM_JUX_REAL_ESTATE_CALL_FOR_PRICE');
		}
		return $formattedprice;
	}

	public static function getConfiguration() {

		// let load the content if it doesn't already exist
		$db = JFactory::getDbo();
		if (empty($data)) {
			$query = 'SELECT *' . ' FROM #__re_configs';
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$data = new JObject();
			for ($i = 0, $n = count($rows); $i < $n; $i++) {
				$row = $rows[$i];
				$key = $row->key;
				$value = $row->value;
				$data->set($key, $value);
			}
		}
		return $data;
	}

	public static function getMakers($rows, $params) {
                $config = JFactory::getConfig();
		header("Content-type: text/xml");
		$xml = '<?xml version="1.0" encoding="utf-8" ?>';
		$xml.= '<markers>';
		if (count($rows)) {
			foreach ($rows as $row) {
                                if($config->get('sef') == 1){
                                    $url = 'index.php?option=com_jux_real_estate&view=realty&id=' . $row->id . '-' . $row->alias;
                                } else {
                                    $url = 'index.php?option=com_jux_real_estate&view=realty&id=' . $row->id;
                                }
				$image = '<a href="' . JRoute::_($url) . '"><img style="width: 360px; height: 190px" src="' . JUri::root() . $row->preview_image . '"/></a>';
				$title = '<a href="' . JRoute::_($url) . '">' . $row->title . '</a>';
				$title2 = $row->title;
				$xml.= '<marker
				image="' . htmlspecialchars($image) . '"
				url="' . htmlspecialchars($url) . '"
				title="' . htmlspecialchars($title) . '"
				title2="' . htmlspecialchars($title2) . '"
				type="' . htmlspecialchars($row->type) . '"
				category="' . $row->category . '"
				price="' . htmlspecialchars($row->formattedprice) . '"
				icon="' . $row->icon . '"
				latitude="' . $row->latitude . '"
				longitude="' . $row->longitude . '"
				baths="' . $row->baths . '"
				beds="' . $row->beds . '"
				sqft="' . $row->sqft . ' sqft" />';
			}
		}

		$xml.= "</markers>";
		file_put_contents('modules/mod_jux_realestate_maprealty/xml/data.xml', $xml);
		@ob_end_clean();
		ob_start();
	}

}

?>