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
 * JUX_Real_Estate Component - List View
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewList extends JViewLegacy {

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
        $user = JFactory::getUser();
        $pathway = $app->getPathway();
        $document = JFactory::getDocument();



        $configs = JUX_Real_EstateFactory::getConfiguration();
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
        $tmpl = new JUX_Real_EstateTemplate();
        $permissions = new JUX_Real_EstatePermission();
        // get page layout
        $layout = $this->getLayout();

        // Selected Request vars
        // ID may come from the contact switcher
        $catid = JRequest::getInt('catid', 0);
        $typeid = JRequest::getInt('typeid', 0);

        $model = $this->getModel();
        // Get the parameters of the active menu item
        $menus = $app->getMenu();
        $menu = $menus->getActive();


        // Get some data from the model
        $state = $this->get('State');
        $params = $state->params;

        $items = $this->get('Items');
        $total = $this->get('Total');
        $category = $this->get('Category');
        $type = $this->get('Type');
        $pagination = $this->get('Pagination');

        // Set the document page title
        // because the application sets a default page title, we need to get it
        // right from the menu item itself
        //if (is_object($menu) && isset($menu->query['option']) && $menu->query['option'] == 'com_jux_real_estate' && isset($menu->query['view']) && $menu->query['view'] == 'list') {
        $title = '';
        if ($typeid && $catid) {
            $title = "$category - $type";
        } elseif ($typeid) {
            $title = $type;
        } elseif ($catid) {
            $title = $category;
        } else {
            if (!$params->get('page_title')) {
                $params->set('page_title', JText::_('COM_JUX_REAL_ESTATE_ALL_REALTY'));
            } else {
                $params->set('page_title', $params->get('page_title'));
            }
            $title = $params->get('page_title');
        }

        $params->set('page_title', $title);

        $document->setTitle($title);

        //set breadcrumbs
        if (is_object($menu) && isset($menu->query['view']) && $menu->query['view'] == 'list' && !isset($menu->query['layout'])) {
            if (isset($menu->query['typeid']) && $menu->query['typeid'] != $typeid) {
                if ($typeid && $catid) {
                    $link = "index.php?option=com_jux_real_estate&view=list&typeid=$typeid&catid=0";
                    $pathway->addItem($type, $link);
                    $pathway->addItem($category);
                } elseif ($typeid) {
                    $pathway->addItem($type);
                } elseif ($catid) {
                    $pathway->addItem($category);
                }
            } elseif (isset($menu->query['catid']) && $menu->query['catid'] != $catid) {
                if ($catid) {
                    $pathway->addItem($category);
                }
            }
        } else {
            if ($typeid && $catid) {
                $link = "index.php?option=com_jux_real_estate&view=list&typeid=$typeid&catid=0";
                $pathway->addItem($type, $link);
                $pathway->addItem($category);
            } elseif ($typeid) {
                $pathway->addItem($type);
            } elseif ($catid) {
                $pathway->addItem($category);
            }
        }

        $tmpl->set('total', $total);
        $tmpl->set('items', $items);
        $tmpl->set('params', $params);
        $tmpl->set('pagination', $pagination);
        $tmpl->set('configs', $configs);
        $tmpl->set('permissions', $permissions);
        $tmpl->set('catid', $catid);
        $tmpl->set('typeid', $typeid);
        echo $tmpl->fetch('list', $layout);
    }

}

?>