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

jimport('joomla.application.component.controlleradmin');

/**
 * JUX_Real_Estate Component - Currencies Controller
 * @package        JUX_Real_Estate
 * @subpackage    Controller
 */
class JUX_Real_EstateControllerCurrencies extends JControllerAdmin {

    /**
     * @var        string    The prefix to use with controller messages.
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_CURRENCIES';

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    function __construct() {
	parent::__construct();

	// register extra tasks
    }

    /**
     * Proxy for getModel.
     *
     * @param    string    $name    The name of the model.
     * @param    string    $prefix    The prefix for the PHP class name.
     *
     * @return    JModel
     */
    public function getModel($name = 'Currency', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true)) {
	$model = parent::getModel($name, $prefix, $config);
	return $model;
    }

    public function saveOrderAjax() {
	// Get the input
	$pks = $this->input->post->get('cid', array(), 'array');
	$order = $this->input->post->get('order', array(), 'array');

	// Sanitize the input
	JArrayHelper::toInteger($pks);
	JArrayHelper::toInteger($order);

	// Get the model
	$model = $this->getModel();

	// Save the ordering
	$return = $model->saveorder($pks, $order);

	if ($return) {
	    echo "1";
	}

	// Close the application
	JFactory::getApplication()->close();
    }

}
