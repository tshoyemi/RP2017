<?php

/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
// no direct access

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ROOT . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'libraries' . '/' . 'fields.php');
class JUX_Real_EstateAjaxSearch extends JUX_Real_EstateFields{

    /** @var object */
    var $_db = null;

    public static function javascript() {
        $document = JFactory::getDocument();
        if (JVERSION < '3.0.0') {
            $document->addScript(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/js/jquery.min.js');
        }
//        $document->addScript(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/js/bootstrap-datetimepicker.min.js');
    }

    public static function css() {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/style.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/jux-font-awesome.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/jux-responsivestyle.css');
//        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/bootstrap-datetimepicker.min.css');
    }
    public static function css_horizontal() {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/jux-font-awesome.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/jux-responsivestyle.css');
        $document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/style_horizontal_new.css');
    }
    

    /**
     * Construct function
     *
     * @return JUX_Real_EstateAjaxSearch
     */
    function JUX_Real_EstateAjaxSearch() {
        $db = JFactory::getDBO();
        $this->_db = $db;
    }

    /**
     * function to get all realty mod
     *
     * @return html select list
     */
    function getMod() {
        $sql = 'SELECT id, username AS name FROM #__re_agents WHERE published = 1 and approved = 1 ORDER BY name';
        $this->_db->setQuery($sql);
        $rows = $this->_db->loadObjectList();

        $options = array();
        $options[] = JHTML::_('select.option', '', JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_AN_AGENT'), 'id', 'name');
        $options = array_merge($options, $rows);
        $lists = JHTML::_('select.genericlist', $options, 'agent_id', ' class="input-medium" ', 'id', 'name');

        return $lists;
    }

    /**
     * Function to get all realty categories
     *
     * @return html select list
     */
    function getCategory_old() {
        $sql = 'SELECT id, title FROM #__categories WHERE published = 1 AND extension ="com_jux_real_estate" ORDER BY title';
        $this->_db->setQuery($sql);
        $rows = $this->_db->loadObjectList();

        $options = array();
        $options[] = JHTML::_('select.option', '', JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_CONTRACT'), 'id', 'title');
        foreach ($rows as $row) {
            $options[] = JHTML::_('select.option', $row->id, JText::_($row->title), 'id', 'title');
        }
        $lists = JHTML::_('select.genericlist', $options, 'catid', ' class="input-medium" ', 'id', 'title');

        return $lists;
    }

    function getCategory() {
        $lang = JFactory::getLanguage();
        $lang_c = $lang->getTag(); 
        $t_lang = $lang_c ? " AND (language = '" . $lang_c . "' OR language = '*')" : '';
        $sql = 'SELECT id, title FROM #__categories WHERE published = 1 AND extension ="com_jux_real_estate" '. $t_lang .' ORDER BY title';
        $this->_db->setQuery($sql);
        $rows = $this->_db->loadObjectList();

        $options = '<select id="cat_id" name="cat_id" class="input-medium">';
        $options .= '<option value="0" >' . JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_CONTRACT') . '</option>';
        foreach ($rows as $row) {
            if (isset($_REQUEST['cat_id']) && $row->id == $_REQUEST['cat_id']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            $options .= '<option ' . $selected . ' value="' . $row->id . '">' . $row->title . '</option>';
        }
        $options .= '</select>';

        return $options;
    }

    function getCountry() {
        // Build the script.
            $script = "jQuery(function() {
                jQuery('#country_id').change(function() {
                            jQuery('ajax-container').empty().addClass('ajax-loading');

                            //Ajax Request start here
                            var myElement = document.getElementById('ajax-container');
                            var cid = document.getElementById('country_id').value;
                            jQuery.ajax({
                                url: 'index.php?option=com_jux_real_estate&task=ajax.getstates',
                                method: 'get',
                                data: {
                                        'country_id' : cid,
                                },
                                complete: function(data){
                                        jQuery('#ajax-container').html(data.responseText);
                                },
                                error: function(){
                                        jQuery('#ajax-container').html('Sorry, your request failed :');
                                        jQuery('#ajax-container').addClass('error');
                                }
                            });
                        });
                    });
                ";

        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration($script);


        $sql = 'SELECT id, name FROM #__re_countries WHERE published = 1  ORDER BY name';
        $this->_db->setQuery($sql);
        $rows = $this->_db->loadObjectList();

        $options = array();

        $options[] = JHTML::_('select.option', '', JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_COUNTRY'), 'id', 'name');
        foreach ($rows as $row) {
            $options[] = JHTML::_('select.option', $row->id, JText::_($row->name), 'id', 'name');
        }
        $lists = JHTML::_('select.genericlist', $options, 'country_id', ' class="input-medium" ', 'id', 'name');

        return $lists;
    }

    /**
     * Method to get the field input markup.
     *
     * @return  string   The field input markup.
     *
     * @since   11.1
     */
    function getStates($attr = '') {
        // Initialize variables.
        $html = array();
        
        $query = $this->_db->getQuery(true);

        $query->select('`id` AS value, `state_name` AS text');
        $query->from('#__re_states');
        if(isset($_REQUEST['country_id']) && $_REQUEST['country_id']){
            $query->where('country_id = '.$_REQUEST['country_id']);
        }
        $query->order('id');
        $this->_db->setQuery($query);
        $sec = array();
        $sec[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_STATE'), 'value', 'text');
        try {
            $countries = $this->_db->loadObjectList();
            if (count($countries)) {
                foreach ($countries as $country) {
                    $sec[] = JHTML::_('select.option', $country->value, $country->text, 'value', 'text');
                }
            }
        } catch (JDatabaseException $e) {
            $je = new JException($e->getMessage());
            $this->setError($je);
            return array();
        }
        return '<span id="ajax-container">' . JHTML::_('select.genericlist', $sec, 'locstate', 'class="input-medium"' . $attr, 'value', 'text') . '</span>';

        return implode($html);
    }

    /**
     * Function to get all Realty type
     *
     * @return html select list
     */
    function getType_old() {

        $sql = 'SELECT id, title FROM #__re_types WHERE published = 1 ORDER BY ordering';
        $this->_db->setQuery($sql);
        $rows = $this->_db->loadObjectList();

        $options = array();
        $options[] = JHTML::_('select.option', '', JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_TYPE'), 'id', 'title');

        foreach ($rows as $row) {
            $options[] = JHTML::_('select.option', $row->id, JText::_($row->title), 'id', 'title');
        }
        $lists = JHTML::_('select.genericlist', $options, 'type_id', ' class="input-medium" ', 'id', 'title');

        return $lists;
    }

    function getType() {
        $lang = JFactory::getLanguage();
        $lang_c = $lang->getTag(); 
        $t_lang = $lang_c ? " AND (language = '" . $lang_c . "' OR language = '*')" : '';
        $sql = 'SELECT id, title FROM #__re_types WHERE published = 1 ' .$t_lang. 'ORDER BY ordering';
        $this->_db->setQuery($sql);
        $rows = $this->_db->loadObjectList();

        $options = '<select id="type_id" name="type_id" class="input-medium">';
        $options .= '<option value="0" >' . JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_TYPE') . '</option>';
        foreach ($rows as $row) {
            if (isset($_REQUEST['type_id']) && $row->id == $_REQUEST['type_id']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            $options .= '<option ' . $selected . ' value="' . $row->id . '">' . $row->title . '</option>';
        }
        $options .= '</select>';

        return $options;
    }

    /**
     * Function to dender date search
     *
     * @param string $row
     */
    function getDate($name, $title) {

        $sql = 'SELECT `value` FROM #__re_configs WHERE `key`="date_format"';
        $this->_db->setQuery($sql);
        $dateFormat = $this->_db->loadResult();
        $dateFormat = '%d-%m-%Y';

        $html = '<label for="' . $name . '"><strong>' . JText::_($title) . '</strong></label>';
        $html .= JHTML::_('calendar', '', $name, $name, $dateFormat, 'style="width: 55%"');

        return $html;
    }

    function getMinPrice($array, $active = '', $format_price = ',') {
        $options = array();
        $options[] = JHTML::_('select.option', '', '-- ' . JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_PRICE') . ' --');
        foreach ($array as $value) {
            $options[] = JHTML::_('select.option', $value, (number_format($value, 0, '.', $format_price)));
        }

        return JHTML::_('select.genericlist', $options, 'minprice', 'class="input-medium"', 'value', 'text', $active);
    }

    function getMaxPrice($array, $active = '', $format_price = ',') {

        $options = array();
        $options[] = JHTML::_('select.option', '', '-- ' . JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SELECT_PRICE') . ' --');

        foreach ($array as $value) {
            $options[] = JHTML::_('select.option', $value, (number_format($value, 0, '.', $format_price)));
        }

        return JHTML::_('select.genericlist', $options, 'maxprice', 'class="input-medium"', 'value', 'text', $active);
    }

    /**
     * Function to get all Curency
     *
     * @return html select list
     */
    function getCurrency() {

        $sql = 'SELECT id, title FROM #__re_currencies WHERE published = 1 ORDER BY ordering';
        $this->_db->setQuery($sql);
        $rows = $this->_db->loadObjectList();

        $options = array();
        $options[] = JHTML::_('select.option', '', '-- ' . JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_ALL_CURRENCY') . ' --', 'id', 'title');

        foreach ($rows as $row) {
            $options[] = JHTML::_('select.option', $row->id, JText::_($row->title), 'id', 'title');
        }
        $lists = JHTML::_('select.genericlist', $options, 'currency_id', ' class="input-medium" ', 'id', 'title');

        return $lists;
    }

    /*
     * Function get fields search in backend
     * 
     * $return html
     */

    function getFieldSearch() {
        $JUXFields = new JUX_Real_EstateFields();
        $fields = $JUXFields->renderCustomSearchFields2();
        
        return $fields;
    }

}