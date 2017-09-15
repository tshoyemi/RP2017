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
require_once JPATH_ADMINISTRATOR . '/components/com_jux_real_estate/libraries/language.php';
require_once JPATH_ADMINISTRATOR . '/components/com_jux_real_estate/helpers/jux_language.php';
require_once JPATH_ADMINISTRATOR . '/components/com_jux_real_estate/libraries/language_factory.php';

/**
 * JUX_Language Component - Fields view
 * @package        JUX_Language
 * @subpackage    View
 * @since        3.0
 */
class JUX_Real_EstateViewLanguage extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $lists;
    protected $trans;
    protected $option;
    protected $flag;
    protected $original;
    protected $extension_name;

    function display($tpl = null) {
        JUX_Real_EstateHelper::addSubmenu('language');
        if(isset($_REQUEST['lang'])){
            $original_lang = $_REQUEST['lang'];
        } else {
            $original_lang = 'en-GB';
        }
        $this->original = $original_lang;
        if(isset($_REQUEST['extension_name'])){
            $file_name = $_REQUEST['extension_name'];
        } else {
            $file_name = 'com_jux_real_estate';
        }
        $this->extension_name = $file_name;
        
        $this->option = JRequest::getVar('option'); 
        $trans = JUX_Real_EstateViewLanguage::translation_list($this->option, 'trans', $original_lang, $file_name);
        $lists = JUX_Real_EstateViewLanguage::translation_list($this->option, 'lists', $original_lang, $file_name);

        $this->trans = $trans;
        $this->lists = $lists;
        $this->addToolBar();

        if (JVERSION >= '3.0.0') {
            $this->sidebar = JHtmlSidebar::render();
        }

        if (JVERSION < '3.0.0') {
            $this->setLayout($this->getLayout() . '25');
        }

        $this->lists['extensions'] = $this->getExtensions();
        parent::display($tpl);
    }

    function addToolBar() {

        $canDo = JUX_LanguageHelper::getActions();
        // set page title
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_JUX_LANGUAGE_LANGUAGE_MANAGEMENT'));

        JToolBarHelper::title(JText::_('COM_JUX_LANGUAGE_LANGUAGE_MANAGEMENT'), 'language.png');
        if ($canDo->get('core.admin')) {
            JToolbarHelper::custom('language.translation_save', 'save.png', 'save_f2.png', 'Translation save', FALSE);
        }

        JHtmlSidebar::setAction('index.php?option=com_jux_real_estate&view=language');
    }

    protected function translation_list($option, $select, $original_lang, $file_name) {

        global $mainframe;
        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();

        jimport('joomla.filesystem.file');

        $lang = JRequest::getVar('lang', '');
        if (!$lang)
            $lang = 'en-GB';
        $lists['lang'] = $lang;
        $site = JRequest::getVar('site', 0);

        $path = JPATH_ROOT . '/' . 'language';
        if ($site)
            $path = JPATH_ROOT . '/' . 'administrator' . '/' . 'language';

        $languages = JUX_LanguageLanguage::getLanguages($path);
        $options = array();
        $options[] = JHTML::_('select.option', '', JText::_('Select Language'));
        foreach ($languages as $language) {
            $options[] = JHTML::_('select.option', $language, $language);
        }
        $lists['langs'] = JHTML::_('select.genericlist', $options, 'lang', ' class="input-medium"  onchange="this.form.submit();" ', 'value', 'text', $lang);

        $options = array();
        $options[] = JHTML::_('select.option', 0, JText::_('JUX_FRONTEND'));
        $options[] = JHTML::_('select.option', 1, JText::_('JUX_BACKEND'));
        $lists['site'] = JHTML::_('select.genericlist', $options, 'site', ' class="input-medium"  onchange="this.form.submit();" ', 'value', 'text', $site);
        $item = $file_name;

        $lists['item'] = $item;
        // Check language file
        $check_path = $path . '/' . $original_lang . '/' . $original_lang . '.' . $item . '.ini';
        if (JFile::exists($check_path)) {
            $this->flag = true;
        } else {
            $this->flag = FALSE;
        }
        
        switch ($select) {
            case 'trans':
            $trans = JUX_LanguageLanguage::getTrans($lang, $item, $path, $original_lang);
            return $trans;

            case 'lists': return $lists;
        }
    }
    
    function getExtensions(){
        $data = $this->get('Extensions');
        $options = array();
        if(isset($_REQUEST['extension_name']) && $_REQUEST['extension_name']){
            $options[] = JHTML::_('select.option', '', $_REQUEST['extension_name']);
        } else {
            $options[] = JHTML::_('select.option', '', 'com_jux_real_estate');
        }
        foreach ($data as $value) {
            $options[] = JHTML::_('select.option', $data, $value->element);
        }
        return JHTML::_('select.genericlist', $options,' " class="inputbox" name="extension_name" onchange="this.form.submit(); ', 'value', 'text');
    }

}