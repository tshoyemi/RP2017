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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
JTable::addIncludePath(JPATH_ADMINISTRATOR . DS . "components" . DS . "com_jse_digital_store" . DS . 'tables');

class JFormFieldProduct extends JFormField {

    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Realty';

    function getInput() {
	$db = JFactory::getDBO();
	$doc = JFactory::getDocument();
	$js = "
		function jSelectProduct(id, title, object) {
			document.getElementById(object).value = id;
			document.getElementById(object + '_name').value = title;
			SqueezeBox.close();
		}";
	$doc->addScriptDeclaration($js);
	//var_dump($this); exit();

	$product = JTable::getInstance('realty', 'Jux_Reak_Estate_Table');
	if ($this->value) {
	    $realty->load($this->value);
	} else {
	    $realty->title = JText::_('COM_JSE_DIGITAL_STORE_SELECT_A_PRODUCT');
	}

	$link = 'index.php?option=com_jse_digital_store&view=realties&amp;tmpl=component&amp;object=' . $this->id;

	JHTML::_('behavior.modal', 'a.modal');
	$html = "\n" . '<span class="input-append"><input class="input-medium" type="text" id="' . $this->id . '_name" value="' . htmlspecialchars($product->title, ENT_QUOTES, 'UTF-8') . '" disabled="disabled" />';
	$html .= '<a class="modal btn btn-info" title="' . JText::_('COM_JSE_DIGITAL_STORE_SELECT_A_PRODUCT') . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 850, y: 375}}"><i class="icon-list icon-white"></i> ' . JText::_('COM_JSE_DIGITAL_STORE_SELECT') . '</a></span>' . "\n";
	$html .= "\n" . '<input class="required" type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . (int) $this->value . '" />';

	return $html;
    }

}