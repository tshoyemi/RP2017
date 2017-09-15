<?php

/**
 * @version     $Id$
 * @author      JoomlaUX Admin
 * @package     Joomla!
 * @subpackage  JUX Real Estate Slideshow
 * @copyright   Copyright (C) 2015 by JoomlaUX Solutions. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_SITE . '/components/com_jux_real_estate/helpers/html.helper.php';

class mod_RealtyEstateSlideShowHelper
{
    
    /**
     * load javascript files: processing override js, load js compress or not.
     */
    public static function javascript() {
        $document = JFactory::getDocument();
        
        $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_slideshow/assets/js/jquery.carouFredSel-6.2.1-packed.js');
        $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_slideshow/assets/js/jquery.touchSwipe.min.js');
        $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_slideshow/assets/js/imagesloaded.pkgd.min.js');
        $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_slideshow/assets/js/script.js');
    }
    
    /**
     * load css files: processing override css
     */
    public static function css() {
        $document = JFactory::getDocument();
        
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_slideshow/assets/css/style.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_slideshow/assets/css/responsive.css');
    }
    
    public static function getData($params) {
        
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true);
        if (count($params->get('realty_id')) != 0) {
            $query = 'SELECT r.* ' . 'From #__re_realties AS r ' . ' WHERE r.published = 1 AND id IN (' . implode(',', $params->get('realty_id')) . ')';
        } else {
            $query = 'SELECT r.* ' . 'From #__re_realties AS r ' . ' WHERE r.published = 1';
        }
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
