<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_language
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controlleradmin');

/**
 * JUX_Language Component - States Controller
 * @package        JUX_Language
 * @subpackage    Controller
 */
class JUX_Real_EstateControllerLanguage extends JControllerAdmin {

    /**
     * @var        string    The prefix to use with controller messages.
     */
    protected $text_prefix = 'JUX_Real_Estate';

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->registerTask('translation_save', 'translation_save');
    }

    public function translation_save() {
        jimport('joomla.filesystem.file');
        $post = JRequest::get('post', JREQUEST_ALLOWHTML);
        
        $lang = $post['lang'];
        $item = $post['item'];
        $site = $post['site'];
        $filePath = JPATH_ROOT . '/' . 'language' . '/' . $lang . '/' . $lang . '.' . $item . '.ini';
        if ($site)
            $filePath = JPATH_ROOT . '/' . 'administrator' . '/' . 'language' . '/' . $lang . '/' . $lang . '.' . $item . '.ini';

        $content = ";language\n";
        $lang_page = $_POST['lang_page'];
        $langArr = explode("@@@", $lang_page);

        for ($i = 0; $i < count($langArr); $i++) {
            $l = $langArr[$i];
            $l = explode("|", $l);
            $key = $l[0];
            $value = $l[1];
            $content.="$key=\"$value\"\n";
        }
        JFile::write($filePath, $content);
        $this->setRedirect("index.php?option=com_jux_real_estate&view=language&site=" . $site . "&lang=" . $lang . "&extension_name=" . $item);
    }

}
