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
 * JUX_Real_Estate Component - Category
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewCategory extends JViewLegacy {

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
        $category = $this->get('Category');
        $pagination = $this->get('Pagination');
        
        if ($configs->get('show_featured'))
            $featured = $this->get('Featured');

        $extra = array();
        //$extra['agentid']  = JRequest::getVar('agentid');
        //$extra['permissions'] = $permissions;
        echo JUX_Real_EstateHTML::buildToolbar('category', $extra);


        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        // Check whether category access level allows access.
        $user = JFactory::getUser();
        $groups = $user->getAuthorisedViewLevels();
        //if (!in_array($category->access, $groups)) {
        //    return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
        //}

        $params = $state->params;
        $params->def('num_intro_realties', $configs->get('num_intro_realties'));

        if ($menu) {
            $params->def('page_heading', $params->def('page_title', $menu->title));
        } else {
            $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_CATEGORY'));
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

        $category_folder = JURI::root(true) . '/images/joom_property/categories/';
        $thumb_width = ($configs->get('thumbwidth')) ? $configs->get('thumbwidth') . 'px' : '200px';
        $thumb_height = round((($thumb_width) / 1.5), 0) . 'px';
        $enable_featured = $configs->get('show_featured');


        $lists = array();
        $lists['filter_order'] = JUX_Real_EstateHTML::buildRealtySortList('filter_order', $state->get('list.ordering'), 'class="input-medium" onchange="document.adminForm.submit();"');
        $lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="input-medium" onchange="document.adminForm.submit();"');
        //$lists['cat_id'] = JHTML::_('joom_property.catSelectList', 'cat_id', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.cat_id'));
        $lists['type_id'] = JUX_Real_EstateHelperQuery::getTypesList('type_id', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.type_id'), true);
        $lists['city'] = JUX_Real_EstateHelperQuery::getCityList('city', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.city'), false, $state->get('list.locastate'));
        //$lists['state'] = JUX_Real_EstateHelperQuery::getStateList('locstate', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.locstate'), true);
        //$lists['province'] = JUX_Real_EstateHelperQuery::getProvinceList('province', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.province'));
        //$lists['county'] = JUX_Real_EstateHelperQuery::getCountyList('county', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.county'));
        //$lists['region'] = JUX_Real_EstateHelperQuery::getRegionList('region', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.region'));
        //$lists['country_id'] = JUX_Real_EstateHelperQuery::getCountryList('country_id', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.country_id'), true);



        $tmpl->set('permissions', $permissions);
        $tmpl->set('items', $items);
        $tmpl->set('category', $category);
        $tmpl->set('params', $params);
        $tmpl->set('featured', $featured);
        $tmpl->set('total', $total);
        $tmpl->set('state', $state);
        $tmpl->set('pagination', $pagination);
        $tmpl->set('configs', $configs);
        $tmpl->set('lists', $lists);
        $tmpl->set('category_folder', $category_folder);
        $tmpl->set('thumb_width', $thumb_width);
        $tmpl->set('thumb_height', $thumb_height);
        $tmpl->set('enable_featured', $enable_featured);

        echo $tmpl->fetch('category', $layout);
    }

}

?>