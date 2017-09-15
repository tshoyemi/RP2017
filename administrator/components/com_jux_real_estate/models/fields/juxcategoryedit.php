<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla
 * @subpackage		com_jux_real_estate
 * 
 * @copyright		Copyright (C) 2012 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, See LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

class JFormFieldJUXCategoryedit extends JFormField {

    protected $type = 'JUXCategoryedit';

    protected function getInput() {
	$multiple = ($this->element['multiple']) ? ' multiple="multiple"' : '';
	$size = ($this->element['size']) ? ' size="' . $this->element['size'] . '"' : '';
	$style = ($this->element['style']) ? ' style="' . $this->element['style'] . '"' : '';

	$cats = JHTML::_('jux_real_estate.catSelectList', '', '', '', true);

	return JHtml::_('select.genericlist', $cats, $this->name, 'class="inputbox"' . $multiple . $size . $style, 'value', 'text', $this->value, $this->id);
    }

    public function getOptions() {
	$options = array();
	$options = JHTML::_('jux_real_estate.catSelectList', '', '', '', true);

	return $options;
    }

}

