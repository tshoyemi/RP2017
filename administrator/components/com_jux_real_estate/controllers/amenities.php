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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controlleradmin');

class JUX_Real_EstateControllerAmenities extends JControllerAdmin {

    protected $text_prefix = 'COM_JUX_REAL_ESTATE';

    function __construct($config = array()) {
	parent::__construct($config);
    }

    public function getModel($name = 'Amenity', $prefix = 'JUX_Real_EstateModel', $config = array('ignore_request' => true)) {
	$model = parent::getModel($name, $prefix, $config);
	return $model;
    }

    public function saveCats() {
	// Check for request forgeries
	JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

	// Get items from the request.
	$cid = JRequest::getVar('cid', array(), '', 'array');
	$post = JRequest::get('post');

	if (empty($cid)) {
	    JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
	} else {
	    // Get the model.
	    $model = $this->getModel();

	    // Publish the items.
	    if ($count = $model->saveCats($cid, $post)) {
		$ntext = $this->text_prefix . '_N_ITEMS_SAVED';
		$this->setMessage(JText::plural($ntext, $count));
	    } else {
		JError::raiseWarning(500, $model->getError());
	    }
	}
	$this->setRedirect(JRoute::_('index.php?option=com_jux_real_estate&view=amenities', false));
    }

}

?>
