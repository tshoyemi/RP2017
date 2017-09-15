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
/* * 
 * DEFINE CONSTANTS.
 */

define('JUX_REAL_ESTATE_COM_PATH', JPATH_ROOT . '/' . 'components' . '/' . 'com_jux_real_estate');
define('JUX_REAL_ESTATE_COM_PATH_ADMIN', JPATH_ROOT . '/' . 'administrator' . '/' . 'components' . '/' . 'com_jux_real_estate');
define('JUX_REAL_ESTATE_IMG', JURI::base() . 'components/com_jux_real_estate/libraries/image.php');

// permission return code
define('JUX_REAL_ESTATE_PERM_SUCCESS', 0);
define('JUX_REAL_ESTATE_PERM_DONE', 1);
define('JUX_REAL_ESTATE_PERM_GUEST', 2);
define('JUX_REAL_ESTATE_PERM_FAIL', 3);
define('JUX_REAL_ESTATE_PERM_AGENT_EXPIRED', 4);
define('JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_COUNT', 5);
define('JUX_REAL_ESTATE_PERM_AGENT_ADDED', 6);
define('JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_APPROVE', 7);
define('JUX_REAL_ESTATE_PERM_IS_AGENT', 8);
define('JUX_REAL_ESTATE_PERM_NOT_AGENT', 9);
define('JUX_REAL_ESTATE_PERM_AGENT_ADDED_EXTRA', 10);

require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'libraries' . '/' . 'factory.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'libraries' . '/' . 'fields.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'libraries' . '/' . 'route.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'libraries' . '/' . 'utils.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'helpers' . '/' . 'query.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'helpers' . '/' . 'html.helper.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'helpers' . '/' . 'route.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'libraries' . '/' . 'template.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'libraries' . '/' . 'permission.php');
require_once (JUX_REAL_ESTATE_COM_PATH . '/' . 'helpers' . '/' . 'juximage.php');
// require the library
require_once (JUX_REAL_ESTATE_COM_PATH_ADMIN . '/' . 'libraries' . '/' . 'image.php');
JLoader::register('JUX_Real_EstateHelper', JUX_REAL_ESTATE_COM_PATH_ADMIN . '/' . 'helpers' . '/' . 'jux_real_estate.php');

jimport('joomla.utilities.utility');
jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JUX_REAL_ESTATE_COM_PATH . '/' . 'models');