<?php

/**
 * @version     $Id$
 * @author      JoomlaUX Admin
 * @package     Joomla!
 * @subpackage  JUX Real Estate Properties
 * @copyright   Copyright (C) 2015 by JoomlaUX Solutions. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
if (!class_exists('modJUXRealEstateProperties')) {
    require_once dirname(__FILE__) . '/includes/css.php';
}
require_once JPATH_SITE . '/components/com_jux_real_estate/helpers/html.helper.php';
class mod_JUX_Real_Estate_Properties_Helper
{
    public static function getCssProcessor(&$params, $filename, $prefix) {
        return modJUXRealEstateProperties::process($params, $filename, $prefix);
    }
}
class mod_RealtyEstatePropertiesHelper
{
    /**
     * load javascript files: processing override js, load js compress or not.
     */
    public static function javascript($params) {
        $document = JFactory::getDocument();
        if($params->get('enable_jquery')==1){   
           $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/js/jquery-1.9.1.min.js'); 
       }
       $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/js/owl.carousel.js');

   }

    /**
     * load css files: processing override css
     */
    public static function css() {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/style.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/owl.carousel.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/owl.theme.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/jux-font-awesome.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/jux-responsivestyle.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/imagein.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/imageon.css');
    }
    
    public static function getData($params) {
        $db = JFactory::getDbo();
        $nullDate = $db->Quote($db->getNullDate());
        $date = JFactory::getDate();
        $nowDate = $db->Quote($date->toSql());

        $coutLimit = $params->get('count_limit');
        $select_type = $params->get('select_type');

        $select_contract = $params->get('select_contract');
        $sort_order_field = $params->get('sort_order_field');
        $count_limit = $params->get('count_limit');
        $query = $db->getQuery(true);
        $query->select('r.*');
        $query->select($db->quoteName('c.title', 'cat_title'));
        $query->select($db->quoteName('t.title', 'type_title'));
        $query->from($db->quoteName('#__re_realties', 'r'));
        $query->join('LEFT', $db ->quoteName('#__re_types', 't') . ' ON (' . $db->quoteName('t.id') . ' = ' . $db->quoteName('r.type_id') . ')');
        $query->join('LEFT', $db ->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db ->quoteName('r.cat_id') . ')')->where('r.published = 1');

        $query->where('(r.publish_up = ' . $nullDate . ' OR r.publish_up <= ' . $nowDate . ')');
        $query->where('(r.publish_down = ' . $nullDate . ' OR r.publish_down >= ' . $nowDate . ')');
        
        $query->setLimit("$count_limit");
        if ($select_type != 0) {
            $query->where("r.type_id =" . "\"" . $select_type . "\"");
        }
        if ($select_contract != "all") {
            $query->where("r.cat_id =" . "\"" . $select_contract . "\"");
        }

        switch ($sort_order_field)
        {

            case 'date' :
                $orderby = 'r.date_created ASC';
            break;

            case 'rdate' :
                $orderby = 'r.date_created DESC';
            break;

            case 'alpha' :
                $orderby = 'r.title';
            break;

            case 'ralpha' :
                $orderby = 'r.title DESC';
            break;
            case 'order' :
                $orderby = 'r.ordering ASC';
            break;

            case 'hits' :
                $orderby = 'r.hits DESC';
            break;

            case 'modified' :
                $orderby = 'modified_by DESC';
            break;

            case 'publish_up' :
                $orderby = 'r.publish_up DESC';
            break;                
            default :
                $orderby = 'r.ordering';
            break;
        }
        $query->order($orderby);

        $db->setQuery($query);
        $data = $db->loadObjectList();
        if (count($data)) {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]->formattedprice = JUX_Real_EstateHTML::getFormattedPriceModule($data[$i]->price, $data[$i]->price_freq, $data[$i]->currency_id, false, $data[$i]->call_for_price, $data[$i]->price2);
            }
        }

        return $data;
    }   
}
?>