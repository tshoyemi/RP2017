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
 * JUX_Real_Estate Component - Company realties
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewCompanyrealties extends JViewLegacy {

    /**
     * Display
     *
     */
    protected $state = null;
    protected $item = null;
    protected $items = null;
    protected $pagination = null;

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
        $pagination = $this->get('Pagination');

        $company = $this->get('Company');
        $company_name = ($company->name);
        if ($configs->get('show_featured'))
            $featured = $this->get('Featured');

        $extra = array();
        //$extra['companyid']  = JRequest::getVar('companyid');
        $extra['permissions'] = $permissions;
        $extra['item'] = $company;
//        echo JUX_Real_EstateHTML::buildToolbar('company', $extra);
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

//        $params->def('num_intro_realties', $configs->get('num_intro_realties'));

        if ($menu) {
            $params->def('page_heading', $params->def('page_title', $menu->title));
        } else {
            $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JOOM_PROPERTY'));
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
        $company_image_width = ($configs->get('company_image_width')) ? $configs->get('company_image_width') : '90';
        $company_folder = JURI::root(true) . '/images/joom_property/companies/';
        $thumb_width = ($configs->get('thumbwidth')) ? $configs->get('thumbwidth') . 'px' : '200px';
        $thumb_height = round((($thumb_width) / 1.5), 0) . 'px';
        $enable_featured = $configs->get('show_featured');
        $picfolder = JURI::root(true) . $configs->get('imgpath');



        $lists = array();
        $lists['filter_order'] = JUX_Real_EstateHTML::buildRealtySortList('filter_order', $state->get('list.ordering'), 'class="input-medium" onchange="document.adminForm.submit();"');
        $lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="input-medium" onchange="document.adminForm.submit();"');
        $lists['type_id'] = JUX_Real_EstateHelperQuery::getTypesList('type_id', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.type_id'), true);
        $lists['city'] = JUX_Real_EstateHelperQuery::getCityList('city', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.city'), false, $state->get('list.locastate'));
        //$lists['state'] = JUX_Real_EstateHelperQuery::getStateList('locstate', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.locstate'), true);
        //$lists['province'] = JUX_Real_EstateHelperQuery::getProvinceList('province', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.province'));
        //$lists['county'] = JUX_Real_EstateHelperQuery::getCountyList('county', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.county'));
        //$lists['region'] = JUX_Real_EstateHelperQuery::getRegionList('region', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.region'));
        //$lists['country_id'] = JUX_Real_EstateHelperQuery::getCountryList('country_id', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.country_id'), true);
        //$lists['cat_id'] = JHTML::_('joom_property.catSelectList', 'cat_id', 'class="input-medium" onchange="document.adminForm.submit();"', $state->get('list.cat_id'));

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
        $tmpl->set('company_image_width', $company_image_width);
        $tmpl->set('company_folder', $company_folder);
        $tmpl->set('thumb_width', $thumb_width);
        $tmpl->set('thumb_height', $thumb_height);
        $tmpl->set('enable_featured', $enable_featured);
        $tmpl->set('picfolder', $picfolder);

        echo $tmpl->fetch('companyrealties', $layout);
    }

}

?>