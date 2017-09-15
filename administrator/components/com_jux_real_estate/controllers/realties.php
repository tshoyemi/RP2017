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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Realties list controller class.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 * @since    1.6
 */
class JUX_Real_EstateControllerRealties extends JControllerAdmin {

    /**
     * @var        string    The prefix to use with controller messages.
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_REALTIES';

    /**
     * Constructor.
     *
     * @param    array    $config    An optional associative array of configuration settings.
     * @return    JUX_Real_EstateControllerRealties
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {

	parent::__construct($config);

	$this->registerTask('rejected', 'approved');
	$this->registerTask('unfeatured', 'featured');
	$this->registerTask('sold', 'sale');
	$this->registerTask('pedding', 'approved');
    }

    /**
     * Method to toggle the approved setting of a list of realties.
     *
     * @return    void
     * @since    1.6
     */
    function approved() {
	// Check for request forgeries
	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	// Initialise variables.
	$user = JFactory::getUser();
	$ids = JRequest::getVar('cid', array(), '', 'array');
	$values = array('approved' => 1, 'rejected' => -1, 'pedding' => 0);
	$task = $this->getTask();
	$value = JArrayHelper::getValue($values, $task, 1, 'int');

	// Access checks.
	foreach ($ids as $i => $id) {
	    if (!$user->authorise('core.edit.state', 'com_jux_real_estate.realty.' . (int) $id)) {
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
	    if (!$model->approved($ids, $value)) {
		JError::raiseWarning(500, $model->getError());
	    } else {
		if ($value == 1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_APPROVED';
		} else if ($value == -1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_REJECTED';
		} else if ($value == 0) {
		    $ntext = $this->text_prefix . '_N_ITEMS_PENDDING';
		}
		$this->setMessage(JText::plural($ntext, count($ids)));
	    }
	}

	$this->setRedirect('index.php?option=com_jux_real_estate&view=realties');
    }

    /**
     * Method to toggle the featured setting of a list of realties.
     *
     * @return    void
     * @since    1.6
     */
    function featured() {
	// Check for request forgeries
	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	// Initialise variables.
	$user = JFactory::getUser();
	$ids = JRequest::getVar('cid', array(), '', 'array');
	$values = array('featured' => 1, 'unfeatured' => -1);
	$task = $this->getTask();
	$value = JArrayHelper::getValue($values, $task, 1, 'int');

	// Access checks.
	foreach ($ids as $i => $id) {
	    if (!$user->authorise('core.edit.state', 'com_jux_real_estate.realty.' . (int) $id)) {
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
	    if (!$model->feature($ids, $value)) {
		JError::raiseWarning(500, $model->getError());
	    } else {
		if ($value == 1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_FEATURED';
		} else if ($value == -1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_UNFEATURED';
		}
		$this->setMessage(JText::plural($ntext, count($ids)));
	    }
	}

	$this->setRedirect('index.php?option=com_jux_real_estate&view=realties');
    }

    /**
     * Method to toggle the sold setting of a list of realties.
     *
     * @return    void
     * @since    1.6
     */
    function sale() {
	// Check for request forgeries
	JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

	// Initialise variables.
	$user = JFactory::getUser();
	$ids = JRequest::getVar('cid', array(), '', 'array');
	$values = array('sale' => 1, 'sold' => 0);
	$task = $this->getTask();
	$value = JArrayHelper::getValue($values, $task, 0, 'int');

	// Access checks.
	foreach ($ids as $i => $id) {
	    if (!$user->authorise('core.edit.state', 'com_jux_real_estate.realty.' . (int) $id)) {
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

	    // sold the items.
	    if (!$model->sale($ids, $value)) {
		JError::raiseWarning(500, $model->getError());
	    } else {
		if ($value == 1) {
		    $ntext = $this->text_prefix . '_N_ITEMS_SALE';
		} else if ($value == 0) {
		    $ntext = $this->text_prefix . '_N_ITEMS_SOLD';
		}
		$this->setMessage(JText::plural($ntext, count($ids)));
	    }
	}

	$this->setRedirect('index.php?option=com_jux_real_estate&view=realties');
    }

    /**
     * Proxy for getModel.
     *
     * @param    string    $name    The name of the model.
     * @param    string    $prefix    The prefix for the PHP class name.
     *
     * @return    JModel
     * @since    3.0
     */
    public function getModel($name = 'Realty', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true)) {
	$model = parent::getModel($name, $prefix, $config);

	return $model;
    }

    public function saveOrderAjax() {
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
