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

jimport('joomla.application.component.view');

/**
 * JUX_Real_Estate Component - Submit a realty form HTML View
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewForm extends JViewLegacy {

    /**
     * Display
     *
     */
    protected $gallery;

    function display($tpl = null) {

	$app = JFactory::getApplication();
	$user = JFactory::getUser();

	$permission = JUX_Real_EstatePermission::userCanAddagent($user->get('id'));
	if ($permission == 9 || $permission == 2) {
	    $itemIdPlan = JUX_Real_EstateHelperRoute::getAgentPlan();
	    $url = JUX_Real_EstateRoute::getURI();
	    $app->redirect(JRoute::_($itemIdPlan, false), JText::_('COM_JUX_REAL_ESTATE_PLEASE_SIGNUP_TO_PERFORM_THIS_TASK'));
	    return false;
	}

	$db = JFactory::getDBO();
	$request = JRequest::get();
	$document = JFactory::getDocument();
	$language = $document->getLanguage();
	$state = $this->get('State');
	$return_page = $this->get('ReturnPage');
	$params = $state->params;
	$form = $this->get('Form');
	$item = $this->get('Item');
	$agent = $this->get('Agent');
	$this->gallery = $this->get('Images');
	$permissions = new JUX_Real_EstatePermission();
	$img_src = JURI::base() . 'components/com_jux_real_estate/templates/default/images/';
	// get page layout
	$layout = $this->getLayout();
	//Get Configuration model
	$config = JUX_Real_EstateFactory::getConfiguration();


	if (!empty($item)) {
	    $form->bind($item);
	}

	// add reCaptcha script
	if ($config->get('enable_recaptcha')) {
	    $RecaptchaOptions = "
                    var RecaptchaOptions = {
                        theme : '" . $config->get('theme_recaptcha') . "',
                        lang  : '" . substr($language, 0, 2) . "'
                    };
                ";
	    $document->addScriptDeclaration($RecaptchaOptions);
	}

	//extra fields object
	$JUXFields = new JUX_Real_EstateFields();

	if ($JUXFields->getTotal()) {
	    $customField = true;
	    if ($item->id)
		$fields = $JUXFields->renderCustomFieldsEdit($item->id);
	    else
		$fields = $JUXFields->renderCustomFields();

	    $validations = $JUXFields->renderJSValidation();
	    $this->fields = $fields;
	    $this->validations = $validations;
	} else {
	    $customField = false;
	}
	//Check if user is owner
	$query = "SELECT id FROM #__re_realties WHERE user_id = " . $user->get('id');
	$db->setQuery($query);
	$isOwner = $db->loadResult();

	//get mods options
	$isAgentPost = 0;
	$sql = "SELECT id FROM #__re_agents WHERE user_id = " . $user->get('id') . " AND published = 1 LIMIT 1";
	$db->setQuery($sql);

	//moderator ID
	$isAgentPost = $db->loadResult();

	//who can post realty
	$id = (int) JRequest::getVar('id', 0);
	$user_access = 0;
	switch ($config->get('user_access')) {

	    //Guest can post realty
	    case 1:
		if (!$item->id)
		    $user_access = 1;
		elseif ($item->user_id == $user->get('id'))
		    $user_access = 1;
		break;

	    //All registered users can post
	    case 2:
		if ($user->get('id')) {
		    //if is owner
		    if (($item->id && $item->user_id == $user->get('id')) || $isAgentPost)
			$user_access = 1;
		    elseif (!$item->id)
			$user_access = 1;
		} else {
		    $msg = JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK');
		    $uri = JRequest::getVar('REQUEST_URI', '', 'server', 'string');
		    $url = base64_encode($uri);
		    $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), $msg);
		}
		break;

	    //Agents only
	    case 3:
	    default:
		break;
	}
	//Agent can post any where
	if ($isAgentPost)
	    $user_access = 1;
	//hack item id
	if ($item->id != $id)
	    $user_access = 0;
//        //Get all item images,
	$images = array();
	if (isset($item->images)) {
	    $images = explode(',', $item->images);
	}

	// Get the parameters of the active menu item
	//$pathway	= &$mainframe->getPathway();
	$menus = $app->getMenu();
	$menu = $menus->getActive();


	// Set the document page title
	// because the application sets a default page title, we need to get it
	// right from the menu item itself
	$default_title = ($item->title) ? JText::_('COM_JUX_REAL_ESTATE_EDIT') . ":" . $item->title : @$menu->name;
	if (is_object($menu) && isset($menu->query['view']) && $menu->query['view'] == 'form') {
	    if (!$params->get('page_title')) {
		$params->set('page_title', $default_title);
	    }
	} else {
	    $params->set('page_title', $default_title);
	}
	$document->setTitle($params->get('page_title'));

	$sql = 'SELECT id, username AS name, user_id FROM #__re_agents WHERE published = 1 ORDER BY name';
	$db->setQuery($sql);
	$rows = $db->loadObjectList();
	$options = array();
	$options[] = JHTML::_('select.option', 0, JText::_('COM_JUX_REAL_ESTATE_SELECT_AN_AGENT'), 'id', 'name');
	foreach ($rows as $row) {
	    if ($row->user_id != $user->get('id'))
		$options[] = JHTML::_('select.option', $row->id, JText::_($row->name), 'id', 'name');
	}
	$lists['agent_id'] = JHTML::_('select.genericlist', $options, 'agent_id', ' class="inputbox required"', 'id', 'name', (int) $item->agent_id);
	// auto approve
	if (!$item->id && $config->get('auto_approve'))
	    $item->approved = 1;

	$live_site = JURI::base(true);


	$this->live_site = $live_site;
	$this->request = $request;
	$this->item = $item;
	$this->lists = $lists;
	$this->config = $config;
	$this->customField = $customField;
	$this->isAgentPost = $isAgentPost;
	$this->isOwner = $isOwner;
	$this->params = $params;
	$this->user_access = $user_access;
	$this->permissions = $permissions;
	$this->return_page = $return_page;
	$this->form = $form;
	$this->state = $state;
	$this->agent = $agent;
	$this->images = $images;
	$this->img_src = $img_src;
	parent::display($tpl);
    }

}

?>