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
 * JUX_Real_Estate Component - Featured
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewFeatured extends JViewLegacy
{

    /**
     * Display
     *
     */
    function display($tpl = null)
    {

        $app = JFactory::getApplication();
        $document = & JFactory::getDocument();
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
        $state = &$this->get('State');
        $items = &$this->get('Items');

        $total = &$this->get('Total');
        $pagination = $this->get('Pagination');
        $extra = array();
        //$extra['companyid']  = JRequest::getVar('companyid');
        $extra['permissions'] = $permissions;
        echo JUX_Real_EstateHTML::buildToolbar('featured', $extra);


        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        $params = $state->params;

        $params->def('num_leading_realties', 2);
        $params->def('num_intro_realties', 4);
        $params->def('num_columns', 2);

        if ($menu) {
            $params->def('page_heading', $params->def('page_title', $menu->title));
        } else {
            $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JUX_REAL_ESTATE'));
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

        $thumb_width = ($configs->get('thumbwidth')) ? $configs->get('thumbwidth') . 'px' : '200px';
        $thumb_height = round((($thumb_width) / 1.5), 0) . 'px';
        $enable_featured = $configs->get('show_featured');
        $picfolder = JURI::root(true) . $configs->get('imgpath');



        $lists = array();
        $lists['filter_order'] = JUX_Real_EstateHTML::buildRealtySortList('filter_order', $state->get('list.ordering'), 'class="inputbox" onchange="document.adminForm.submit();"');
        $lists['filter_order_Dir'] = JUX_Real_EstateHTML::buildOrderList('filter_order_Dir', $state->get('list.direction'), 'class="inputbox" onchange="document.adminForm.submit();"');
//        $lists['cat_id'] = JHTML::_('jux_real_estate.catSelectList', 'cat_id', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.cat_id'));
        $lists['type_id'] = JUX_Real_EstateHelperQuery::getTypesList('type_id', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.type_id'), true);
        $lists['city'] = JUX_Real_EstateHelperQuery::getCityList('city', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.city') , false, $state->get('list.locastate'));
        //$lists['state'] = JUX_Real_EstateHelperQuery::getStateList('locstate', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.locstate'), true);
        //$lists['province'] = JUX_Real_EstateHelperQuery::getProvinceList('province', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.province'));
        //$lists['county'] = JUX_Real_EstateHelperQuery::getCountyList('county', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.county'));
        //$lists['region'] = JUX_Real_EstateHelperQuery::getRegionList('region', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.region'));
        //$lists['country_id'] = JUX_Real_EstateHelperQuery::getCountryList('country_id', 'class="inputbox" onchange="document.adminForm.submit();"', $state->get('list.country_id'), true);


        $tmpl->set('permissions', $permissions);
        $tmpl->set('items', $items);
        $tmpl->set('params', $params);
        $tmpl->set('featured', $featured);
        $tmpl->set('total', $total);
        $tmpl->set('state', $state);
        $tmpl->set('pagination', $pagination);
        $tmpl->set('configs', $configs);
        $tmpl->set('lists', $lists);
        $tmpl->set('thumb_width', $thumb_width);
        $tmpl->set('thumb_height', $thumb_height);
        $tmpl->set('enable_featured', $enable_featured);
        $tmpl->set('picfolder', $picfolder);

        echo $tmpl->fetch('featured', $layout);
    }

}

?>