<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jse_libraries_template
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

/**
 * JUX_Language Factory Libraries
 * @package		JUX_Language
 * @subpackage	Libraries
 * @since		1.0
 *
 */
class JUX_LanguageFactory {
	
	/**
	 * Get credits footer string
	 * 
	 */
	public static function getFooter() {
		return '<p class="jp_copyright" align="center"><span class="jp_title">JUX Language</span> copyright (C) 2012 by JoomlaUX Solutions - <a href="http://www.joomlaux.com" target="_blank">http://www.joomlaux.com</a></p>';
	}

	function &getModel($type, $prefix = 'JUX_LanguageModel', $config = array()) {
		static $instance;

		if(!isset($instance[$type]) || !is_object($instance[$type])) {
			$instance[$type] = JModelAdmin::getInstance($type, $prefix, $config);
		}

		return $instance[$type];
	}

	/**
	 * Get component configuration.
	 */
	function getConfigs() {
		static $instance;

		if (!is_object($instance)) {
			$model		= &JUX_LanguageFactory::getModel('configs');
			$instance	= $model->getData();
		}

		return $instance;
	}
}