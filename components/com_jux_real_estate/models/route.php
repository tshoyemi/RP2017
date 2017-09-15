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

/**
 * JUX_Real_Estate Component - Route Model
 * @package        JUX_Real_Estate
 * @subpackage    Model
 */
class JUX_Real_EstateModelRoute extends JModelLegacy
{
    /** @var array Itemid array */
    var $_itemid = null;
    /** @var int Default Itemid */
    var $_default_itemid = null;

    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get component Itemid.
     */
    function getItemid($view = null, $id = null, $url = null)
    {
        $id2 = (int)$id;
        if (empty($this->_itemid[$view][$id2])) {

            $this->_itemid[$view][$id2] = '';
            $is_valid = false;
            $db = $this->getDBO();

            // get Itemid for this url
            $itemid = $this->_getUrlItemid($url);
            if ($itemid) {
                $this->_itemid[$view][$id2] = $itemid;
                $is_valid = true;
            }

            // get Itemid for this view
            if (!$is_valid) {
                $view_itemid = $this->_getViewItemid($view, $id);
                if ($view_itemid !== 0 && !is_null($view_itemid)) {
                    $this->_itemid[$view][$id2] = $view_itemid;
                    $is_valid = true;
                }
            }

          

            // get the active menu Itemid
            if (!$is_valid) {
                $itemid = $this->_getActiveItemid();
                if ($itemid !== 0 && !is_null($itemid)) {
                    $this->_itemid[$view][$id2] = $itemid;
                }
                $is_valid = true;
            }

            if (!$is_valid) {
                $this->_itemid[$view][$id2] = 9999; // none of Itemid
            }
        }

        return $this->_itemid[$view][$id2];
    }

    /**
     * Get exactly Itemid for link
     */
    function _getUrlItemid($url = null)
    {
        if ($url) {
            $url = $this->_db->quote('%' . $url . '%');
            $query = 'SELECT id'
                . ' FROM #__menu'
                . ' WHERE published = 1 AND link LIKE ' . $url;
            $this->_db->setQuery($query);

            return $this->_db->loadResult();
        }
        return null;
    }

    /**
     * Get Itemid specific for the given view.
     */
    function _getViewItemid($view, $id = null)
    {
        $url = 'option=com_jux_real_estate&view=' . $view;
        if (!empty($id)) {
            $url .= '&id=' . (int)$id;
        }
        $url = $this->_db->quote('%' . $url . '%');
        $query = 'SELECT id'
            . ' FROM #__menu'
            . ' WHERE published = 1 AND link LIKE ' . $url;
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }

    /**
     * Get Itemid from actived menu
     */
    function _getActiveItemid()
    {
        $app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$item	= $menu->getActive();

        if (is_object($item)) {
            return $item->id;
        }

        return null;
    }
}