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
 * JUX_Real_Estate Component - Categories View
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewCategories extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $document = JFactory::getDocument();
        $config = JUX_Real_EstateFactory::getConfiguration();
        $tmpl = new JUX_Real_EstateTemplate();

        // get page layout
        $layout = $this->getLayout();

        // Get the parameters of the active menu item

        $menu = $app->getMenu()->getActive();
        //
        //echo $p
        // Get some data from the model
        $state = $this->get('State');
        $items = $this->get('Items');

        //var_dump($items);die;
        $total = $this->get('Total');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        if ($items === false) {
            JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_CATEGORIES_NOT_FOUND'));
            return false;
        }


        $params = $state->params;
        // Parameters
        $params->def('num_intro_categories', 4);
        $params->def('show_headings', 1);
        $params->def('show_pagination', 2);
        $params->def('show_pagination_results', 1);
        $params->def('show_pagination_limit', 1);
        $params->def('max_desc', 100);
        $image_path = $params->get('image');
        // Set the document page title
        // because the application sets a default page title, we need to get it
        // right from the menu item itself
        if (is_object($menu) && isset($menu->query['option']) && $menu->query['option'] == 'com_jux_real_estate' && isset($menu->query['view']) && $menu->query['view'] == 'categories') {
            if ($menu) {
                $params->def('page_heading', $params->def('page_title', $menu->title));
            } else {
                $params->def('page_heading', JText::_('COM_JUX_REAL_ESTATE_JOOM_PROPERTY'));
            }

            if (!$params->get('page_title')) {

                $params->set('page_title', $menu->title);
            } else {
                $params->set('page_title', $params->get('page_title'));
            }
        } else {
            $params->set('page_title', $params->get('page_title') ? $params->get('page_title') : JText::_('COM_JUX_REAL_ESTATE_CATEGORIES'));
        }
        $document->setTitle($params->get('page_title'));

        // add pathway
        if (!is_object($menu) || !isset($menu->query['option']) || $menu->query['option'] != 'com_jux_real_estate' || !isset($menu->query['view']) || $menu->query['view'] != 'categories') {
            $pathway->addItem(@$menu->name ? JText::_(@$menu->name) : JText::_('COM_JUX_REAL_ESTATE_CATEGORIES'));
        }
        $pagination = $this->get('Pagination');
        
        $tmpl->set('state', $state);
        $tmpl->set('items', $items);
        $tmpl->set('total', $total);
        $tmpl->set('params', $params);
        $tmpl->set('pagination', $pagination);
        $tmpl->set('config', $config);
        echo $tmpl->fetch('categories', $layout);
    }

}

?>