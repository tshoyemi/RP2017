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
 * JUX_Real_Estate Component - Types Controller
 * @package        JUX_Real_Estate
 * @subpackage    Controller
 */
class JUX_Real_EstateControllerTypes extends JControllerAdmin {

    /**
     * @var        string    The prefix to use with controller messages.
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_TYPES';

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
	$this->registerTask('unshowbanner', 'showbanner');
    }

    /**
     * Proxy for getModel.
     *
     * @param    string    $name    The name of the model.
     * @param    string    $prefix    The prefix for the PHP class name.
     *
     * @return    JModel
     */
    public function getModel($name = 'Type', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true)) {
	$model = parent::getModel($name, $prefix, $config);
	return $model;
    }

    /**
     * Method to toggle the showbanner setting of a list of types.
     *
     * @return    void
     * @since    1.6
     */
    function showbanner() {
	// Check for request forgeries
	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	// Initialise variables.
	$user = JFactory::getUser();
	$ids = JRequest::getVar('cid', array(), '', 'array');
	$values = array('showbanner' => 1, 'unshowbanner' => -1);
	$task = $this->getTask();
	$value = JArrayHelper::getValue($values, $task, 1, 'int');

	// Access checks.
	foreach ($ids as $i => $id) {
	    if (!$user->authorise('core.edit.state', 'com_jux_real_estate.type.' . (int) $id)) {
		// Prune items that you can't change.
		unset($ids[$i]);
		JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
	    }
	}

	if (empty($ids)) {
	    JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
	} else {
	    // Get the model.
	    $model = $this->getModel();

	    // approved the items.
	    if (!$model->showbanner($ids, $value)) {
		JError::raiseWarning(500, $model->getError());
	    } else {
		if ($value == 1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_SHOWED';
		} else if ($value == -1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_HIDED';
		}
		$this->setMessage(JText::plural($ntext, count($ids)));
	    }
	}

	$this->setRedirect('index.php?option=com_jux_real_estate&view=types');
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
