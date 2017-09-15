<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * JUX_Real_Estate Component - Company agents
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewCompanyagents extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {

        $app = JFactory::getApplication();
        $document = JFactory::getDocument();
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $tmpl = new JUX_Real_EstateTemplate();
        $permissions = new JUX_Real_EstatePermission();
        $featured = '';
        // get page layout
        $layout = $this->getLayout();

        // Get the parameters of the active menu item
        $menu = $app->getMenu()->getActive();
        // Get some data from the model
        $state = $this->get('State');
        $items = $this->get('Items');
        $total = $this->get('Total');
        $company = $this->get('Company');
        $pagination = $this->get('Pagination');
        if ($configs->get('agent_show_featured'))
            $featured = $this->get('Featured');

        $extra = array();
        $extra['id'] = JRequest::getVar('id');
        $extra['permissions'] = $permissions;
        $extra['item'] = $company;
        //      echo JUX_Real_EstateHTML::buildToolbar('company', $extra);
        $company_name = ($company->name);

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        if (!$company) {
            JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_COMPANY_NOT_FOUND'));
            return false;
        }

        $params = $state->params;

        $params->def('num_intro_agents', $configs->get('num_intro_agents'));
        $params->def('max_desc', $configs->get('max_desc_agents'));

        // page heading
        if ($company_name) {
            $params->def('page_heading', $company_name);
        } else {
            $params->def('page_heading', $params->def('page_title', $menu->title));
        }

        // Set the document page title
        // because the application sets a default page title, we need to get it
        // right from the menu item itself

        $title = '';
        if ($company_name) {
            $title = "$company_name";
        } else {
            if (!$params->get('page_title')) {
                $params->set('page_title', $menu->name);
            } else {
                $params->set('page_title', $params->get('page_title'));
            }
            $title = $params->get('page_title');
        }
        $params->set('page_title', $title);

        $document->setTitle($title);
        $agent_image_width = ($configs->get('agent_image_width')) ? $configs->get('agent_image_width') : '90';
        $agents_folder = JURI::root(true) . '/images/joom_property/agents/';
        $company_image_width = ($configs->get('company_image_width')) ? $configs->get('company_image_width') : '90';
        $companies_folder = JURI::root(true) . '/images/joom_property/companies/';
        $thumb_width = ($configs->get('thumbwidth')) ? $configs->get('thumbwidth') . 'px' : '200px';
        $thumb_height = round((($thumb_width) / 1.5), 0) . 'px';
        $enable_featured = $configs->get('show_featured');
        $picfolder = JURI::root(true) . $configs->get('imgpath');



        $lists = array();
        $lists['filter_order'] = JUX_Real_EstateHTML::buildAgentSortList('filter_order', $state->get('list.ordering'), 'class="input-medium" onchange="document.adminForm.submit();" title="' . JText::_('COM_JUX_REAL_ESTATE_FILTER_SEARCH_DESC') . '"');
        $lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="input-medium" onchange="document.adminForm.submit();"');
        $tmpl->set('lists', $lists);
        $tmpl->set('permissions', $permissions);
        $tmpl->set('items', $items);
        $tmpl->set('company', $company);
        $tmpl->set('params', $params);
        $tmpl->set('featured', $featured);
        $tmpl->set('total', $total);
        $tmpl->set('state', $state);
        $tmpl->set('pagination', $pagination);
        $tmpl->set('configs', $configs);
        $tmpl->set('lists', $lists);
        $tmpl->set('agent_image_width', $agent_image_width);
        $tmpl->set('agents_folder', $agents_folder);
        $tmpl->set('company_image_width', $company_image_width);
        $tmpl->set('companies_folder', $companies_folder);
        $tmpl->set('thumb_width', $thumb_width);
        $tmpl->set('thumb_height', $thumb_height);
        $tmpl->set('enable_featured', $enable_featured);
        $tmpl->set('picfolder', $picfolder);

        echo $tmpl->fetch('companyagents', $layout);
    }

}

?>