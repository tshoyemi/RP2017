<?php
/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate Ouragents
 * @copyright	Copyright (C) 2015 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
if (!class_exists('modJUXRealEstateOuragents')){
	require_once dirname(__FILE__).'/includes/css.php';
}
class mod_JUX_Real_Estate_Ouragents_Helper {
	public static function getCssProcessor(&$params,$filename,$prefix){
		return modJUXRealEstateOuragents::process($params,$filename,$prefix);		
	}
}
class mod_RealtyEstateOuragentsHelper {

    /**
     * load javascript files: processing override js, load js compress or not.
     */
    public static function javascript($params) {
        $document = JFactory::getDocument();
        
        if($params->get('enable_jquery')==1){   
           $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/js/jquery-1.9.1.min.js'); 
       }
       $document->addScript(JURI::base() . 'modules/mod_jux_real_estate_ouragents/assets/js/owl.carousel.js');
   }

    /**
     * load css files: processing override css
     */
    public static function css($text) {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_ouragents/assets/css/font-awesome.min.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_ouragents/assets/css/style.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_ouragents/assets/css/owl.carousel.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_ouragents/assets/css/owl.theme.css');
    }

    public static function getData($params) {
        $db = JFactory::getDbo();
        $coutLimit = $params->get('count_limit');
        $query = $db->getQuery(true);
        $query->select('r.*');
        $query->from('#__re_agents as r');
        $query->where('r.published = 1');
        $query->setLimit("$coutLimit");
        $db->setQuery($query);
        $data = $db->loadObjectList();

        return $data;
    }
}
?>
