<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class JUX_Real_EstateViewCompanies extends JViewLegacy {

    function display($tpl = null) {
        $app = JFactory::getApplication();
        $option = JRequest::getCmd('option');

        JHTML::_('behavior.tooltip');

        $featured = '';
        $baseurl = JURI::root(true);
        $document = JFactory::getDocument();
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $permissions = new JUX_Real_EstatePermission();
        $tmpl = new JUX_Real_EstateTemplate();

        // get page layout
        $layout = $this->getLayout();

        // Get the parameters of the active menu item
        $menu = $app->getMenu()->getActive();


        $state = $this->get('State');
        $items = $this->get('Items');
        $total = $this->get('Total');
        $pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        $params = $state->params;

        if ($menu) {
            $params->def('page_heading', $params->def('page_title', $menu->title));
        } else {
            $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JOOM_PROPERTY'));
        }

        // Set the document page title
        // because the application sets a default page title, we need to get it
        // right from the menu item itself

        if (!$params->get('page_title')) {
            $params->set('page_title', $menu->name);
        } else {
            $params->set('page_title', $params->get('page_title'));
        }
        $document->setTitle($params->get('page_title'));
        if ($configs->get('com_show_featured'))
            $featured = $this->get('Featured');

        $extra = array();
        //$extra['permissions'] = $permissions;
        //	echo JUX_Real_EstateHTML::buildToolbar('companies', $extra);

        $company_image_width = ( $configs->get('company_image_width') ) ? $configs->get('company_image_width') : '90';
        $accent_color = ( $configs->get('accent') ) ? $configs->get('accent') : '#d2e4ea';
        $secondary_accent = ( $configs->get('secondary_accent') ) ? $configs->get('secondary_accent') : '#deeaff';
        $company_folder = $baseurl . '/images/joom_property/companies/';
        // BUILD SORT

        $lists = array();
        $lists['filter_order'] = JUX_Real_EstateHTML::buildCompanySortList('filter_order', $state->get('list.ordering'), 'class="input-medium" onchange="document.adminForm.submit();" title="' . JText::_('COM_JUX_REAL_ESTATE_FILTER_SEARCH_DESC') . '"');
        $lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="input-medium" onchange="document.adminForm.submit();"');
        $tmpl->set('lists', $lists);
        $tmpl->set('items', $items);
        $tmpl->set('params', $params);
        $tmpl->set('total', $total);
        $tmpl->set('state', $state);
        $tmpl->set('permissions', $permissions);
        $tmpl->set('pagination', $pagination);
        $tmpl->set('configs', $configs);
        $tmpl->set('company_image_width', $company_image_width);
        $tmpl->set('accent_color', $accent_color);
        $tmpl->set('secondary_accent', $secondary_accent);
        $tmpl->set('company_folder', $company_folder);
        $tmpl->set('featured', $featured);

        echo $tmpl->fetch('companies', $layout);
    }

}

?>
