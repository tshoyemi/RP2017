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

class JFormFieldJUXHeader extends JFormField {

    protected $type = 'JUXHeader';

    protected function getInput() {
	return;
    }

    protected function getLabel() {
	echo '<div class="clr"></div>';
	$bgcolor = ($this->element['bgcolor']) ? $this->element['bgcolor'] : '#377391';
	$color = ($this->element['color']) ? $this->element['color'] : '#ffffff';
	$style = 'background: ' . $bgcolor . '; color: ' . $color . '; line-height: 38px; font-weight: bold; height: 38px; font-size: 12px; padding: 0 10px; margin: 21px 0 0; border: 1px solid #cccccc; border-top-color: #f7f7f7; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; cursor: pointer; margin-bottom: 10px;';

	if ($this->element['default']) {
	    echo '<div style="' . $style . '">';
	    if ($this->element['description'] && $this->element['description'] != "") {
		echo '<span class="hasTip" title="' . JText::_($this->element['default']) . '::' . JText::_($this->element['description']) . '"><strong>' . JText::_($this->element['default']) . '</strong></span>';
	    } else {
		echo '<strong>' . JText::_($this->element['default']) . '</strong>';
	    }
	    echo '</div>';
	} else {
	    return parent::getLabel();
	}
	echo '<div class="clr"></div>';
    }

}