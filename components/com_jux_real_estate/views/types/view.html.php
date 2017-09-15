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
 * JUX_Real_Estate Component - Types View
 * @package        JUX_Real_Estate
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewTypes extends JViewLegacy {

    /**
     * Display
     *
     */
    function display($tpl = null) {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $document = JFactory::getDocument();
        $configs = JUX_Real_EstateFactory::getConfiguration();
        $tmpl = new JUX_Real_EstateTemplate();

        // get page layout
        $layout = $this->getLayout();

        // Get the parameters of the active menu item

        $menu = $app->getMenu()->getActive();

        // Get some data from the model
        $state = $this->get('State');
        $items = $this->get('Items');
        $total = $this->get('Total');
        $pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        if ($items === false) {
            JError::raiseError(404, JText::_('COM_JUX_REAL_ESTATE_TYPES_NOT_FOUND'));
            return false;
        }

        $params = $state->params;

        // Set the document page title
        // because the application sets a default page title, we need to get it
        // right from the menu item itself
        if (is_object($menu) && isset($menu->query['option']) && $menu->query['option'] == 'com_jux_real_estate' && isset($menu->query['view']) && $menu->query['view'] == 'types') {
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
            $params->set('page_title', $params->get('page_title') ? $params->get('page_title') : JText::_('COM_JUX_REAL_ESTATE_TYPES'));
        }
        $document->setTitle($params->get('page_title'));

        // add pathway
        if (!is_object($menu) || !isset($menu->query['option']) || $menu->query['option'] != 'com_jux_real_estate' || !isset($menu->query['view']) || $menu->query['view'] != 'types') {
            $pathway->addItem(@$menu->name ? JText::_(@$menu->name) : JText::_('COM_JUX_REAL_ESTATE_TYPES'));
        }
        $tmpl->set('state', $state);
        $tmpl->set('items', $items);
        $tmpl->set('total', $total);
        $tmpl->set('params', $params);
        $tmpl->set('pagination', $pagination);
        $tmpl->set('configs', $configs);
        echo $tmpl->fetch('types', $layout);
    }

}

?>