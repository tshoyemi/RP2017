<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_language
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JUX_Real_EstateModelLanguage extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'key', 'a.key',
                'orginal', 'a.orginal'
            );
        }

        parent::__construct($config);
    }

    protected function translation_list($option) {
        global $mainframe;
        $db = JFactory::getDBO();
        $mainframe = & JFactory::getApplication();

        jimport('joomla.filesystem.file');
        $search = $mainframe->getUserStateFromRequest($option . 'language_search', 'search', '', 'string');
        $search = JString::strtolower($search);
        $lists['search'] = $search;

        $lang = JRequest::getVar('lang', '');
        if (!$lang)
            $lang = 'en-GB';
        $lists['lang'] = $lang;
        $site = JRequest::getVar('site', 0);

        $path = JPATH_ROOT . '/' . 'language';
        if ($site)
            $path = JPATH_ROOT . '/' . 'administrator' . '/' . 'language';

        $languages = JUX_LanguageHelper::getLanguages($path);
        $options = array();
        $options[] = JHTML::_('select.option', '', JText::_('Select Language'));
        foreach ($languages as $language) {
            $options[] = JHTML::_('select.option', $language, $language);
        }
        $lists['langs'] = JHTML::_('select.genericlist', $options, 'lang', ' class="inputbox"  onchange="this.form.submit();" ', 'value', 'text', $lang);

        $options = array();
        $options[] = JHTML::_('select.option', 0, JText::_('OS_FRONTEND'));
        $options[] = JHTML::_('select.option', 1, JText::_('OS_BACKEND'));
        $lists['site'] = JHTML::_('select.genericlist', $options, 'site', ' class="inputbox"  onchange="this.form.submit();" ', 'value', 'text', $site);

        $item = JRequest::getVar('item', '');
        if (!$item)
            $item = 'com_jux_language';
        $trans = JUX_LanguageHelper::getTrans($lang, $item, $path);
        $lists['item'] = $item;
        
        return $lists;
    }

    function getExtensions(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('element')
                ->from('#__extensions')
                ->order('element');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        
        return $results;
    }
}
