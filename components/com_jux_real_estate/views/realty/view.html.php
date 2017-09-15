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

/**
 * JUX_Real_Estate Component - Realty View
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewRealty extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {
	$this->get('JuxCount');
	$app = JFactory::getApplication();
	$user = JFactory::getUser();
	$document = JFactory::getDocument();

	$permissions = new JUX_Real_EstatePermission();
	// get page layout
	$layout = $this->getLayout();
	$configs = JUX_Real_EstateFactory::getConfiguration();

	// Get the parameters of the active menu item
	$params = $app->getParams();
	// Get some data from the model
	$item = $this->get('Item');
	if (isset($item->id) && $item->id) {
	    $item->extra_field_new = $this->get('ExtraField');
	}
        $type= $this->get('Type');
	$agentRealty = JUX_Real_EstateModelRealty::getItemRelate($item->agent_id, $item->id);
	$amenities = $this->get('Amenities');
	//get Agent options
	$isOwner = 0;
	if ($item->user_id && $item->user_id == $user->get('id'))
	    $isOwner = 1;

	//extra fields object
	$JUXFields = new JUX_Real_EstateFields();
	if ($JUXFields->getTotal()) {
	    $customField = true;
	    $fields = $JUXFields->getCustomFields((int) $item->id, $isOwner);
	} else {
	    $customField = false;
	}
	$extra = array();
	$extra['permissions'] = $permissions;
	$extra['item'] = $item;

	$agent_image_width = ( $configs->get('agent_image_width')) ? $configs->get('agent_image_width') : '90';
	$agents_folder = JURI::root(true) . '/images/joom_property/agents/';

	// check if we have a category
	if (!is_object($item)) {
	    JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_REALTY_NOT_FOUND'));
	    return;
	}

	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself

	if (!$params->get('page_title')) {
	    $params->set('page_title', $item->title);
	} else {
	    $params->set('page_title', $item->title);
	}
	$document->setTitle($params->get('page_title'));

	if ($item->price) {
	    $item->price = ' <span class="item-price">(<b>' . JUX_Real_EstateUtils::formatPrice($item->price, $item->currency_id, $configs->get('thousand_separator')) . '</b>)</span>';
	} else {
	    $item->price = ' <span class="item-price">(<b>' . JText::_('COM_JUX_REAL_ESTATE_CONTACT_FOR_PRICE') . '</b>)</span>';
	}

	// Compute view access permissions.
	$groups = $user->getAuthorisedViewLevels();
	$params->set('access-view', in_array($item->access, $groups));

	$extras_array = array("beds" => JText::_('COM_JUX_REAL_ESTATE_BEDS'),
	    "baths" => JText::_('COM_JUX_REAL_ESTATE_BATHS'),
	    "sqft" => (!$configs->get('measurement_units')) ? JText::_('COM_JUX_REAL_ESTATE_SQFT') : JText::_('COM_JUX_REAL_ESTATE_SQM'),
	    "lotsize" => JText::_('COM_JUX_REAL_ESTATE_LOT_SIZE'),
	    "lot_acres" => JText::_('COM_JUX_REAL_ESTATE_LOT_ACRES'),
	    "yearbuilt" => JText::_('COM_JUX_REAL_ESTATE_YEAR_BUILT'),
	    "heat" => JText::_('COM_JUX_REAL_ESTATE_HEAT'),
	    "garage_type" => JText::_('COM_JUX_REAL_ESTATE_GARAGE_TYPE'),
	    "roof" => JText::_('COM_JUX_REAL_ESTATE_ROOF'));

	// tabs
	jimport('joomla.html.html.tabs');
	JUX_Real_EstateFactory::renderMetaData($item);
	
	$this->item = $item;
        
	$this->params = $params;
	$this->configs = $configs;
	$this->amenities = $amenities;
	$this->agent_image_width = $agent_image_width;
	$this->agents_folder = $agents_folder;
	$this->extras_array = $extras_array;
	$this->customField = $customField;
	$this->user = $user;
	$this->agentRealty = $agentRealty;
        
        $this->type = $type;
      
	parent::display($tpl);
    }

}

?>