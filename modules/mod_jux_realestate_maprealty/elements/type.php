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
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldType extends JFormFieldList {

    public $realty = 'Type';

    /**
     * fetch Element
     */
    protected function getInput() {
        // Initialize variables.
        $html = array();
        $attr = '';

        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '1';
        $attr .= $this->element['multiple'] ? ' multiple="multiple"' : '';
        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        $data = $this->getData();

        return JHTML::_('select.genericlist', $data, $this->name, $attr, 'value', 'text', $this->value, $this->id, true);

        return implode($html);
    }

    /**
     * Method to get list of countries.
     *
     * @return	array
     */
    protected function getData() {
        // Get a database object.
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        $query->select('`id` AS value, `title` AS text');
        $query->from('#__re_types');
        $query->where('published = 1');
        $query->order('title');
        $db->setQuery($query);
        $sec = array();
        $sec[] = JHTML::_('select.option', '', '- ' . JText::_('MOD_JUX_REAL_ESTATE_MAP_SELECT_A_TYPE') . ' -', 'value', 'text', 'multiple="multiple" size="5"');
        try {
            $types = $db->loadObjectList();
            if (count($types)) {
                foreach ($types as $type) {
                    $sec[] = JHTML::_('select.option', $type->value, $type->text, 'value', 'text');
                }
            }
        } catch (JDatabaseException $e) {
            $je = new JException($e->getMessage());
            $this->setError($je);
            return array();
        }
       
        return $sec;
    }

}

?>
