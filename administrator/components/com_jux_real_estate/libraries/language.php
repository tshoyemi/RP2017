<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_language
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

/**
 * JUX_Language Factory Libraries
 * @package		JUX_Language
 * @subpackage	Libraries
 * @since		1.0
 *
 */
class JUX_LanguageLanguage {

    /**
     * get translate
     *
     * @param unknown_type $lang
     * @param unknown_type $item
     * @return unknown
     */
    public static function getTrans($lang, $item, $path_orginal, $original_lang) {
        global $mainframe;
        jimport('joomla.filesystem.file');
        $registry = new JRegistry();
        $languages = array();
        $path = $path_orginal . '/' . $original_lang . '/' . $original_lang . '.' . $item . '.ini';
      
        if (JFile::exists($path)) {
            $content = JFile::read($path);
            $registry->loadString($content);
            $languages[$original_lang][$item] = $registry->toArray();
            // get language from select
            $path1 = $path_orginal . '/' . $lang . '/' . $lang . '.' . $item . '.ini';
            if (JFile::exists($path1)) {
                $content = JFile::read($path1);
                $registry->loadString($content);
                $languages[$lang][$item] = $registry->toArray();
            } else {
                $text1 = JText::_('JUX_LANGUAGE_FILE') . ' ' . $path1 . ' ' . JText::_('JUX_DOES_NOT_EXISTS');
                $text2 = JText::_('JUX_YOU_CAN_CLICK_TRANSLATION_SAVE_BUTTON_TO_SAVE_NEW_FILE_LANGUAGE_OR_CLICK_CANCEL');
                JFactory::getApplication()->enqueueMessage($text1, 'error');
                JFactory::getApplication()->enqueueMessage($text2, 'error');
                $languages[$lang][$item] = array();
            }
        } else {
            $text1 = JText::_('JUX_LANGUAGE_ORIGINAL_FILE') . ' ' . $path . ' ' . JText::_('JUX_DOES_NOT_EXISTS');
            $text2 = JText::_('JUX_LANGUAGE_CHECK_EXTENSION_NAME_CONFIGURATION');
            JFactory::getApplication()->enqueueMessage($text1, 'error');
            JFactory::getApplication()->enqueueMessage($text2, 'error');
            
            // get language in en-GB
            $path2 = $path_orginal . '/en-GB/en-GB.' . $item . '.ini';
            if (JFile::exists($path2)) {
                $content = JFile::read($path2);
                $registry->loadString($content);
                $languages[$lang][$item] = $registry->toArray();
            } else {
                $languages[$lang][$item] = array();
            }
        }
        return $languages;
    }

    /**
     * get option langguage of site
     *
     */
    public static function getLanguages($path) {
        jimport('joomla.filesystem.folder');
        $folders = JFolder::folders($path);
        $rets = array();
        foreach ($folders as $folder)
            if ($folder != 'pdf_fonts')
                $rets[] = $folder;
            return $rets;
        }

    }

    ?>