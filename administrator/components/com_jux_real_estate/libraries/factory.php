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
jimport('joomla.application.component.model');

/**
 * JUX_Real_Estate Factory Libraries
 * @package		JUX_Real_Estate
 * @subpackage	Libraries
 * @since		1.0
 *
 */
class JUX_Real_EstateFactory {

    /**
     * Get credits footer string
     * 
     */
    public static function getFooter() {
	return '<p class="jp_copyright"><span class="jp_title">JUX Real Estate</span> copyright (C) 2012 by JoomlaUX Solutions - <a href="http://www.joomlaux.com" target="_blank">http://www.joomlaux.com</a></p>';
    }

    /**
     * Get model for the component.
     */
    public static function getModel($type, $prefix = 'JUX_Real_EstateModel', $config = array()) {
	static $instance;

	if (!isset($instance[$type]) || !is_object($instance[$type])) {
	    $instance[$type] = JModelAdmin::getInstance($type, $prefix, $config);
	}
	return $instance[$type];
    }

    /**
     * Get component configuration.
     */
    public static function getConfigs() {
	static $instance;

	if (!is_object($instance)) {
	    $model = JUX_Real_EstateFactory::getModel('configs');
	    $instance = $model->getData();
	}
	return $instance;
    }

}